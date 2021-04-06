<?php

namespace App\Http\Resources;

use App\Models\Continent;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            'continent'=>new ContinentResource(Continent::findOrFail($this->continent_id)),
        ];
    }
}
