<?php


namespace App\Repositories\Eloquent;


use App\Models\Project;
use App\Repositories\Contracts\IProject;
use Illuminate\Http\Request;

class ProjectRepository extends BaseRepository implements IProject
{

    public function model()
    {
        return Project::class;
    }

}
