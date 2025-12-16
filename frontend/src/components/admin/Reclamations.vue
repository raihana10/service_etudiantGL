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
                <!-- Status badge -->
                <div class="reclamation-badges">
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
                  <button 
                    v-if="reclamation.demande"
                    @click="voirDemande(reclamation.demande)"
                    class="action-btn secondary-btn"
                  >
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

    <!-- Demand Details Modal -->
    <div v-if="showDemandModal" class="modal-overlay" @click="closeDemandModal">
      <div class="modal-content demand-modal-content" @click.stop>
        <div class="modal-header">
          <h3>Détails de la demande #{{ selectedDemand?.num_demande }}</h3>
          <button @click="closeDemandModal" class="close-btn">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        
        <div class="modal-body" v-if="selectedDemand">
          <!-- Informations étudiant -->
          <div class="detail-section">
            <h4 class="detail-title">
              <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              Informations étudiant
            </h4>
            <div class="student-card">
              <div class="student-avatar">
                <svg class="avatar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              </div>
              <div class="student-info">
                <h5 class="student-name">{{ selectedDemand.etudiant?.nom }} {{ selectedDemand.etudiant?.prenom }}</h5>
                <div class="student-details-grid">
                  <div class="detail-item">
                    <label>N° APOGEE</label>
                    <p class="detail-value">{{ selectedDemand.etudiant?.numApogee || '-' }}</p>
                  </div>
                  <div class="detail-item">
                    <label>Niveau</label>
                    <p class="detail-value">{{ selectedDemand.etudiant?.niveau || '-' }}</p>
                  </div>
                  <div class="detail-item">
                    <label>CIN</label>
                    <p class="detail-value">{{ selectedDemand.etudiant?.CIN || '-' }}</p>
                  </div>
                  <div class="detail-item">
                    <label>Email institutionnel</label>
                    <p class="detail-value">{{ selectedDemand.etudiant?.emailInstitu || '-' }}</p>
                  </div>
                  <div class="detail-item">
                    <label>Date de naissance</label>
                    <p class="detail-value">{{ formatDate(selectedDemand.etudiant?.dateNaissance) }}</p>
                  </div>
                  <div class="detail-item">
                    <label>Lieu de naissance</label>
                    <p class="detail-value">{{ selectedDemand.etudiant?.lieuNaissance || '-' }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Informations demande -->
          <div class="detail-section">
            <h4 class="detail-title">
              <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              Informations demande
            </h4>
            <div class="demande-info-grid">
              <div class="detail-item">
                <label>Type de document</label>
                <div class="type-badge" :class="getTypeClass(selectedDemand.typeDoc)">{{ getTypeLabel(selectedDemand.typeDoc) }}</div>
              </div>
              <div class="detail-item">
                <label>Date de soumission</label>
                <p class="detail-value">{{ formatDate(selectedDemand.datesoumission) }}</p>
              </div>
              <div class="detail-item">
                <label>Date de traitement</label>
                <p class="detail-value">{{ formatDate(selectedDemand.date_traitement) }}</p>
              </div>
              <div class="detail-item">
                <label>Statut</label>
                <span :class="['status-badge', getDemandStatusClass(selectedDemand.statut)]">
                  {{ selectedDemand.statut }}
                </span>
              </div>
            </div>
          </div>

          <!-- Motif de refus -->
          <div class="detail-section" v-if="selectedDemand.motif_refus">
            <h4 class="detail-title">
              <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Motif de refus
            </h4>
            <div class="refus-card">
              <p class="motif-refus">{{ selectedDemand.motif_refus }}</p>
            </div>
          </div>
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
      searchTimeout: null,
      showDemandModal: false,
      selectedDemand: null
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
    },

    async voirDemande(demande) {
      try {
        const response = await api.get(`/admin/demandes/details/${demande.idDemande}`)
        if (response.data.success) {
          this.selectedDemand = response.data.data
          this.showDemandModal = true
        } else {
          this.error = response.data.message || 'Erreur lors du chargement des détails de la demande'
        }
      } catch (error) {
        console.error('Error loading demand details:', error)
        this.error = 'Erreur lors du chargement des détails de la demande'
      }
    },

    closeDemandModal() {
      this.showDemandModal = false
      this.selectedDemand = null
    },

    getTypeLabel(type) {
      const labels = {
        'AttestationScolarite': 'Attestation de Scolarité',
        'AttestationReussite': 'Attestation de Réussite',
        'ReleveNote': 'Relevé de Notes',
        'ConventionStage': 'Convention de Stage'
      }
      return labels[type] || type
    },

    getTypeClass(type) {
      return {
        'type-attestation-scolarite': type === 'AttestationScolarite',
        'type-attestation-reussite': type === 'AttestationReussite',
        'type-releve-notes': type === 'ReleveNote',
        'type-convention-stage': type === 'ConventionStage'
      }
    },

    getDemandStatusClass(statut) {
      return {
        'status-valide': statut === 'Validée',
        'status-refuse': statut === 'Refusée',
        'status-encours': statut === 'En cours'
      }
    }
  }
}
</script>

