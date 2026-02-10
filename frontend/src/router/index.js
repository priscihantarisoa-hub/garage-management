import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('../views/Home.vue')
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Login.vue')
  },
  {
    path: '/clients',
    name: 'Clients',
    component: () => import('../views/Clients.vue')
  },
  {
    path: '/clients/:id',
    name: 'ClientDetail',
    component: () => import('../views/ClientHistory.vue')
  },
  {
    path: '/clients/:id/history',
    name: 'ClientHistory',
    component: () => import('../views/ClientHistory.vue')
  },
  {
    path: '/interventions',
    name: 'Interventions',
    component: () => import('../views/Interventions.vue')
  },
  {
    path: '/statistics',
    name: 'Statistics',
    component: () => import('../views/Statistics.vue')
  },
  {
    path: '/backoffice',
    name: 'Backoffice',
    component: () => import('../views/Backoffice.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
