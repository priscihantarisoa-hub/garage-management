@extends('layouts.admin')

@section('title', 'Réparations en cours - Garage Backoffice')
@section('page-title', 'Réparations en Cours')
@section('page-description', 'Suivi des réparations en temps réel (2 emplacements max)')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">
            <i class="fas fa-car-side text-blue-600 mr-2"></i>
            Réparations en cours
        </h1>
        <p class="text-gray-600 mt-2">Maximum 2 voitures simultanément</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($repairs->count() === 0)
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <i class="fas fa-car text-gray-400 text-4xl mb-4"></i>
            <p class="text-gray-500">Aucune réparation en cours</p>
            <a href="{{ route('admin.repairs.create') }}" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Créer une réparation
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($repairs as $repair)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <!-- Header -->
                    <div class="bg-blue-600 text-white p-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold">
                                {{ $repair->client->nom }} {{ $repair->client->prenom }}
                            </h3>
                            <span class="px-3 py-1 bg-white text-blue-600 rounded-full text-sm font-medium">
                                {{ $repair->slot }}/2
                            </span>
                        </div>
                        <p class="text-blue-100 text-sm mt-1">
                            {{ $repair->client->voiture_marque }} {{ $repair->client->voiture_modele }}
                        </p>
                    </div>

                    <!-- Body -->
                    <div class="p-4">
                        <!-- Statut -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Statut actuel</span>
                                <span class="px-2 py-1 text-xs rounded-full {{ $repair->statut === 'en_cours' ? 'bg-yellow-100 text-yellow-800' : ($repair->statut === 'termine' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $repair->statut === 'en_attente' ? 'En attente' : ($repair->statut === 'en_cours' ? 'En cours' : ($repair->statut === 'termine' ? 'Terminé' : 'Payé')) }}
                                </span>
                            </div>
                            
                            <!-- Boutons de transition -->
                            <div class="flex space-x-2">
                                @if($repair->statut === 'en_attente')
                                    <form action="{{ route('admin.repairs.update-status', $repair->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="en_cours">
                                        <button type="submit" class="flex-1 bg-yellow-600 text-white px-3 py-1 rounded text-sm hover:bg-yellow-700">
                                            <i class="fas fa-play mr-1"></i>Démarrer
                                        </button>
                                    </form>
                                @endif

                                @if($repair->statut === 'en_cours')
                                    <form action="{{ route('admin.repairs.update-status', $repair->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="termine">
                                        <button type="submit" class="flex-1 bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                            <i class="fas fa-check mr-1"></i>Terminer
                                        </button>
                                    </form>
                                @endif

                                @if($repair->statut === 'termine')
                                    <div class="flex-1 bg-gray-400 text-white px-3 py-1 rounded text-sm text-center">
                                        <i class="fas fa-check mr-1"></i>Terminé
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Interventions -->
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Interventions</h4>
                            <div class="space-y-2">
                                @foreach($repair->interventions as $intervention)
                                    <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                                        <span class="text-sm">{{ $intervention->nom }}</span>
                                        <span class="text-sm font-medium text-blue-600">{{ number_format($intervention->prix, 0, ',', ' ') }} Ar</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Prix total -->
                        <div class="border-t pt-3">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-700">Total estimé</span>
                                <span class="text-lg font-bold text-blue-600">
                                    {{ number_format($repair->montant_total, 0, ',', ' ') }} Ar
                                </span>
                            </div>
                        </div>

                        <!-- Dates -->
                        @if($repair->debut_reparation || $repair->fin_reparation)
                            <div class="mt-3 text-xs text-gray-500 border-t pt-2">
                                @if($repair->debut_reparation)
                                    <div>Début: {{ $repair->debut_reparation->format('H:i') }}</div>
                                @endif
                                @if($repair->fin_reparation)
                                    <div>Fin: {{ $repair->fin_reparation->format('H:i') }}</div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Bouton créer (si place disponible) -->
        @if($repairs->count() < 2)
            <div class="mt-6 text-center">
                <a href="{{ route('admin.repairs.create') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Nouvelle réparation
                </a>
            </div>
        @endif
    @endif
</div>
@endsection
