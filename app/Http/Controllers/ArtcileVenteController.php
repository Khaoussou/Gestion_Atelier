<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArtcileVenteRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Requests\UpdateArticleVenteRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleVenteResource;
use App\Http\Resources\CategorieResource;
use App\Models\Approvisionnement;
use App\Models\Article;
use App\Models\ArticleVente;
use App\Models\Categorie;
use App\Traits\Format;
use App\Traits\UploadFile;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArtcileVenteController extends Controller
{
    use UploadFile, Format;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $artVente = new ArticleVente();
        $artConf = new Article();
        $categorie = new Categorie();
        $allCategories = CategorieResource::collection($categorie->where("type_categorie", "vente")->get());
        $allArticles = ArticleVenteResource::collection($artVente->all());
        $allArticleConfs = ArticleResource::collection($artConf->all());
        return $this->response(Response::HTTP_ACCEPTED, "", ["articleVentes" => $allArticles, "categories" => $allCategories, "articleConfs" => $allArticleConfs]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArtcileVenteRequest $request)
    {
        $libelle = $request->libelle;
        $articleConfs = $request->confection;
        $marge = $request->marge;
        $categorieExist = Categorie::getCatByLib($request->categorie)->first();
        $cat = ArticleVente::getArtByCat($categorieExist->id)->get();
        $categorie = $this->hasCategorie($articleConfs);
        $cout = $this->coutDeFabrique($articleConfs);
        if ($categorie) {
            $newArticle = [
                "image" => $request->image ?? null,
                "libelle" => $request->libelle,
                "cout_de_fabrication" => $cout,
                "prix_de_vente" => $cout + $marge,
                "marge" => $marge,
                "promo" => $request->promo ?? null,
                "categorie_id" => $categorieExist->id,
                "reference" => "REF" . "-" . strtoupper(substr($libelle, 0, 3)) . "-" . strtoupper($categorieExist->libelle) . "-" . count($cat) + 1
            ];
            $data = new ArticleVenteResource(ArticleVente::create($newArticle));
            return $this->response(Response::HTTP_ACCEPTED, "Insertion réussie !", [$data]);
        } else {
            return $this->response(Response::HTTP_UNAUTHORIZED, "Impossible de créer cet article !", []);
        }
    }
    public function hasCategorie($articleConfs)
    {
        $libelleArticles = array_map(fn ($article) => $article["lib"], $articleConfs);
        foreach ($libelleArticles as $categorie) {
            if (!Article::getArtByLib($categorie)->first()) {
                return false;
            }
            $categories[] = Article::getArtByLib($categorie)->first()->categorie_id;
        }
        foreach ($categories as $id) {
            $libelleCategories[] = Categorie::getCatById($id)->first()->libelle;
        }
        if (in_array("Tissus", $libelleCategories, true) && in_array("Boutons", $libelleCategories, true) && in_array("Fils", $libelleCategories, true)) {
            return true;
        }
        return false;
    }
    public function coutDeFabrique($articleConfs)
    {
        $libelleArticles = array_map(fn ($article) => $article["lib"], $articleConfs);
        $qte = array_map(fn ($article) => $article["quantite"], $articleConfs);
        foreach ($libelleArticles as $categorie) {
            if (!Article::getArtByLib($categorie)->first()) {
                return false;
            }
            $prix[] = Article::getArtByLib($categorie)->first()->prix;
        }
        $taille = count($prix);
        $coutDeFabrique = 0;
        for ($i = 0; $i < $taille; $i++) {
            $coutDeFabrique += $prix[$i] * $qte[$i];
        }
        return $coutDeFabrique;
    }
    public function cout(Request $request)
    {
        return $this->coutDeFabrique($request->confection);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleVenteRequest $request, ArticleVente $articleVente)
    {
        $libelle = $request->libelle;
        $articleConfs = $request->confection;
        $marge = $request->marge;
        $categorieExist = Categorie::getCatByLib($request->categorie)->first();
        $cat = ArticleVente::getArtByCat($categorieExist->id)->get();
        $categorie = $this->hasCategorie($articleConfs);
        $cout = $this->coutDeFabrique($articleConfs);
        $count = 0;
        if ($articleVente->categorie_id === $categorieExist->id) {
            $count = count($cat);
        } else {
            $count = count($cat) + 1;
        }
        if ($categorie) {
            $newArticle = [
                "image" => $request->image ?? null,
                "libelle" => $request->libelle,
                "cout_de_fabrication" => $cout,
                "prix_de_vente" => $cout + $marge,
                "marge" => $marge,
                "promo" => $request->promo ?? null,
                "categorie_id" => $categorieExist->id,
                "reference" => "REF" . "-" . strtoupper(substr($libelle, 0, 3)) . "-" . strtoupper($categorieExist->libelle) . "-" . $count
            ];
            $articleVente->update($newArticle);
            return $this->response(Response::HTTP_ACCEPTED, "Modification réussie !", [new ArticleVenteResource($articleVente)]);
        } else {
            return $this->response(Response::HTTP_UNAUTHORIZED, "Impossible de modifier cet article !", []);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ArticleVente $articleVente)
    {
        ArticleVente::where("id", $articleVente->id)->delete();
        $artDelete = new ArticleVenteResource($articleVente);
        return $this->response(Response::HTTP_ACCEPTED, "Suppression réussie", [$artDelete]);
    }
}
