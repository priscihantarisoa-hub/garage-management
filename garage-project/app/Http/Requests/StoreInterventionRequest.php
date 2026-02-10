<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInterventionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // À adapter selon votre logique d'authentification
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-_àáâäãåçéèêëìíîïñòóôöõùúûüýÿ]+$/'
            ],
            'description' => [
                'required',
                'string',
                'min:10',
                'max:1000'
            ],
            'prix' => [
                'required',
                'numeric',
                'min:0',
                'max:500000',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'duree' => [
                'required',
                'integer',
                'min:5',
                'max:480'
            ],
            'type' => [
                'required',
                'string',
                'in:vidange,frein,climatisation,moteur,carrosserie,electricite,entretien,diagnostic,autre'
            ],
            'actif' => [
                'sometimes',
                'boolean'
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom de l\'intervention est obligatoire.',
            'nom.min' => 'Le nom doit contenir au moins 2 caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'nom.regex' => 'Le nom contient des caractères non valides.',
            'description.required' => 'La description est obligatoire.',
            'description.min' => 'La description doit contenir au moins 10 caractères.',
            'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
            'prix.required' => 'Le prix est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'prix.min' => 'Le prix ne peut pas être négatif.',
            'prix.max' => 'Le prix ne peut pas dépasser 500 000 Ar.',
            'prix.regex' => 'Le prix doit avoir au maximum 2 décimales.',
            'duree.required' => 'La durée est obligatoire.',
            'duree.integer' => 'La durée doit être un nombre entier.',
            'duree.min' => 'La durée minimale est de 5 minutes.',
            'duree.max' => 'La durée maximale est de 480 minutes (8 heures).',
            'type.required' => 'Le type d\'intervention est obligatoire.',
            'type.in' => 'Le type d\'intervention doit être l\'une des valeurs prédéfinies.',
            'actif.boolean' => 'Le statut actif doit être vrai ou faux.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nom' => 'nom de l\'intervention',
            'description' => 'description',
            'prix' => 'prix',
            'duree' => 'durée',
            'type' => 'type d\'intervention',
            'actif' => 'statut actif',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validation personnalisée pour le prix
            if ($this->prix && $this->type) {
                $priceRanges = [
                    'vidange' => [20000, 100000],
                    'frein' => [50000, 200000],
                    'climatisation' => [30000, 150000],
                    'moteur' => [50000, 500000],
                    'carrosserie' => [40000, 300000],
                    'electricite' => [25000, 200000],
                    'entretien' => [15000, 100000],
                    'diagnostic' => [20000, 80000],
                    'autre' => [10000, 200000],
                ];

                if (isset($priceRanges[$this->type])) {
                    $minPrice = $priceRanges[$this->type][0];
                    $maxPrice = $priceRanges[$this->type][1];

                    if ($this->prix < $minPrice) {
                        $validator->errors()->add('prix', "Le prix pour ce type d'intervention doit être d'au moins {$minPrice} Ar.");
                    }

                    if ($this->prix > $maxPrice) {
                        $validator->errors()->add('prix', "Le prix pour ce type d'intervention ne peut pas dépasser {$maxPrice} Ar.");
                    }
                }
            }

            // Validation de la cohérence durée/prix
            if ($this->prix && $this->duree) {
                $pricePerMinute = $this->prix / $this->duree;
                
                if ($pricePerMinute > 1000) {
                    $validator->errors()->add('prix', 'Le prix semble trop élevé par rapport à la durée estimée.');
                }
                
                if ($pricePerMinute < 50) {
                    $validator->errors()->add('prix', 'Le prix semble trop bas par rapport à la durée estimée.');
                }
            }
        });
    }
}
