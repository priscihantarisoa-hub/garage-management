<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReparationRequest extends FormRequest
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
            'client_id' => [
                'required',
                'exists:clients,id',
                Rule::unique('reparations')->where(function ($query) {
                    return $query->where('slot', $this->slot)
                        ->where('statut', '!=', 'termine');
                })->message('Ce créneau horaire est déjà occupé par ce client.'),
            ],
            'intervention_id' => [
                'required',
                'exists:interventions,id',
                'exists:interventions,id,actif,1'
            ],
            'statut' => [
                'required',
                'string',
                Rule::in(['en_attente', 'en_cours', 'termine', 'paye'])
            ],
            'slot' => [
                'required',
                'integer',
                'between:1,2',
                Rule::unique('reparations')->where(function ($query) {
                    return $query->where('statut', '!=', 'termine');
                })->message('Ce créneau horaire est déjà occupé.'),
            ],
            'montant_total' => [
                'required',
                'numeric',
                'min:0',
                'max:1000000'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000'
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
            'client_id.required' => 'Le client est obligatoire.',
            'client_id.exists' => 'Le client sélectionné n\'existe pas.',
            'intervention_id.required' => 'L\'intervention est obligatoire.',
            'intervention_id.exists' => 'L\'intervention sélectionnée n\'existe pas ou n\'est pas active.',
            'statut.required' => 'Le statut est obligatoire.',
            'statut.in' => 'Le statut doit être l\'une des valeurs suivantes: en attente, en cours, terminé, payé.',
            'slot.required' => 'Le créneau horaire est obligatoire.',
            'slot.between' => 'Le créneau horaire doit être 1 ou 2.',
            'slot.unique' => 'Ce créneau horaire est déjà occupé.',
            'montant_total.required' => 'Le montant total est obligatoire.',
            'montant_total.numeric' => 'Le montant total doit être un nombre.',
            'montant_total.min' => 'Le montant total ne peut pas être négatif.',
            'montant_total.max' => 'Le montant total ne peut pas dépasser 1 000 000 Ar.',
            'notes.max' => 'Les notes ne peuvent pas dépasser 1000 caractères.',
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
            'client_id' => 'client',
            'intervention_id' => 'intervention',
            'statut' => 'statut',
            'slot' => 'créneau horaire',
            'montant_total' => 'montant total',
            'notes' => 'notes',
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
            if ($this->slot && $this->statut) {
                // Vérification supplémentaire pour les créneaux
                $existingRepairs = \App\Models\Reparation::where('slot', $this->slot)
                    ->where('statut', '!=', 'termine')
                    ->count();

                if ($existingRepairs >= 2) {
                    $validator->errors()->add('slot', 'Les deux créneaux horaires sont déjà occupés.');
                }
            }
        });
    }
}
