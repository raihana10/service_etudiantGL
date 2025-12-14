<template>
  <aside class="sidebar">
    <div class="sidebar-content">
      <!-- Navigation principale -->
      <nav class="sidebar-nav">
        <router-link
          to="/admin/dashboard"
          class="nav-link"
          :class="{ 'nav-link-active': $route.path === '/admin/dashboard' }"
        >
          <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
          </svg>
          Dashboard
        </router-link>

        <router-link
          to="/admin/demandes"
          class="nav-link"
          :class="{ 'nav-link-active': $route.path === '/admin/demandes' }"
        >
          <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
          Gestion demandes
        </router-link>

        <router-link
          to="/admin/reclamations"
          class="nav-link"
          :class="{ 'nav-link-active': $route.path === '/admin/reclamations' }"
        >
          <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          Réclamations
        </router-link>

        <router-link
          to="/admin/historique"
          class="nav-link"
          :class="{ 'nav-link-active': $route.path === '/admin/historique' }"
        >
          <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          Historique
        </router-link>
      </nav>

      <!-- Séparateur -->
      <div class="nav-separator"></div>

      <!-- Déconnexion -->
      <div class="logout-section">
        <button @click="handleLogout" class="logout-button">
          <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
          Déconnexion
        </button>
      </div>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'

const router = useRouter()

const handleLogout = () => {
  // Logique de déconnexion
  localStorage.removeItem('adminToken')
  router.push('/admin/login')
}
</script>

<style scoped>
.sidebar {
  width: 16rem;
  background-color: #0A0D25;
  min-height: 100vh;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.sidebar-content {
  padding: 1.5rem 0;
}

.sidebar-nav {
  padding: 0 1rem;
  margin-bottom: 0.5rem;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  color: #d1d5db;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
  text-decoration: none;
  font-weight: 500;
}

.nav-link:hover {
  background-color: rgba(255, 255, 255, 0.1);
  color: white;
}

.nav-link-active {
  background-color: rgba(255, 255, 255, 0.2);
  color: white;
}

.nav-icon {
  width: 1.25rem;
  height: 1.25rem;
  margin-right: 0.75rem;
  transition: transform 0.2s ease;
}

.nav-link:hover .nav-icon {
  transform: scale(1.1);
}

.nav-separator {
  border-top: 1px solid #374151;
  margin: 1.5rem 1rem;
}

.logout-section {
  padding: 0 1rem;
}

.logout-button {
  width: 100%;
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  color: #f87171;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
  background: none;
  border: none;
  cursor: pointer;
  font-weight: 500;
}

.logout-button:hover {
  background-color: rgba(239, 68, 68, 0.2);
  color: #fca5a5;
}

.logout-button:hover .nav-icon {
  transform: scale(1.1);
}
</style>
