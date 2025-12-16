<template>
  <div class="login-container">
    <div class="login-wrapper">
      <div class="login-card">
        <!-- Logo et titre -->
        <div class="header">
          <div class="logo-container">
            <svg class="logo-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
            </svg>
          </div>
          <div class="title-container">
            <div class="lock-icon">
              <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
            </div>
            <h1 class="title">Espace Administration</h1>
          </div>
          <p class="subtitle">Connectez-vous pour gérer les demandes</p>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="handleSubmit" class="form">
          <div class="form-group">
            <label for="email" class="label">Email administrateur</label>
            <div class="input-container">
              <div class="input-icon">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
              </div>
              <input
                type="email"
                id="email"
                v-model="formData.email"
                placeholder="admin@universite.ma"
                required
                class="input"
              />
            </div>
          </div>

          <div class="form-group">
            <label for="password" class="label">Mot de passe</label>
            <div class="input-container">
              <div class="input-icon">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
              </div>
              <input
                :type="showPassword ? 'text' : 'password'"
                id="password"
                v-model="formData.password"
                placeholder="••••••••"
                required
                class="input"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="toggle-password"
              >
                <svg v-if="showPassword" class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
                <svg v-else class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
              </button>
            </div>
          </div>

          <div class="form-footer">
            <label class="remember-me">
              <input
                type="checkbox"
                v-model="formData.rememberMe"
                class="checkbox"
              />
              <span>Se souvenir de moi</span>
            </label>
            <button type="button" class="forgot-password">
              Mot de passe oublié ?
            </button>
          </div>

          <button type="submit" class="submit-button" :disabled="isLoading">
            <span v-if="isLoading" class="loading-spinner">
              <svg class="spinner-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
              Connexion en cours...
            </span>
            <span v-else>Se connecter</span>
          </button>

          <!-- Message d'erreur -->
          <div v-if="errorMessage" class="error-message">
            <svg class="error-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ errorMessage }}
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

const formData = reactive({
  email: '',
  password: '',
  rememberMe: false,
})

const showPassword = ref(false)
const isLoading = ref(false)
const errorMessage = ref('')

const handleSubmit = async () => {
  isLoading.value = true
  errorMessage.value = ''

  try {
    const response = await axios.post('http://localhost:8000/api/admin/login', {
      email: formData.email,
      password: formData.password
    })

    if (response.data.success) {
      // Stocker le token et les infos admin
      localStorage.setItem('admin_token', response.data.data.token)
      localStorage.setItem('admin_info', JSON.stringify(response.data.data.admin))
      
      // Rediriger vers le dashboard
      router.push('/admin/dashboard')
    } else {
      errorMessage.value = response.data.message || 'Erreur lors de la connexion'
    }
  } catch (error) {
    if (error.response?.status === 422) {
      // Erreurs de validation
      const errors = error.response.data.errors
      errorMessage.value = errors.email?.[0] || errors.password?.[0] || 'Erreur de validation'
    } else if (error.response?.status === 401) {
      errorMessage.value = 'Email ou mot de passe incorrect'
    } else {
      errorMessage.value = 'Erreur de connexion au serveur'
    }
    console.error('Login error:', error)
  } finally {
    isLoading.value = false
  }
}
</script>

