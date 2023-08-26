<?php

namespace App\Observers;

use App\Models\Article;
use App\Models\ArticleFournisseur;

class ArticleObserver
{
    /**
     * Handle the Article "created" event.
     */
    public function creating(Article $article)
    {
        $article->REF = "REF-" . strtoupper(substr($article->libelle, 0, 3)) . "-" . strtoupper($article->categorie->libelle) . "-" . (count($article->categorie->articles) + 1);
    }
    public function created(Article $article): void
    {
        foreach ($article->fournisseurs as $fournisseur) {
            ArticleFournisseur::create([
                "article_id" => $article->id,
                "fournisseur_id" => $fournisseur->id,
            ]);
        }
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "restored" event.
     */
    public function restored(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "force deleted" event.
     */
    public function forceDeleted(Article $article): void
    {
        //
    }
}
