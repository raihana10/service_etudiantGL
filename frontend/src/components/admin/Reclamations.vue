<template>
  <div class="dashboard-layout">
    <!-- Navbar -->
    <Navbar />
    
    <!-- Contenu principal avec Sidebar -->
    <div class="dashboard-main">
      <!-- Sidebar -->
      <Sidebar />
      
      <!-- Contenu de la page -->
      <div class="dashboard-content">
        <!-- Loading state -->
        <div v-if="loading" class="loading-state">
          <div class="loading-spinner"></div>
          <p>Chargement des réclamations...</p>
        </div>

        <!-- Error state -->
        <div v-else-if="error" class="error-state">
          <p>{{ error }}</p>
          <button @click="loadReclamations" class="retry-button">Réessayer</button>
        </div>

        <!-- Réclamations content -->
        <div v-else class="reclamations-container">
          <!-- Header -->
          <div class="page-header">
            <h1>Espace des Réclamations</h1>
            <p>Gérez et répondez aux réclamations des étudiants</p>
          </div>

          <!-- Statistics -->
          <div class="stats-grid">
            <div class="stat-card stat-card-blue">
              <div class="stat-content">
                <div class="stat-info">
                  <p class="stat-label">Total Réclamations</p>
                  <p class="stat-value">{{ stats.total }}</p>
                </div>
                <div class="stat-icon stat-icon-blue">
                  <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                </div>
              </div>
            </div>

            <div class="stat-card stat-card-orange">
              <div class="stat-content">
                <div class="stat-info">
                  <p class="stat-label">Nouvelles</p>
                  <p class="stat-value">{{ stats.nouvelles }}</p>
                </div>
                <div class="stat-icon stat-icon-orange">
                  <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                  </svg>
                </div>
              </div>
            </div>

            <div class="stat-card stat-card-yellow">
              <div class="stat-content">
                <div class="stat-info">
                  <p class="stat-label">En cours</p>
                  <p class="stat-value">{{ stats.en_cours }}</p>
                </div>
                <div class="stat-icon stat-icon-yellow">
                  <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>
            </div>

            <div class="stat-card stat-card-green">
              <div class="stat-content">
                <div class="stat-info">
                  <p class="stat-label">Résolues</p>
                  <p class="stat-value">{{ stats.resolues }}</p>
                </div>
                <div class="stat-icon stat-icon-green">
                  <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <!-- Filters and Search -->
          <div class="filters-section">
            <!-- Status tabs -->
            <div class="status-tabs">
              <button 
                v-for="status in statusTabs" 
                :key="status.value"
                @click="setStatusFilter(status.value)"
                :class="['status-tab', { active: currentStatus === status.value }]"
              >
                {{ status.label }}
              </button>
            </div>

            <!-- Filters row -->
            <div class="filters-row">
              <!-- Priority filter -->
              <select v-model="currentPriority" @change="loadReclamations" class="filter-select">
                <option value="Toutes">Toutes les priorités</option>
                <option value="Haute">Haute priorité</option>
                <option value="Normale">Priorité normale</option>
                <option value="Basse">Basse priorité</option>
              </select>

              <!-- Search -->
              <div class="search-container">
                <input 
                  v-model="searchQuery"
                  @input="debouncedSearch"
                  type="text" 
                  placeholder="Rechercher par N° réclamation, étudiant..."
                  class="search-input"
                />
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
              </div>
            </div>
          </div>

          <!-- Réclamations list -->
          <div class="reclamations-list">
            <div v-if="reclamations.length === 0" class="empty-state">
              <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              <h3>Aucune réclamation trouvée</h3>
              <p>Il n'y a aucune réclamation correspondant à vos critères de recherche.</p>
            </div>

            <div v-else>
              <div 
                v-for="reclamation in reclamations" 
                :key="reclamation.idReclamation"
                class="reclamation-card"
              >
                <!-- Priority and Status badges -->
                <div class="reclamation-badges">
                  <span :class="['priority-badge', getPriorityClass(reclamation.priorite)]">
                    {{ reclamation.priorite }}
                  </span>
                  <span :class="['status-badge', getStatusClass(reclamation.statut)]">
                    {{ reclamation.statut }}
                  </span>
                </div>

                <!-- Reclamation content -->
                <div class="reclamation-content">
                  <div class="reclamation-header">
                    <h4>{{ reclamation.sujet }}</h4>
                    <span class="reclamation-number">#{{ reclamation.idReclamation }}</span>
                  </div>

                  <div class="reclamation-info">
                    <div class="info-row">
                      <span class="label">Étudiant:</span>
                      <span>{{ reclamation.etudiant?.nom }} {{ reclamation.etudiant?.prenom }} - {{ reclamation.etudiant?.codeMassar }}</span>
                    </div>
                    <div class="info-row">
                      <span class="label">Email:</span>
                      <span>{{ reclamation.etudiant?.emailInstitu }}</span>
                    </div>
                    <div class="info-row">
                      <span class="label">Date soumission:</span>
                      <span>{{ formatDate(reclamation.datesoumission) }}</span>
                    </div>
                  </div>

                  <div class="reclamation-description">
                    <p>{{ reclamation.description }}</p>
                  </div>

                  <!-- Response section (if exists) -->
                  <div v-if="reclamation.reponse" class="response-section">
                    <h5>Réponse envoyée:</h5>
                    <p>{{ reclamation.reponse }}</p>
                    <small>Date de réponse: {{ formatDate(reclamation.dateReponse) }}</small>
                  </div>
                </div>

                <!-- Action buttons -->
                <div class="reclamation-actions">
                  <button class="action-btn secondary-btn">
                    Voir demande associée
                  </button>
                  <button 
                    @click="openResponseModal(reclamation)"
                    class="action-btn primary-btn"
                  >
                    Répondre
                  </button>
                  <button 
                    v-if="reclamation.statut === 'Nouvelle'"
                    @click="updateStatus(reclamation.idReclamation, 'En cours')"
                    class="action-btn warning-btn"
                  >
                    Marquer en cours
                  </button>
                  <button 
                    v-if="reclamation.statut === 'En cours'"
                    @click="updateStatus(reclamation.idReclamation, 'Résolue')"
                    class="action-btn success-btn"
                  >
                    Marquer résolue
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="pagination.last_page > 1" class="pagination">
            <button 
              @click="changePage(pagination.current_page - 1)"
              :disabled="pagination.current_page <= 1"
              class="pagination-btn"
            >
              Précédent
            </button>
            <span class="pagination-info">
              Page {{ pagination.current_page }} sur {{ pagination.last_page }}
            </span>
            <button 
              @click="changePage(pagination.current_page + 1)"
              :disabled="pagination.current_page >= pagination.last_page"
              class="pagination-btn"
            >
              Suivant
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Response Modal -->
    <div v-if="showResponseModal" class="modal-overlay" @click="closeResponseModal">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3>Répondre à la réclamation #{{ selectedReclamation?.idReclamation }}</h3>
          <button @click="closeResponseModal" class="close-btn">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <div class="modal-body">
          <div class="reclamation-summary">
            <h4>Réclamation de {{ selectedReclamation?.etudiant?.nom }} {{ selectedReclamation?.etudiant?.prenom }}</h4>
            <p><strong>Sujet:</strong> {{ selectedReclamation?.sujet }}</p>
            <p><strong>Date:</strong> {{ formatDate(selectedReclamation?.datesoumission) }}</p>
            <div class="original-description">
              <p><strong>Description originale:</strong></p>
              <p>{{ selectedReclamation?.description }}</p>
            </div>
          </div>

          <form @submit.prevent="sendResponse">
            <div class="form-group">
              <label for="response">Votre réponse:</label>
              <textarea 
                id="response"
                v-model="responseText"
                rows="6"
                placeholder="Écrivez votre réponse ici..."
                required
              ></textarea>
            </div>

            <div class="modal-actions">
              <button type="button" @click="closeResponseModal" class="btn-secondary">
                Annuler
              </button>
              <button type="submit" :disabled="sendingResponse" class="btn-primary">
                <span v-if="sendingResponse">Envoi en cours...</span>
                <span v-else>Envoyer la réponse</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from './Navbar.vue'
