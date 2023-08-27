<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleVenteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "libelle" => $this->libelle,
            "categorie" => $this->categorie->libelle,
            "prix" => $this->prix_de_vente,
            "reference" => $this->reference,
            "image" => $this->image,
            "promo" => $this->promo,
            "stock" => $this->stock,
            "confection" => $this->article
        ];
    }
}
