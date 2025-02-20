<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use JsonSerializable;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'photo_url' => ! empty($this->photo) ? Storage::disk('public')->url($this->photo) : Storage::disk('public')->url('../assets/img/no-image.png'),
            'phone_number' => $this->phone_number,
            'updated_security' => $this->updated_security,
            'user_roles_id' => (string) $this->user_roles_id,
            'access' => isset($this->role->access) ? json_decode($this->role->access) : [],
        ];
    }
}
