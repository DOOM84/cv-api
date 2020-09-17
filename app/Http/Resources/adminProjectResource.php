<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class adminProjectResource extends JsonResource
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
            'ru_name' => $this->ru_name,
            'en_name' => $this->en_name,
            'ua_name' => $this->ua_name,
            'ru_details' => $this->ru_details,
            'ua_details' => $this->ua_details,
            'en_details' => $this->en_details,
            'skills' => $this->skills,
            'ids' => $this->skills->pluck('id'),
            'images' => $this->images,
            'status' => $this->status
        ];
    }
}
