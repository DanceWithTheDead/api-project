<?php

namespace App\Services;

use App\Models\Post;


class PostService
{
    public function UserCreatePost(array $data)
    {
        return Post::create([
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'content' => $data['content'],
        ]);
    }

}