<style scoped>
/* Reset et base */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* Container principal */
.login-container {
  min-height: 100vh;
  background: linear-gradient(135deg, #4E7D96 0%, #E3EDF2 50%, #0A0D25 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 3rem 1rem;
}

.login-wrapper {
  width: 100%;
  max-width: 28rem;
}

/* Bouton retour */
.back-button {
  color: white;
  background: none;
  border: none;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  margin-bottom: 2rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
}

.back-button:hover {
  color: #FF844B;
}

.back-button .icon {
  width: 1.25rem;
  height: 1.25rem;
}

/* Carte de connexion */
.login-card {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-radius: 1rem;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  padding: 2rem;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Header */
.header {
  text-align: center;
  margin-bottom: 2rem;
}

.logo-container {
  width: 5rem;
  height: 5rem;
  background: linear-gradient(135deg, #4E7D96, #0A0D25);
  border-radius: 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1.5rem;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
}

.logo-container:hover {
  transform: scale(1.05);
}

.logo-icon {
  width: 3rem;
  height: 3rem;
  color: white;
}

.title-container {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  margin-bottom: 0.75rem;
}

.lock-icon {
  width: 2rem;
  height: 2rem;
  background-color: #FF844B;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.lock-icon .icon {
  width: 1.25rem;
  height: 1.25rem;
  color: white;
}

.title {
  font-size: 1.5rem;
  font-weight: bold;
  color: #1a202c;
}

.subtitle {
  color: #4a5568;
  font-weight: 500;
}

/* Formulaire */
.form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.label {
  color: #374151;
  font-weight: 600;
  margin-bottom: 0.75rem;
  display: block;
}

.input-container {
  position: relative;
}

.input-icon {
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
}

.input-icon .icon {
  width: 1.25rem;
  height: 1.25rem;
  color: #9ca3af;
}

.input {
  width: 100%;
  padding: 1rem 1rem 1rem 3rem;
  border-radius: 0.75rem;
  border: 2px solid #e5e7eb;
  background-color: #f9fafb;
  font-size: 1rem;
  transition: all 0.3s ease;
  outline: none;
}

.input:focus {
  border-color: #4E7D96;
  background-color: white;
  box-shadow: 0 0 0 3px rgba(78, 125, 150, 0.1);
}

.toggle-password {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #9ca3af;
  cursor: pointer;
  padding: 0;
  transition: color 0.3s ease;
}

.toggle-password:hover {
  color: #FF844B;
}

.toggle-password .icon {
  width: 1.25rem;
  height: 1.25rem;
}

/* Footer du formulaire */
.form-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.remember-me {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  cursor: pointer;
  color: #374151;
  font-weight: 500;
  transition: color 0.3s ease;
}

.remember-me:hover {
  color: #4E7D96;
}

.checkbox {
  width: 1.25rem;
  height: 1.25rem;
  border-radius: 0.5rem;
  border: 1px solid #d1d5db;
  cursor: pointer;
  accent-color: #4E7D96;
}

.forgot-password {
  color: #FF844B;
  font-weight: 600;
  font-size: 0.875rem;
  background: none;
  border: none;
  cursor: pointer;
  transition: text-decoration 0.3s ease;
}

.forgot-password:hover {
  text-decoration: underline;
}

/* Bouton de soumission */
.submit-button {
  width: 100%;
  background: linear-gradient(to right, #4E7D96, #0A0D25);
  color: white;
  padding: 1rem 1.5rem;
  border-radius: 0.75rem;
  border: none;
  font-weight: 600;
  font-size: 1.125rem;
  cursor: pointer;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.submit-button:hover {
  background: linear-gradient(to right, #0A0D25, #4E7D96);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
  transform: translateY(-2px);
}

/* Info sécurité */
.security-info {
  margin-top: 2rem;
  padding-top: 1.5rem;
  border-top: 1px solid #e5e7eb;
}

.security-text {
  text-align: center;
  color: #6b7280;
  font-size: 0.875rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  font-weight: 500;
}

.security-icon {
  width: 1.25rem;
  height: 1.25rem;
  background-color: #4E7D96;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.security-icon .icon {
  width: 0.75rem;
  height: 0.75rem;
  color: white;
}

/* Icones globales */
.icon {
  display: block;
}

/* Loading spinner */
.loading-spinner {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.spinner-icon {
  width: 1.25rem;
  height: 1.25rem;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* Message d'erreur */
.error-message {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem;
  background-color: #fef2f2;
  border: 1px solid #fecaca;
  border-radius: 0.75rem;
  color: #dc2626;
  font-weight: 500;
  font-size: 0.875rem;
}

.error-icon {
  width: 1.25rem;
  height: 1.25rem;
  flex-shrink: 0;
}

/* Responsive */
@media (max-width: 640px) {
  .login-container {
    padding: 1.5rem 1rem;
  }

  .login-card {
    padding: 1.5rem;
  }

  .title {
    font-size: 1.25rem;
  }

  .form-footer {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }
}
</style>