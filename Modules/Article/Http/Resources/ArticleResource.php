<?php

namespace Modules\Article\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
            [
                'id' => $this->id,
                'title' => $this->title,
                'date' => $this->arabic_date,
                'short_description' => $this->short_description,
                'image' => $this->image_thumbnail,
            ];
    }
}
