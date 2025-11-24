<?php

namespace Modules\Generic\Http\Resources;

use Modules\Generic\Models\Setting;
use Modules\Gym\Http\Resources\GymCategoryResource;
use Modules\Trainer\Http\Resources\TrainerCategoryResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
                'title' => $this->body['title'],
                'type' => $this->body['type'],
                'body' => $this->body['body'],
                'url' => $this->body['url'] ?? '',
                'image' => $this->body['image'] ?? asset('resources/assets/front/img/logo/favicon.ico'),//Setting::first()->logo,
                'date' => Carbon::parse($this->created_at)->toDateTimeString()
            ];


    }
}
