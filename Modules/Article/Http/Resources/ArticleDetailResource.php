<?php

namespace Modules\Article\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleDetailResource extends JsonResource
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
                'description' => $this->description,
                'image' => $this->image,
                'views' => $this->views,
            ];
    }
}
