<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoriePostRequest extends FormRequest
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
            "libelle" => "required|unique:categories|min:3"
        ];
    }
    public function messages(): array
    {
        return [
            "libelle.required" => "Le libelle est obligatoire !",
            "libelle.unique" => "Le libelle est unique !",
            "libelle.min" => "Le libelle doit avoir au moins 3 caractères !",
        ];
    }
    public function form(): array
    {
        return [
            "libelle.required" => "Le libelle est obligatoire !",
            "libelle.unique" => "Le libelle est unique !",
            "libelle.min" => "Le libelle doit avoir au moins 3 caractères !",
        ];
    }
    
}