<?php

namespace App\Http\Resources;

use App\Models\Label;
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
            'id'=>$this->id,
            'name'=>$this->name,
            'user'=>new UserResource(User::findOrFail($this->user->id)),
            'linked_users'=> new UserCollection($this->linked_users),
            'labels'=>new LabelCollection($this->labels),
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,

        ];
    }
}
