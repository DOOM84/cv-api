<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\SkillResource;
use App\Http\Resources\TrainingResource;
use App\Repositories\Contracts\ICategory;
use App\Repositories\Contracts\IPost;
use App\Repositories\Contracts\IProject;
use App\Repositories\Contracts\ISkill;
use App\Repositories\Contracts\ITraining;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    protected $trainings, $skills, $projects, $posts, $categories, $perPage = 10;


    public function __construct(ITraining $trainings,
                                ISkill $skills,
                                IProject $projects,
                                IPost $posts,
                                ICategory $categories)
    {
        $this->trainings = $trainings;
        $this->skills = $skills;
        $this->projects = $projects;
        $this->posts = $posts;
        $this->categories = $categories;
    }

    public function index()
    {
        return response()->json([
            'trainings' => TrainingResource::collection($this->trainings->orderAll('id', 'DESC')),
            'skills' => SkillResource::collection($this->skills->findWhere('status', 1)),
            'age' => Carbon::create(1984, 6, 7)->diffInYears(Carbon::now()),
            'projects' => ProjectResource::collection($this->projects
                ->withCriteria([new EagerLoad(['skills'])])->all()),
            'posts' => PostResource::collection($this->posts
                ->withCriteria([new EagerLoad(['comments', 'likes'])])
                ->findWhereLatestAndLimit('status', 1, 3)),
        ]);
    }

    public function post($slug)
    {
        $recentPosts = $this->posts->findWhereLatestAndLimit('status', 1, 6);

        $post = $this->posts
            ->withCriteria([new EagerLoad(['comments.user', 'likes', 'tags'])])
            ->findFirstBySeveralColumns(['slug' => $slug, 'status' => 1]);

        $isLiked = $this->posts->isLikedByUser($post->id);

        $post['isLiked'] = $isLiked;

        return response()->json([
                'recentPosts' => PostResource::collection($recentPosts),
                'post' => new PostResource($post),
                'tags' => $this->posts->getTag(),
                'categories' => CategoryResource::collection($this->categories->findWhere('status', 1)),
            ]
        );
    }

    public function blog()
    {
        $recentPosts = $this->posts->findWhereLatestAndLimit('status', 1, 6);
        $posts = $this->posts
            ->withCriteria([new EagerLoad(['comments', 'likes'])])
            ->findWhereAndPaginate('status', 1, $this->perPage, ['*'], 1);

        return response()->json([
                'recentPosts' => PostResource::collection($recentPosts),
                'posts' => PostResource::collection($posts),
                'tags' => $this->posts->getTag(),
                'categories' => CategoryResource::collection($this->categories->findWhere('status', 1)),
                'postsPages' => $posts->lastPage(),
                'postsTotal' => $posts->total(),
                'currentPage' => $posts->currentPage(),
            ]
        );
    }

    public function getPagedPosts($page)
    {
        $posts = $this->posts
            ->withCriteria([new EagerLoad(['comments', 'likes'])])
            ->findWhereAndPaginate('status', 1, $this->perPage, ['*'], $page);

        return response()->json([
                'posts' => PostResource::collection($posts),
                'postsPages' => $posts->lastPage(),
                'postsTotal' => $posts->total(),
                'currentPage' => $posts->currentPage(),
            ]
        );

    }

    public function search(Request $request)
    {
        $request['search'] = trim($request->search);
        $request['page'] = $request->page;

        $this->validate($request, [
            'search' => 'required|string|min:3|max:255',
            'page' => 'sometimes|numeric',
        ], [
            'search.required' => __('validation.required'),
            'search.string' => __('validation.string'),
            'search.max' => __('validation.max.numeric'),
            'search.min' => __('validation.min.numeric'),
            'page.numeric' => __('validation.numeric'),
        ]);

        $recentPosts = $this->posts->findWhereLatestAndLimit('status', 1, 6);
        $posts = $this->posts->withCriteria([new EagerLoad(['comments', 'likes'])])
            ->search($request->search, $this->perPage, ['*'], $request->page);

        return response()->json([
                'recentPosts' => PostResource::collection($recentPosts),
                'posts' => PostResource::collection($posts),
                'tags' => $this->posts->getTag(),
                'categories' => CategoryResource::collection($this->categories->findWhere('status', 1)),
                'postsPages' => $posts->lastPage(),
                'postsTotal' => $posts->total(),
                'currentPage' => $posts->currentPage(),
            ]
        );
    }

    public function searchPostsOnly(Request $request)
    {
        $request['search'] = trim($request->search);
        $request['page'] = $request->page;

        $this->validate($request, [
            'search' => 'required|string|min:3|max:255',
            'page' => 'sometimes|numeric',
        ], [
            'search.required' => __('validation.required'),
            'search.string' => __('validation.string'),
            'search.max' => __('validation.max.numeric'),
            'search.min' => __('validation.min.numeric'),
            'page.numeric' => __('validation.numeric'),
        ]);

        $posts = $this->posts->withCriteria([new EagerLoad(['comments', 'likes'])])
            ->search($request->search, $this->perPage, ['*'], $request->page);

        return response()->json([
                'posts' => PostResource::collection($posts),
                'postsPages' => $posts->lastPage(),
                'postsTotal' => $posts->total(),
                'currentPage' => $posts->currentPage(),
            ]
        );
    }

    public function category($category)
    {
        $recentPosts = $this->posts
            ->findWhereLatestAndLimit('status', 1, 6);

        $categories = $this->categories->findWhere('status', 1);

        $category = $this->categories
            ->withCriteria([new EagerLoad(['posts'])])
            ->findFirstBySeveralColumns(['slug' => $category, 'status' => 1]);

        $posts = $this->categories
            ->catPaginatedPosts($category, ['comments', 'likes'], $this->perPage, ['*'], 1);

        return response()->json([
                'recentPosts' => PostResource::collection($recentPosts),
                'tags' => $this->categories->getTag(),
                'categories' => CategoryResource::collection($categories),
                'category' => new CategoryResource($category),
                'postsPages' => $posts->lastPage(),
                'postsTotal' => $posts->total(),
                'currentPage' => $posts->currentPage(),
                'posts' => PostResource::collection($posts),

            ]
        );
    }

    public function getPagedCats($category, $page)
    {
        $category = $this->categories->withCriteria([new EagerLoad(['posts'])])
            ->findFirstBySeveralColumns(['slug' => $category, 'status' => 1]);

        $posts = $this->categories
            ->catPaginatedPosts($category, ['comments', 'likes'], $this->perPage, ['*'], $page);

        return response()->json([
                'posts' => PostResource::collection($posts),
                'postsPages' => $posts->lastPage(),
                'postsTotal' => $posts->total(),
                'currentPage' => $posts->currentPage(),
            ]
        );

    }
    public function tag($tag)
    {
        $recentPosts = $this->posts->findWhereLatestAndLimit('status', 1, 6);

        $categories = $this->categories->findWhere('status', 1);

        $posts = $this->posts->withCriteria([new EagerLoad(['comments', 'likes'/*, 'tags'*/])])
            ->withTags($tag, $this->perPage, ['*'], 1);

        if(!$posts->count()){return response()->json([], 404);}

        return response()->json([
                'recentPosts' => PostResource::collection($recentPosts),
                'tags' => $this->posts->getTag(),
                'categories' => CategoryResource::collection($categories),
                'postsPages' => $posts->lastPage(),
                'postsTotal' => $posts->total(),
                'currentPage' => $posts->currentPage(),
                'posts' => PostResource::collection($posts),
                'tag' => $this->posts->getTag($tag, 'app\Models\Post'),

            ], 200
        );
    }

    public function getPagedTag($tag, $page)
    {
        $posts = $this->posts->withCriteria([new EagerLoad(['comments', 'likes'])])
            ->withTags($tag, $this->perPage, ['*'], $page);

        return response()->json([
                'posts' => PostResource::collection($posts),
                'postsPages' => $posts->lastPage(),
                'postsTotal' => $posts->total(),
                'currentPage' => $posts->currentPage(),
            ]
        );

    }
}
