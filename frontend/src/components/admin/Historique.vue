<template>
  <div class="historique-layout">
    <!-- Navbar -->
    <Navbar />
    
    <!-- Contenu principal avec Sidebar -->
    <div class="historique-main">
      <!-- Sidebar -->
      <Sidebar />
      
      <!-- Contenu de la page -->
      <div class="historique-content">
        <!-- Header -->
        <div class="page-header">
          <h1 class="page-title">Historique des Demandes</h1>
          <p class="page-subtitle">Consultez toutes les demandes traitées (validées et refusées)</p>
        </div>

        <!-- Filtres -->
        <div class="filters-section">
          <div class="filters-container">
            <div class="filter-group">
              <label class="filter-label">Nom étudiant</label>
              <input 
                v-model="filters.nomEtudiant" 
                @input="debouncedSearch" 
                type="text" 
                placeholder="Rechercher par nom..."
                class="filter-input"
              />
            </div>
            
            <div class="filter-group">
              <label class="filter-label">N° APOGEE</label>
              <input 
                v-model="filters.apogee" 
                @input="debouncedSearch" 
                type="text" 
                placeholder="N° APOGEE..."
                class="filter-input"
              />
            </div>

            <div class="filter-group">
              <label class="filter-label">Date de traitement</label>
              <input 
                v-model="filters.dateTraitement" 
                @change="loadHistorique" 
                type="date" 
                class="filter-input"
              />
            </div>

            <div class="filter-group">
              <label class="filter-label">Statut</label>
              <select v-model="filters.statut" @change="loadHistorique" class="filter-select">
                <option value="">Tous</option>
                <option value="Validée">Validées</option>
                <option value="Refusée">Refusées</option>
              </select>
            </div>
            
            <div class="filter-group">
              <label class="filter-label">Type de document</label>
              <select v-model="filters.typeDoc" @change="loadHistorique" class="filter-select">
                <option value="">Tous</option>
                <option value="AttestationScolarite">Attestation de Scolarité</option>
                <option value="AttestationReussite">Attestation de Réussite</option>
                <option value="ReleveNote">Relevé de Notes</option>
                <option value="ConventionStage">Convention de Stage</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Loading state -->
        <div v-if="loading" class="loading-state">
          <div class="loading-spinner"></div>
          <p>Chargement de l'historique...</p>
        </div>

        <!-- Error state -->
        <div v-else-if="error" class="error-state">
          <p>{{ error }}</p>
          <button @click="loadHistorique" class="retry-button">Réessayer</button>
        </div>

        <!-- Historique content -->
        <div v-else class="historique-data">
          <!-- Tableau des demandes -->
          <div class="table-container">
            <div class="table-header">
              <h2 class="table-title">Liste des demandes traitées</h2>
              <div class="table-info">
                <span>{{ pagination.total }} résultats</span>
              </div>
            </div>

            <div class="table-wrapper">
              <table class="demandes-table">
                <thead>
                  <tr>
                    <th>N° APOGEE</th>
                    <th>Étudiant</th>
                    <th>Type de document</th>
                    <th>Date de soumission</th>
                    <th>Date de traitement</th>
                    <th>Statut</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="demande in demandes" :key="demande.idDemande" class="table-row">
                    <td class="table-cell">
                      <span class="apogee-number">{{ demande.etudiant?.numApogee || '-' }}</span>
                    </td>
                    <td class="table-cell">
                      <div class="etudiant-info">
                        <div class="etudiant-avatar">
                          <svg class="avatar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                          </svg>
                        </div>
                        <div class="etudiant-details">
                          <p class="etudiant-name">{{ demande.etudiant?.nom }} {{ demande.etudiant?.prenom }}</p>
                          <p class="etudiant-email">{{ demande.etudiant?.email }}</p>
                        </div>
                      </div>
                    </td>
                    <td class="table-cell">
                      <span :class="['type-badge', getTypeClass(demande.typeDoc)]">{{ getTypeLabel(demande.typeDoc) }}</span>
                    </td>
                    <td class="table-cell">
                      <span class="date-cell">{{ formatDate(demande.datesoumission) }}</span>
                    </td>
                    <td class="table-cell">
                      <span class="date-cell">{{ formatDate(demande.date_traitement) }}</span>
                    </td>
                    <td class="table-cell">
                      <span :class="['status-badge', getStatusClass(demande.statut)]">
                        {{ demande.statut }}
                      </span>
                    </td>
                    <td class="table-cell">
                      <button @click="showDetails(demande)" class="action-button">
                        <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-container">
              <div class="pagination-info">
                <span>Affichage de {{ pagination.from }} à {{ pagination.to }} sur {{ pagination.total }}</span>
              </div>
              <div class="pagination-controls">
                <button 
                  @click="changePage(pagination.current_page - 1)" 
                  :disabled="pagination.current_page === 1"
                  class="pagination-button"
                >
                  Précédent
                </button>
                
                <span class="pagination-text">Page {{ pagination.current_page }} / {{ pagination.last_page }}</span>
                
                <button 
                  @click="changePage(pagination.current_page + 1)" 
                  :disabled="pagination.current_page === pagination.last_page"
                  class="pagination-button"
                >
                  Suivant
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal détails -->
    <div v-if="showModal" class="modal-overlay" @click="closeModal">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h3 class="modal-title">Détails de la demande #{{ selectedDemande?.idDemande }}</h3>
          <button @click="closeModal" class="modal-close">
            <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        
        <div class="modal-body" v-if="selectedDemande">
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
                <h5 class="student-name">{{ selectedDemande.etudiant?.nom }} {{ selectedDemande.etudiant?.prenom }}</h5>
                <div class="student-details-grid">
                  <div class="detail-item">
                    <label>N° APOGEE</label>
                    <p class="detail-value">{{ selectedDemande.etudiant?.numApogee || '-' }}</p>
                  </div>
                  <div class="detail-item">
                    <label>Niveau</label>
                    <p class="detail-value">{{ selectedDemande.etudiant?.niveau || '-' }}</p>
                  </div>
                  <div class="detail-item">
                    <label>CIN</label>
                    <p class="detail-value">{{ selectedDemande.etudiant?.CIN || '-' }}</p>
                  </div>
                  <div class="detail-item">
                    <label>Email institutionnel</label>
                    <p class="detail-value">{{ selectedDemande.etudiant?.emailInstitu || '-' }}</p>
                  </div>
                  <div class="detail-item">
                    <label>Date de naissance</label>
                    <p class="detail-value">{{ formatDate(selectedDemande.etudiant?.dateNaissance) }}</p>
                  </div>
                  <div class="detail-item">
                    <label>Lieu de naissance</label>
                    <p class="detail-value">{{ selectedDemande.etudiant?.lieuNaissance || '-' }}</p>
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
                <div class="type-badge">{{ getTypeLabel(selectedDemande.typeDoc) }}</div>
              </div>
              <div class="detail-item">
                <label>Date de soumission</label>
                <p class="detail-value">{{ formatDate(selectedDemande.datesoumission) }}</p>
              </div>
              <div class="detail-item">
                <label>Date de traitement</label>
                <p class="detail-value">{{ formatDate(selectedDemande.date_traitement) }}</p>
              </div>
              <div class="detail-item">
                <label>Statut</label>
                <span :class="['status-badge', getStatusClass(selectedDemande.statut)]">
                  {{ selectedDemande.statut }}
                </span>
              </div>
            </div>
          </div>

          <!-- Motif de refus -->
          <div class="detail-section" v-if="selectedDemande.motif_refus">
            <h4 class="detail-title">
              <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              Motif de refus
            </h4>
            <div class="refus-card">
              <p class="motif-refus">{{ selectedDemande.motif_refus }}</p>
            </div>
          </div>
        </div>
        
        <!-- Pied de page avec actions -->
        <div class="modal-footer" v-if="selectedDemande">
          <div class="modal-actions">
            <!-- Boutons pour les demandes refusées -->
            <template v-if="selectedDemande.statut === 'Refusée'">
              <button @click="renvoyerDemande(selectedDemande)" class="modal-action-button resend-modal-button">
                <svg class="button-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Renvoyer la demande
              </button>
              <button @click="visualiserDocument(selectedDemande)" class="modal-action-button view-modal-button">
                <svg class="button-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Visualiser le document
              </button>
            </template>
            
            <!-- Bouton pour les demandes acceptées -->
            <template v-else-if="selectedDemande.statut === 'Validée'">
              <button @click="visualiserDocument(selectedDemande)" class="modal-action-button view-modal-button">
                <svg class="button-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Visualiser le document
              </button>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Navbar from './Navbar.vue'
