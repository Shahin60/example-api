<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $post = Post::all();
        return response()->json([
            "status" => true,
            "message" => "All post List",
            "posts" => $post,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        //  Validation check
        $request->validate([
            "title"   => "required|string|max:255",
            "content" => "required|string",
            "image"   => "required|image|mimes:png,jpg,jpeg,gif,svg|max:2048"
        ]);

        //  Image Upload Storage
        $image_name = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalName();
            // $image->storeAs('public/images', $image_name); // Storage-এ সংরক্ষণ
            $image->move(public_path('images'), $image_name); // Storage-এ সংরক্ষণ

        }

        // Store in Database
        $post = Post::create([
            "title"   => $request->title,
            "content" => $request->content,
            "image"   => $image_name,
        ]);

        return response()->json([
            "status"  => true,
            "message" => "Post created successfully!",
            "post"    => $post,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $post = Post::findOrFail($id);
        return response()->json([
            "status" => true,
            "message" => " Single Post Show",
            "post" => $post,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "title"   => "required|string|max:255",
            "content" => "required|string",
            "image"   => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048" // Only image files allowed
        ]);

        $post = Post::findOrFail($id);

        // default image store
        $image_name = $post->image;

        // Image Update
        if ($request->hasFile('image')) {
            if ($post->image && file_exists(public_path('images/' . $post->image))) {
                unlink(public_path('images/' . $post->image));
            }

            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $image_name);
        }

        $post->update([
            "title" => $request->title,
            "content" => $request->content,
            "image" => $image_name, // Update the image file
        ]);

        return response()->json([
            "status" => true,
            "message" => "Post Updated Successfully",
            "post" => $post,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $post = Post::findOrFail($id);

    // image delete
    if ($post->image && file_exists(public_path('images/' . $post->image))) {
        unlink(public_path('images/' . $post->image));
    }

    $post->delete();

    return response()->json([
        "status" => true,
        "message" => "Post deleted successfully"
    ], 200);
}

}
