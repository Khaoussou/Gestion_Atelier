<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApprovisionnementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "article" => new ArticleResource($this->article),
            "articleVente" => $this->article_vente_id,
            "quantite" => $this->quantite
        ];
    }
}
