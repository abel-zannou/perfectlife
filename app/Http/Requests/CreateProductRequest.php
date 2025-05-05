<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_name' => 'required|string',

            // Accepte soit un fichier image, soit une URL (optionnelle)
            'product_image' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Vérifie si c'est un fichier image valide
                    if (request()->hasFile('product_image')) {
                        $file = request()->file('product_image');
                        if (!$file->isValid()) {
                            $fail('L\'image uploadée n\'est pas valide.');
                        }
                    } 
                    // Sinon, vérifie si c'est une URL valide
                    elseif (!filter_var($value, FILTER_VALIDATE_URL)) {
                        $fail('Le champ image doit être un fichier image ou une URL valide.');
                    }
                },
                // 'mimes:jpeg,png,jpg,gif,svg|max:2048'
            ],

            // 'product_code' => 'required|string',
            'price' => 'required|string',
            'brand' => 'required|string',
            'departement_id' => 'required|exists:departements,id',
            'remark' => 'nullable|string',
            'star' => 'nullable|string',

            

        ];
    }
}
