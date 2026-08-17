<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Http\Requests\APIs\Post\StoreRequest;
use App\Http\Requests\APIs\Post\UpdateRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Post::orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'Posts fetched successfully.',
            'data' => $data,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $image = $data['image'];

        $imageName = time() . '.' . $image->getClientOriginalExtension();

        $imagePath = $image->storeAs(
            'posts',
            $imageName,
            'public'
        );

        $data['image'] = $imagePath;

        $post = Post::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Post created successfully!',
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'description' => $post->description,
                'image' => $post->image,
            ],
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Post::find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Post fetched successfully.',

            'data' => $data,
        ], 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Post $post)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {

            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $image = $data['image'];

            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $imagePath = $image->storeAs(
                'posts',
                $imageName,
                'public'
            );

            $data['image'] = $imagePath;
        }

        $post->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Post updated successfully!',
            'data' => $post->fresh(),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Delete image from storage
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        // Delete post
        $post->delete();

        return response()->json([
            'status' => true,
            'message' => 'Post deleted successfully.',
        ], 200);
    }
}
