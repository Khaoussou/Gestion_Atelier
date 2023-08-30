<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approvisionnement extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, "article_id");
    }
    public function article_vente(): BelongsTo
    {
        return $this->belongsTo(ArticleVente::class, "article_vente_id");
    }
}
