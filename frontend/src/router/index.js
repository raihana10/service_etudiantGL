import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/components/admin/Login.vue'
import Dashboard from '@/components/admin/Dashboard.vue'
import DemandesList from '@/components/admin/DemandesList.vue'
import Historique from '@/components/admin/Historique.vue'
import Reclamations from '@/components/admin/Reclamations.vue'
import FormulaireEtudiant from '@/components/etudiant/FormulaireEtudiant.vue'

// Guard d'authentification pour les routes admin
const requireAuth = (to, from, next) => {
  const token = localStorage.getItem('admin_token')
  const adminInfo = localStorage.getItem('admin_info')
  
  if (!token || !adminInfo) {
    // Rediriger vers la page de login si non authentifié
    next('/admin/login')
  } else {
    // Vérifier si le token est valide (optionnel: appel API pour vérifier)
    next()
  }
}

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
      component: Login,
      // Si déjà connecté, rediriger vers le dashboard
      beforeEnter: (to, from, next) => {
        const token = localStorage.getItem('admin_token')
        if (token) {
          next('/admin/dashboard')
        } else {
          next()
        }
      }
    },
    {
      path: '/admin/dashboard',
      name: 'AdminDashboard',
      component: Dashboard,
      beforeEnter: requireAuth
    },
    {
      path: '/admin/demandes',
      name: 'AdminDemandes',
      component: DemandesList,
      beforeEnter: requireAuth
    },
    {
      path: '/admin/historique',
      name: 'AdminHistorique',
      component: Historique,
      beforeEnter: requireAuth
    },
    {
      path: '/admin/reclamations',
      name: 'AdminReclamations',
      component: Reclamations,
      beforeEnter: requireAuth
    }
  ],
})

export default router
