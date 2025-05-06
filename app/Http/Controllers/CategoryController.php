<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder = Category::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with([
            'message' => 'Folder created successfully.',
            'type' => 'success'
        ]);
    }
}
