<template>
  <div class="demandes-layout">
    <Navbar />
    <div class="demandes-main">
      <Sidebar />
      <div class="demandes-content">
        <div class="section">
          <div>
            <h1 class="title">Gestion des Demandes</h1>
            <p class="subtitle">Traiter et valider les demandes étudiantes</p>
          </div>

          <!-- Tabs -->
          <div class="card card--p2">
            <div class="tabs">
              <button
                v-for="tab in tabs"
                :key="tab.id"
                @click="setActiveTab(tab.id)"
                class="tab-btn"
                :class="{ 'tab-btn--active': activeTab === tab.id }"
              >
                {{ tab.label }}
              </button>
            </div>
          </div>

          <!-- Filtres -->
          <div class="card card--p4">
            <div class="filters">
              <div>
                <select v-model="selectedStatut" @change="fetchDemandes" class="select">
                  <option value="tous">Tous les statuts</option>
                  <option value="en_attente">En attente</option>
                  <option value="acceptee">Acceptées</option>
                  <option value="refusee">Refusées</option>
                </select>
              </div>
              <div class="filters__search">
                <div class="search">
                  <svg class="search__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                  </svg>
                  <input
                    v-model="searchQuery"
                    @input="debouncedSearch"
                    type="text"
                    placeholder="Rechercher par N° Apogée, nom..."
                    class="search__input"
                  />
                </div>
              </div>
            </div>
          </div>

          <!-- Liste des demandes -->
          <div class="list">
            <div v-if="loading" class="loading">
              <div class="spinner"></div>
              <p class="muted">Chargement des demandes...</p>
            </div>

            <div v-else-if="demandes.length === 0" class="empty">Aucune demande trouvée</div>

            <div v-else v-for="demande in demandes" :key="demande.id" class="card card--shadow">
              <!-- Header carte -->
              <div class="card__header">
                <div class="card__header-left">
                  <span class="badge" :class="getTypeBadgeColor(demande.type)">
                    {{ demande.typeLabel }}
                  </span>
                  <span v-html="getStatutBadge(demande.statut)"></span>
                </div>
                <span class="small muted">{{ demande.id }}</span>
              </div>

              <!-- Corps carte -->
              <div class="card__body">
                <div class="media">
                  <div class="avatar">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                  </div>
                  <div class="media__content">
                    <h3 class="item-title">{{ demande.etudiant.nom }}</h3>
                    <div class="details">
                      <p>N° Apogée: {{ demande.etudiant.apogee }}</p>
                      <p>Email: {{ demande.etudiant.email }}</p>
                      <p>Date soumission: {{ demande.date }}</p>
                    </div>

                    <!-- Détails expandables -->
                    <button @click="toggleExpanded(demande.id)" class="link small">
                      📎 Détails spécifiques
                      <svg :class="['chevron', { 'chevron--up': expandedId === demande.id }]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                      </svg>
                    </button>

                    <div v-if="expandedId === demande.id" class="panel">
                      <div v-for="[key, value] in Object.entries(demande.details)" :key="key" class="panel__row">
                        <span class="muted capitalize">{{ formatKey(key) }}:</span>
                        <span class="text">{{ value }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Footer actions -->
              <div class="card__footer">
                <button @click="openPreviewModal(demande)" class="btn btn--info">
                  <svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                  Prévisualiser
                </button>
                <button @click="openValidateModal(demande)" class="btn btn--success" :disabled="demande.statut !== 'en_attente'">
                  <svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                  </svg>
                  Valider
                </button>
                <button @click="openRefuseModal(demande)" class="btn btn--danger-outline" :disabled="demande.statut !== 'en_attente'">
                  <svg class="icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                  Refuser
                </button>
              </div>
            </div>
          </div>

          <!-- Modal de prévisualisation -->
          <div v-if="previewModal.isOpen" class="modal">
            <div class="modal__dialog modal__dialog--xl">
              <div class="modal__header">
                <h2 class="title">Prévisualisation du document</h2>
                <button @click="closePreviewModal" class="icon-btn">
                  <svg class="icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
              </div>
              <div class="modal__body">
                <div v-if="previewLoading" class="loading">
                  <div class="spinner"></div>
                  <p class="muted">Chargement de la prévisualisation...</p>
                </div>
                <div v-else-if="previewError" class="empty">{{ previewError }}</div>
                <div v-else-if="previewUrl" class="pdf-container">
                  <div style="display:flex; justify-content:flex-end; margin-bottom: .5rem; gap: .5rem;">
                    <a :href="downloadHref" target="_blank" rel="noopener" class="btn btn--success">
                      Télécharger ({{ previewModal.demande?.num_demande || previewModal.demande?.id }}).pdf
                    </a>
                  </div>
                  <iframe :src="previewUrl" class="pdf-frame"></iframe>
                </div>
                <div v-else class="empty">Aucune prévisualisation disponible</div>
              </div>
            </div>
          </div>

          <!-- Modal de validation -->
          <div v-if="validateModal.isOpen" class="modal">
            <div class="modal__dialog modal__dialog--md">
              <div class="modal__header">
                <h2 class="title">Confirmer la validation</h2>
              </div>
              <div class="modal__body space">
                <div class="notice notice--success">
                  <svg class="icon-sm text--success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                  </svg>
                  <div class="small">
                    Le document sera généré et envoyé par email à <span class="bold">{{ validateModal.demande?.etudiant.email }}</span>
                  </div>
                </div>
                <div class="info-panel small">
                  <p><span class="muted">Étudiant:</span> <span class="bold">{{ validateModal.demande?.etudiant.nom }}</span></p>
                  <p><span class="muted">Type de document:</span> <span class="bold">{{ validateModal.demande?.typeLabel }}</span></p>
                  <p><span class="muted">N° demande:</span> <span class="bold">{{ validateModal.demande?.id }}</span></p>
                </div>
              </div>
              <div class="modal__footer">
                <button @click="closeValidateModal" class="btn">Annuler</button>
                <button @click="confirmValidation" class="btn btn--success">Confirmer l'envoi</button>
              </div>
            </div>
          </div>

          <!-- Modal de refus -->
          <div v-if="refuseModal.isOpen" class="modal">
            <div class="modal__dialog modal__dialog--md">
              <div class="modal__header">
                <h2 class="title">Refuser la demande</h2>
              </div>
              <div class="modal__body space">
                <div class="notice notice--danger">
                  <svg class="icon-sm text--danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                  </svg>
                  <div class="small">
                    Un email de refus sera envoyé à <span class="bold">{{ refuseModal.demande?.etudiant.email }}</span> avec la raison spécifiée.
                  </div>
                </div>
                <div>
                  <label class="label">Raison du refus <span class="text--danger">*</span></label>
                  <textarea v-model="refuseReason" placeholder="Veuillez indiquer la raison du refus..." rows="5" class="textarea"></textarea>
                </div>
              </div>
              <div class="modal__footer">
                <button @click="closeRefuseModal" class="btn">Annuler</button>
                <button @click="confirmRefusal" class="btn btn--danger">Confirmer le refus</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Navbar from './Navbar.vue'
import Sidebar from './Sidebar.vue'

const activeTab = ref('toutes')
const expandedId = ref(null)
const selectedStatut = ref('tous')
const searchQuery = ref('')
const loading = ref(false)
const demandes = ref([])

const previewModal = ref({ isOpen: false, demande: null })
const validateModal = ref({ isOpen: false, demande: null })
const refuseModal = ref({ isOpen: false, demande: null })
const refuseReason = ref('')

// Preview state
const previewUrl = ref(null)
const previewLoading = ref(false)
const previewError = ref(null)
const downloadHref = ref(null)

const tabs = [
  { id: 'toutes', label: 'Toutes' },
  { id: 'attestation_scolarite', label: 'Attestation Scolarité' },
  { id: 'attestation_reussite', label: 'Attestation Réussite' },
  { id: 'releve_notes', label: 'Relevé Notes' },
  { id: 'convention_stage', label: 'Convention Stage' }
]

const setActiveTab = (tabId) => {
  activeTab.value = tabId
  fetchDemandes()
}

const toggleExpanded = (id) => {
  expandedId.value = expandedId.value === id ? null : id
}

const getTypeBadgeColor = (type) => {
  switch (type) {
    case 'attestation_scolarite':
      return 'badge--blue'
    case 'attestation_reussite':
      return 'badge--purple'
    case 'releve_notes':
      return 'badge--indigo'
    case 'convention_stage':
      return 'badge--teal'
    default:
      return 'badge--gray'
  }
}

const getStatutBadge = (statut) => {
  switch (statut) {
    case 'en_attente':
      return '<span class="badge badge--orange small">En attente</span>'
    case 'acceptee':
      return '<span class="badge badge--green small">Acceptée</span>'
    case 'refusee':
      return '<span class="badge badge--red small">Refusée</span>'
    default:
      return ''
  }
}

const formatKey = (key) => key.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase())

