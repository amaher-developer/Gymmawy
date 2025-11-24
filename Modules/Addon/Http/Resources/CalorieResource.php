<?php

namespace Modules\Addon\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CalorieResource extends JsonResource
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
                'calories' => $this->calories,
                'unit' => $this->unit,
                'unit_name' => calorie_units($this->lang)[(int)$this->unit_id],
            ];
    }
}
