<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            //'stock' => $this->stock == 0 ? 'Out of Stock' : $this->stock,
            'image_url'=> $this->image_url,
            'quantity'=>$this->quantity,
            'category_id'=>$this->category_id,
            'user_id'=>$this->user_id,
            //'discount' =>$this->discount,
          //  'totalPrice' => round(( 1 - ($this->discount/100)) * $this->price,2),
            'rating' => $this->reviews->count() > 0 ? round($this->reviews->sum('star')/$this->reviews->count(),2) : 'No rating yet',
            'href' => [
                'reviews' => route('reviews.index',$this->id)
            ]
            ];
    }
}
