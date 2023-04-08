<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user_name,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'password' => $this->password,
            'phone' => $this->phone,
            'address' => $this->address,
            'remember_token' => $this->remember_token,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'media_count' => $this->media_count,
            'notifications_count' => $this->notifications_count,
            'orders_count' => $this->orders_count,
            'permissions_count' => $this->permissions_count,
            'read_notifications_count' => $this->read_notifications_count,
            'roles_count' => $this->roles_count,
            'tokens_count' => $this->tokens_count,
            'unread_notifications_count' => $this->unread_notifications_count,
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
        ];
    }
}