import Sidebar from './Sidebar.vue'
import api from '@/services/api'

export default {
  name: 'Reclamations',
  components: {
    Navbar,
    Sidebar
  },
  data() {
    return {
      loading: false,
      error: null,
      reclamations: [],
      stats: {
        total: 0,
        nouvelles: 0,
        en_cours: 0,
        resolues: 0
      },
      currentStatus: 'Toutes',
      currentPriority: 'Toutes',
      searchQuery: '',
      pagination: {
        current_page: 1,
        last_page: 1,
        per_page: 10
      },
      statusTabs: [
        { label: 'Toutes', value: 'Toutes' },
        { label: 'Nouvelles', value: 'Nouvelle' },
        { label: 'En cours', value: 'En cours' },
        { label: 'Résolues', value: 'Résolue' }
      ],
      showResponseModal: false,
      selectedReclamation: null,
      responseText: '',
      sendingResponse: false,
      searchTimeout: null
    }
  },
  mounted() {
    this.loadReclamations()
    this.loadStatistics()
  },
  methods: {
    async loadReclamations() {
      this.loading = true
      this.error = null
      
      try {
        const params = {
          page: this.pagination.current_page,
          per_page: this.pagination.per_page
        }

        if (this.currentStatus !== 'Toutes') {
          params.statut = this.currentStatus
        }

        if (this.currentPriority !== 'Toutes') {
          params.priorite = this.currentPriority
        }

        if (this.searchQuery) {
          params.search = this.searchQuery
        }

        const response = await api.get('/admin/reclamations', { params })
        
        this.reclamations = response.data.data.data
        this.pagination.current_page = response.data.data.current_page
        this.pagination.last_page = response.data.data.last_page
        this.pagination.per_page = response.data.data.per_page
      } catch (error) {
        console.error('Error loading reclamations:', error)
        this.error = 'Erreur lors du chargement des réclamations'
      } finally {
        this.loading = false
      }
    },

    async loadStatistics() {
      try {
        const response = await api.get('/admin/reclamations/statistics')
        this.stats = response.data.data
      } catch (error) {
        console.error('Error loading statistics:', error)
      }
    },

    setStatusFilter(status) {
      this.currentStatus = status
      this.pagination.current_page = 1
      this.loadReclamations()
    },

    debouncedSearch() {
      clearTimeout(this.searchTimeout)
      this.searchTimeout = setTimeout(() => {
        this.pagination.current_page = 1
        this.loadReclamations()
      }, 500)
    },

    changePage(page) {
      if (page >= 1 && page <= this.pagination.last_page) {
        this.pagination.current_page = page
        this.loadReclamations()
      }
    },

    async updateStatus(reclamationId, newStatus) {
      try {
        const adminDataString = localStorage.getItem('admin_info')
        if (!adminDataString) {
            this.error = "Session expirée, veuillez vous reconnecter."
            return
        }
        const adminData = JSON.parse(adminDataString)
        if (!adminData || !adminData.id) {
             this.error = "Données administrateur manquantes."
             return
        }

        await api.put(`/admin/reclamations/${reclamationId}/status`, {
          statut: newStatus,
          idAdmin: adminData.id
        })

        await this.loadReclamations()
        await this.loadStatistics()
      } catch (error) {
        console.error('Error updating status:', error)
        this.error = 'Erreur lors de la mise à jour du statut'
      }
    },

    openResponseModal(reclamation) {
      this.selectedReclamation = reclamation
      this.responseText = ''
      this.showResponseModal = true
    },

    closeResponseModal() {
      this.showResponseModal = false
      this.selectedReclamation = null
      this.responseText = ''
    },

    async sendResponse() {
      if (!this.responseText.trim()) return

      this.sendingResponse = true

      try {
        const adminDataString = localStorage.getItem('admin_info')
        if (!adminDataString) {
             this.error = "Session expirée, veuillez vous reconnecter."
             this.sendingResponse = false
             return
        }
        const adminData = JSON.parse(adminDataString)
         if (!adminData || !adminData.id) {
             this.error = "Données administrateur manquantes."
             this.sendingResponse = false
             return
        }

        await api.post(`/admin/reclamations/${this.selectedReclamation.idReclamation}/respond`, {
          reponse: this.responseText,
          idAdmin: adminData.id
        })

        await this.loadReclamations()
        await this.loadStatistics()
        this.closeResponseModal()
      } catch (error) {
        console.error('Error sending response:', error)
        this.error = 'Erreur lors de l\'envoi de la réponse'
      } finally {
        this.sendingResponse = false
      }
    },

    getPriorityClass(priority) {
      const classes = {
        'Haute': 'priority-high',
        'Normale': 'priority-normal',
        'Basse': 'priority-low'
      }
      return classes[priority] || 'priority-normal'
    },

    getStatusClass(status) {
      const classes = {
        'Nouvelle': 'status-new',
        'En cours': 'status-in-progress',
        'Résolue': 'status-resolved'
      }
      return classes[status] || 'status-new'
    },

    formatDate(dateString) {
      if (!dateString) return ''
      const date = new Date(dateString)
      return date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    }
  }
}
</script>

