<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">📋 Gestion des Clients</h1>
      <router-link 
        to="/clients/new" 
        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700"
      >
        + Nouveau Client
      </router-link>
    </div>

    <!-- Search -->
    <div class="mb-6">
      <input
        v-model="search"
        type="text"
        placeholder="Rechercher par nom, email ou immatriculation..."
        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        @input="fetchClients"
      />
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <div class="bg-white p-4 rounded-lg shadow">
        <p class="text-gray-500 text-sm">Total Clients</p>
        <p class="text-2xl font-bold text-blue-600">{{ stats.total }}</p>
      </div>
      <div class="bg-white p-4 rounded-lg shadow">
        <p class="text-gray-500 text-sm">Réparations actives</p>
        <p class="text-2xl font-bold text-yellow-600">{{ stats.activeRepairs }}</p>
      </div>
      <div class="bg-white p-4 rounded-lg shadow">
        <p class="text-gray-500 text-sm">Chiffre d'affaires</p>
        <p class="text-2xl font-bold text-green-600">{{ formatCurrency(stats.revenue) }}</p>
      </div>
    </div>

    <!-- Clients Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">ID</th>
            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nom</th>
            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Email</th>
            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Téléphone</th>
            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Véhicule</th>
            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="client in clients" :key="client.id" class="border-t hover:bg-gray-50">
            <td class="px-4 py-3">{{ client.id }}</td>
            <td class="px-4 py-3 font-medium">{{ client.nom }}</td>
            <td class="px-4 py-3 text-gray-500">{{ client.email }}</td>
            <td class="px-4 py-3 text-gray-500">{{ client.telephone || '-' }}</td>
            <td class="px-4 py-3 text-gray-500">
              {{ client.voiture_marque }} {{ client.voiture_modele }}
              <br>
              <span class="text-xs text-gray-400">{{ client.voiture_immatriculation }}</span>
            </td>
            <td class="px-4 py-3">
              <router-link 
                :to="`/clients/${client.id}`"
                class="text-blue-500 hover:underline mr-3"
              >
                Voir
              </router-link>
              <router-link 
                :to="`/clients/${client.id}/history`"
                class="text-green-500 hover:underline"
              >
                Historique
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="loading" class="p-8 text-center">
        <p class="text-gray-500">Chargement...</p>
      </div>

      <div v-if="!loading && clients.length === 0" class="p-8 text-center">
        <p class="text-gray-500">Aucun client trouvé</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Clients',
  data() {
    return {
      clients: [],
      search: '',
      loading: false,
      stats: {
        total: 0,
        activeRepairs: 0,
        revenue: 0
      }
    }
  },
  mounted() {
    this.fetchClients()
  },
  methods: {
    async fetchClients() {
      this.loading = true
      try {
        const params = new URLSearchParams()
        if (this.search) params.append('search', this.search)
        
        const response = await fetch(`http://localhost:8000/api/clients?${params}`)
        const data = await response.json()
        
        if (data.success) {
          this.clients = data.data.data
        }
      } catch (err) {
        console.error('Erreur:', err)
      } finally {
        this.loading = false
      }
    },
    async fetchStats() {
      try {
        const response = await fetch('http://localhost:8000/api/stats')
        const data = await response.json()
        if (data.success) {
          this.stats = {
            total: 0, // Calculé depuis les clients
            activeRepairs: data.data.repairs_in_progress,
            revenue: data.data.total_amount
          }
        }
      } catch (err) {
        console.error('Erreur stats:', err)
      }
    },
    formatCurrency(value) {
      return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(value || 0)
    }
  }
}
</script>
