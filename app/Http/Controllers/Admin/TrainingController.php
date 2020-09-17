<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminTrainingRequest;
use App\Http\Resources\adminTrainingResource;
use App\Repositories\Contracts\ITraining;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class TrainingController extends Controller
{
    protected $trainings;

    public function __construct(ITraining $trainings)
    {
        $this->trainings = $trainings;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'trainings' =>  adminTrainingResource::collection($this->trainings->orderAll('id', 'DESC'))
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
    public function store(AdminTrainingRequest $request)
    {
        $training = $this->trainings->create($request->all());

        return response()->json([
            'success' => __('auth.info_added'),
            'training' => new adminTrainingResource($training),
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
    public function update(AdminTrainingRequest $request, $id)
    {
        $training = $this->trainings->update($id, $request->except('id', 'lang'));

        return response()->json([
            'success' => __('auth.info_updated'),
            'training' => new adminTrainingResource($training),
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
        $this->trainings->delete($id);

        return response()->json([
            'success' =>  __('auth.info_deleted')
        ], 200);
    }
}
