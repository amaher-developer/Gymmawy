<?php

namespace Modules\Trainer\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainerDetailResource extends JsonResource
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
                'about' => $this->about,
                'experience' => $this->experience,
                'age' => $this->age,
                'birthday' => $this->birthday,
                'gender' => $this->gender,
                'gender_name' => $this->gender_name,
                'phone' => $this->phone,
                'image' => $this->image ?? asset('resources/assets/front/img/logo/default_'.$this->lang.'.png'),
                'gym_name' => $this->gym_name,
                'website' => $this->website,
                'facebook' => $this->facebook,
                'twitter' => $this->twitter,
                'instagram' => $this->instagram,
                'linkedin' => $this->linkedin,
                'snapchat' => $this->snapchat,
                'city_name' => @$this->city->name,
                'views' => $this->views,
                'category_logos' => TrainerCategoryResource::collection($this->categories),
            ];
    }
}
