<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'categories' => $this->whenLoaded('categories', function () {
                return $this->categories;
            }),
            'comments' => $this->whenLoaded('comments', function () {
                return
                    request('slug') ? CommentResource::collection($this->comments) : $this->comments->count();
            }),
            'likes' => $this->whenLoaded('likes', function () {
                return $this->likes->count();
            }),
            'isLiked' => $this->isLiked ? $this->isLiked : false,
            'tag_list' =>
                $this->whenLoaded('tags', function () {
                    return [
                        'tags' => $this->tagArray,
                        'normalized' => $this->tagArrayNormalized,
                    ];
                }),
            'date' => $this->date,
            'created_at_dates' => [
                'created_at_human' => $this->created_at->formatLocalized('%d %b %Y'),
                'created_at' => $this->created_at,
            ],
        ];
    }
}
