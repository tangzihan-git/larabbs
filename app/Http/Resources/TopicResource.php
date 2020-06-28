<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TopicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $data['user'] = new UserResource($this->whenLoaded('user'));//user是否预加载，预加载则返回用户资源
        $data['category'] = new CategoryResource($this->whenLoaded('category'));
        return $data;
    }
}