<style scoped>
/* Dashboard layout */
.dashboard-layout {
  min-height: 100vh;
  background: #E3EDF2;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.dashboard-main {
  display: flex;
  min-height: calc(100vh - 70px);
}

.dashboard-content {
  flex: 1;
  padding: 30px;
  margin-left: 16rem;
  margin-top: 104px; /* Espace pour le header fixe */
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
  border: 4px solid #E3EDF2;
  border-top: 4px solid #4E7D96;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 20px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.retry-button {
  background: #4E7D96;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 15px;
}

.retry-button:hover {
  background: #3d6275;
}

/* Page header */
.page-header {
  margin-bottom: 30px;
}

.page-header h1 {
  color: #0A0D25;
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
  color: #0A0D25;
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
  background: #E3EDF2;
  color: #4E7D96;
}

.stat-icon-orange {
  background: #fff0e8;
  color: #FF844B;
}

.stat-icon-yellow {
  background: #fef3c7;
  color: #d97706;
}

.stat-icon-green {
  background: #e6e8eb;
  color: #0A0D25;
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
  background: #4E7D96;
  color: white;
  border-color: #4E7D96;
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
  background: #E3EDF2;
  color: #4E7D96;
}

.status-in-progress {
  background: #fff0e8;
  color: #FF844B;
}

.status-resolved {
  background: #e6e8eb;
  color: #0A0D25;
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
  background: #4E7D96;
  color: white;
}

.primary-btn:hover {
  background: #3d6275;
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
  background: #FF844B;
  color: white;
}

.warning-btn:hover {
  background: #e56b30;
}

.success-btn {
  background: #0A0D25;
  color: white;
}

.success-btn:hover {
  background: #050613;
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
  border-color: #4E7D96;
  box-shadow: 0 0 0 3px rgba(78, 125, 150, 0.1);
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

/* Demand Details Modal Styles */
.demand-modal-content {
  max-width: 900px;
}

.detail-section {
  margin-bottom: 2rem;
}

.detail-section:last-child {
  margin-bottom: 0;
}

.detail-title {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 1.25rem;
  font-weight: 700;
  color: #0A0D25;
  margin-bottom: 1.5rem;
  padding-bottom: 0.75rem;
  border-bottom: 2px solid #4E7D96;
}

.section-icon {
  width: 1.5rem;
  height: 1.5rem;
  color: #4E7D96;
}

/* Student Card */
.student-card {
  background: white;
  border-radius: 1rem;
  padding: 2rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  border-left: 4px solid #4E7D96;
}

.student-info {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.student-avatar {
  width: 4rem;
  height: 4rem;
  background: linear-gradient(135deg, #4E7D96 0%, #3d6275 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1rem;
}

.student-avatar .avatar-icon {
  width: 2rem;
  height: 2rem;
  color: white;
}

.student-name {
  font-size: 1.5rem;
  font-weight: 700;
  color: #0A0D25;
  margin: 0;
}

.student-details-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
}

/* Demande Info Grid */
.demande-info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* Detail Items */
.detail-item {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.detail-item label {
  font-size: 0.875rem;
  font-weight: 600;
  color: #6b7280;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.detail-value {
  font-size: 1rem;
  font-weight: 500;
  color: #1f2937;
  margin: 0;
  padding: 0.5rem 0.75rem;
  background: #f8fafc;
  border-radius: 0.5rem;
  border-left: 3px solid #4E7D96;
}

/* Type Badge */
.type-badge {
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 600;
  text-align: center;
  color: white;
}

.type-attestation-scolarite {
  background: linear-gradient(135deg, #4E7D96 0%, #3d6275 100%);
}

.type-attestation-reussite {
  background: linear-gradient(135deg, #059669 0%, #047857 100%);
}

.type-releve-notes {
  background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
}

.type-convention-stage {
  background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%);
}

/* Status Badge for Demand */
.status-valide {
  background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
  color: #059669;
  border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-refuse {
  background: linear-gradient(135deg, rgba(255, 132, 75, 0.1) 0%, rgba(255, 132, 75, 0.05) 100%);
  color: #ea580c;
  border: 1px solid rgba(255, 132, 75, 0.2);
}

.status-encours {
  background: linear-gradient(135deg, rgba(251, 191, 36, 0.1) 0%, rgba(251, 191, 36, 0.05) 100%);
  color: #d97706;
  border: 1px solid rgba(251, 191, 36, 0.2);
}

/* Refus Card */
.refus-card {
  background: linear-gradient(135deg, rgba(255, 132, 75, 0.1) 0%, rgba(255, 132, 75, 0.05) 100%);
  border: 1px solid rgba(255, 132, 75, 0.2);
  border-radius: 1rem;
  padding: 1.5rem;
  border-left: 4px solid #ea580c;
}

.motif-refus {
  font-size: 1rem;
  color: #1f2937;
  margin: 0;
  line-height: 1.6;
}
</style>
