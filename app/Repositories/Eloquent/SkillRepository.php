<?php


namespace App\Repositories\Eloquent;


use App\Models\Skill;
use App\Repositories\Contracts\ISkill;
use Illuminate\Http\Request;

class SkillRepository extends BaseRepository implements ISkill
{

    public function model()
    {
        return Skill::class;
    }

}
