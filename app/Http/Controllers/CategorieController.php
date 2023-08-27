<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriePostRequest;
use App\Http\Resources\CategorieResource;
use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function response($status, $message, $data)
    {
        return [
            "statut" => $status,
            "message" => $message,
            "data" => $data
        ];
    }
    public function index()
    {
        $categorie = new Categorie();
        $data = CategorieResource::collection($categorie->orderBy("id", "DESC")->get());
        return $this->response(Response::HTTP_ACCEPTED, "", $data);
    }
    public function list($paginator = 3)
    {
        $categorie = new Categorie();
        $data = $categorie->paginate($paginator);
        return $this->response(Response::HTTP_ACCEPTED, "", $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoriePostRequest $request)
    {
        $categorie = [
            "libelle" => $request->libelle,
            "type_categorie" => $request->type
        ];
        $newCategorie = new CategorieResource(Categorie::create($categorie));
        return $this->response(Response::HTTP_ACCEPTED, "Insertion réussie !", $newCategorie);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categorie = Categorie::find($id);
        $data = new CategorieResource($categorie);
        return $this->response(Response::HTTP_ACCEPTED, "", $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoriePostRequest $request, string $id)
    {
        $categorie = Categorie::find($id);
        if ($categorie) {
            $categorie->update([
                "libelle" => $request->libelle,
                "type_categorie" => $request->type
            ]);
            return $this->response(Response::HTTP_ACCEPTED, "Modification réussie !", ["Libelle" => $categorie->libelle]);
        } else {
            return $this->response(Response::HTTP_UNAUTHORIZED, "Modification impossible !", []);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request, $id = null)
    {
        $ids = $request->all();
        $article = new Article();
        $catArticle = Article::whereIn("categorie_id", $ids["id"])->get();
        // return $catArticle;
        if (!empty($ids)) {
            if (count($catArticle) == 0) {
                Categorie::whereIn("id", $ids["id"])->delete();
                return $this->response(Response::HTTP_ACCEPTED, "Suppression réussie  !", []);
            } else {
                return $this->response(Response::HTTP_ACCEPTED, "Impossible de supprimer cette catégorie car elle est reliée à au moins un article !", []);
            }
        } else {
            $categorie = Categorie::find($id);
            if ($categorie) {
                $categorie->delete();
                return $this->response(Response::HTTP_ACCEPTED, "Vous venez de supprimer: " . $categorie->libelle, []);
            }
        }
    }

    public function search($libelle)
    {
        if (strlen($libelle) >= 3) {
            $libelleExist = Categorie::getCatByLib($libelle)->first();
            if ($libelleExist) {
                return $this->response(Response::HTTP_ACCEPTED, "Cette catégorie existe deja !", new CategorieResource($libelleExist));
            }
            return $this->response(Response::HTTP_ACCEPTED, "", []);
        }
    }
}
