import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/components/admin/Login.vue'
import Dashboard from '@/components/admin/Dashboard.vue'
import DemandesList from '@/components/admin/DemandesList.vue'
import Historique from '@/components/admin/Historique.vue'
import Reclamations from '@/components/admin/Reclamations.vue'
import FormulaireEtudiant from '@/components/etudiant/FormulaireEtudiant.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Espace Etudiant',
      component: FormulaireEtudiant
    },
    {
      path: '/admin/login',
      name: 'AdminLogin',
      component: Login
    },
    {
      path: '/admin/dashboard',
      name: 'AdminDashboard',
      component: Dashboard
    },
    {
      path: '/admin/demandes',
      name: 'AdminDemandes',
      component: DemandesList
    },
    {
      path: '/admin/historique',
      name: 'AdminHistorique',
      component: Historique
    },
    {
      path: '/admin/reclamations',
      name: 'AdminReclamations',
      component: Reclamations
    }
  ],
})

export default router
