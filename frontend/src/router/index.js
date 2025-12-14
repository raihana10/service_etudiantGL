import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/components/admin/Login.vue'
import Dashboard from '@/components/admin/Dashboard.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/admin/login',
      name: 'AdminLogin',
      component: Login
    },
    {
      path: '/admin/dashboard',
      name: 'AdminDashboard',
      component: Dashboard
    }
  ],
})

export default router
