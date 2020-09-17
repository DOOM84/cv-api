<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Image;
use File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadAvatar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $disk = 'public'; //$this->design->disk;
        $filename =  $this->user->avatar;
        $original_file = storage_path() . '/uploads/Avatars/original/' . $filename;

        try {
            //create the large Img and save to tmp disk
            Image::make($original_file)->resize(80, 80)
                ->save($large = storage_path('uploads/Avatars/original/'. $filename));

            if(Storage::disk($disk)
                ->put('uploads/Avatars/original/'. $filename, fopen($original_file, 'r+'))){
                File::delete($original_file);
            }

        }catch (\Exception $e){
            \Log::error($e->getMessage());

        }
    }
}
