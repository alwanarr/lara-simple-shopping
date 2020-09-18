<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SellerResource;
class ProductResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'image' => url('https://www.harmoni/' .$this->image),
            'seller' => new SellerResource($this->seller)
        ];
    }
    
}
