<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return View('index', compact('posts'));
    }
    public function getdata()
    {
        $posts = Post::all();
        return response()->json([
            'posts'=>$posts
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $post = new Post();
        $post->name = $request->name;
        $post->save();
        return response()->json([
            'posts' => Post::all()
        ]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $post = Post::find($id);
        $post->name = $request->name;
        $post->save();
        return response()->json([
            'posts' => Post::all()
        ]);
    }
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
        return response()->json([
            'posts' => Post::all()
        ]);
    }
}
