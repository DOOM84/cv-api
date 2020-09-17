<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminPostRequest;
use App\Http\Resources\adminCategoryResource;
use App\Http\Resources\adminPostResource;
use App\Jobs\UploadPost;
use App\Repositories\Contracts\ICategory;
use App\Repositories\Contracts\IPost;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    protected $posts, $categories;

    public function __construct(IPost $posts, ICategory $categories)
    {
        $this->posts = $posts;
        $this->categories = $categories;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'posts' => adminPostResource::collection($this->posts
                ->withCriteria([
                    new EagerLoad(['categories', 'tags'])
                ])
                ->all()),
            'categories' => adminCategoryResource::collection($this->categories->all()),
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminPostRequest $request)
    {
        $request['ids'] = explode(",", $request['ids']);

        $request['status'] = json_decode($request->status) ? true : false;

        $post = $this->posts->create($request->except('ids', 'image', 'lang', 'check'));

        $this->posts->applyTags($post->id, json_decode($request->tags, true));


        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($file->getClientOriginalName()));
            $tmp = $file->storeAs('uploads/Posts/original', $filename, 'tmp');

            $this->posts->update($post->id, [
                'image' => $filename
            ]);

            $this->dispatch(new UploadPost($post));
        }
        $this->posts->syncRelation($post->id, 'categories', $request->ids);

        return response(['success' => __('auth.post_added'),
            'post' => new adminPostResource($post->refresh()),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminPostRequest $request, $id)
    {
        $request['ids'] = explode(",", $request['ids']);

        $request['status'] = json_decode($request->status) ? true : false;

        $this->posts->syncRelation($id, 'categories', $request->ids);

        $post = $this->posts->update($id, $request->except('ids', 'image', 'lang'));

        $this->posts->applyTags($post->id, json_decode($request->tags, true));

        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($file->getClientOriginalName()));
            $tmp = $file->storeAs('uploads/Posts/original', $filename, 'tmp');

            foreach (['thumbnail', 'original'] as $size) {
                //check if file exists
                if (Storage::disk('public')->exists("uploads/Posts/{$size}/" . $post->image)) {
                    Storage::disk('public')->delete("uploads/Posts/{$size}/" . $post->image);
                }
            }

            $this->posts->update($id, [
                'image' => $filename
            ]);

            //$post->image = $filename;
            // $post->save();

            $this->dispatch(new UploadPost($post));
        }

        return response(['success' => __('auth.post_updated'),
            'post' => new adminPostResource($post->refresh()),
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->posts->detachRelation($id, 'categories');
        $this->posts->deleteRelation($id, 'comments');

        $post = $this->posts->find($id);

        foreach (['thumbnail', 'original'] as $size) {
            //check if file exists
            if (Storage::disk('public')->exists("uploads/Posts/{$size}/" . $post->image)) {
                Storage::disk('public')->delete("uploads/Posts/{$size}/" . $post->image);
            }
        }

        $this->posts->delete($id);

        return response(['success' => __('auth.post_deleted')], Response::HTTP_ACCEPTED);
    }
}
