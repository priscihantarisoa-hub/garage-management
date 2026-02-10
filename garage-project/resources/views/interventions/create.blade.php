@extends('layouts.admin')

@section('title', 'Créer une intervention - Garage Backoffice')
@section('page-title', 'Nouvelle Intervention')
@section('page-description', 'Ajouter un nouveau service au garage')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold" style="color: #ffffff;">
            <i class="fas fa-plus mr-2" style="color: #54ACBF;"></i>Créer une intervention
        </h2>
        <a href="{{ route('interventions.index') }}" 
            style="background-color: #54ACBF;"
            class="hover:opacity-90 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
    </div>

    <div style="background-color: #1a2332;" class="shadow rounded-lg p-6">
        <form method="POST" action="{{ route('interventions.store') }}">
            @csrf

            <div class="mb-6">
                <label for="nom" class="block text-sm font-bold mb-2" style="color: #ffffff;">
                    Nom de l'intervention *
                </label>
                <input type="text" id="nom" name="nom" required
                    style="background-color: #0f1419; border-color: #2d3f54; color: #ffffff;"
                    class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
                    value="{{ old('nom') }}">
                @error('nom')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="type" class="block text-sm font-bold mb-2" style="color: #ffffff;">
                    Type d'intervention *
                </label>
                <select id="type" name="type" required
                    style="background-color: #0f1419; border-color: #2d3f54; color: #ffffff;"
                    class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="" style="background-color: #0f1419; color: #ffffff;">Sélectionner un type</option>
                    @foreach($types as $key => $value)
                        <option value="{{ $key }}" style="background-color: #0f1419; color: #ffffff;" {{ old('type') == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
                @error('type')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="prix" class="block text-sm font-bold mb-2" style="color: #ffffff;">
                        Prix (Ar) *
                    </label>
                    <input type="number" id="prix" name="prix" step="0.01" min="0" required
                        style="background-color: #0f1419; border-color: #2d3f54; color: #ffffff;"
                        class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
                        value="{{ old('prix') }}">
                    @error('prix')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="duree" class="block text-sm font-bold mb-2" style="color: #ffffff;">
                        Durée (secondes) *
                    </label>
                    <input type="number" id="duree" name="duree" min="1" required
                        style="background-color: #0f1419; border-color: #2d3f54; color: #ffffff;"
                        class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
                        value="{{ old('duree') }}">
                    @error('duree')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-bold mb-2" style="color: #ffffff;">
                    Description
                </label>
                <textarea id="description" name="description" rows="4"
                    style="background-color: #0f1419; border-color: #2d3f54; color: #ffffff;"
                    class="shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="actif" value="1" checked
                        style="accent-color: #54ACBF;" class="form-checkbox h-4 w-4">
                    <span class="ml-2" style="color: #ffffff;">Intervention active</span>
                </label>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('interventions.index') }}" 
                    style="background-color: #54ACBF;"
                    class="hover:opacity-90 text-white font-bold py-2 px-4 rounded">
                    Annuler
                </a>
                <button type="submit" 
                    style="background-color: #54ACBF;"
                    class="hover:opacity-90 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    <i class="fas fa-save mr-2"></i>Créer l'intervention
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
