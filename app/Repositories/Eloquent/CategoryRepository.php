<?php


namespace App\Repositories\Eloquent;


use App\Models\Category;
use App\Repositories\Contracts\ICategory;
use Illuminate\Http\Request;

class CategoryRepository extends BaseRepository implements ICategory
{

    public function model()
    {
        return Category::class;
    }

    public function catPaginatedPosts($category, array $load, $perPage, $all, $page)
    {
        return $category->posts()->with($load)->latest()->paginate($perPage, $all, 'page', $page);

    }

}
