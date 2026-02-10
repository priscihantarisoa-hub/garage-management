<template>
  <div>
    <button @click="$router.back()" class="mb-4 text-blue-600 hover:underline">
      ← Retour
    </button>
    
    <div v-if="loading" class="text-center py-8">
      <p class="text-gray-500">Chargement...</p>
    </div>
    
    <div v-else>
      <h1 class="text-2xl font-bold mb-2">📜 Historique du Client</h1>
      <p class="text-gray-500 mb-6">{{ clientName }}</p>

      <div v-if="history.length === 0" class="card text-center py-8">
        <p class="text-gray-500">Aucune réparation pour ce client</p>
      </div>

      <div v-else class="space-y-4">
        <div 
          v-for="item in history" 
          :key="item.id"
          class="card"
        >
          <div class="flex justify-between items-start">
            <div>
              <p class="font-semibold">{{ formatDate(item.date) }}</p>
              <div class="mt-2">
                <span 
                  v-for="(inter, idx) in item.interventions" 
                  :key="idx"
                  class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-2"
                >
                  {{ inter }}
                </span>
              </div>
            </div>
            <p class="text-xl font-bold text-green-600">
              {{ formatCurrency(item.amount) }}
            </p>
          </div>
        </div>
      </div>

      <div class="card mt-6 bg-gray-50">
        <div class="flex justify-between items-center">
          <span class="font-semibold">Total des dépenses</span>
          <span class="text-2xl font-bold text-green-600">
            {{ formatCurrency(totalAmount) }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useGarageStore } from '../stores/garage'

const route = useRoute()
const store = useGarageStore()
const history = ref([])
const loading = ref(true)

const clientName = computed(() => {
  const client = store.clients.find(c => c.id === parseInt(route.params.id))
  return client ? client.name : 'Client'
})

const totalAmount = computed(() => 
  history.value.reduce((sum, item) => sum + item.amount, 0)
)

function formatDate(dateStr) {
  return new Date(dateStr).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

function formatCurrency(amount) {
  return new Intl.NumberFormat('fr-FR', { 
    style: 'currency', 
    currency: 'EUR' 
  }).format(amount)
}

onMounted(async () => {
  await store.fetchClients()
  history.value = await store.fetchClientHistory(route.params.id)
  loading.value = false
})
</script>
