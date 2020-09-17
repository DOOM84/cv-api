<?php


namespace App\Repositories\Contracts;


use Illuminate\Http\Request;

interface ICategory
{
    public function catPaginatedPosts($category, array $load, $perPage, $all, $page);

}
