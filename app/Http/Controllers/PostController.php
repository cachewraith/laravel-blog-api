<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // crud operation can create , read , update and delete post
    public function index(){

        $posts = Post::all();
        
        return response()->json([
            'msg' => 'Get successfully',
            'posts' => $posts
        ]);
    }

    public function store(Request $request){

        $request->validate([
            'title' => 'required|string',
            'image_url' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $data = $request->all();

        if($request->hasFile('image_url')) {
            $path = $request->file('image_url')->store('images','public');
            $data['image_url'] = $path;
        }

        $post = Post::create($data);

        return response()->json([
            'msg' => 'Create success',
            'post' => $post
        ]);
        
    }

    public function update(Request $request , $id){
        $post = Post::find($id);

        if(!$post) {
            return response()->json([
                'msg' => 'Post not found',
            ]);
        }

        $request->validate([
            'title' => 'required|string',
            'image_url' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $data = $request->all();

        if($request->hasFile('image_url')){
            if($post->image_url){
                Storage::disk('public')->delete($post->image_url);
            }

            $data['image_url'] = $request->file('image_url')->store('images','public');
        }

        $post->update($data);

        return response()->json([
            'msg' => 'Updated success',
            'post' => $post
        ]);
    }

    public function destroy($id){

        $post = Post::find($id);

        if(!$post) {
            return response()->json([
                'msg' => 'Post not found',
            ]);
        }

        $post->delete();

        return response()->json([
            'msg' => 'Deleted success',
        ]);
    }
}

// homework 
// 1. update post with image 
// 2. delete post
