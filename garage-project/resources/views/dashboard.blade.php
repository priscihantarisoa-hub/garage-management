@extends('layouts.admin')

@section('title', 'Tableau de bord - Garage Backoffice')
@section('page-title', 'Dashboard')
@section('page-description', "Vue d'ensemble de l'activité du garage")

@push('styles')
<style>
    :root {
        --glass-bg: rgba(26, 35, 50, 0.7);
        --glass-border: rgba(255, 255, 255, 0.05);
        --accent-primary: #54ACBF;
        --accent-dark: #26658C;
        --accent-light: #A7EBF2;
        --accent-very-dark: #023859;
        --accent-darkest: #011C40;
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.45);
    }

    .stat-gradient-blue { background: linear-gradient(135deg, rgba(84, 172, 191, 0.2) 0%, rgba(167, 235, 242, 0.2) 100%); }
    .stat-gradient-dark { background: linear-gradient(135deg, rgba(38, 101, 140, 0.2) 0%, rgba(84, 172, 191, 0.2) 100%); }
    .stat-gradient-teal { background: linear-gradient(135deg, rgba(167, 235, 242, 0.2) 0%, rgba(84, 172, 191, 0.2) 100%); }
    .stat-gradient-accent { background: linear-gradient(135deg, rgba(2, 56, 89, 0.2) 0%, rgba(38, 101, 140, 0.2) 100%); }

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(84, 172, 191, 0.5);
        border-radius: 10px;
    }
    
    .accent-primary { color: #54ACBF; }
    .accent-dark { color: #26658C; }
    .accent-light { color: #A7EBF2; }
    .accent-border-primary { border-color: #54ACBF; }
    .accent-border-dark { border-color: #26658C; }
    .accent-border-light { border-color: #A7EBF2; }
    .accent-bg-primary { background: rgba(84, 172, 191, 0.1); }
    .accent-bg-dark { background: rgba(38, 101, 140, 0.1); }
    .accent-bg-light { background: rgba(167, 235, 242, 0.1); }

    @keyframes pulse-soft {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    .pulse-animation {
        animation: pulse-soft 2s infinite ease-in-out;
    }
</style>
@endpush

@section('content')
<!-- Section KPI - Résumé Rapide -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="glass-card rounded-2xl p-6 border-l-4 border-l-[#54ACBF]" style="background: linear-gradient(135deg, rgba(84, 172, 191, 0.15) 0%, rgba(84, 172, 191, 0.25) 100%);">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 text-sm font-medium mb-1">Total réparations</p>
                <h3 class="text-3xl font-bold text-white">{{ $totalReparations }}</h3>
            </div>
            <div class="p-3 rounded-xl" style="background: rgba(84, 172, 191, 0.2); color: #54ACBF;">
                <i class="fas fa-wrench text-xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-[#54ACBF]">
            <i class="fas fa-history mr-1"></i> Historique complet
        </div>
    </div>

    <div class="glass-card rounded-2xl p-6 border-l-4 border-l-[#54ACBF]" style="background: linear-gradient(135deg, rgba(84, 172, 191, 0.15) 0%, rgba(84, 172, 191, 0.25) 100%);">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 text-sm font-medium mb-1">Nombre de clients</p>
                <h3 class="text-3xl font-bold text-white">{{ $clientsCount }}</h3>
            </div>
            <div class="p-3 rounded-xl" style="background: rgba(84, 172, 191, 0.2); color: #54ACBF;">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-[#54ACBF]">
            <i class="fas fa-user-check mr-1"></i> Base clients active
        </div>
    </div>

    <div class="glass-card rounded-2xl p-6 border-l-4 border-l-[#54ACBF]" style="background: linear-gradient(135deg, rgba(84, 172, 191, 0.15) 0%, rgba(84, 172, 191, 0.25) 100%);">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 text-sm font-medium mb-1">Chiffre d'affaires</p>
                <h3 class="text-3xl font-bold text-white">{{ number_format($totalRevenue, 0, ',', ' ') }} <span class="text-lg">Ar</span></h3>
            </div>
            <div class="p-3 rounded-xl" style="background: rgba(84, 172, 191, 0.2); color: #54ACBF;">
                <i class="fas fa-coins text-xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-[#54ACBF]">
            <i class="fas fa-chart-line mr-1"></i> Revenus générés
        </div>
    </div>
</div>

<!-- Deuxième ligne de statistiques -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="glass-card rounded-2xl p-6 border-l-4 border-l-[#54ACBF]" style="background: linear-gradient(135deg, rgba(84, 172, 191, 0.15) 0%, rgba(84, 172, 191, 0.25) 100%);">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 text-sm font-medium mb-1">Réparations en cours</p>
                <h3 class="text-3xl font-bold text-white">{{ $reparationsEnCours->count() }}/3</h3>
            </div>
            <div class="p-3 rounded-xl" style="background: rgba(84, 172, 191, 0.2); color: #54ACBF;">
                <i class="fas fa-car-wrench text-xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-[#54ACBF]">
            <i class="fas fa-tools mr-1"></i> Slots occupés
        </div>
    </div>
    
    <div class="glass-card rounded-2xl p-6 border-l-4 border-l-[#54ACBF]" style="background: linear-gradient(135deg, rgba(84, 172, 191, 0.15) 0%, rgba(84, 172, 191, 0.25) 100%);">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 text-sm font-medium mb-1">Réparations terminées aujourd'hui</p>
                <h3 class="text-3xl font-bold text-white">{{ $reparationsAujourdHui }}</h3>
            </div>
            <div class="p-3 rounded-xl" style="background: rgba(84, 172, 191, 0.2); color: #54ACBF;">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-[#54ACBF]">
            <i class="fas fa-calendar-day mr-1"></i> Activité du jour
        </div>
    </div>

    <div class="glass-card rounded-2xl p-6 border-l-4 border-l-[#54ACBF]" style="background: linear-gradient(135deg, rgba(84, 172, 191, 0.15) 0%, rgba(84, 172, 191, 0.25) 100%);">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 text-sm font-medium mb-1">Paiements en attente</p>
                <h3 class="text-3xl font-bold text-white">{{ number_format($totalPending, 0, ',', ' ') }} <span class="text-lg">Ar</span></h3>
            </div>
            <div class="p-3 rounded-xl" style="background: rgba(84, 172, 191, 0.2); color: #54ACBF;">
                <i class="fas fa-clock text-xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs text-[#54ACBF]">
            <i class="fas fa-exclamation-triangle mr-1"></i> À encaisser
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- COLONNE GAUCHE: Opérations en cours & Statistiques -->
    <div class="lg:col-span-8 space-y-8">
        
        <!-- Garage Status / Slots -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-tools mr-3" style="color: #26658C;"></i>
                    Atelier en Direct
                </h3>
                <div class="flex items-center space-x-2">
                    <span class="pulse-animation flex h-3 w-3 rounded-full bg-green-500"></span>
                    <span class="text-xs text-gray-400 uppercase tracking-widest">Live</span>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @for($i = 1; $i <= 3; $i++)
                        @php $repair = $reparationsEnCours->where('slot', $i)->first(); @endphp
                        <div class="relative rounded-xl p-5 border" style="border-color: {{ $repair ? 'rgba(84, 172, 191, 0.3)' : 'rgb(55, 65, 81)' }}; background-color: {{ $repair ? 'rgba(84, 172, 191, 0.05)' : 'rgba(31, 41, 55, 0.2)' }};">
                            <div class="absolute top-4 right-4">
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold tracking-tighter uppercase" style="background-color: {{ $repair ? '#54ACBF' : 'rgb(55, 65, 81)' }}; color: white;">
                                    SLOT {{ $i }}
                                </span>
                            </div>
                            @if($repair)
                                <div class="flex items-start space-x-4">
                                    <div class="p-3 rounded-full" style="background: rgba(167, 235, 242, 0.15); color: #A7EBF2;">
                                        <i class="fas fa-car text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-white font-bold leading-tight">{{ $repair->client->nom }}</h4>
                                        <p class="text-gray-400 text-xs mt-1">{{ $repair->client->voiture_marque }} {{ $repair->client->voiture_modele }} • <span class="font-mono" style="color: #A7EBF2;">{{ $repair->client->voiture_immatriculation }}</span></p>
                                        <div class="mt-4 pt-4 border-t border-gray-700/50">
                                            <p class="text-[10px] text-gray-500 uppercase tracking-wider">Intervention</p>
                                            <p class="text-sm text-gray-200 mt-1">{{ $repair->intervention->nom }}</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center py-6 text-gray-600 border-2 border-dashed border-gray-700 rounded-lg">
                                    <i class="fas fa-plus-circle mb-2 opacity-30 text-2xl"></i>
                                    <span class="text-xs font-medium uppercase">Disponible</span>
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Graphiques & Analyse -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-white font-bold mb-6 flex items-center">
                    <i class="fas fa-chart-line mr-3" style="color: #A7EBF2;"></i>
                    Flux Financier
                </h3>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            <div class="glass-card rounded-2xl p-6">
                <h3 class="text-white font-bold mb-6 flex items-center">
                    <i class="fas fa-chart-pie mr-3" style="color: #26658C;"></i>
                    Répartition Services
                </h3>
                <div class="chart-container">
                    <canvas id="typeChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Filtres Temporels -->
        <div class="glass-card rounded-2xl p-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <h3 class="text-white font-bold flex items-center">
                    <i class="fas fa-calendar-alt mr-3" style="color: #A7EBF2;"></i>
                    Analyse Temporelle
                </h3>
                <div class="flex flex-wrap gap-2">
                    <select id="yearSelector" onchange="updateTemporalStats()" class="bg-gray-800 border-0 text-white text-xs rounded-lg px-3 py-2 outline-none focus:ring-2" style="focus:ring-color: #54ACBF;">
                        @for($y = 2024; $y <= now()->year + 1; $y++)
                            <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <select id="monthSelector" onchange="updateTemporalStats()" class="bg-gray-800 border-0 text-white text-xs rounded-lg px-3 py-2 outline-none focus:ring-2" style="focus:ring-color: #54ACBF;">
                        @foreach(['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'] as $index => $month)
                            <option value="{{ $index + 1 }}" {{ $selectedMonth == ($index + 1) ? 'selected' : '' }}>{{ $month }}</option>
                        @endforeach
                    </select>
                    <select id="weekSelector" onchange="updateTemporalStats()" class="bg-gray-800 border-0 text-white text-xs rounded-lg px-3 py-2 outline-none focus:ring-2" style="focus:ring-color: #54ACBF;">
                        @for($w = 1; $w <= 52; $w++)
                            <option value="{{ $w }}" {{ $selectedWeek == $w ? 'selected' : '' }}>Semaine {{ $w }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 rounded-xl" style="background: rgba(84, 172, 191, 0.15); backdrop-filter: blur(10px); border: 2px solid rgba(84, 172, 191, 0.5); box-shadow: 0 4px 15px rgba(84, 172, 191, 0.1);">
                    <p class="text-gray-400 text-[10px] uppercase font-bold mb-1">Cette Année</p>
                    <p class="text-white text-xl font-bold">{{ number_format($revenuesYear, 0, ',', ' ') }} <span class="text-sm font-normal text-gray-500">Ar</span></p>
                    <p style="color: #54ACBF;" class="text-xs mt-1">{{ $repairsYear }} interventions</p>
                </div>
                <div class="p-4 rounded-xl" style="background: rgba(38, 101, 140, 0.15); backdrop-filter: blur(10px); border: 2px solid rgba(38, 101, 140, 0.5); box-shadow: 0 4px 15px rgba(38, 101, 140, 0.1);">
                    <p class="text-gray-400 text-[10px] uppercase font-bold mb-1">Ce Mois</p>
                    <p class="text-white text-xl font-bold">{{ number_format($revenuesMonth, 0, ',', ' ') }} <span class="text-sm font-normal text-gray-500">Ar</span></p>
                    <p style="color: #26658C;" class="text-xs mt-1">{{ $repairsMonth }} interventions</p>
                </div>
                <div class="p-4 rounded-xl" style="background: rgba(167, 235, 242, 0.15); backdrop-filter: blur(10px); border: 2px solid rgba(167, 235, 242, 0.5); box-shadow: 0 4px 15px rgba(167, 235, 242, 0.1);">
                    <p class="text-gray-400 text-[10px] uppercase font-bold mb-1">Cette Semaine</p>
                    <p class="text-white text-xl font-bold">{{ number_format($revenuesWeek, 0, ',', ' ') }} <span class="text-sm font-normal text-gray-500">Ar</span></p>
                    <p style="color: #A7EBF2;" class="text-xs mt-1">{{ $repairsWeek }} interventions</p>
                </div>
            </div>
        </div>
    </div>

    <!-- COLONNE DROITE: Activité Récente & Exports -->
    <div class="lg:col-span-4 space-y-8">
        
        <!-- Section Export -->
        <div class="glass-card rounded-2xl p-6 overflow-hidden relative">
            <div class="absolute top-0 right-0 p-8 opacity-10">
                <i class="fas fa-file-export text-6xl text-white"></i>
            </div>
            <h3 class="text-white font-bold mb-2">Rapports</h3>
            <p class="text-gray-400 text-sm mb-6">Générez vos documents administratifs en un clic.</p>
            <div class="grid grid-cols-2 gap-3">
                <button onclick="exportToPDF()" class="flex items-center justify-center px-4 py-3 rounded-xl transition" style="background-color: rgba(239, 68, 68, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(239, 68, 68, 0.3); color: #EF4444;">
                    <i class="fas fa-file-pdf mr-2"></i> PDF
                </button>
                <button onclick="exportToExcel()" class="flex items-center justify-center px-4 py-3 rounded-xl transition" style="background-color: rgba(34, 197, 94, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(34, 197, 94, 0.3); color: #22C55E;">
                    <i class="fas fa-file-excel mr-2"></i> Excel
                </button>
            </div>
        </div>

        <!-- Terminées & En attente -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-800 bg-gray-800/10">
                <h3 class="text-white font-bold text-sm uppercase tracking-wider flex items-center">
                    <i class="fas fa-check-circle mr-2" style="color: #023859;"></i>
                    Terminées (En attente)
                </h3>
            </div>
            <div class="p-4 max-h-[400px] overflow-y-auto custom-scrollbar">
                @forelse($reparationsTerminees as $reparation)
                    <div class="p-4 rounded-xl bg-gray-800/20 border border-gray-700/50 mb-3 hover:bg-gray-800/40 transition group">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-white font-medium text-sm">{{ $reparation->client->nom }}</p>
                                <p class="text-gray-500 text-xs mt-1">{{ $reparation->intervention->nom }}</p>
                            </div>
                            <span class="text-white font-bold text-sm">{{ number_format($reparation->montant_total, 0, ',', ' ') }} <span class="text-[10px] text-gray-400">Ar</span></span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-600">
                        <i class="fas fa-inbox text-3xl mb-2 opacity-20"></i>
                        <p class="text-xs uppercase tracking-widest font-bold">Aucune</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Derniers Paiements -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-800 bg-gray-800/10">
                <h3 class="text-white font-bold text-sm uppercase tracking-wider flex items-center">
                    <i class="fas fa-history mr-2" style="color: #26658C;"></i>
                    Derniers Paiements
                </h3>
            </div>
            <div class="p-4 max-h-[400px] overflow-y-auto custom-scrollbar">
                @forelse($reparationsPayees as $reparation)
                    <div class="flex items-center space-x-4 mb-4 last:mb-0 p-3 rounded-lg hover:bg-white/5 transition">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center" style="background-color: rgba(38, 101, 140, 0.1); color: #26658C;">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ $reparation->client->nom }}</p>
                            <p class="text-xs text-gray-500">{{ $reparation->updated_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold" style="color: #A7EBF2;">+{{ number_format($reparation->montant_total, 0, ',', ' ') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center py-4 text-gray-600 text-xs italic">Aucun paiement récent</p>
                @endforelse
            </div>
        </div>

        <!-- Top Interventions -->
        <div class="glass-card rounded-2xl p-6">
            <h3 class="text-white font-bold mb-6 text-sm uppercase tracking-wider">Demandes Populaires</h3>
            <div class="space-y-4">
                @foreach($interventionsPopulaires as $intervention)
                    <div class="flex items-center">
                        <div class="flex-1">
                            <div class="flex justify-between mb-1">
                                <span class="text-gray-300 text-xs">{{ $intervention->intervention->nom }}</span>
                                <span class="text-white text-xs font-bold">{{ $intervention->count }}</span>
                            </div>
                            <div class="w-full bg-gray-800 rounded-full h-1.5 overflow-hidden">
                                @php 
                                    $divisor = max(array_merge($interventionsPopulaires->pluck('count')->toArray(), [1]));
                                    $percentage = ($intervention->count / $divisor) * 100;
                                @endphp
                                <div class="h-1.5 rounded-full" style="width: {{ $percentage }}%; background: linear-gradient(90deg, #54ACBF, #26658C, #A7EBF2);"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
console.log('Dashboard data:', {
    revenue: [{{ $totalRevenue }}, {{ $totalPending }}, {{ $reparationsEnCours->sum('montant_total') }}],
    types: {!! json_encode($interventionsPopulaires->pluck('intervention.nom')) !!},
    counts: {!! json_encode($interventionsPopulaires->pluck('count')) !!}
});

// Graphique des revenus
const revenueCtx = document.getElementById('revenueChart');
if (revenueCtx) {
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: ['Réglés', 'Attendus', 'En Cours'],
            datasets: [{
                data: [{{ $totalRevenue }}, {{ $totalPending }}, {{ $reparationsEnCours->sum('montant_total') }}],
                backgroundColor: ['#54ACBF', '#26658C', '#A7EBF2'],
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a2332',
                    titleColor: '#fff',
                    bodyColor: '#a0a0a0',
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return context.raw.toLocaleString() + ' Ar';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255,255,255,0.05)' },
                    ticks: { color: '#666', font: { size: 10 } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#666', font: { size: 10 } }
                }
            }
        }
    });
}

// Graphique des types
const typeCtx = document.getElementById('typeChart').getContext('2d');
if (typeCtx) {
    new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($interventionsPopulaires->pluck('intervention.nom')) !!},
            datasets: [{
                data: {!! json_encode($interventionsPopulaires->pluck('count')) !!},
                backgroundColor: ['#54ACBF', '#26658C', '#A7EBF2', '#023859', '#011C40'],
                borderWidth: 0,
                hoverOffset: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#a0a0a0', boxWidth: 12, padding: 15, font: { size: 10 } }
                }
            }
        }
    });
}

function updateTemporalStats() {
    const year = document.getElementById('yearSelector')?.value;
    const month = document.getElementById('monthSelector')?.value;
    const week = document.getElementById('weekSelector')?.value;
    if (year && month && week) {
        const url = new URL(window.location);
        url.searchParams.set('year', year);
        url.searchParams.set('month', month);
        url.searchParams.set('week', week);
        window.location.href = url.toString();
    }
}

function exportToPDF() {
    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.setFontSize(20);
        doc.text('Rapport Dashboard - Garage', 20, 20);
        doc.setFontSize(10);
        doc.text('Généré le: ' + new Date().toLocaleString(), 20, 30);
        
        const data = [
            ['Indicateur', 'Valeur'],
            ['Réparations Totales', '{{ $totalReparations }}'],
            ['Chiffre d\'Affaires', '{{ number_format($totalRevenue, 0, ",", " ") }} Ar'],
            ['En attente de paiement', '{{ number_format($totalPending, 0, ",", " ") }} Ar'],
            ['Trafic Clients', '{{ $clientsCount }}']
        ];
        
        doc.autoTable({
            head: [data[0]],
            body: data.slice(1),
            startY: 40,
            styles: { fontSize: 9 },
            headStyles: { fillColor: [84, 172, 191] }
        });
        
        doc.save('garage_report_' + new Date().toISOString().split('T')[0] + '.pdf');
    } catch (e) { console.error(e); }
}

function exportToExcel() {
    try {
        const wb = XLSX.utils.book_new();
        const data = [
            ['Dashboard Stats', ''],
            ['Total Réparations', '{{ $totalReparations }}'],
            ['CA Réglé', '{{ $totalRevenue }}'],
            ['CA Attendu', '{{ $totalPending }}'],
            ['Clients', '{{ $clientsCount }}']
        ];
        const ws = XLSX.utils.aoa_to_sheet(data);
        XLSX.utils.book_append_sheet(wb, ws, "Stats");
        XLSX.writeFile(wb, "garage_data.xlsx");
    } catch (e) { console.error(e); }
}
</script>
@endpush
@endsection
