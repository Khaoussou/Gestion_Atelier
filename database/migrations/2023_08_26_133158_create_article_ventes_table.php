<?php

use App\Models\Categorie;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\CommonMark\Extension\Table\TableCell;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Categorie::class)->constrained()->cascadeOnDelete();
            $table->float("cout_de_fabrication");
            $table->float("marge");
            $table->float("prix_de_vente");
            $table->float("promo")->nullable();
            $table->string("reference");
            $table->string("image")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_ventes');
    }
};
