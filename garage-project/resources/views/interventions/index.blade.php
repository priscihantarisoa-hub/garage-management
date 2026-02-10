@extends('layouts.admin')

@section('title', 'Interventions - Garage Backoffice')
@section('page-title', 'Gestion des Interventions')
@section('page-description', 'Configurer les services et prix du garage')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
    <h2 class="text-2xl font-bold" style="color: #ffffff;">
        <i class="fas fa-coins text-yellow-600 text-xl"></i>Gestion des interventions
    </h2>
    <a href="{{ route('interventions.create') }}" 
        style="background-color: #54ACBF;"
        class="hover:opacity-90 text-white font-bold py-2 px-4 rounded inline-flex items-center">
        <i class="fas fa-plus mr-2"></i>Nouvelle intervention
    </a>
</div>

<div style="background-color: #1a2332;" class="shadow rounded-lg">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y" style="border-color: #2d3f54;">
            <thead style="background-color: #252f3f;">
                <tr>
                    <th class="px-2 sm:px-6 py-3 text-left text-xs font-medium" style="color: #a0a0a0;" class="uppercase tracking-wider">
                        Nom
                    </th>
                    <th class="hidden sm:table-cell px-6 py-3 text-left text-xs font-medium" style="color: #a0a0a0;" class="uppercase tracking-wider">
                        Type
                    </th>
                    <th class="px-2 sm:px-6 py-3 text-left text-xs font-medium" style="color: #a0a0a0;" class="uppercase tracking-wider">
                        Prix (Ar)
                    </th>
                    <th class="hidden sm:table-cell px-6 py-3 text-left text-xs font-medium" style="color: #a0a0a0;" class="uppercase tracking-wider">
                        Durée
                    </th>
                    <th class="hidden sm:table-cell px-6 py-3 text-left text-xs font-medium" style="color: #a0a0a0;" class="uppercase tracking-wider">
                        Statut
                    </th>
                    <th class="px-2 sm:px-6 py-3 text-left text-xs font-medium" style="color: #a0a0a0;" class="uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody style="background-color: #1a2332;" class="divide-y" style="border-color: #2d3f54;">
                @forelse($interventions as $intervention)
                    <tr style="background-color: #1a2332;">
                        <td class="px-2 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium" style="color: #ffffff;">{{ $intervention->nom }}</div>
                            @if($intervention->description)
                                <div class="text-sm hidden sm:block" style="color: #a0a0a0;">{{ Str::limit($intervention->description, 50) }}</div>
                            @endif
                            <div class="sm:hidden mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: #A7EBF2; color: #023859;">
                                    {{ $intervention->type }}
                                </span>
                            </div>
                        </td>
                        <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: #A7EBF2; color: #023859;">
                                {{ $intervention->type }}
                            </span>
                        </td>
                        <td class="px-2 sm:px-6 py-4 whitespace-nowrap text-sm" style="color: #ffffff;">
                            {{ number_format($intervention->prix, 0, ',', ' ') }} Ar
                        </td>
                        <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap text-sm" style="color: #ffffff;">
                            {{ $intervention->duree }}s
                        </td>
                        <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap">
                            @if($intervention->actif)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: rgba(16, 185, 129, 0.2); color: #10b981;">
                                    Actif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: rgba(239, 68, 68, 0.2); color: #ef4444;">
                                    Inactif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                <a href="{{ route('interventions.edit', $intervention) }}" 
                                    style="color: #54ACBF;" class="hover:opacity-70 inline-flex items-center">
                                    <i class="fas fa-edit mr-1"></i> 
                                    <span class="sm:hidden">Modifier</span>
                                    <span class="hidden sm:inline">Modifier</span>
                                </a>
                                <form action="{{ route('interventions.destroy', $intervention) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="color: #ef4444;" class="hover:opacity-70 inline-flex items-center"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette intervention ?')">
                                        <i class="fas fa-trash mr-1"></i>
                                        <span class="sm:hidden">Suppr</span>
                                        <span class="hidden sm:inline">Supprimer</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center" style="color: #a0a0a0;" class="sm:table-cell">
                            Aucune intervention trouvée
                        </td>
                        <td colspan="3" class="px-2 py-4 text-center" style="color: #a0a0a0;" class="sm:hidden">
                            Aucune intervention trouvée
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($interventions->hasPages())
        <div class="bg-gray-50 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                            {{ $interventions->links() }}
                        </div>
                    @endif
                </div>
                @endsection
