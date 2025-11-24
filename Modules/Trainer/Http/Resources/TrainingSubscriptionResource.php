<?php

namespace Modules\Trainer\Http\Resources;

use Modules\Generic\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingSubscriptionResource extends JsonResource
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
                'name' => $this->title,
                'image' => $this->image,
                'content' => $this->content,
                'date' => @Carbon::parse($this->created_at)->toDateString()
            ];
    }
}
