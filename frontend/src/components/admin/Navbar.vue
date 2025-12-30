<template>
  <nav class="navbar">
    <div class="navbar-container">
      <div class="navbar-content">
        <!-- Profil admin à droite -->
        <div class="profile-section">
          <button @click="toggleProfile" class="profile-button">
            <div class="profile-avatar">
              <svg class="avatar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </div>
            <span class="profile-name">{{ adminEmail }}</span>
            <svg class="dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>

          <!-- Dropdown profil -->
          <div v-if="showProfile" class="profile-dropdown">
            <div class="dropdown-header">
              <p class="dropdown-name">Administrateur</p>
              <p class="dropdown-email">{{ adminEmail }}</p>
            </div>
            <div class="dropdown-actions">
              <button @click="logout" class="logout-button">
                <svg class="logout-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Déconnexion
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const showProfile = ref(false)
const adminEmail = ref('Chargement...')
const loading = ref(true)

const toggleProfile = () => {
  showProfile.value = !showProfile.value
}

// Récupérer les infos de l'admin connecté
const fetchAdminInfo = async () => {
  try {
    // Récupérer le token depuis localStorage
    const token = localStorage.getItem('admin_token')
    if (!token) {
      console.log('Aucun token trouvé')
      adminEmail.value = 'Non connecté'
      return
    }

    // Essayer de récupérer l'email depuis localStorage d'abord (plus rapide)
    const storedAdminInfo = localStorage.getItem('admin_info')
    if (storedAdminInfo) {
      try {
        const adminInfo = JSON.parse(storedAdminInfo)
        adminEmail.value = adminInfo.email || 'admin@universite.ma'
      } catch (e) {
        console.error('Erreur parsing admin info:', e)
        adminEmail.value = 'admin@universite.ma'
      }
    }

    // Ensuite, faire un appel API pour vérifier et mettre à jour
    try {
      const response = await axios.get('http://localhost:8000/api/admin/profile', {
        headers: {
          'Authorization': `Bearer ${token}`
        }
      })

      if (response.data.success) {
        adminEmail.value = response.data.data.email
        // Mettre à jour localStorage avec les infos fraîches
        localStorage.setItem('admin_info', JSON.stringify(response.data.data))
      }
    } catch (apiError) {
      console.log('Erreur API profile, utilisation des données locales:', apiError)
      // Garder l'email déjà récupéré depuis localStorage
    }
  } catch (error) {
    console.error('Erreur lors de la récupération des infos admin:', error)
    adminEmail.value = 'admin@universite.ma'
  } finally {
    loading.value = false
  }
}

// Déconnexion
const logout = () => {
  localStorage.removeItem('admin_token')
  localStorage.removeItem('admin_info')
  window.location.href = '/admin/login'
}

onMounted(() => {
  fetchAdminInfo()
})

// Fermer le dropdown quand on clique ailleurs
document.addEventListener('click', (e) => {
  if (showProfile.value && !e.target.closest('.profile-section')) {
    showProfile.value = false
  }
})
</script>

<style scoped>
.navbar {
  background-color: #4E7D96;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  border-bottom: 2px solid #3a6275;
  height: 88px; /* Même hauteur exacte que le rectangle de la sidebar */
  z-index: 999;
  position: fixed;
  top: 0;
  right: 0;
  left: 13rem; /* Largeur de la sidebar */
  transition: left 0.3s ease;
}

.navbar-container {
  padding: 0 2rem;
  height: 100%;
  display: flex;
  align-items: center;
}

.navbar-content {
  display: flex;
  justify-content: flex-end; /* Aligner le contenu à droite */
  align-items: center;
  width: 100%;
  height: 100%;
}

.profile-section {
  position: relative;
}

.profile-button {
  display: flex;
  align-items: center;
  color: white;
  padding: 0.5rem 0.75rem;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
  background: none;
  border: none;
  cursor: pointer;
}

.profile-button:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.profile-avatar {
  width: 2rem;
  height: 2rem;
  background-color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.avatar-icon {
  width: 1.25rem;
  height: 1.25rem;
  color: #4E7D96;
}

.profile-name {
  margin-left: 0.75rem;
  font-weight: 500;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

@media (max-width: 640px) {
  .profile-name {
    display: none;
  }
}

.dropdown-icon {
  width: 1rem;
  height: 1rem;
  margin-left: 0.75rem;
}

.profile-dropdown {
  position: absolute;
  right: 0;
  top: 3rem;
  width: 12rem;
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  border: 1px solid #e5e7eb;
  z-index: 50;
}

.dropdown-header {
  padding: 1rem;
  border-bottom: 1px solid #e5e7eb;
}

.dropdown-name {
  font-size: 0.875rem;
  font-weight: 500;
  color: #111827;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.dropdown-email {
  font-size: 0.75rem;
  color: #6b7280;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.dropdown-actions {
  padding: 0.5rem;
}

.logout-button {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  width: 100%;
  padding: 0.75rem;
  border: none;
  border-radius: 0.375rem;
  background: none;
  color: #dc2626;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.logout-button:hover {
  background-color: #fef2f2;
  color: #b91c1c;
}

.logout-icon {
  width: 1rem;
  height: 1rem;
  flex-shrink: 0;
}

/* Responsive */
@media (max-width: 768px) {
  .navbar {
    left: 140px; /* Largeur de la sidebar responsive */
  }
}
</style>