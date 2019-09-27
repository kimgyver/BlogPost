<?php

namespace App\Http\Controllers;

use App\BlogPost;
use App\Http\Requests\StoreComment;
use Illuminate\Http\Request;
use App\Http\Resources\Comment as CommentResource;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }

    public function index(BlogPost $post)
    {
        return CommentResource::collection($post->comments()->with('user')->get());
        //return $post->comments()->with('user')->get();
    }

    public function store(BlogPost $post, StoreComment $request)
    {
        $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id,
        ]);
        
        $request->session()->flash('status', 'Comment was created!');

        return redirect()->back();
    }
}
