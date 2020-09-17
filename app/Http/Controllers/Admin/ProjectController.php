<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminProjectRequest;
use App\Http\Resources\adminProjectResource;
use App\Http\Resources\adminSkillResource;
use App\Jobs\UploadProject;
use App\Repositories\Contracts\IProject;
use App\Repositories\Contracts\ISkill;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    protected $projects, $skills;

    public function __construct(IProject $projects, ISkill $skills)
    {
        $this->projects = $projects;
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
            'projects' => adminProjectResource::collection($this->projects
                ->withCriteria([
                    new EagerLoad(['skills'])
                ])
            ->all()),
            'skills' => adminSkillResource::collection($this->skills->all()),
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminProjectRequest $request)
    {
        $request['ids'] = explode(",", $request['ids']);

        $request['status'] = json_decode($request->status) ? true : false;

        $project = $this->projects->create($request->except('ids', 'image', 'lang'));

        if($request->hasfile('image'))
        {
            $file = $request->file('image');
            $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($file->getClientOriginalName()));
            $tmp = $file->storeAs('uploads/Projects/original', $filename, 'tmp');
            /*$uploadedFile = $this->details->applyImage($request->name ? $request->name : '',
                $filename, json_decode($request->status) ? true : false);*/
            $project->image = $filename;
            $project->save();
            $this->dispatch(new UploadProject($project));
        }
        $this->projects->syncRelation($project->id, 'skills', $request->ids);

        return response(['success' => __('auth.project_added'),
            'project' => new adminProjectResource($project),
        ], Response::HTTP_CREATED);
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
     * @return \Illuminate\Http\Response
     */
    public function update(AdminProjectRequest $request, $id)
    {
            $request['ids'] = explode(",", $request['ids']);

            $request['status'] = json_decode($request->status) ? true : false;

            $this->projects->syncRelation($id, 'skills', $request->ids);
            $project = $this->projects->update($id, $request->except('ids', 'image', 'lang'));

        if($request->hasfile('image'))
        {
            $file = $request->file('image');
            $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($file->getClientOriginalName()));
            $tmp = $file->storeAs('uploads/Projects/original', $filename, 'tmp');

            foreach (['thumbnail', 'original'] as $size) {
                //check if file exists
                if (Storage::disk('public')->exists("uploads/Projects/{$size}/".$project->image))
                {
                    Storage::disk('public')->delete("uploads/Projects/{$size}/".$project->image);
                }
            }

            $project->image = $filename;
            $project->save();
            $this->dispatch(new UploadProject($project));
        }

        return response(['success' => __('auth.project_updated'),
            'project' => new adminProjectResource($project),
            ], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->projects->detachRelation($id, 'skills');

        $project = $this->projects->find($id);

        foreach (['thumbnail', 'original'] as $size) {
            //check if file exists
            if (Storage::disk('public')->exists("uploads/Projects/{$size}/".$project->image))
            {
                Storage::disk('public')->delete("uploads/Projects/{$size}/".$project->image);
            }
        }

        $this->projects->delete($id);

        return response(['success' => __('auth.project_deleted')], Response::HTTP_ACCEPTED);
    }
}
