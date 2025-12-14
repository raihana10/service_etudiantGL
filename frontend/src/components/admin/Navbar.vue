<template>
  <nav class="navbar">
    <div class="navbar-container">
      <div class="navbar-content">
        <!-- Logo et nom de l'app -->
        <div class="navbar-brand">
          <div class="brand-logo">
            <div class="logo-container">
              <svg class="logo-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
              </svg>
            </div>
            <h1 class="brand-title">Service Étudiant</h1>
          </div>
        </div>

        <!-- Profil admin -->
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
const adminEmail = ref('admin@universite.ma')
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
      return
    }

    // Récupérer l'email depuis le token ou faire un appel API
    const response = await axios.get('http://localhost:8000/api/admin/profile', {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    })

    if (response.data.success) {
      adminEmail.value = response.data.data.email
    }
  } catch (error) {
    console.log('Erreur lors de la récupération des infos admin:', error)
    // En cas d'erreur, utiliser l'email stocké dans localStorage
    const storedEmail = localStorage.getItem('admin_email')
    if (storedEmail) {
      adminEmail.value = storedEmail
    }
  } finally {
    loading.value = false
  }
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
  border-bottom: 1px solid #0A0D25;
}

.navbar-container {
  padding: 0 1rem;
}

@media (min-width: 640px) {
  .navbar-container {
    padding: 0 1.5rem;
  }
}

@media (min-width: 1024px) {
  .navbar-container {
    padding: 0 2rem;
  }
}

.navbar-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 4rem;
}

.navbar-brand {
  display: flex;
  align-items: center;
}

.brand-logo {
  display: flex;
  align-items: center;
  flex-shrink: 0;
}

.logo-container {
  width: 2.5rem;
  height: 2.5rem;
  background-color: white;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 0.75rem;
}

.logo-icon {
  width: 1.5rem;
  height: 1.5rem;
  color: #4E7D96;
}

.brand-title {
  color: white;
  font-size: 1.25rem;
  font-weight: 700;
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
  top: 2.5rem;
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
}

.dropdown-email {
  font-size: 0.75rem;
  color: #6b7280;
}

.dropdown-actions {
  padding: 0.5rem;
}

.dropdown-item {
  width: 100%;
  text-align: left;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  color: #374151;
  border-radius: 0.375rem;
  transition: all 0.2s ease;
  background: none;
  border: none;
  cursor: pointer;
}

.dropdown-item:hover {
  background-color: #f9fafb;
}

.dropdown-item.logout {
  color: #dc2626;
}

.dropdown-item.logout:hover {
  background-color: #fef2f2;
}
</style>
