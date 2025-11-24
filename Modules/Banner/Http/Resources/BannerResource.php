<?php

namespace Modules\Banner\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
                'image' => $this->image,
                'title' => $this->title,
                'phone' => $this->phone,
                'url' => $this->url,
                'gym_id' => $this->gym_id,
                'category_id' => $this->category_id,
            ];
    }
}