import Sidebar from './Sidebar.vue'
import axios from 'axios'

// État
const loading = ref(true)
const error = ref(null)
const demandes = ref([])
const pagination = ref({
  current_page: 1,
  last_page: 1,
  total: 0,
  from: 0,
  to: 0
})

// Filtres
const filters = ref({
  nomEtudiant: '',
  apogee: '',
  dateTraitement: '',
  statut: '',
  typeDoc: ''
})

// Modal
const showModal = ref(false)
const selectedDemande = ref(null)

// Debounce search
const debouncedSearch = (() => {
  let timeout
  return () => {
    clearTimeout(timeout)
    timeout = setTimeout(loadHistorique, 500)
  }
})()

// Charger l'historique
const loadHistorique = async () => {
  try {
    loading.value = true
    error.value = null
    
    const params = new URLSearchParams()
    if (filters.value.nomEtudiant) params.append('nomEtudiant', filters.value.nomEtudiant)
    if (filters.value.apogee) params.append('numApogee', filters.value.apogee)
    if (filters.value.dateTraitement) params.append('dateTraitement', filters.value.dateTraitement)
    if (filters.value.statut) params.append('statut', filters.value.statut)
    if (filters.value.typeDoc) params.append('typeDoc', filters.value.typeDoc)
    params.append('page', pagination.value.current_page)
    
    const response = await axios.get(`http://localhost:8000/api/admin/historique?${params}`)
    
    if (response.data.success) {
      const data = response.data.data
      
      demandes.value = data.demandes
      pagination.value = data.pagination
    }
  } catch (err) {
    console.error('Erreur lors du chargement de l\'historique:', err)
    error.value = 'Impossible de charger l\'historique. Vérifiez que le serveur Laravel est démarré.'
  } finally {
    loading.value = false
  }
}

