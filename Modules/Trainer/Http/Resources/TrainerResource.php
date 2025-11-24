<?php

namespace Modules\Trainer\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainerResource extends JsonResource
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
                'is_favorite' => $this->is_favorite ? true : false,
                'name' => $this->name,
                'gender' => $this->gender,
                'gender_name' => $this->gender_name,
                'phone' => $this->phone,
                'image' => $this->image_thumbnail ?? asset('resources/assets/front/img/logo/default_'.$this->lang.'.png'),
                'gym_name' => $this->gym_name,
                'website' => $this->website,
                'facebook' => $this->facebook,
                'twitter' => $this->twitter,
                'instagram' => $this->instagram,
                'linkedin' => $this->linkedin,
                'snapchat' => $this->snapchat,
                'city_name' => @$this->city->name,
//                'category_logos' => collect($this->categories)->pluck('logo'),
                'category_logos' => TrainerCategoryResource::collection($this->categories),
            ];
    }
}
