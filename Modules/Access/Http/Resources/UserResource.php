<?php

namespace Modules\Access\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $email = $this->email;
        if (isset($email) && stristr($email, '@gymmawy.com') !== false) {
            $email = '';
        }
        return
            [
                "id" => $this->id,
                "email" => $email,
                "name" => $this->name,
                "phone" => $this->phone,
//                "about" => $this->about,
//                "image" => $this->image,
//                "facebook_id" => $this->facebook_id,
//                "twitter_id" => $this->twitter_id,
//                "google_id" => $this->google_id,
//                "instagram_id" => $this->instagram_id,
            ];
    }
}
