<?php

namespace Modules\Generic\Http\Resources;

use Modules\Gym\Http\Resources\GymCategoryResource;
use Modules\Trainer\Http\Resources\TrainerCategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteTrainerResource extends JsonResource
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
                'id' => $this->trainer->id,
                'is_favorite' => true,
                'name' => $this->trainer->name,
                'gender_name' => $this->trainer->gender_name,
                'phone' => $this->trainer->phone,
                'image' => $this->trainer->image_thumbnail,
                'gym_name' => $this->trainer->gym_name,
                'website' => $this->trainer->website,
                'facebook' => $this->trainer->facebook,
                'twitter' => $this->trainer->twitter,
                'instagram' => $this->trainer->instagram,
                'linkedin' => $this->trainer->linkedin,
                'city_name' => @$this->trainer->city->name,
//                'category_logos' => @$this->trainer->categories ? collect($this->trainer->categories)->pluck('logo') : [],
                'category_logos' => @$this->trainer->categories ? TrainerCategoryResource::collection($this->trainer->categories) : [],
            ];
    }
}
