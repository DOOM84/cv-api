<?php


namespace App\Repositories\Contracts;


use Illuminate\Http\Request;

interface IPost
{
    public function applyTags($id, array $data);
    public function addComment($postId, array $data);
    public function like($postId);
    public function isLikedByUser($postId);
    public function search($term, $perPage, $all, $page);
    public function withTags($tag, $perPage, $all, $page);

}
