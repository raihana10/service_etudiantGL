<template>
  <aside class="sidebar">
    <div class="sidebar-content">
      <!-- Zone de marque/Logo -->
      <div class="brand-section">
        <div class="brand-rectangle">
          <span class="brand-text">Service Étudiant</span>
        </div>
      </div>

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
  width: 13rem;
  background-color: #0a1929; /* Noir/bleu marine très foncé */
  min-height: 100vh;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  position: fixed;
  left: 0;
  top: 0;
  z-index: 1000;
}

.sidebar-content {
  padding: 0;
  height: 100vh;
  display: flex;
  flex-direction: column;
}

/* Zone de marque/Logo */
.brand-section {
  padding: 0;
}

.brand-rectangle {
  background-color: #4E7D96;
  padding: 0; /* Supprimé le padding */
  height: 88px; /* Hauteur fixe identique à la navbar */
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  border-bottom: 2px solid #3a6275;
}

.brand-text {
  color: #ffffff;
  font-size: 1.25rem;
  font-weight: 700;
  letter-spacing: 0.5px;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Navigation */
.sidebar-nav {
  padding: 2rem 1rem; /* Espace vertical significatif après le rectangle bleu */
  flex: 1;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  color: #b0bec5;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
  text-decoration: none;
  font-weight: 500;
  margin-bottom: 0.5rem;
}

.nav-link:hover {
  background-color: rgba(255, 255, 255, 0.1);
  color: #ffffff;
}

.nav-link-active {
  background-color: rgba(255, 255, 255, 0.15); /* Fond plus clair */
  color: #ffffff;
  position: relative;
  border-left: 4px solid #4E7D96; /* Liseré de même couleur que l'en-tête */
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

/* Séparateur */
.nav-separator {
  border-top: 1px solid #374151;
  margin: 1rem 1rem;
}

/* Déconnexion */
.logout-section {
  padding: 0 1rem 1rem;
}

.logout-button {
  width: 100%;
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  color: #b71c1c; /* Rouge foncé/bordeaux */
  border-radius: 0.5rem;
  transition: all 0.2s ease;
  background: rgba(183, 28, 28, 0.1);
  border: 1px solid rgba(183, 28, 28, 0.2);
  cursor: pointer;
  font-weight: 600;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.logout-button:hover {
  background: rgba(183, 28, 28, 0.2);
  color: #d32f2f;
  border-color: rgba(183, 28, 28, 0.3);
}

.logout-button:hover .nav-icon {
  transform: scale(1.1);
  color: #d32f2f;
}

/* Responsive */
@media (max-width: 768px) {
  .sidebar {
    width: 140px;
  }
  
  .nav-link {
    justify-content: center;
    padding: 0.75rem;
  }
  
  .nav-link-active::before {
    display: none;
  }
}
</style>
