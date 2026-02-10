<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">⚙️ Backoffice - Administration</h1>
      <div class="flex gap-2">
        <router-link 
          to="/statistics" 
          class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700"
        >
          📊 Statistiques
        </router-link>
        <router-link 
          to="/clients" 
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
        >
          👥 Clients
        </router-link>
      </div>
    </div>

    <!-- Active Repairs (3 slots) -->
    <div class="mb-8">
      <h2 class="text-xl font-bold mb-4">🚗 Réparations en Cours ({{ activeRepairs.length }}/3)</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div 
          v-for="slot in 3" 
          :key="slot"
          class="border-2 border-dashed rounded-lg p-4 min-h-[200px]"
          :class="getSlotClass(slot)"
        >
          <div class="text-center">
            <p class="font-bold text-gray-400">Slot {{ slot }}</p>
            
            <div v-if="getRepairInSlot(slot)" class="mt-4">
              <div class="bg-white rounded-lg p-4 shadow">
                <h3 class="font-bold text-lg">
                  {{ getRepairInSlot(slot).client.nom }}
                </h3>
                <p class="text-sm text-gray-500">
                  {{ getRepairInSlot(slot).client.voiture_marque }} 
                  {{ getRepairInSlot(slot).client.voiture_modele }}
                </p>
                <p class="text-sm text-gray-400 mt-2">
                  {{ getRepairInSlot(slot).client.voiture_immatriculation }}
                </p>
                
                <div class="mt-3">
                  <span 
                    class="px-2 py-1 rounded text-xs"
                    :class="getStatusClass(getRepairInSlot(slot).statut)"
                  >
                    {{ getStatusLabel(getRepairInSlot(slot).statut) }}
                  </span>
                </div>

                <div class="mt-3 space-y-1">
                  <p 
                    v-for="intervention in getRepairInSlot(slot).interventions" 
                    :key="intervention.id"
                    class="text-sm"
                  >
                    • {{ intervention.nom }} ({{ formatCurrency(intervention.prix) }})
                  </p>
                </div>

                <p class="mt-3 font-bold text-green-600">
                  Total: {{ formatCurrency(getRepairInSlot(slot).montant_total) }}
                </p>

                <div class="mt-4 flex gap-2">
                  <button 
                    v-if="getRepairInSlot(slot).statut === 'en_attente'"
                    @click="updateStatus(getRepairInSlot(slot).id, 'en_cours')"
                    class="flex-1 bg-yellow-500 text-white py-1 rounded text-sm hover:bg-yellow-600"
                  >
                    ▶ Démarrer
                  </button>
                  <button 
                    v-if="getRepairInSlot(slot).statut === 'en_cours'"
                    @click="updateStatus(getRepairInSlot(slot).id, 'termine')"
                    class="flex-1 bg-green-500 text-white py-1 rounded text-sm hover:bg-green-600"
                  >
                    ✓ Terminer
                  </button>
                </div>
              </div>
            </div>
            
            <div v-else class="mt-4 text-gray-400">
              <p class="text-4xl">➕</p>
              <p>Place disponible</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pending Repairs -->
    <div class="mb-8">
      <h2 class="text-xl font-bold mb-4">⏳ Réparations en Attente</h2>
      <div v-if="pendingRepairs.length === 0" class="text-gray-500 text-center py-8 bg-gray-100 rounded-lg">
        Aucune réparation en attente
      </div>
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div 
          v-for="repair in pendingRepairs" 
          :key="repair.id"
          class="bg-white rounded-lg p-4 shadow border-l-4 border-yellow-500"
        >
          <div class="flex justify-between items-start">
            <div>
              <h3 class="font-bold">{{ repair.client.nom }}</h3>
              <p class="text-sm text-gray-500">
                {{ repair.client.voiture_marque }} {{ repair.client.voiture_modele }}
              </p>
            </div>
            <button 
              @click="assignToSlot(repair.id)"
              class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600"
            >
              Slot →
            </button>
          </div>
          <div class="mt-3">
            <p class="text-sm font-medium">Interventions:</p>
            <div class="flex flex-wrap gap-1 mt-1">
              <span 
                v-for="intervention in repair.interventions" 
                :key="intervention.id"
                class="px-2 py-0.5 bg-gray-200 rounded text-xs"
              >
                {{ intervention.nom }}
              </span>
            </div>
          </div>
          <p class="mt-2 font-bold text-right">{{ formatCurrency(repair.montant_total) }}</p>
        </div>
      </div>
    </div>

    <!-- Waiting for Payment -->
    <div class="mb-8">
      <h2 class="text-xl font-bold mb-4">💳 En Attente de Paiement</h2>
      <div v-if="completedRepairs.length === 0" class="text-gray-500 text-center py-8 bg-gray-100 rounded-lg">
        Aucune réparation terminée en attente de paiement
      </div>
      <div v-else class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-3 text-left">Client</th>
              <th class="px-4 py-3 text-left">Véhicule</th>
              <th class="px-4 py-3 text-left">Date</th>
              <th class="px-4 py-3 text-right">Montant</th>
              <th class="px-4 py-3 text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="repair in completedRepairs" :key="repair.id" class="border-t">
              <td class="px-4 py-3">{{ repair.client.nom }}</td>
              <td class="px-4 py-3 text-gray-500">
                {{ repair.client.voiture_marque }} {{ repair.client.voiture_modele }}
              </td>
              <td class="px-4 py-3 text-gray-500">
                {{ formatDate(repair.updated_at) }}
              </td>
              <td class="px-4 py-3 font-bold text-right">
                {{ formatCurrency(repair.montant_total) }}
              </td>
              <td class="px-4 py-3 text-center">
                <button 
                  @click="markAsPaid(repair.id)"
                  class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600"
                >
                  💰 Marquer Payé
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-xl font-bold mb-4">⚡ Actions Rapides</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <router-link 
          to="/interventions" 
          class="p-4 border rounded-lg text-center hover:bg-gray-50"
        >
          <p class="text-2xl">🔧</p>
          <p class="font-medium mt-2">Interventions</p>
        </router-link>
        <router-link 
          to="/clients" 
          class="p-4 border rounded-lg text-center hover:bg-gray-50"
        >
          <p class="text-2xl">👥</p>
          <p class="font-medium mt-2">Clients</p>
        </router-link>
        <button 
          @click="syncFirebase"
          class="p-4 border rounded-lg text-center hover:bg-gray-50"
        >
          <p class="text-2xl">🔥</p>
          <p class="font-medium mt-2">Sync Firebase</p>
        </button>
        <button 
          @click="exportData"
          class="p-4 border rounded-lg text-center hover:bg-gray-50"
        >
          <p class="text-2xl">📤</p>
          <p class="font-medium mt-2">Exporter</p>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Backoffice',
  data() {
    return {
      activeRepairs: [],
      pendingRepairs: [],
      completedRepairs: []
    }
  },
  mounted() {
    this.fetchRepairs()
  },
  methods: {
    async fetchRepairs() {
      try {
        // Fetch active repairs
        const activeResponse = await fetch('http://localhost:8000/api/repairs/active')
        const activeData = await activeResponse.json()
        if (activeData.success) {
          this.activeRepairs = activeData.data.repairs
        }

        // Fetch completed repairs
        const completedResponse = await fetch('http://localhost:8000/api/repairs/completed')
        const completedData = await completedResponse.json()
        if (completedData.success) {
          this.completedRepairs = completedData.data
        }

        // Fetch pending repairs (en_attente)
        const pendingResponse = await fetch('http://localhost:8000/api/repairs?statut=en_attente')
        const pendingData = await pendingResponse.json()
        if (pendingData.success) {
          this.pendingRepairs = pendingData.data.data
        }
      } catch (err) {
        console.error('Erreur:', err)
      }
    },
    getRepairInSlot(slot) {
      return this.activeRepairs.find(r => r.slot === slot)
    },
    getSlotClass(slot) {
      return this.getRepairInSlot(slot) 
        ? 'border-solid border-blue-300 bg-blue-50' 
        : 'border-gray-300'
    },
    getStatusClass(status) {
      const classes = {
        'en_attente': 'bg-yellow-100 text-yellow-800',
        'en_cours': 'bg-blue-100 text-blue-800',
        'termine': 'bg-green-100 text-green-800',
        'paye': 'bg-gray-100 text-gray-800'
      }
      return classes[status] || 'bg-gray-100'
    },
    getStatusLabel(status) {
      const labels = {
        'en_attente': 'En attente',
        'en_cours': 'En cours',
        'termine': 'Terminé',
        'paye': 'Payé'
      }
      return labels[status] || status
    },
    async updateStatus(repairId, newStatus) {
      try {
        const response = await fetch(`http://localhost:8000/api/repairs/${repairId}/status`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          },
          body: JSON.stringify({ statut: newStatus })
        })
        const data = await response.json()
        if (data.success) {
          this.fetchRepairs()
        } else {
          alert(data.message)
        }
      } catch (err) {
        console.error('Erreur:', err)
      }
    },
    async assignToSlot(repairId) {
      const slot = prompt('Numéro du slot (1, 2 ou 3):')
      if (!slot || !['1', '2', '3'].includes(slot)) {
        alert('Slot invalide')
        return
      }
      
      try {
        const response = await fetch(`http://localhost:8000/api/repairs/${repairId}/status`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          },
          body: JSON.stringify({ 
            statut: 'en_attente',
            slot: parseInt(slot)
          })
        })
        const data = await response.json()
        if (data.success) {
          this.fetchRepairs()
        } else {
          alert(data.message)
        }
      } catch (err) {
        console.error('Erreur:', err)
      }
    },
    async markAsPaid(repairId) {
      if (!confirm('Confirmer le paiement ?')) return
      
      try {
        const response = await fetch(`http://localhost:8000/api/repairs/${repairId}/status`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`
          },
          body: JSON.stringify({ statut: 'paye' })
        })
        const data = await response.json()
        if (data.success) {
          this.fetchRepairs()
        } else {
          alert(data.message)
        }
      } catch (err) {
        console.error('Erreur:', err)
      }
    },
    syncFirebase() {
      alert('Synchronisation Firebase déclenchée')
    },
    exportData() {
      alert('Export des données déclenché')
    },
    formatCurrency(value) {
      return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(value || 0)
    },
    formatDate(dateStr) {
      return new Date(dateStr).toLocaleDateString('fr-FR')
    }
  }
}
</script>
