@extends('layouts.admin')

@section('title', 'Réparations - Garage Backoffice')
@section('page-title', 'Gestion des Réparations')
@section('page-description', '3 emplacements actifs + liste des réparations terminées')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    <!-- Messages flash -->
    @if(session('success'))
        <div style="background-color: rgba(16, 185, 129, 0.2); border-color: #10b981; color: #10b981;" class="border px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: rgba(239, 68, 68, 0.2); border-color: #ef4444; color: #ef4444;" class="border px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Section: Réparations Actives (3 slots) -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold" style="color: #ffffff;">
                <i class="fas fa-car-side mr-2" style="color: #54ACBF;"></i>
                Réparations Actives
            </h1>
            <a href="{{ route('admin.repairs.create') }}" style="background-color: #54ACBF;" class="text-white px-4 py-2 rounded hover:opacity-90">
                <i class="fas fa-plus mr-2"></i>Nouvelle réparation
            </a>
        </div>
        
        <p class="mb-4" style="color: #a0a0a0;">Maximum 3 voitures simultanément</p>

        @if($activeRepairs->count() === 0)
            <div style="background-color: #1a2332;" class="rounded-lg shadow p-6 text-center">
                <i class="fas fa-car text-gray-400 text-4xl mb-4"></i>
                <p style="color: #a0a0a0;">Aucune réparation active</p>
                <p style="color: #6a7a8a;" class="text-sm mt-2">Les 3 emplacements sont disponibles</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @foreach($activeRepairs as $repair)
                    <div style="background-color: #1a2332;" class="rounded-lg shadow-lg overflow-hidden">
                        <!-- Header -->
                        <div style="background-color: #54ACBF;" class="text-white p-4">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold">
                                    {{ $repair->client->nom }} {{ $repair->client->prenom }}
                                </h3>
                                <span style="background-color: white; color: #54ACBF;" class="px-3 py-1 rounded-full text-sm font-medium">
                                    Slot {{ $loop->index + 1 }}/3
                                </span>
                            </div>
                            <div class="mt-2 text-sm opacity-90">
                                {{ $repair->client->voiture_marque }} {{ $repair->client->voiture_modele }}
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="p-4">
                            <!-- Statut -->
                            <div class="mb-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($repair->statut === 'en_attente') @elseif($repair->statut === 'en_cours') text-white @endif"
                                    @if($repair->statut === 'en_attente')style="background-color: rgba(250, 204, 21, 0.2); color: #fbbf24;"@elseif($repair->statut === 'en_cours')style="background-color: #54ACBF;"@endif>
                                    @if($repair->statut === 'en_attente') En attente
                                    @elseif($repair->statut === 'en_cours') En cours
                                    @endif
                                </span>
                            </div>

                            <!-- Intervention -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium mb-2" style="color: #ffffff;">Intervention</h4>
                                <div style="background-color: #252f3f;" class="p-3 rounded">
                                    <div class="flex justify-between items-center">
                                        <span style="color: #ffffff;" class="text-sm font-medium">{{ $repair->intervention->nom }}</span>
                                        <span class="text-sm font-bold" style="color: #54ACBF;">
                                            {{ number_format($repair->montant_total, 0, ',', ' ') }} Ar
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-2">
                                @if($repair->statut === 'en_attente')
                                    <form action="{{ route('admin.repairs.update-status', $repair->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="status" value="en_cours">
                                        <button type="submit" class="w-full text-white px-3 py-2 rounded text-sm hover:opacity-80" style="background-color: #fbbf24; color: #1a1a1a;">
                                            <i class="fas fa-play mr-1"></i>Démarrer
                                        </button>
                                    </form>
                                @endif

                                @if($repair->statut === 'en_cours')
                                    <form action="{{ route('admin.repairs.update-status', $repair->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="status" value="termine">
                                        <button type="submit" class="w-full text-white px-3 py-2 rounded text-sm hover:opacity-80" style="background-color: #10b981;">
                                            <i class="fas fa-check mr-1"></i>Terminer
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <!-- Dates -->
                            @if($repair->debut_reparation || $repair->fin_reparation)
                                <div class="mt-3 text-xs border-t pt-2" style="border-color: #2d3f54; color: #a0a0a0;">
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

                <!-- Slots vides -->
                @for($i = $activeRepairs->count(); $i < 3; $i++)
                    <div style="background-color: #1a2332; border-color: #2d3f54;" class="rounded-lg shadow-lg p-6 border-2 border-dashed">
                        <div class="text-center">
                            <i class="fas fa-plus-circle text-gray-400 text-4xl mb-4"></i>
                            <p style="color: #a0a0a0;" class="font-medium">Slot {{ $i + 1 }} disponible</p>
                            <p style="color: #6a7a8a;" class="text-sm mt-2">Emplacement libre</p>
                        </div>
                    </div>
                @endfor
            </div>
        @endif
    </div>

    <!-- Section: Réparations Terminées (liste) -->
    <div>
        <h2 class="text-xl font-bold mb-6" style="color: #ffffff;">
            <i class="fas fa-check-circle mr-2" style="color: #10b981;"></i>
            Réparations Terminées
        </h2>

        @if($completedRepairs->count() === 0)
            <div style="background-color: #1a2332;" class="rounded-lg shadow p-6 text-center">
                <i class="fas fa-clipboard-check text-gray-400 text-4xl mb-4"></i>
                <p style="color: #a0a0a0;">Aucune réparation terminée</p>
            </div>
        @else
            <div style="background-color: #1a2332;" class="rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y" style="border-color: #2d3f54;">
                    <thead style="background-color: #252f3f;">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium" style="color: #a0a0a0;" class="uppercase tracking-wider">
                                Client
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium" style="color: #a0a0a0;" class="uppercase tracking-wider">
                                Véhicule
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium" style="color: #a0a0a0;" class="uppercase tracking-wider">
                                Intervention
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium" style="color: #a0a0a0;" class="uppercase tracking-wider">
                                Total
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium" style="color: #a0a0a0;" class="uppercase tracking-wider">
                                Date
                            </th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #1a2332;" class="divide-y" style="border-color: #2d3f54;">
                        @foreach($completedRepairs as $repair)
                            <tr style="background-color: #1a2332;">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div style="color: #ffffff;" class="text-sm font-medium">
                                        {{ $repair->client->nom }} {{ $repair->client->prenom }}
                                    </div>
                                    <div style="color: #a0a0a0;" class="text-sm">{{ $repair->client->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div style="color: #ffffff;" class="text-sm">
                                        {{ $repair->client->voiture_marque }} {{ $repair->client->voiture_modele }}
                                    </div>
                                    <div style="color: #a0a0a0;" class="text-sm">{{ $repair->client->voiture_immatriculation }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span style="color: #ffffff;" class="text-sm">{{ $repair->intervention->nom }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold" style="color: #54ACBF;">
                                        {{ number_format($repair->montant_total, 0, ',', ' ') }} Ar
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #a0a0a0;">
                                    {{ $repair->updated_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
