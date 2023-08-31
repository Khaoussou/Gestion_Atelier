<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticleVente extends Model
{
    use HasFactory;

    protected $guarded = [
        "id"
    ];
    protected static function boot()
    {
        parent::boot();

        static::created(function ($articleVente) {
            self::addOrUpdateFour($articleVente);
        });
        static::updating(function ($articleVente) {
            $articleVente->article()->detach();
            self::addOrUpdateFour($articleVente);
        });
    }
    protected static function addOrUpdateFour($articleVente)
    {
        $articleConfs = request()->confection;
        foreach ($articleConfs as $conf) {
            $appro[] = [
                "article_id" => Article::getArtByLib($conf["lib"])->first()->id,
                "article_vente_id" => $articleVente->id,
                "quantite" => $conf["quantite"]
            ];
        }
        $articleVente->article()->attach($appro);
    }
    public function scopeGetArtByCat(Builder $builder, $cat)
    {
        return $builder->where("categorie_id", $cat);
    }
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }
    public function article(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, "approvisionnements");
    }
    public function approvisionnement(): HasMany
    {
        return $this->hasMany(Approvisionnement::class, "article_vente_id");
    }
}
