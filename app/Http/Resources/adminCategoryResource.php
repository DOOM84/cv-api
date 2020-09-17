<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class adminCategoryResource extends JsonResource
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
            'ua_name' => $this->ua_name,
            'en_name' => $this->en_name,
            'status' => $this->status,
        ];
    }
}
