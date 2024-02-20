<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
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
            'employee_id' => $this->employee_id,
            'empID' => $this->empID,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'password' => $this->password,
            'remember_token' => $this->remember_token,
            'login_token' => $this->login_token,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'uuid' => $this->uuid
        ];
    }
}
