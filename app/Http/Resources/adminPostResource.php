<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class adminPostResource extends JsonResource
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
            'slug' => $this->slug,
            'ru_title' => $this->ru_title,
            'en_title' => $this->en_title,
            'ua_title' => $this->ua_title,
            'ru_subtitle' => $this->ru_subtitle,
            'en_subtitle' => $this->en_subtitle,
            'ua_subtitle' => $this->ua_subtitle,
            'body' => $this->body,
            'source' => $this->source,
            'author' => $this->author,
            'images' => $this->images,
            'status' => $this->status,
            'tags' => $this->tagArray,
            'categories' => $this->categories,
            'ids' => $this->categories->pluck('id'),
            /*'tag_list' => [
                'tags' => $this->tagArray,
                'normalized' => $this->tagArrayNormalized,
            ],*/
        ];
    }
}