// Changer de page
const changePage = (page) => {
  if (page >= 1 && page <= pagination.value.last_page) {
    pagination.value.current_page = page
    loadHistorique()
  }
}

// Afficher les détails
const showDetails = (demande) => {
  selectedDemande.value = demande
  showModal.value = true
}

// Fermer la modal
const closeModal = () => {
  showModal.value = false
  selectedDemande.value = null
}

// Renvoyer une demande refusée
const renvoyerDemande = async (demande) => {
  try {
    const response = await axios.post(`http://localhost:8000/api/admin/demandes/${demande.idDemande}/renvoyer`)
    
    if (response.data.success) {
      // Recharger l'historique pour voir les changements
      await loadHistorique()
      // Fermer la modal et afficher un message de succès
      closeModal()
      alert('Demande renvoyée avec succès!')
    } else {
      alert('Erreur lors du renvoi de la demande: ' + response.data.message)
    }
  } catch (error) {
    console.error('Erreur lors du renvoi de la demande:', error)
    alert('Erreur lors du renvoi de la demande')
  }
}

// Visualiser un document
const visualiserDocument = (demande) => {
  // Ouvrir le document dans un nouvel onglet
  // L'URL dépendra de votre backend pour générer les documents
  const documentUrl = `http://localhost:8000/api/admin/demandes/${demande.idDemande}/document`
  window.open(documentUrl, '_blank')
}

// Formater la date
const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Obtenir le libellé du type de document
const getTypeLabel = (type) => {
  const labels = {
    'AttestationScolarite': 'Attestation de Scolarité',
    'AttestationReussite': 'Attestation de Réussite',
    'ReleveNote': 'Relevé de Notes',
    'ConventionStage': 'Convention de Stage'
  }
  return labels[type] || type
}

// Obtenir la classe CSS pour le type de document
const getTypeClass = (type) => {
  return {
    'type-attestation-scolarite': type === 'AttestationScolarite',
    'type-attestation-reussite': type === 'AttestationReussite',
    'type-releve-notes': type === 'ReleveNote',
    'type-convention-stage': type === 'ConventionStage'
  }
}

// Obtenir la classe CSS pour le statut
const getStatusClass = (statut) => {
  return {
    'status-valide': statut === 'Validée',
    'status-refuse': statut === 'Refusée'
  }
}

