<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Repositories\Contracts\IComment;
use App\Repositories\Contracts\IPost;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $comments, $posts;


    public function __construct(IComment $comments, IPost $posts)
    {
        $this->comments = $comments;
        $this->posts = $posts;
    }

    public function store(Request $request, $postId)
    {
        $request['body'] = clean($request->body);

        $this->validate($request, [
           'body' => 'required|string'
        ],
        [
            'body.required' => __('validation.custom.body.required'),
            'body.string' => __('validation.custom.body.string'),
        ]);

        $comment = $this->posts->addComment($postId, [
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'comment' => new CommentResource($comment),
            'success' => __('auth.comment_added'),
        ]);

    }

    public function removeComment($commentId)
    {
        $this->comments->delete($commentId);

        return response()->json([
            'success' =>  __('auth.comment_deleted')
        ], 200);
    }
}
