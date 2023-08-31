<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "categorie" => new CategorieConfectionResource($this->categorie),
            "libelle" => $this->libelle,
            "prix" => $this->prix
        ];
    }
}
