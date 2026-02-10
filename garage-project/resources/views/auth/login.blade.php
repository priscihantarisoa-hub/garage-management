@extends('layouts.admin')

@section('title', 'Connexion - Garage Backoffice')
@section('page-title', 'Connexion Administrateur')
@section('page-description', 'Accéder au backoffice du garage')

@section('content')
<div class="min-h-screen flex items-center justify-center" style="background-color: #0f1419;">
    <div class="max-w-md w-full rounded-lg shadow-md p-8" style="background-color: #1a2332;">
        <div class="text-center mb-8">
            <i class="fas fa-wrench text-6xl mb-4" style="color: #54ACBF;"></i>
            <h2 class="text-3xl font-bold" style="color: #ffffff;">Garage Backoffice</h2>
            <p class="mt-2" style="color: #a0a0a0;">Connectez-vous pour accéder à l'administration</p>
        </div>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            
            @if($errors->any())
                <div class="border border-red-400 text-white px-4 py-3 rounded mb-4" style="background-color: #c91c1c;">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="mb-6">
                <label for="email" class="block text-sm font-bold mb-2" style="color: #ffffff;">
                    <i class="fas fa-envelope mr-2"></i>Email
                </label>
                <input type="email" id="email" name="email" required
                    style="background-color: #0f1419; color: #ffffff; border: 1px solid #2d3f54;"
                    class="shadow appearance-none rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="admin@garage.com">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-bold mb-2" style="color: #ffffff;">
                    <i class="fas fa-lock mr-2"></i>Mot de passe
                </label>
                <input type="password" id="password" name="password" required
                    style="background-color: #0f1419; color: #ffffff; border: 1px solid #2d3f54;"
                    class="shadow appearance-none rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" 
                    style="background-color: #54ACBF;"
                    class="hover:opacity-90 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
