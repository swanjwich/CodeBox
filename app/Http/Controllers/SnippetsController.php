<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Snippet;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SnippetsController extends Controller
{
    public function index()
    {
        $snippets = Snippet::with(['tags', 'category'])->latest()->get();
        return Inertia::render('AllSnippets', ['snippets' => $snippets]);
    }

    public function favorites()
    {
        $favorites = Snippet::with('tags')->where('is_favorite', true)->latest()->get();
        return Inertia::render('Favorites', ['snippets' => $favorites]);
    }

    public function trash()
    {
        $trash = Snippet::onlyTrashed()->with('tags')->latest()->get();
        return Inertia::render('Trash', ['snippets' => $trash]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'visibility' => 'required|in:private,public',
            'tags' => 'nullable|string',
        ]);

        $snippet = Snippet::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'visibility' => $request->visibility,
        ]);

        // Process tags
        if ($request->tags) {
            $tagNames = explode(',', $request->tags); // Split tags by comma
            $tagIds = [];

            foreach ($tagNames as $tagName) {
                $tagName = trim($tagName); // Remove extra spaces
                if (!empty($tagName)) {
                    // Find or create tag
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
            }

            // Attach tags to snippet
            $snippet->tags()->sync($tagIds);
        }

        return redirect()->route('all')->with([
            'message' => 'Snippet created successfully!',
            'type' => 'success',
        ]);
    }

    public function show($id)
    {
        $snippet = Snippet::with('tags')->findOrFail($id);
        return inertia('View', [
            'snippet' => $snippet
        ]);
    }

    public function update(Request $request, Snippet $snippet)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'tagsString' => 'nullable|string',
        ]);

        $snippet->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'content' => $validated['content'],
        ]);

        if ($validated['tagsString']) {
            $tagNames = array_map('trim', explode(',', $validated['tagsString']));
            $tags = collect($tagNames)->map(fn($name) => Tag::firstOrCreate(['name' => $name]));
            $snippet->tags()->sync($tags->pluck('id'));
        }

        return back()->with([
            'snippet' => $snippet->load('tags'),
            'message' => 'Snippet updated successfully!',
            'type' => 'success'
        ]);

    }


    public function toggleFavorite($id)
    {
        $snippet = Snippet::findOrFail($id);
        $snippet->is_favorite = !$snippet->is_favorite;
        $snippet->save();

        return redirect()->back()->with([
            'message' => $snippet->is_favorite ? 'Added to favorites.' : 'Removed from favorites.',
            'type' => 'success',
        ]);
    }

    public function delete($id)
    {
        $snippet = Snippet::findOrFail($id);
        $snippet->delete();

        return redirect()->back()->with([
            'message' => 'Snippet has been moved to trash.',
            'type' => 'info',
        ]);
    }

    public function restore($id)
    {
        $snippet = Snippet::withTrashed()->findOrFail($id);
        $snippet->restore();

        return redirect()->back()->with([
            'message' => 'Snippet has been restored.',
            'type' => 'info',
        ]);
    }

    public function destroy($id)
    {
        $snippet = Snippet::withTrashed()->findOrFail($id);
        $snippet->forceDelete();

        return redirect()->back()->with([
            'message' => 'Snippet has been deleted permenantly.',
            'type' => 'info',
        ]);
    }

}
