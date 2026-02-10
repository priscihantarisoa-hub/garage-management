<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">🔧 Liste des Interventions</h1>
      <button 
        @click="showAddModal = true" 
        class="btn btn-primary"
        :disabled="store.availableSlots === 0"
      >
        + Nouvelle Intervention
        <span v-if="store.availableSlots === 0" class="ml-2 text-xs">
          (Garage plein)
        </span>
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div 
        v-for="intervention in store.interventions" 
        :key="intervention.id"
        class="card"
      >
        <div class="flex justify-between items-start mb-3">
          <div>
            <h3 class="font-bold text-lg">{{ intervention.vehicle }}</h3>
            <p class="text-sm text-gray-500">{{ intervention.client_name }}</p>
          </div>
          <span 
            class="px-2 py-1 rounded-full text-xs font-medium"
            :class="{
              'bg-yellow-100 text-yellow-800': intervention.status === 'pending',
              'bg-green-100 text-green-800': intervention.status === 'completed',
              'bg-blue-100 text-blue-800': intervention.status === 'in_progress',
              'bg-gray-100 text-gray-800': intervention.status === 'waiting_payment'
            }"
          >
            {{ statusLabel(intervention.status) }}
          </span>
        </div>
        
        <div class="mb-3">
          <span 
            v-for="(inter, idx) in intervention.interventions" 
            :key="idx"
            class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded mr-2 mb-1"
          >
            {{ inter }}
          </span>
        </div>
        
        <p class="text-xs text-gray-500 mb-3">
          Créé le {{ formatDate(intervention.created_at) }}
        </p>
        
        <div class="flex gap-2">
          <button 
            v-if="intervention.status === 'pending'"
            @click="startIntervention(intervention)"
            class="btn btn-primary flex-1 text-sm"
          >
            Démarrer
          </button>
          <button 
            v-if="intervention.status === 'in_progress'"
            @click="completeIntervention(intervention)"
            class="btn btn-success flex-1 text-sm"
          >
            Terminer
          </button>
          <button 
            v-if="intervention.status === 'waiting_payment'"
            @click="paymentIntervention(intervention)"
            class="btn btn-success flex-1 text-sm"
          >
            Payer
          </button>
        </div>
      </div>
    </div>

    <!-- Add Intervention Modal -->
    <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="card w-full max-w-lg">
        <h2 class="text-xl font-bold mb-4">Nouvelle Intervention</h2>
        <form @submit.prevent="handleAddIntervention">
          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Client</label>
            <select v-model="newIntervention.client_id" class="input" required>
              <option value="">Sélectionner un client</option>
              <option v-for="client in store.clients" :key="client.id" :value="client.id">
                {{ client.name }}
              </option>
            </select>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Véhicule</label>
            <input v-model="newIntervention.vehicle" type="text" class="input" required>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium mb-2">Réparations</label>
            <div class="grid grid-cols-2 gap-2">
              <label 
                v-for="type in store.interventionTypes" 
                :key="type.id"
                class="flex items-center gap-2 p-2 border rounded cursor-pointer hover:bg-gray-50"
                :class="{ 'bg-blue-50 border-blue-500': newIntervention.interventions.includes(type.name) }"
              >
                <input 
                  type="checkbox" 
                  :value="type.name"
                  v-model="newIntervention.interventions"
                >
                <span class="text-sm">{{ type.name }} ({{ formatCurrency(type.price) }})</span>
              </label>
            </div>
          </div>
          <div class="flex gap-3">
            <button type="submit" class="btn btn-primary flex-1">Créer</button>
            <button type="button" @click="showAddModal = false" class="btn bg-gray-300 flex-1">Annuler</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useGarageStore } from '../stores/garage'

const store = useGarageStore()
const showAddModal = ref(false)
const newIntervention = ref({
  client_id: '',
  vehicle: '',
  interventions: []
})

function statusLabel(status) {
  const labels = {
    pending: 'En attente',
    in_progress: 'En cours',
    completed: 'Terminé',
    waiting_payment: 'En attente paiement'
  }
  return labels[status] || status
}

function formatDate(dateStr) {
  return new Date(dateStr).toLocaleDateString('fr-FR')
}

function formatCurrency(amount) {
  return new Intl.NumberFormat('fr-FR', { 
    style: 'currency', 
    currency: 'EUR' 
  }).format(amount)
}

async function startIntervention(intervention) {
  await store.updateInterventionStatus(intervention.id, 'in_progress')
}

async function completeIntervention(intervention) {
  await store.updateInterventionStatus(intervention.id, 'waiting_payment')
}

async function paymentIntervention(intervention) {
  await store.updateInterventionStatus(intervention.id, 'completed')
}

async function handleAddIntervention() {
  await store.createIntervention(newIntervention.value)
  showAddModal.value = false
  newIntervention.value = { client_id: '', vehicle: '', interventions: [] }
}

onMounted(() => {
  store.fetchClients()
  store.fetchInterventionTypes()
  store.fetchInterventions()
})
</script>
