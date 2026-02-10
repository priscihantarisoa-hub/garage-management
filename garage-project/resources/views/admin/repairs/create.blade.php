@extends('layouts.admin')

@section('title', 'Créer une réparation - Garage Backoffice')
@section('page-title', 'Nouvelle Réparation')
@section('page-description', 'Ajouter une nouvelle réparation au garage')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold" style="color: #ffffff;">
                <i class="fas fa-plus mr-2" style="color: #54ACBF;"></i>Créer une réparation
            </h2>
            <a href="{{ route('admin.repairs.index') }}" style="background-color: #54ACBF;"
                class="hover:opacity-90 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        <div style="background-color: #1a2332;" class="shadow rounded-lg p-6">
            <form method="POST" action="{{ route('admin.repairs.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="client_id" class="block text-sm font-bold mb-2" style="color: #ffffff;">
                            Client *
                        </label>
                        <select id="client_id" name="client_id" required
                            style="background-color: #0f1419; border-color: #2d3f54; color: #ffffff;"
                            class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="" style="background-color: #0f1419; color: #ffffff;">Sélectionner un client
                            </option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" style="background-color: #0f1419; color: #ffffff;" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->nom }} {{ $client->prenom }}
                                    @if($client->voiture_immatriculation)
                                        ({{ $client->voiture_immatriculation }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="intervention_ids" class="block text-sm font-bold mb-2" style="color: #ffffff;">
                            Intervention *
                        </label>
                        <select id="intervention_ids" name="intervention_ids[]" required
                            style="background-color: #0f1419; border-color: #2d3f54; color: #ffffff;"
                            class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="" style="background-color: #0f1419; color: #ffffff;">Sélectionner une
                                intervention</option>
                            @foreach($interventions as $intervention)
                                <option value="{{ $intervention->id }}" style="background-color: #0f1419; color: #ffffff;" {{ old('intervention_ids.0') == $intervention->id ? 'selected' : '' }}>
                                    {{ $intervention->nom }} - {{ number_format($intervention->prix, 0, ',', ' ') }} Ar
                                </option>
                            @endforeach
                        </select>
                        @error('intervention_ids')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="p-4 mb-6 rounded-lg"
                    style="background-color: rgba(84, 172, 191, 0.15); border-left: 4px solid #54ACBF;">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle" style="color: #54ACBF;"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm" style="color: #a0a0a0;">
                                <strong style="color: #ffffff;">Information importante :</strong> Le garage peut traiter
                                maximum 3 voitures simultanément.
                                Si la limite est atteinte, cette réparation sera mise en attente.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.repairs.index') }}" style="background-color: #54ACBF;"
                        class="hover:opacity-90 text-white font-bold py-2 px-4 rounded">
                        Annuler
                    </a>
                    <button type="submit" style="background-color: #54ACBF;"
                        class="hover:opacity-90 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        <i class="fas fa-save mr-2"></i>Créer la réparation
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection