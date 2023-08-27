<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArtcileVenteRequest extends FormRequest
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
            "libelle" => "required",
            "categorie" => "required",
            "confection" => "required|min:3",
            "photo" => "sometimes",
            "marge" => "required"
        ];
    }
    public function messages()
    {
        return [
            "libelle.required" => "Le libelle est obligatoire !",
            "categorie.required" => "La categorie est obligatoire !",
            "confection.required" => "L'article de confection est obligatoire !",
            "confection.min" => "Le nombre d'article n'est pas complet !",
            "marge" => "La marge est obligatoire !",
            "photo" => "La photo est obligatoire !"
        ];
    }
}
