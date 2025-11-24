<?php

namespace Modules\Generic\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityDistrictResource extends JsonResource
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
                'lat' => $this->lat,
                'lng' => $this->lng,
                'districts' => DistrictResource::collection($this->district),
            ];
    }
}
