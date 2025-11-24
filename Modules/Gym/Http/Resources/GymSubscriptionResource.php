<?php

namespace Modules\Gym\Http\Resources;

use Modules\Generic\Models\Setting;
use Illuminate\Http\Resources\Json\JsonResource;

class GymSubscriptionResource extends JsonResource
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
                'name' => $this->name,
                'phone' => $this->phone,
                'code' => $this->code,
                'gym_name' => @$this->gym->gym_brand->name,
                'gym_logo' => $this->gym->gym_brand->logo ?? Setting::first()->logo ,
                'gym_code' => asset('uploads/barcodes/' . sprintf("%020d", $this->code) . '.png') ,
            ];
    }
}
