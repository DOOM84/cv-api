<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [

            'name' => $this->name,
            $this->mergeWhen(auth()->check() && auth()->id() == $this->id, [
                'email' => $this->email,
                'id' => $this->id,
            ]),
            $this->mergeWhen(auth()->check() && auth()->id() == $this->id && auth()->user()->is_admin == 1, [
                'can_remove' => $this->is_admin,
            ]),
            'avatar' => $this->image,
           /* 'created_dates' => [
                'created_at_human' => $this->created_at->diffForHumans(),
                'created_at' => $this->created_at
            ],*/
        ];
    }
}
