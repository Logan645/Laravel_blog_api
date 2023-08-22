<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $article = parent::toArray($request);

        $article['title'] = $article['title'] . ' - My Blog';

        // $article['category']= $this->category['name'];

        // $article['tags']= $this->tags;
        
        return $article;
    }
}
