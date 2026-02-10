<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garage Backoffice</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background-color: #0f1419;">
    <nav style="background-color: #54ACBF;" class="text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-wrench mr-3 text-xl"></i>
                    <h1 class="text-xl font-bold">Garage Backoffice</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span style="background-color: rgba(255,255,255,0.1);" class="px-3 py-2 rounded">
                        <i class="fas fa-tachometer-alt mr-2"></i>Tableau de bord
                    </span>
                    <span style="background-color: rgba(255,255,255,0.1);" class="px-3 py-2 rounded">
                        <i class="fas fa-tools mr-2"></i>Interventions
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div style="background-color: #1a2332;" class="rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full" style="background-color: rgba(84, 172, 191, 0.2);">
                        <i class="fas fa-wrench text-xl" style="color: #54ACBF;"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm" style="color: #a0a0a0;">Total réparations</p>
                        <p class="text-2xl font-bold" style="color: #ffffff;">0</p>
                    </div>
                </div>
            </div>

            <div style="background-color: #1a2332;" class="rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full" style="background-color: rgba(16, 185, 129, 0.2);">
                        <i class="fas fa-users text-xl" style="color: #10b981;"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm" style="color: #a0a0a0;">Nombre de clients</p>
                        <p class="text-2xl font-bold" style="color: #ffffff;">0</p>
                    </div>
                </div>
            </div>

            <div style="background-color: #1a2332;" class="rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full" style="background-color: rgba(251, 191, 36, 0.2);">
                        <i class="fas fa-euro-sign text-xl" style="color: #fbbf24;"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm" style="color: #a0a0a0;">Montant total</p>
                        <p class="text-2xl font-bold" style="color: #ffffff;">0.00 Ar</p>
                    </div>
                </div>
            </div>
        </div>

        <div style="background-color: #1a2332;" class="rounded-lg shadow">
            <div style="background-color: #252f3f; border-bottom-color: #2d3f54;" class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold" style="color: #ffffff;">
                    <i class="fas fa-tools mr-2" style="color: #54ACBF;"></i>Interventions disponibles
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div style="background-color: #252f3f; border-color: #2d3f54;" class="border rounded-lg p-4 hover:shadow-md transition">
                        <h4 class="font-semibold" style="color: #54ACBF;">Frein</h4>
                        <p class="text-sm" style="color: #a0a0a0;">Changement plaquettes</p>
                        <p class="text-lg font-bold" style="color: #ffffff;">440,950 Ar</p>
                        <p class="text-xs" style="color: #6a7a8a;">30 minutes</p>
                    </div>
                    <div style="background-color: #252f3f; border-color: #2d3f54;" class="border rounded-lg p-4 hover:shadow-md transition">
                        <h4 class="font-semibold" style="color: #54ACBF;">Vidange</h4>
                        <p class="text-sm" style="color: #a0a0a0;">Vidange moteur</p>
                        <p class="text-lg font-bold" style="color: #ffffff;">293,950 Ar</p>
                        <p class="text-xs" style="color: #6a7a8a;">20 minutes</p>
                    </div>
                    <div style="background-color: #252f3f; border-color: #2d3f54;" class="border rounded-lg p-4 hover:shadow-md transition">
                        <h4 class="font-semibold" style="color: #54ACBF;">Filtre</h4>
                        <p class="text-sm" style="color: #a0a0a0;">Filtre à air</p>
                        <p class="text-lg font-bold" style="color: #ffffff;">146,950 Ar</p>
                        <p class="text-xs" style="color: #6a7a8a;">10 minutes</p>
                    </div>
                    <div style="background-color: #252f3f; border-color: #2d3f54;" class="border rounded-lg p-4 hover:shadow-md transition">
                        <h4 class="font-semibold" style="color: #54ACBF;">Batterie</h4>
                        <p class="text-sm" style="color: #a0a0a0;">Changement batterie</p>
                        <p class="text-lg font-bold" style="color: #ffffff;">636,950 Ar</p>
                        <p class="text-xs" style="color: #6a7a8a;">15 minutes</p>
                    </div>
                    <div style="background-color: #252f3f; border-color: #2d3f54;" class="border rounded-lg p-4 hover:shadow-md transition">
                        <h4 class="font-semibold" style="color: #54ACBF;">Amortisseurs</h4>
                        <p class="text-sm" style="color: #a0a0a0;">Remplacement</p>
                        <p class="text-lg font-bold" style="color: #ffffff;">1,224,950 Ar</p>
                        <p class="text-xs" style="color: #6a7a8a;">1 heure</p>
                    </div>
                    <div style="background-color: #252f3f; border-color: #2d3f54;" class="border rounded-lg p-4 hover:shadow-md transition">
                        <h4 class="font-semibold" style="color: #54ACBF;">Embrayage</h4>
                        <p class="text-sm" style="color: #a0a0a0;">Kit complet</p>
                        <p class="text-lg font-bold" style="color: #ffffff;">2,204,950 Ar</p>
                        <p class="text-xs" style="color: #6a7a8a;">1h30</p>
                    </div>
                    <div style="background-color: #252f3f; border-color: #2d3f54;" class="border rounded-lg p-4 hover:shadow-md transition">
                        <h4 class="font-semibold" style="color: #54ACBF;">Pneus</h4>
                        <p class="text-sm" style="color: #a0a0a0;">Montage 4 pneus</p>
                        <p class="text-lg font-bold" style="color: #ffffff;">391,950 Ar</p>
                        <p class="text-xs" style="color: #6a7a8a;">40 minutes</p>
                    </div>
                    <div style="background-color: #252f3f; border-color: #2d3f54;" class="border rounded-lg p-4 hover:shadow-md transition">
                        <h4 class="font-semibold" style="color: #54ACBF;">Refroidissement</h4>
                        <p class="text-sm" style="color: #a0a0a0;">Vidange système</p>
                        <p class="text-lg font-bold" style="color: #ffffff;">489,950 Ar</p>
                        <p class="text-xs" style="color: #6a7a8a;">25 minutes</p>
                    </div>
                </div>
            </div>
        </div>

        </main>
</body>
</html>