<style scoped>
/* Dashboard layout */
.dashboard-layout {
  min-height: 100vh;
  background: #f5f5f5;
}

.dashboard-main {
  display: flex;
  min-height: calc(100vh - 70px);
}

.dashboard-content {
  flex: 1;
  padding: 30px;
}

/* Loading and Error states */
.loading-state, .error-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 400px;
  text-align: center;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #e5e7eb;
  border-top: 4px solid #1e40af;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 20px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.retry-button {
  background: #1e40af;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 15px;
}

.retry-button:hover {
  background: #1d4ed8;
}

/* Page header */
.page-header {
  margin-bottom: 30px;
}

.page-header h1 {
  color: #1f2937;
  font-size: 28px;
  margin-bottom: 8px;
}

.page-header p {
  color: #6b7280;
  font-size: 16px;
}

/* Statistics grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.stat-card {
  background: white;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s;
}

.stat-card:hover {
  transform: translateY(-2px);
}

.stat-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.stat-label {
  color: #6b7280;
  font-size: 14px;
  margin-bottom: 4px;
}

.stat-value {
  font-size: 32px;
  font-weight: bold;
  color: #1f2937;
}

.stat-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.stat-icon-blue {
  background: #dbeafe;
  color: #1e40af;
}

.stat-icon-orange {
  background: #fed7aa;
  color: #ea580c;
}

.stat-icon-yellow {
  background: #fef3c7;
  color: #d97706;
}

.stat-icon-green {
  background: #d1fae5;
  color: #059669;
}

/* Filters section */
.filters-section {
  background: white;
  padding: 24px;
  border-radius: 12px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  margin-bottom: 24px;
}

