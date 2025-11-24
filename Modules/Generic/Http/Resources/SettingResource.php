<?php

namespace Modules\Generic\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
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
                "phone" => $this->phone,
                "facebook" => $this->facebook,
                "twitter" => $this->twitter,
                "instagram" => $this->instagram,
                "support_email" => $this->support_email,
                "about" => $this->about,
                "terms" => $this->terms,
                "ios_version" => $this->ios_version,
                "android_version" => $this->android_version,
            ];
    }
}
