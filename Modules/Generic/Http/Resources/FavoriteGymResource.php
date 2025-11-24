<?php

namespace Modules\Generic\Http\Resources;

use Modules\Gym\Http\Resources\GymCategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteGymResource extends JsonResource
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
                'id' => $this->gym->id,
                'is_favorite' => true,
                'name' => @$this->gym->gym_brand->name,
                'phone' => @$this->gym->gym_brand->main_phone,
                'logo' => @$this->gym->gym_brand->logo,
                'city_name' => @$this->gym->district->city->name,
                'district_name' => @$this->gym->district->name,
//                'category_logos' => @$this->gym->categories ? collect($this->gym->categories)->pluck('logo') : [],
                'category_logos' => @$this->gym->categories ? GymCategoryResource::collection($this->gym->categories) : [],
            ];
    }
}
