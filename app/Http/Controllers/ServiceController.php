<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvatarRequest;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\ResetPassRequest;
use App\Jobs\UploadAvatar;
use App\Mail\SendContact;
use App\Mail\SendPass;
use App\Repositories\Contracts\IComment;
use App\Repositories\Contracts\IPost;
use App\Repositories\Contracts\IUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends Controller
{
    protected $users, $posts, $comments;


    public function __construct(IUser $users, IPost $posts, IComment $comments)
    {
        $this->users = $users;
        $this->posts = $posts;
        $this->comments = $comments;
    }

    public function getPass(ResetPassRequest $request)
    {
        $pass = Str::random(10);
        $user = $this->users->findWhereFirstNotFail('email', $request->email);
        if ($user) {
            $this->users->update($user->id, ['password' => $pass]);
            Mail::send(new SendPass($pass));
            return response()->json(['success' => __('passwords.sent')
            ], 200);
        }
        return response()->json([
            'errors' => [
                'email' => [__('auth.failed')]
            ]
        ], 422);
    }

    public function like($postId)
    {
        return response()->json([
            'likes' => $this->posts->like($postId),
            'isLiked' => $this->posts->isLikedByUser($postId),
            'success' => __('auth.like')
        ], 200);
    }

    public function avatar(AvatarRequest $request)
    {
        if($request->hasfile('image'))
        {
            $file = $request->file('image');
            $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($file->getClientOriginalName()));
            $tmp = $file->storeAs('uploads/Avatars/original', $filename, 'tmp');

                //check if file exists
            if(auth()->user()->avatar){
                if (Storage::disk('public')->exists("uploads/Avatars/original/".auth()->user()->avatar))
                {
                    Storage::disk('public')->delete("uploads/Avatars/original/".auth()->user()->avatar);
                }
            }

            $user = $this->users->update(auth()->user()->id, [
                'avatar' => $filename
            ]);

            $this->dispatch(new UploadAvatar($user));
        }

        return response(['success' => __('auth.ava_changed'),
        ], Response::HTTP_ACCEPTED);
    }

    public function contact(ContactRequest $request)
    {
        try {
            Mail::send(new SendContact());
        } catch (\Exception $e) {
            return response()->json([
                'errors' => [
                    'failed' => [__('auth.error')]
                ]
            ], 500);
        }
        return response()->json(['success' =>__('auth.mes_sent')], 200);
    }

    public function admin()
    {
        if(!auth()->user() || !auth()->user()->is_admin){
            return 0;
        }
        return 1;
    }


}
