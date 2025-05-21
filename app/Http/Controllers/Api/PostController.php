<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;

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

    public function indexAll()
    {
        return Post::all();
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
    public function store(PostCreateRequest $request)
    {
        $post = $this->postService->UserCreatePost(
            $request->validated() + ['user_id' => auth()->id()]
        );

        return response()->json([
            'post' => $post
        ], 201);
    }

    /**
     * Display post(id) current auth user.
     */
    public function show(string $id)
    {
        $post = auth()->user()->posts->firstWhere('id', $id);

        if ($post) {
            return response()->json([
                'title' => $post->title,
                'content' => $post->content,
            ]);
        }
        return response()->json([
            'message' => 'Post not found.',
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, string $id): JsonResponse
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== auth()->id()){
            return response()->json([
                'message' => 'You are not allowed to update this post.',
            ], 403);
        }

        $updatePost = $this->postService->UserUpdatePost(
            $post,
            $request->validated()
        );

        return response()->json([
            'message' => 'Post updated successfully.',
            'post' => $updatePost
        ]);
    }

    /**
     * Remove post belongs to user.
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
