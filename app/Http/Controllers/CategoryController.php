<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Snippet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json([
            'folders' => Auth::user()->categories
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder = Category::create([
            'name' => $request->name,
            'user_id' => Auth::id(),
        ]);


        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'folder' => $folder,
                'message' => 'Folder created successfully.'
            ]);
        }
        return redirect()->back()->with([
            'message' => 'Folder created successfully.',
            'type' => 'success'
        ]);
    }

    public function show(Category $category)
    {
        $snippets = $category->snippets()->with('tags','categories')->get();
        $folders = Category::where('user_id', Auth::id())->latest()->get();

            return Inertia::render('Category', [
                'snippets' => $snippets,
                'folders' => $folders,
                'category' => $category,
            ]);
    }
}
