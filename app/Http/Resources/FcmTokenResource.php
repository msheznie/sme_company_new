<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FcmTokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'userID' => $this->userID,
            'fcm_token' => $this->fcm_token,
            'deviceType' => $this->deviceType
        ];
    }
}
