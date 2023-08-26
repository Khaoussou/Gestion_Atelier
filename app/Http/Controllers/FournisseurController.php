<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FournisseurController extends Controller
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
    public function search($fournisseur)
    {
        $four = '%' . $fournisseur . '%';
        $fours = Fournisseur::where("nom_complet", "like", $four)->get();
        $names = [];
        foreach ($fours as $name) {
            $names[] = $name->nom_complet;
        }
        return $this->response(Response::HTTP_ACCEPTED, "", $names);
    }

    public function index()
    {
        return "";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
