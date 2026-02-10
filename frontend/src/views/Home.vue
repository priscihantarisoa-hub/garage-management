<template>
  <div>
    <h1 class="text-3xl font-bold mb-6">🏢 Garage Manager</h1>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="card">
        <h3 class="text-gray-500 text-sm">Total des Interventions</h3>
        <p class="text-3xl font-bold text-green-600">{{ formatCurrency(store.statistics.totalAmount) }}</p>
      </div>
      <div class="card">
        <h3 class="text-gray-500 text-sm">Nombre de Clients</h3>
        <p class="text-3xl font-bold text-blue-600">{{ store.statistics.clientCount }}</p>
      </div>
      <div class="card">
        <h3 class="text-gray-500 text-sm">Places Disponibles</h3>
        <p class="text-3xl font-bold" :class="store.availableSlots > 0 ? 'text-green-600' : 'text-red-600'">
          {{ store.availableSlots }}/3
        </p>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="card">
        <h2 class="text-xl font-bold mb-4">🚗 Réparations en Cours</h2>
        <div v-if="store.interventions.length === 0" class="text-gray-500">
          Aucune réparation en cours
        </div>
        <div v-else class="space-y-3">
          <div 
            v-for="intervention in inProgressInterventions" 
            :key="intervention.id"
            class="border rounded p-3 flex justify-between items-center"
          >
            <div>
              <p class="font-semibold">{{ intervention.vehicle }}</p>
              <p class="text-sm text-gray-500">{{ intervention.client_name }}</p>
            </div>
            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
              En cours
            </span>
          </div>
        </div>
      </div>

      <div class="card">
        <h2 class="text-xl font-bold mb-4">📋 Réparations en Attente</h2>
        <div v-if="pendingInterventions.length === 0" class="text-gray-500">
          Aucune réparation en attente
        </div>
        <div v-else class="space-y-3">
          <div 
            v-for="intervention in pendingInterventions" 
            :key="intervention.id"
            class="border rounded p-3 flex justify-between items-center"
          >
            <div>
              <p class="font-semibold">{{ intervention.vehicle }}</p>
              <p class="text-sm text-gray-500">{{ intervention.client_name }}</p>
            </div>
            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
              En attente
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useGarageStore } from '../stores/garage'

const store = useGarageStore()

const inProgressInterventions = computed(() => 
  store.interventions.filter(i => i.status === 'in_progress')
)

const pendingInterventions = computed(() => 
  store.interventions.filter(i => i.status === 'pending')
)

function formatCurrency(amount) {
  return new Intl.NumberFormat('fr-FR', { 
    style: 'currency', 
    currency: 'EUR' 
  }).format(amount)
}

onMounted(() => {
  store.fetchStatistics()
  store.fetchInterventions()
})
</script>
