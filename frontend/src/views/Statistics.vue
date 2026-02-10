<template>
  <div>
    <h1 class="text-2xl font-bold mb-6">📊 Statistiques du Garage</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Total Réparations</p>
            <p class="text-3xl font-bold text-blue-600">{{ stats.total_repairs }}</p>
          </div>
          <div class="text-4xl">🔧</div>
        </div>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">En Cours</p>
            <p class="text-3xl font-bold text-yellow-600">{{ stats.repairs_in_progress }}</p>
          </div>
          <div class="text-4xl">⚙️</div>
        </div>
        <p class="text-sm text-gray-400 mt-2">Places disponibles: {{ stats.available_slots }}/3</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Terminées</p>
            <p class="text-3xl font-bold text-green-600">{{ stats.repairs_completed }}</p>
          </div>
          <div class="text-4xl">✅</div>
        </div>
      </div>

      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Payées</p>
            <p class="text-3xl font-bold text-purple-600">{{ stats.repairs_paid }}</p>
          </div>
          <div class="text-4xl">💰</div>
        </div>
      </div>
    </div>

    <!-- Revenue Card -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-lg shadow mb-8">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-green-100 text-sm">Chiffre d'affaires total</p>
          <p class="text-4xl font-bold">{{ formatCurrency(stats.total_amount) }}</p>
        </div>
        <div class="text-6xl">💵</div>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Monthly Chart Placeholder -->
      <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-bold mb-4">📈 Réparations par mois</h2>
        <div class="h-64 flex items-end justify-between gap-2">
          <div 
            v-for="(month, index) in monthlyData" 
            :key="index"
            class="flex-1 bg-blue-500 rounded-t"
            :style="{ height: `${month.percentage}%` }"
          >
            <div class="text-center text-xs text-white py-1">{{ month.count }}</div>
          </div>
        </div>
        <div class="flex justify-between mt-2 text-xs text-gray-500">
          <span>Jan</span>
          <span>Fév</span>
          <span>Mar</span>
          <span>Avr</span>
          <span>Mai</span>
          <span>Juin</span>
        </div>
      </div>

      <!-- Interventions Distribution -->
      <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-bold mb-4">🔧 Réparations par type</h2>
        <div class="space-y-4">
          <div v-for="type in interventionTypes" :key="type.name" class="flex items-center">
            <span class="w-32 text-sm text-gray-600">{{ type.name }}</span>
            <div class="flex-1 bg-gray-200 rounded-full h-4 mx-2">
              <div 
                class="bg-blue-500 h-4 rounded-full"
                :style="{ width: `${type.percentage}%` }"
              ></div>
            </div>
            <span class="w-12 text-sm text-right">{{ type.count }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white p-6 rounded-lg shadow mt-6">
      <h2 class="text-lg font-bold mb-4">🕐 Activité récente</h2>
      <div class="space-y-3">
        <div v-for="activity in recentActivity" :key="activity.id" class="flex items-center p-3 bg-gray-50 rounded">
          <span class="text-2xl mr-3">{{ activity.icon }}</span>
          <div>
            <p class="font-medium">{{ activity.message }}</p>
            <p class="text-sm text-gray-400">{{ activity.time }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Statistics',
  data() {
    return {
      stats: {
        total_repairs: 0,
        repairs_in_progress: 0,
        repairs_pending: 0,
        repairs_completed: 0,
        repairs_paid: 0,
        total_amount: 0,
        available_slots: 3,
        total_clients: 0
      },
      monthlyData: [
        { count: 12, percentage: 60 },
        { count: 18, percentage: 90 },
        { count: 15, percentage: 75 },
        { count: 22, percentage: 100 },
        { count: 20, percentage: 95 },
        { count: 16, percentage: 80 }
      ],
      interventionTypes: [
        { name: 'Frein', count: 25, percentage: 80 },
        { name: 'Vidange', count: 30, percentage: 100 },
        { name: 'Pneus', count: 18, percentage: 60 },
        { name: 'Batterie', count: 12, percentage: 40 },
        { name: 'Amortisseurs', count: 15, percentage: 50 }
      ],
      recentActivity: []
    }
  },
  mounted() {
    this.fetchStats()
  },
  methods: {
    async fetchStats() {
      try {
        const response = await fetch('http://localhost:8000/api/stats')
        const data = await response.json()
        
        if (data.success) {
          this.stats = data.data
        }
      } catch (err) {
        console.error('Erreur:', err)
        // Mock data for demo
        this.stats = {
          total_repairs: 156,
          repairs_in_progress: 2,
          repairs_pending: 5,
          repairs_completed: 142,
          repairs_paid: 138,
          total_amount: 45230,
          available_slots: 1,
          total_clients: 89
        }
      }
    },
    formatCurrency(value) {
      return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(value || 0)
    }
  }
}
</script>
