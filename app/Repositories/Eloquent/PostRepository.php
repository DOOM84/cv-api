<?php


namespace App\Repositories\Eloquent;


use App\Models\Post;
use App\Repositories\Contracts\IPost;
use Illuminate\Http\Request;

class PostRepository extends BaseRepository implements IPost
{

    public function model()
    {
        return Post::class;
    }

    public function applyTags($id, array $data)
    {
        $post = $this->find($id);
        $post->retag($data);
    }

    public function addComment($postId, array $data)
    {
        $post = $this->find($postId);
        return $post->comments()->create($data)->load('user');

    }

    public function search($term, $perPage, $all, $page)
    {
        return $this->model->where('status', 1)->searching($term)->latest()->paginate($perPage, $all, 'page', $page);
    }

    public function like($postId)
    {
        $post = $this->model->findOrFail($postId);
        if($post->isLikedByUser(auth()->id()))
        {
            $post->unlike();
        }else{
            $post->like();
        }

        return $post->likes()->count();
    }


    public function isLikedByUser($postId)
    {
        $post = $this->model->findOrFail($postId);

        return $post->isLikedByUser(auth()->id());

    }

    public function withTags($tag, $perPage, $all, $page)
    {
        return $this->model->withAllTags($tag)->latest()->paginate($perPage, $all, 'page', $page);
    }

}
