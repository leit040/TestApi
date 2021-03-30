<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'name'=>$this->name,
            'user'=>new UserResource(User::find($this->user_id)),
            'users'=>UserResource::collection($this->users),
            'labels'=>LabelResource::collection($this->labels),
        ];
    }
}
