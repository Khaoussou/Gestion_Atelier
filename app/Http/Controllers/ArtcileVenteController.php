<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArtcileVenteRequest;
use App\Http\Resources\ArticleVenteResource;
use App\Http\Resources\CategorieResource;
use App\Http\Resources\FournisseurResource;
use App\Models\Approvisionnement;
use App\Models\Article;
use App\Models\ArticleVente;
use App\Models\Categorie;
use App\Models\Fournisseur;
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
        $categorie = new Categorie();
        $allCategories = CategorieResource::collection($categorie->where("type_categorie", "vente")->get());
        $allArticles = ArticleVenteResource::collection($artVente->all());
        return $this->response(Response::HTTP_ACCEPTED, "", ["articles" => $allArticles, "categories" => $allCategories]);
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
        $newArticle = [
            "photo" => $this->insertFile($request) ?? null,
            "libelle" => $request->libelle,
            "cout_de_fabrication" => $cout,
            "prix_de_vente" => $cout + $marge,
            "marge" => $marge,
            "categorie_id" => $categorieExist->id,
            "reference" => "REF" . "-" . strtoupper(substr($libelle, 0, 3)) . "-" . strtoupper($categorieExist->libelle) . "-" . count($cat) + 1
        ];
        if ($categorie) {
            $data = new ArticleVenteResource(ArticleVente::create($newArticle));
            foreach ($articleConfs as $conf) {
                $appro[] = [
                    "article_id" => Article::getArtByLib($conf["libelle"])->first()->id,
                    "article_vente_id" => $data->id,
                    "quantite" => $conf["quantite"]
                ];
            }
            Approvisionnement::insert($appro);
            return $this->response(Response::HTTP_ACCEPTED, "", ["articles" => $data]);
        }
    }
    public function hasCategorie($articleConfs)
    {
        $libelleArticles = array_map(fn ($article) => $article["libelle"], $articleConfs);
        foreach ($libelleArticles as $categorie) {
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
        $libelleArticles = array_map(fn ($article) => $article["libelle"], $articleConfs);
        $qte = array_map(fn ($article) => $article["quantite"], $articleConfs);
        foreach ($libelleArticles as $categorie) {
            $prix[] = Article::getArtByLib($categorie)->first()->prix;
        }
        $taille = count($prix);
        $coutDeFabrique = 0;
        for ($i = 0; $i < $taille; $i++) {
            $coutDeFabrique += $prix[$i] * $qte[$i];
        }
        return $coutDeFabrique;
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
