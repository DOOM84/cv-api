<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SkillResource extends JsonResource
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
            'slug' => $this->slug,
            'rate' => $this->rate,
            'color' => $this->color,
            'status' => $this->status,
        ];
    }
}