.status-tabs {
  display: flex;
  gap: 8px;
  margin-bottom: 20px;
}

.status-tab {
  padding: 10px 20px;
  border: 1px solid #e5e7eb;
  background: white;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 14px;
}

.status-tab:hover {
  background: #f9fafb;
}

.status-tab.active {
  background: #1e40af;
  color: white;
  border-color: #1e40af;
}

.filters-row {
  display: flex;
  gap: 16px;
  align-items: center;
}

.filter-select {
  padding: 10px 16px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  background: white;
  min-width: 200px;
}

.search-container {
  flex: 1;
  position: relative;
  max-width: 400px;
}

.search-input {
  width: 100%;
  padding: 10px 16px 10px 40px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  font-size: 14px;
}

.search-icon {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  width: 20px;
  height: 20px;
  color: #9ca3af;
}

/* Reclamations list */
.reclamations-list {
  margin-bottom: 24px;
}

.empty-state {
  text-align: center;
  padding: 60px 20px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.empty-icon {
  width: 64px;
  height: 64px;
  color: #9ca3af;
  margin: 0 auto 20px;
}

.empty-state h3 {
  color: #6b7280;
  margin-bottom: 8px;
}

.empty-state p {
  color: #9ca3af;
}

/* Reclamation card */
.reclamation-card {
  background: white;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  margin-bottom: 16px;
  transition: box-shadow 0.2s;
}

.reclamation-card:hover {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.reclamation-badges {
  display: flex;
  gap: 8px;
  margin-bottom: 16px;
}

.priority-badge, .status-badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}

.priority-high {
  background: #fee2e2;
  color: #dc2626;
}

.priority-normal {
  background: #fef3c7;
  color: #d97706;
}

.priority-low {
  background: #e0e7ff;
  color: #4f46e5;
}

.status-new {
  background: #dbeafe;
  color: #1e40af;
}

.status-in-progress {
  background: #fef3c7;
  color: #d97706;
}

.status-resolved {
  background: #d1fae5;
  color: #059669;
}

.reclamation-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 16px;
}