const fetchDemandes = async () => {
  loading.value = true
  try {
    const params = { statut: selectedStatut.value, typeDoc: activeTab.value, search: searchQuery.value }
    const response = await axios.get('http://localhost:8000/api/admin/demandes', { params })
    demandes.value = response.data.data.demandes
  } catch (error) {
    console.error('Erreur lors du chargement des demandes:', error)
    demandes.value = []
  } finally {
    loading.value = false
  }
}

const debouncedSearch = (() => {
  let timeout
  return () => {
    clearTimeout(timeout)
    timeout = setTimeout(fetchDemandes, 500)
  }
})()

const openPreviewModal = async (demande) => {
  previewError.value = null
  previewUrl.value = null
  previewLoading.value = true
  previewModal.value = { isOpen: true, demande }
  try {
    const id = demande.num_demande ?? demande.id
    // Charger directement l'URL pour que le navigateur utilise Content-Disposition (nom de fichier)
    previewUrl.value = `http://localhost:8000/api/admin/demandes/${id}/preview?t=${Date.now()}`
    downloadHref.value = `http://localhost:8000/api/admin/demandes/${id}/download?t=${Date.now()}`
  } catch (e) {
    console.error('Erreur de prévisualisation:', e)
    previewError.value = 'Impossible de charger la prévisualisation du PDF'
  } finally {
    previewLoading.value = false
  }
}
const closePreviewModal = () => {
  if (previewUrl.value) {
    if (previewUrl.value.startsWith('blob:')) {
      URL.revokeObjectURL(previewUrl.value)
    }
    previewUrl.value = null
  }
  previewLoading.value = false
  previewError.value = null
  previewModal.value = { isOpen: false, demande: null }
  downloadHref.value = null
}
const openValidateModal = (demande) => { validateModal.value = { isOpen: true, demande } }
const closeValidateModal = () => { validateModal.value = { isOpen: false, demande: null } }
const openRefuseModal = (demande) => { refuseModal.value = { isOpen: true, demande }; refuseReason.value = '' }
const closeRefuseModal = () => { refuseModal.value = { isOpen: false, demande: null }; refuseReason.value = '' }

