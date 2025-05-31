<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->posts()->latest()->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        // Strip tags for basic XSS protection
        $validated['title'] = strip_tags($validated['title']);
        $validated['body'] = strip_tags($validated['body']);

        $post = $request->user()->posts()->create($validated);

        return response()->json($post, 201);
    }

    public function show(Request $request, Post $post)
    {
        $this->authorize('view', $post);
        return response()->json($post);
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'body' => 'sometimes|required|string',
        ]);

        // Sanitize inputs
        if (isset($validated['title'])) {
            $validated['title'] = strip_tags($validated['title']);
        }

        if (isset($validated['body'])) {
            $validated['body'] = strip_tags($validated['body']);
        }

        $post->update($validated);

        return response()->json($post);
    }

    public function destroy(Request $request, Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }

    public function viewImage(Request $request, $path)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid or expired image URL.');
        }

        $fullPath = storage_path("app/public/posts/images/{$path}");

        if (!file_exists($fullPath)) {
            abort(404, 'Image not found.');
        }

        return response()->file($fullPath);
    }
}
