<?php

namespace App\Http\Resources;

use App\Models\Country;
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
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'country'=>new CountryResource(Country::findOrFail($this->country_id)),
            'email_verified_at'=>$this->email_verified_at,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at

        ];
    }
}
