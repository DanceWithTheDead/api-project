<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Get posts current auth users.
     */
    public function index()
    {
        return auth()->user()->posts;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:55',
            'content' => 'required|string|max:255',
        ]);

        $validated['user_id'] = auth()->id();
        $post = $this->postService->UserCreatePost($validated);

        return response()->json([
            'post' => $post,
        ], 201);
    }

    /**
     * Display post(id) current auth user.
     */
    public function show(string $id)
    {
        foreach (auth()->user()->posts as $post){
            if ($post['id'] == $id){
                return response()->json([
                    $post->title,
                    $post->content
                ]);
            }
        }


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        /** @var Post $post */
        foreach (auth()->user()->posts as $post){
            if ($post['id'] == $id){
                $post->delete();
                return response()->json([
                    'message' => "deleting is done"
                ]);
            }
        } return response()->json([
            'message' => 'Post not found'
    ], 404);
    }
}
