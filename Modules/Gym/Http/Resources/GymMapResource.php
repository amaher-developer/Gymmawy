<?php

namespace Modules\Gym\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GymMapResource extends JsonResource
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
                'name' => @$this->gym_brand->name,
                'phone' => @$this->gym_brand->main_phone ? strtok($this->gym_brand->main_phone, ',') : null,
                'logo' => @$this->gym_brand->logo,
                'city_name' => @$this->district->city->name,
                'district_name' => @$this->district->name,
                'lat' => @$this->lat,
                'lng' => @$this->lng,
//                'category_logos' => @$this->categories ? collect($this->categories)->pluck('logo') : '',
                'category_logos' => $this->categories ? GymCategoryResource::collection($this->categories) : [],
                'discount' => $this->discount ? new GymDiscountResource($this->discount) : null,
            ];
    }
}