.reclamation-header h4 {
  color: #1f2937;
  font-size: 18px;
  margin: 0;
  flex: 1;
}

.reclamation-number {
  color: #6b7280;
  font-size: 14px;
  font-weight: 500;
}

.reclamation-info {
  margin-bottom: 16px;
}

.info-row {
  display: flex;
  gap: 8px;
  margin-bottom: 8px;
  font-size: 14px;
}

.info-row .label {
  color: #6b7280;
  font-weight: 500;
  min-width: 120px;
}

.reclamation-description {
  background: #f9fafb;
  padding: 16px;
  border-radius: 8px;
  margin-bottom: 16px;
}

.reclamation-description p {
  color: #4b5563;
  margin: 0;
  line-height: 1.6;
}

.response-section {
  background: #d1fae5;
  padding: 16px;
  border-radius: 8px;
  margin-bottom: 16px;
}

.response-section h5 {
  color: #059669;
  margin: 0 0 8px 0;
  font-size: 14px;
}

.response-section p {
  color: #047857;
  margin: 0 0 8px 0;
  line-height: 1.6;
}

.response-section small {
  color: #065f46;
  font-size: 12px;
}

.reclamation-actions {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.action-btn {
  padding: 8px 16px;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
}

.primary-btn {
  background: #1e40af;
  color: white;
}

.primary-btn:hover {
  background: #1d4ed8;
}

.secondary-btn {
  background: #f3f4f6;
  color: #4b5563;
  border: 1px solid #e5e7eb;
}

.secondary-btn:hover {
  background: #e5e7eb;
}

.warning-btn {
  background: #d97706;
  color: white;
}

.warning-btn:hover {
  background: #b45309;
}

.success-btn {
  background: #059669;
  color: white;
}

.success-btn:hover {
  background: #047857;
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 16px;
  margin-top: 24px;
}

.pagination-btn {
  padding: 8px 16px;
  border: 1px solid #e5e7eb;
  background: white;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
}

.pagination-btn:hover:not(:disabled) {
  background: #f9fafb;
}

.pagination-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination-info {
  color: #6b7280;
  font-size: 14px;
}

/* Modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 12px;
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 24px;
  border-bottom: 1px solid #e5e7eb;
}

.modal-header h3 {
  color: #1f2937;
  margin: 0;
}

.close-btn {
  width: 32px;
  height: 32px;
  border: none;
  background: none;
  cursor: pointer;
  color: #6b7280;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.close-btn:hover {
  background: #f3f4f6;
}

.modal-body {
  padding: 24px;
}

.reclamation-summary {
  background: #f9fafb;
  padding: 16px;
  border-radius: 8px;
  margin-bottom: 24px;
}

.reclamation-summary h4 {
  color: #1f2937;
  margin: 0 0 12px 0;
}

.reclamation-summary p {
  color: #4b5563;
  margin: 0 0 8px 0;
  font-size: 14px;
}

.original-description {
  margin-top: 12px;
  padding-top: 12px;
  border-top: 1px solid #e5e7eb;
}

.original-description p {
  color: #4b5563;
  line-height: 1.6;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  color: #374151;
  font-weight: 500;
  margin-bottom: 8px;
}

.form-group textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  font-size: 14px;
  line-height: 1.5;
  resize: vertical;
}

.form-group textarea:focus {
  outline: none;
  border-color: #1e40af;
  box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
}

.modal-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
}

.btn-secondary, .btn-primary {
  padding: 10px 20px;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-secondary {
  background: #f3f4f6;
  color: #4b5563;
  border: 1px solid #e5e7eb;
}

.btn-secondary:hover {
  background: #e5e7eb;
}

.btn-primary {
  background: #1e40af;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background: #1d4ed8;
}

.btn-primary:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Responsive */
@media (max-width: 768px) {
  .dashboard-content {
    margin-left: 0;
    padding: 20px;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }

  .filters-row {
    flex-direction: column;
    align-items: stretch;
  }

  .search-container {
    max-width: none;
  }

  .reclamation-actions {
    flex-direction: column;
  }

  .action-btn {
    width: 100%;
    text-align: center;
  }
}
</style>
