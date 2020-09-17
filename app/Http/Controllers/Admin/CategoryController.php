<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminCategoryRequest;
use App\Http\Resources\adminCategoryResource;
use App\Repositories\Contracts\ICategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    protected $categories;

    public function __construct(ICategory $categories)
    {
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
            'categories' =>  adminCategoryResource::collection($this->categories->all())
        ], 200);

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AdminCategoryRequest $request)
    {
        $request['status'] = json_decode($request->status) ? true : false;
        $category = $this->categories->create($request->except('lang'));

        return response()->json([
            'success' => __('auth.category_added'),
            'category' => new adminCategoryResource($category),
        ], Response::HTTP_ACCEPTED);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AdminCategoryRequest $request, $id)
    {

        $request['status'] = json_decode($request->status) ? true : false;
        $category = $this->categories->update($id, $request->except('id', 'lang'));

        return response()->json([
            'success' => __('auth.category_updated'),
            'category' => new adminCategoryResource($category),
        ], Response::HTTP_ACCEPTED);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */


    public function destroy($id)
    {
        $this->categories->delete($id);

        return response()->json([
            'success' =>  __('auth.category_deleted')
        ], 200);
    }
}
