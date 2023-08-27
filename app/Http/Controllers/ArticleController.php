<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\CategorieResource;
use App\Http\Resources\FournisseurResource;
use App\Models\ArticleFournisseur;
use App\Models\ArticleVente;
use App\Models\Categorie;
use App\Traits\UploadFile;
use App\Models\Fournisseur;
use App\Traits\Format;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    use UploadFile, Format;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $article = new Article();
        $categorie = new Categorie();
        $fournisseur = new Fournisseur();
        $allArticles = ArticleResource::collection($article->orderBy("id", "DESC")->get());
        $allCategories = CategorieResource::collection($categorie->where("type_categorie", "confection")->get());
        $allFournisseurs = FournisseurResource::collection($fournisseur->all());
        return $this->response(Response::HTTP_ACCEPTED, "", ["articles" => $allArticles, "categories" => $allCategories, "fournisseurs" => $allFournisseurs]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        $libelle = $request->libelle;
        $categorieExist = Categorie::getCatByLib($request->categorie)->first();
        $cat = Article::getArtByCat($categorieExist->id)->get();
        $newArticle = [
            "photo" => $this->insertFile($request) ?? null,
            "libelle" => $request->libelle,
            "prix" => $request->prix,
            "stock" => $request->stock,
            "categorie_id" => $categorieExist->id,
            "REF" => "REF" . "-" . strtoupper(substr($libelle, 0, 3)) . "-" . strtoupper($categorieExist->libelle) . "-" . count($cat) + 1
        ];
        $data = new ArticleResource(Article::create($newArticle));
        return $this->response(Response::HTTP_ACCEPTED, "Insertion réussie", [$data]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $libelle = $request->libelle;
        $categorieExist = Categorie::getCatByLib($request->categorie)->first();
        $cat = Article::getArtByCat($categorieExist->id)->get();
        $count = 0;
        if ($article->categorie_id === $categorieExist->id) {
            $count = count($cat);
        } else {
            $count = count($cat) + 1;
        }
        $newArticle = [
            "photo" => $this->insertFile($request) ?? null,
            "libelle" => $libelle,
            "prix" => $request->prix,
            "stock" => $request->stock,
            "categorie_id" => $categorieExist->id,
            "REF" => "REF" . "-" . strtoupper(substr($libelle, 0, 3)) . "-" . strtoupper($categorieExist->libelle) . "-" . $count
        ];
        $article->update($newArticle);
        return $this->response(Response::HTTP_ACCEPTED, "Modification réussie !", [new ArticleResource($article)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        Article::where("id", $article->id)->delete();
        $artDelete = new ArticleResource($article);
        return $this->response(Response::HTTP_ACCEPTED, "Suppression réussie", [$artDelete]);
    }
}