const confirmValidation = async () => {
  try {
    const demandeId = validateModal.value.demande.num_demande
    console.log('Validation de la demande ID:', demandeId)
    
    if (!demandeId) {
      alert('Erreur: ID de demande manquant')
      return
    }
    
    const response = await axios.post(`http://localhost:8000/api/admin/demandes/${demandeId}/valider`)
    
    if (response.data.success) {
      alert(response.data.message || 'Demande validée avec succès !')
      closeValidateModal()
      fetchDemandes()
    } else {
      alert(response.data.message || 'Erreur lors de la validation')
    }
  } catch (error) {
    console.error('Erreur lors de la validation:', error)
    const errorMessage = error.response?.data?.message || error.message || 'Erreur lors de la validation de la demande'
    alert('Erreur: ' + errorMessage)
  }
}

const confirmRefusal = async () => {
  if (!refuseReason.value.trim()) { 
    alert('Veuillez indiquer une raison de refus')
    return 
  }
  
  try {
    const demandeId = refuseModal.value.demande.num_demande
    console.log('Refus de la demande ID:', demandeId, 'Motif:', refuseReason.value)
    
    if (!demandeId) {
      alert('Erreur: ID de demande manquant')
      return
    }
    
    const response = await axios.post(
      `http://localhost:8000/api/admin/demandes/${demandeId}/refuser`, 
      { motif_refus: refuseReason.value }
    )
    
    if (response.data.success) {
      alert(response.data.message || 'Demande refusée avec succès !')
      closeRefuseModal()
      fetchDemandes()
    } else {
      alert(response.data.message || 'Erreur lors du refus')
    }
  } catch (error) {
    console.error('Erreur lors du refus:', error)
    const errorMessage = error.response?.data?.message || error.message || 'Erreur lors du refus de la demande'
    alert('Erreur: ' + errorMessage)
  }
}

