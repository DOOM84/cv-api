<?php


namespace App\Repositories\Eloquent;


use App\Models\Training;
use App\Repositories\Contracts\ITraining;
use Illuminate\Http\Request;

class TrainingRepository extends BaseRepository implements ITraining
{

    public function model()
    {
        return Training::class;
    }

}
