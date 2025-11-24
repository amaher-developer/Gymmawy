<?php

namespace Modules\Gym\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GymDetailResource extends JsonResource
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

                'website' => $this->gym_brand->socials['website'] ?? '',
                'facebook' => $this->gym_brand->socials['facebook'] ?? '',
                'twitter' => $this->gym_brand->socials['twitter'] ?? '',
                'instagram' => $this->gym_brand->socials['instagram'] ?? '',
                'linkedin' => $this->gym_brand->socials['linkedin'] ?? '',
                'snapchat' => $this->gym_brand->socials['snapchat'] ?? '',

                'logo' => $this->gym_brand->logo ?? asset('resources/assets/front/img/logo/default_'.$this->lang.'.png'),
                'main_phone' => $this->gym_brand->main_phone,
                'name' => $this->gym_brand->name,
                'description' => $this->gym_brand->description,


                'cover_image' => $this->cover_image ?? asset('resources/assets/front/img/bg/gyms.jpg'),
                'image' => $this->image ?? asset('resources/assets/front/img/logo/default_'.$this->lang.'.png'),
                'phones' => $this->phones,
                'views' => $this->views,
                'lat' => $this->lat,
                'lng' => $this->lng,
                'address' => $this->address,

                'city_name' => $this->district->city->name,
                'district_name' => $this->district->name,
                'images' => GymImageResource::collection($this->images),
                'services' => GymServiceResource::collection($this->services),
                'categories' => GymCategoryResource::collection($this->categories),
                'discount' => new GymDiscountResource($this->discount),
            ];
    }
}
