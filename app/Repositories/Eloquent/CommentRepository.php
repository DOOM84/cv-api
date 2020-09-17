<?php


namespace App\Repositories\Eloquent;


use App\Models\Comment;
use App\Repositories\Contracts\IComment;
use Illuminate\Http\Request;

class CommentRepository extends BaseRepository implements IComment
{

    public function model()
    {
        return Comment::class;
    }

}