onMounted(fetchDemandes)
</script>

<style scoped>
/* Layout */
.demandes-layout { 
  min-height: 100vh; 
  background: linear-gradient(135deg, #E3EDF2 0%, #F8FBFC 100%); 
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.demandes-main { display: flex; }
.demandes-content { flex: 1; min-height: 100vh; padding: 2rem; margin-left: 16rem; margin-top: 104px; /* Espace pour le header fixe */ }
.section > * + * { margin-top: 1.5rem; }

/* Typography */
.title { 
  color: #0A0D25; 
  margin-bottom: 0.5rem; 
  font-size: 2.2rem;
  font-weight: 700;
  letter-spacing: -0.5px;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.subtitle { 
  color: #4E7D96; 
  font-size: 1rem;
  font-weight: 400;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.small { font-size: 0.875rem; }
.muted { color: #6b7280; }
.bold { font-weight: 600; }
.item-title { color: #111827; margin-bottom: 0.25rem; }
.text { color: #111827; }
.capitalize { text-transform: capitalize; }

/* Cards */
.card { 
  background: #ffffff; 
  border: 1px solid #E3EDF2; 
  border-radius: 20px; 
  box-shadow: 0 20px 60px rgba(78, 125, 150, 0.15);
  transition: all 0.3s ease;
}
.card:hover {
  transform: translateY(-2px);
  box-shadow: 0 25px 50px rgba(10, 13, 37, 0.12);
}
.card--p2 { padding: 1rem; }
.card--p4 { padding: 2rem; }
.card--shadow { 
  box-shadow: 0 20px 60px rgba(78, 125, 150, 0.15); 
  overflow: hidden; 
  border-radius: 20px; 
  transition: all 0.3s ease;
}
.card--shadow:hover {
  transform: translateY(-2px);
  box-shadow: 0 25px 50px rgba(10, 13, 37, 0.12);
}
.card__header { 
  padding: 1.5rem; 
  background: #F8FBFC; 
  border-bottom: 2px solid #E3EDF2; 
  display: flex; 
  align-items: center; 
  justify-content: space-between;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.card__header-left { display: flex; align-items: center; gap: 0.75rem; }
.card__body { padding: 2rem; }
.card__footer { 
  padding: 1.5rem; 
  background: #F8FBFC; 
  border-top: 2px solid #E3EDF2; 
  display: flex; 
  align-items: center; 
  gap: 0.75rem;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Tabs */
.tabs { display: flex; gap: 0.5rem; overflow-x: auto; }
.tab-btn { 
  padding: 0.75rem 1.5rem; 
  border-radius: 12px; 
  white-space: nowrap; 
  border: 1px solid transparent; 
  background: transparent; 
  color: #4E7D96; 
  transition: all 0.3s ease;
  font-weight: 500;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
.tab-btn:hover { 
  background: rgba(255, 132, 75, 0.1); 
  color: #FF844B;
  transform: translateY(-1px);
}
.tab-btn--active { 
  background: #FF844B; 
  color: #ffffff;
  box-shadow: 0 4px 15px rgba(255, 132, 75, 0.2);
}

/* Filters */
.filters { display: grid; grid-template-columns: 1fr; gap: 1rem; }
.select { 
  width: 100%; 
  padding: 0.75rem 1rem; 
  border-radius: 12px; 
  border: 2px solid #E3EDF2;
  background: #ffffff;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  transition: all 0.3s ease;
}
.filters__search { grid-column: span 1; }
.search { position: relative; }
.search__icon { 
  position: absolute; 
  left: 1rem; 
  top: 50%; 
  transform: translateY(-50%); 
  width: 1.25rem; 
  height: 1.25rem; 
  color: #4E7D96;
}
.search__input { 
  width: 100%; 
  padding: 0.75rem 1rem 0.75rem 3rem; 
  border-radius: 12px; 
  border: 2px solid #E3EDF2;
  background: #ffffff;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  transition: all 0.3s ease;
}

/* Fix search bar shrink/overflow */
.filters > * { min-width: 0; }
.filters__search, .search { min-width: 0; width: 100%; }
.search__input { width: 100%; max-width: 100%; box-sizing: border-box; }

/* List */
.list > * + * { margin-top: 1rem; }
.loading { text-align: center; padding: 2rem 0; }
.spinner { width: 2rem; height: 2rem; border-radius: 9999px; border: 2px solid #2563eb; border-right-color: transparent; margin: 0 auto; animation: spin 1s linear infinite; }
.empty { text-align: center; padding: 2rem 0; color: #6b7280; }

/* Media object */
.media { display: flex; align-items: flex-start; gap: 1rem; }
.avatar { width: 3rem; height: 3rem; background: #e5e7eb; border-radius: 9999px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.icon { width: 1.5rem; height: 1.5rem; color: #4b5563; }
.icon-sm { width: 1rem; height: 1rem; }
.icon-md { width: 1.5rem; height: 1.5rem; }
.icon-lg { width: 4rem; height: 4rem; }
.media__content { flex: 1 1 0%; }
.details > * + * { margin-top: 0.25rem; }

.link { margin-top: 0.75rem; display: inline-flex; align-items: center; gap: 0.5rem; color: #2563eb; text-decoration: none; background: transparent; border: none; cursor: pointer; }
.link:hover { text-decoration: underline; }
.chevron { width: 1rem; height: 1rem; transition: transform .15s ease; }
.chevron--up { transform: rotate(180deg); }

.panel { margin-top: 1rem; padding: 1rem; background: #eff6ff; border-radius: 8px; }
.panel__row { display: flex; gap: 0.5rem; }

/* Badges */
.badge { padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; display: inline-block; }
.badge--blue { background: #dbeafe; color: #1e40af; }
.badge--purple { background: #ede9fe; color: #5b21b6; }
.badge--indigo { background: #e0e7ff; color: #3730a3; }
.badge--teal { background: #ccfbf1; color: #115e59; }
.badge--gray { background: #f3f4f6; color: #1f2937; }
.badge--orange { background: #ffedd5; color: #9a3412; }
.badge--green { background: #dcfce7; color: #166534; }
.badge--red { background: #fee2e2; color: #991b1b; }

/* Buttons */
.btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 8px; border: 1px solid transparent; background: #e5e7eb; color: #374151; transition: background-color .15s ease, color .15s ease, border-color .15s ease; cursor: pointer; }
.btn:hover { background: #d1d5db; }
.btn:disabled { opacity: .5; cursor: not-allowed; }
.btn--info { background: #dbeafe; color: #1d4ed8; }
.btn--info:hover { background: #bfdbfe; }
.btn--success { background: #16a34a; color: #fff; }
.btn--success:hover { background: #15803d; }
.btn--danger { background: #dc2626; color: #fff; }
.btn--danger:hover { background: #b91c1c; }
.btn--danger-outline { background: transparent; color: #dc2626; border-color: #dc2626; }
.btn--danger-outline:hover { background: #fef2f2; }

.icon-btn { background: transparent; border: none; color: #6b7280; cursor: pointer; }
.icon-btn:hover { color: #374151; }

/* Modal */
.modal { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 50; padding: 1rem; }
.modal__dialog { background: #fff; border-radius: 8px; width: 100%; max-height: 90vh; overflow-y: auto; }
.modal__dialog--xl { max-width: 56rem; }
.modal__dialog--md { max-width: 28rem; }
.modal__header { padding: 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; }
.modal__body { padding: 1.5rem; }
.modal__footer { padding: 1.5rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 0.75rem; background: #f9fafb; }

.preview { background: #f3f4f6; border-radius: 8px; padding: 2rem; text-align: center; }
.pdf-frame { width: 100%; height: 75vh; border: none; }

.notice { display: flex; align-items: flex-start; gap: 0.75rem; padding: 1rem 1.25rem; border-radius: 12px; border: 1px solid transparent; box-shadow: 0 6px 16px rgba(10, 13, 37, 0.06); }
.notice--success { background: #f0fdf4; border-color: #bbf7d0; }
.notice--danger { background: #fef2f2; border-color: #fecaca; }
.text--success { color: #16a34a; }
.text--danger { color: #dc2626; }

.info-panel { background: #f9fafb; border-radius: 8px; padding: 1rem; }
.info-panel > * + * { margin-top: 0.5rem; }

.label { display: block; color: #374151; margin-bottom: 0.5rem; }
.textarea { width: 100%; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid #d1d5db; resize: none; }

/* Animations */
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

/* Responsive */
@media (min-width: 768px) {
  .filters { grid-template-columns: repeat(3, minmax(0, 1fr)); }
  .filters__search { grid-column: span 2; }
}
</style>