<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/posts', function () {
    return response()->json([
        'posts' => [
            ['id' => 1, 'title' => 'Post 1', 'content' => 'This is post 1'],
            ['id' => 2, 'title' => 'Post 2', 'content' => 'This is post 2'],
        ]
    ]);
});
