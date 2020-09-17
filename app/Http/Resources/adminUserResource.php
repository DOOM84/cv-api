<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class adminUserResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->image,
            'status' => $this->status,
            'is_admin' => $this->is_admin
           /* 'created_dates' => [
                'created_at_human' => $this->created_at->diffForHumans(),
                'created_at' => $this->created_at
            ],*/
        ];
    }
}
