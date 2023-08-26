<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Article extends Model
{
    use HasFactory;
    protected $guarded = [
        "id"
    ];
    protected static function boot()
    {
        parent::boot();

        static::created(function ($article) {
            self::addOrUpdateFour($article);
        });
        static::updating(function ($article) {
            $article->fournisseur()->detach();
            self::addOrUpdateFour($article);
        });
    }
    protected static function addOrUpdateFour($article)
    {
        $fournisseurNames = explode(",", request()->fournisseur);
        $idFours = Fournisseur::getByName($fournisseurNames)->pluck('id');
        $article->fournisseur()->attach($idFours);
    }
    public function scopeGetArtByCat(Builder $builder, $cat)
    {
        return $builder->where("categorie_id", $cat);
    }
    public function scopeGetArtByLib(Builder $builder, $lib)
    {
        return $builder->where("libelle", $lib);
    }
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }
    public function fournisseur(): BelongsToMany
    {
        return $this->belongsToMany(Fournisseur::class, "article_fournisseurs");
    }
}
