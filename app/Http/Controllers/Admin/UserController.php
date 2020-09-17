<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\adminUserResource;
use App\Repositories\Contracts\IUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected $users;

    public function __construct(IUser $users)
    {
        $this->users = $users;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'users' => adminUserResource::collection($this->users->all())
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
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterRequest $request)
    {
        $request['status'] = !$request['status'] ? 0 : 1;
        $request['is_admin'] = !$request['is_admin'] ? 0 : 1;
        $user = $this->users->create($request->except( 'avatar', 'lang'));

        return response(['success' => __('auth.user_added'),
            'user' => new adminUserResource($user),
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
    public function update(UpdateUserRequest $request, $id)
    {
        $request['status'] = !$request['status'] ? 0 : 1;
        $request['is_admin'] = !$request['is_admin'] ? 0 : 1;

        if (!$request->get('password')) {
            $user = $this->users->update($id, $request->except('password', 'avatar', 'lang'));
        } else {
            $user = $this->users->update($id, $request->except( 'avatar', 'lang'));
        }
        return response(['success' => __('auth.user_updated'),
            'user' => new adminUserResource($user),
            ], Response::HTTP_ACCEPTED);
    }

    public function delAvatar(Request $request)
    {
        $user = $this->users->find($request->id);

        if (Storage::disk('public')->exists("uploads/Avatars/original/".$user->avatar))
        {
            Storage::disk('public')->delete("uploads/Avatars/original/".$user->avatar);
        }

        $user = $this->users->update($request->id, [
            'avatar' => null
        ]);

        return response(['success' => __('auth.ava_deleted'), 'avatar' => $user->image], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->users->find($id);

        if (Storage::disk('public')->exists("uploads/Avatars/original/".$user->avatar))
        {
            Storage::disk('public')->delete("uploads/Avatars/original/".$user->avatar);
        }

        $this->users->delete($id);

        return response(['success' => __('auth.user_deleted')], Response::HTTP_ACCEPTED);
    }
}
