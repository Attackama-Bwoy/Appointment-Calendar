<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class Appointment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'title' => strtoupper($this->user->email),
            'start' => $this->begin,
            'end' => $this->end,
            'session' => $this->session,
            'color' => $this->color,
            'textColor' => $this->textColor,
            'user_id' => $this->user_id,
            $this->mergeWhen(Auth::user()->isAdmin, [
                'name' => $this->user->name,
                'email' => $this->user->email,
            ])
        ];
    }
}
