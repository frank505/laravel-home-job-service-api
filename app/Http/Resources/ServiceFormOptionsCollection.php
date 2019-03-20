<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceFormOptionsCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        return [
            "id" => $this->id,
            "service_id"=> $this->service_id,
            "title" =>$this->title,
            "type"=> $this->type,
            "name"=> $this->name,
            "display"=> $this->display,
            "required"=> $this->required,
            "order"=> $this->order,
            "ispublic"=> $this->ispublic,
            "price"=> $this->price,
            "options"=> json_decode($this->options),
            "selected"=> $this->selected
        ];
    }
}
