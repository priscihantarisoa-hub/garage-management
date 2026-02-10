<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
        $clientId = $this->route('client'); // Pour les mises à jour
        
        return [
            'nom' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z\s\-\'àáâäãåçéèêëìíîïñòóôöõùúûüýÿ]+$/'
            ],
            'prenom' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-Z\s\-\'àáâäãåçéèêëìíîïñòóôöõùúûüýÿ]+$/'
            ],
            'telephone' => [
                'required',
                'string',
                'regex:/^(?:(?:\+261|0)?[234]\d{7}|(?:\+261|0)?3[234]\d{6})$/',
                Rule::unique('clients')->ignore($clientId)->where(function ($query) {
                    return $query->where('nom', $this->nom)
                        ->where('prenom', $this->prenom);
                })
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('clients')->ignore($clientId),
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'adresse' => [
                'required',
                'string',
                'min:10',
                'max:500'
            ],
            'voiture_marque' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[a-zA-Z0-9\s\-]+$/'
            ],
            'voiture_modele' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[a-zA-Z0-9\s\-]+$/'
            ],
            'voiture_immatriculation' => [
                'required',
                'string',
                'regex:/^[A-Z]{1,4}\d{1,4}[A-Z]{0,3}$/',
                Rule::unique('clients')->ignore($clientId)
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
            'nom.required' => 'Le nom est obligatoire.',
            'nom.min' => 'Le nom doit contenir au moins 2 caractères.',
            'nom.max' => 'Le nom ne peut pas dépasser 100 caractères.',
            'nom.regex' => 'Le nom ne peut contenir que des lettres, espaces, tirets et apostrophes.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.min' => 'Le prénom doit contenir au moins 2 caractères.',
            'prenom.max' => 'Le prénom ne peut pas dépasser 100 caractères.',
            'prenom.regex' => 'Le prénom ne peut contenir que des lettres, espaces, tirets et apostrophes.',
            'telephone.required' => 'Le téléphone est obligatoire.',
            'telephone.regex' => 'Le numéro de téléphone n\'est pas valide (format malgache requis).',
            'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé par ce client.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email n\'est pas valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'email.regex' => 'L\'email n\'est pas valide.',
            'adresse.required' => 'L\'adresse est obligatoire.',
            'adresse.min' => 'L\'adresse doit contenir au moins 10 caractères.',
            'adresse.max' => 'L\'adresse ne peut pas dépasser 500 caractères.',
            'voiture_marque.required' => 'La marque du véhicule est obligatoire.',
            'voiture_marque.regex' => 'La marque ne peut contenir que des lettres, chiffres et tirets.',
            'voiture_modele.required' => 'Le modèle du véhicule est obligatoire.',
            'voiture_modele.regex' => 'Le modèle ne peut contenir que des lettres, chiffres et tirets.',
            'voiture_immatriculation.required' => 'L\'immatriculation est obligatoire.',
            'voiture_immatriculation.regex' => 'L\'immatriculation n\'est pas valide (format: ABC123).',
            'voiture_immatriculation.unique' => 'Cette immatriculation est déjà utilisée.',
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
            'nom' => 'nom',
            'prenom' => 'prénom',
            'telephone' => 'téléphone',
            'email' => 'email',
            'adresse' => 'adresse',
            'voiture_marque' => 'marque du véhicule',
            'voiture_modele' => 'modèle du véhicule',
            'voiture_immatriculation' => 'immatriculation',
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
            // Validation personnalisée pour le téléphone malgache
            if ($this->telephone) {
                $phone = preg_replace('/[^0-9]/', '', $this->telephone);
                
                if (strlen($phone) === 7) {
                    // Format: 2345678
                    if (!in_array(substr($phone, 0, 1), ['2', '3', '4'])) {
                        $validator->errors()->add('telephone', 'Le numéro de téléphone doit commencer par 2, 3 ou 4.');
                    }
                } elseif (strlen($phone) === 8) {
                    // Format: 032345678
                    if (!in_array(substr($phone, 0, 2), ['32', '33', '34'])) {
                        $validator->errors()->add('telephone', 'Le numéro de téléphone doit commencer par 32, 33 ou 34.');
                    }
                } else {
                    $validator->errors()->add('telephone', 'Le numéro de téléphone doit avoir 7 ou 8 chiffres.');
                }
            }

            // Validation de l'immatriculation
            if ($this->voiture_immatriculation) {
                $immat = strtoupper($this->voiture_immatriculation);
                
                // Vérification du format malgache
                if (!preg_match('/^[A-Z]{1,4}\d{1,4}[A-Z]{0,3}$/', $immat)) {
                    $validator->errors()->add('voiture_immatriculation', 'Format d\'immatriculation invalide. Ex: ABC123 ou 1234ABC');
                }
            }
        });
    }
}