onMounted(() => {
  loadHistorique()
})
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.historique-layout {
  min-height: 100vh;
  background: linear-gradient(135deg, #E3EDF2 0%, #F8FBFC 100%);
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.historique-main {
  display: flex;
}

.historique-content {
  flex: 1;
  background: transparent;
  min-height: 100vh;
  padding: 2rem;
  margin-left: 16rem;
  margin-top: 104px; /* Espace pour le header fixe */
}

@media (max-width: 768px) {
  .historique-content {
    margin-left: 200px;
  }
}

/* Page Header */
.page-header {
  margin-bottom: 2rem;
}

.page-title {
  font-size: 2rem;
  font-weight: 700;
  color: #0A0D25;
  margin-bottom: 0.5rem;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.page-subtitle {
  color: #6b7280;
  font-size: 1rem;
}

/* Filters */
.filters-section {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
  margin-bottom: 2rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.filters-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.filter-group {
  display: flex;
  flex-direction: column;
}

.filter-label {
  font-size: 0.875rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 0.5rem;
}

.filter-select, .filter-input {
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  color: #374151;
  background: white;
  transition: border-color 0.2s ease;
}

.filter-select:focus, .filter-input:focus {
  outline: none;
  border-color: #4E7D96;
}

/* Loading and Error States */
.loading-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 400px;
  color: #6b7280;
}

.loading-spinner {
  width: 3rem;
  height: 3rem;
  border: 3px solid #e5e7eb;
  border-top: 3px solid #4E7D96;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 400px;
  color: #ef4444;
  text-align: center;
  padding: 2rem;
}

.error-state p {
  margin-bottom: 1rem;
  font-size: 1rem;
}

.retry-button {
  background: #4E7D96;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  cursor: pointer;
  font-size: 0.875rem;
  font-weight: 600;
  transition: background-color 0.3s ease;
}

.retry-button:hover {
  background: #3a5f73;
}

.apogee-number {
  font-weight: 600;
  color: #4E7D96;
}

/* Table */
.table-container {
  background: white;
  border-radius: 1rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.table-header {
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.table-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #0A0D25;
}

.table-info {
  color: #6b7280;
  font-size: 0.875rem;
}

.table-wrapper {
  overflow-x: auto;
}

.demandes-table {
  width: 100%;
  border-collapse: collapse;
}

.demandes-table th {
  background: #f9fafb;
  padding: 1rem;
  text-align: left;
  font-weight: 600;
  color: #374151;
  font-size: 0.875rem;
  border-bottom: 1px solid #e5e7eb;
}

.demandes-table td {
  padding: 1rem;
  border-bottom: 1px solid #f3f4f6;
}

.table-row:hover {
  background: #f9fafb;
}

.demande-id {
  font-weight: 600;
  color: #4E7D96;
}

.etudiant-info {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.etudiant-avatar {
  width: 2.5rem;
  height: 2.5rem;
  background: #e5e7eb;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.avatar-icon {
  width: 1.25rem;
  height: 1.25rem;
  color: #6b7280;
}

.etudiant-name {
  font-weight: 600;
  color: #0A0D25;
  font-size: 0.875rem;
}

.etudiant-email {
  color: #6b7280;
  font-size: 0.75rem;
}

.type-badge {
  background: #3d4e70ff;
  color: #374151;
  padding: 0.25rem 0.75rem;
  border-radius: 0.375rem;
  font-size: 0.75rem;
  font-weight: 500;
}

.date-cell {
  color: #6b7280;
  font-size: 0.875rem;
}

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 0.375rem;
  font-size: 0.75rem;
  font-weight: 600;
}

.status-valide {
  background: #d1fae5;
  color: #065f46;
}

.status-refuse {
  background: #fee2e2;
  color: #991b1b;
}

.action-button {
  background: none;
  border: none;
  padding: 0.5rem;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: background 0.2s ease;
}

.action-button:hover {
  background: #f3f4f6;
}

.action-icon {
  width: 1.25rem;
  height: 1.25rem;
  color: #6b7280;
}

/* Pagination */
.pagination-container {
  padding: 1.5rem;
  border-top: 1px solid #e5e7eb;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.pagination-info {
  color: #6b7280;
  font-size: 0.875rem;
}

.pagination-controls {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.pagination-button {
  background: white;
  border: 1px solid #d1d5db;
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  color: #374151;
  cursor: pointer;
  transition: all 0.2s ease;
}

.pagination-button:hover:not(:disabled) {
  background: #f9fafb;
  border-color: #4E7D96;
}

.pagination-button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination-text {
  color: #6b7280;
  font-size: 0.875rem;
}

/* Modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(10, 13, 37, 0.8);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 2rem;
}

.modal-content {
  background: #E3EDF2;
  border-radius: 1.5rem;
  max-width: 900px;
  width: 100%;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
  from {
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.modal-header {
  background: linear-gradient(135deg, #4E7D96 0%, #0A0D25 100%);
  padding: 1.5rem 2rem;
  border-radius: 1.5rem 1.5rem 0 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: white;
}

.modal-title {
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0;
}

.modal-close {
  background: rgba(255, 255, 255, 0.2);
  border: none;
  border-radius: 0.5rem;
  padding: 0.75rem;
  cursor: pointer;
  transition: all 0.3s ease;
  color: white;
}

.modal-close:hover {
  background: rgba(255, 255, 255, 0.3);
  transform: rotate(90deg);
}

.close-icon {
  width: 1.25rem;
  height: 1.25rem;
}

.modal-body {
  padding: 2rem;
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
  background: linear-gradient(135deg, #4E7D96 0%, #0A0D25 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1rem;
}

.avatar-icon {
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
  color: #0A0D25;
  margin: 0;
  padding: 0.5rem 0.75rem;
  background: #F8FBFC;
  border-radius: 0.5rem;
  border-left: 3px solid #4E7D96;
}

/* Refus Card */
.refus-card {
  background: linear-gradient(135deg, rgba(255, 132, 75, 0.1) 0%, rgba(255, 132, 75, 0.05) 100%);
  border: 1px solid rgba(255, 132, 75, 0.2);
  border-radius: 1rem;
  padding: 1.5rem;
  border-left: 4px solid #FF844B;
}

.motif-refus {
  font-size: 1rem;
  color: #0A0D25;
  margin: 0;
  line-height: 1.6;
}

/* Status Badge */
.status-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.5rem 1rem;
  border-radius: 2rem;
  font-size: 0.875rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.status-valide {
  background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);
  color: #059669;
  border: 1px solid rgba(16, 185, 129, 0.2);
}

.status-refuse {
  background: linear-gradient(135deg, rgba(255, 132, 75, 0.1) 0%, rgba(255, 132, 75, 0.05) 100%);
  color: #FF844B;
  border: 1px solid rgba(255, 132, 75, 0.2);
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
  background: linear-gradient(135deg, #4E7D96 0%, #0A0D25 100%);
}

.type-attestation-reussite {
  background: linear-gradient(135deg, #059669 0%, #047857 100%);
}

.type-releve-notes {
  background: linear-gradient(135deg, #7C3AED 0%, #6D28D9 100%);
}

.type-convention-stage {
  background: linear-gradient(135deg, #FF844B 0%, #F97316 100%);
}

/* Modal Footer */
.modal-footer {
  background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
  padding: 1.5rem 2rem;
  border-radius: 0 0 1.5rem 1.5rem;
  border-top: 1px solid #e5e7eb;
}

.modal-actions {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  justify-content: center;
}

.modal-action-button {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem 1.5rem;
  border: none;
  border-radius: 0.75rem;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: none;
  letter-spacing: normal;
  min-width: 200px;
  justify-content: center;
}

.modal-action-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
}

.resend-modal-button {
  background: linear-gradient(135deg, #FF844B 0%, #ea580c 100%);
  color: white;
}

.resend-modal-button:hover {
  background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%);
}

.view-modal-button {
  background: linear-gradient(135deg, #4E7D96 0%, #3a5f73 100%);
  color: white;
}

.view-modal-button:hover {
  background: linear-gradient(135deg, #3a5f73 0%, #2d4a5a 100%);
}

.button-icon {
  width: 1.25rem;
  height: 1.25rem;
  flex-shrink: 0;
}

@media (max-width: 768px) {
  .historique-content {
    padding: 1rem;
  }
  
  .filters-container {
    grid-template-columns: 1fr;
  }
  
  .stats-row {
    grid-template-columns: 1fr;
  }
  
  .table-header {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }
  
  .pagination-container {
    flex-direction: column;
    gap: 1rem;
  }
  
  .modal-content {
    width: 95%;
    margin: 1rem;
  }
}
</style>
