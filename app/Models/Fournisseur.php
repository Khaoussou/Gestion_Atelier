<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $guarded = [
        "id"
    ];

    public function scopeGetByName(Builder $builder, array $name)
    {
        return $builder->whereIn("nom_complet", $name);
    }
}
