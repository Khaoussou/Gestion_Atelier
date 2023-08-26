<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "libelle" => "required|unique:articles",
            "prix" => "required|min:0",
            "stock" => "required|min:0",
            "categorie" => "required",
            "fournisseur" => "required",
            "image" => "sometimes|mimes:jpeg,jpg,png|max:2048"
        ];
    }
    public function messages()
    {
        return [
            "libelle.required" => "Le libelle est obligatoire !",
            "libelle.unique" => "Le libelle est unique !",
            "prix.required" => "Le prix est obligatoire !",
            "prix.min" => "Le prix doit etre positif !",
            "stock.required" => "Le stock est obligatoire !",
            "stock.min" => "Le stock doit etre positif !",
            "categorie" => "La categorie est obligatoire !",
            "fournisseur" => "Le fournisseur est obligatoire !",
            "image.sometimes" => "La photo est obligatoire !",
            "image.mimes" => "Le format de l'image n'est pas recommandé !",
        ];
    }
}
