<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminSkillRequest;
use App\Http\Resources\adminSkillResource;
use App\Repositories\Contracts\ISkill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class SkillController extends Controller
{
    protected $skills;

    public function __construct(ISkill $skills)
    {
        $this->skills = $skills;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'skills' =>  adminSkillResource::collection($this->skills->all())
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
    public function store(AdminSkillRequest $request)
    {
        $request['status'] = json_decode($request->status) ? true : false;
        $skill = $this->skills->create($request->all());

        return response()->json([
            'success' => __('skill_added'),
            'skill' => new adminSkillResource($skill),
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
    public function update(AdminSkillRequest $request, $id)
    {
        $request['status'] = json_decode($request->status) ? true : false;
        $skill = $this->skills->update($id, $request->except('id', 'lang'));

        return response()->json([
            'success' => __('auth.skill_updated'),
            'skill' => new adminSkillResource($skill),
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
        $this->skills->delete($id);

        return response()->json([
            'success' =>  __('auth.skill_deleted')
        ], 200);
    }
}
