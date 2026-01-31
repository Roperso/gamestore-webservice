<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all());
    }

    public function store(Request $request)
    {
        $user = JWTAuth::user();

        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'Only admin can create category'
            ], 403);
        }

        $this->validate($request, [
            'name' => 'required|unique:categories'
        ]);

        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Category created',
            'data' => $category
        ], 201);
    }
}
