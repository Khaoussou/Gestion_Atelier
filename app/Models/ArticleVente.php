<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ArticleVente extends Model
{
    use HasFactory;

    protected $guarded = [
        "id"
    ];
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::created(function ($article) {
    //         self::addOrUpdateFour($article);
    //     });
    //     static::updating(function ($article) {
    //         $article->article()->detach();
    //         self::addOrUpdateFour($article);
    //     });
    // }
    // protected static function addOrUpdateFour($article)
    // {
    //     $libelleArticles = array_map(fn ($article) => $article["libelle"], request()->confection);
    //     $qte = array_map(fn ($article) => $article["quantite"], request()->confection);
    //     $idArticle = Article::getArtByLib($libelleArticles)->pluck('id');
    //     $article->article()->attach($idArticle, ["quantite" => $qte]);
    // }
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
}
