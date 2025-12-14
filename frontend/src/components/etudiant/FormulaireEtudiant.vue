<script setup>
import { ref, computed, watch } from 'vue'
import etudiantService from '../../services/etudiant'

// Données du formulaire
const email = ref('')
const numApogee = ref('')
const cin = ref('')
const typeDocument = ref('')
const etudiantVerifie = ref(null)
const loading = ref(false)
const erreur = ref('')
const succes = ref('')

// Informations supplémentaires selon le type de document
const informationsSupplementaires = ref({})

// Types de documents disponibles
const typesDocuments = etudiantService.getTypesDocuments()

// Computed pour vérifier si tous les champs sont remplis
const tousChampsRemplis = computed(() => {
  return email.value && numApogee.value && cin.value
})

// Computed pour vérifier si l'étudiant est vérifié
const estVerifie = computed(() => etudiantVerifie.value !== null)

// Computed pour afficher les champs supplémentaires
const afficherChampsSupplementaires = computed(() => {
  return estVerifie.value && typeDocument.value
})

// Watcher pour vérification automatique
watch([email, numApogee, cin], () => {
  if (tousChampsRemplis.value && !estVerifie.value && !loading.value) {
    verifierEtudiant()
  }
})

// Fonction de vérification des informations étudiant
const verifierEtudiant = async () => {
  if (!email.value || !numApogee.value || !cin.value) {
    erreur.value = 'Veuillez remplir tous les champs'
    return
  }

  loading.value = true
  erreur.value = ''
  succes.value = ''

  try {
    const response = await etudiantService.verifier(email.value, numApogee.value, cin.value)
    
    if (response.data.success) {
      etudiantVerifie.value = response.data.etudiant
      succes.value = 'Informations vérifiées avec succès!'
    } else {
      erreur.value = response.data.message
    }
  } catch (error) {
    if (error.response && error.response.status === 404) {
      erreur.value = error.response.data.message || 'Les informations sont incorrectes, il faut vérifier vos données (email, N° Apogée, CIN)'
    } else {
      erreur.value = 'Erreur de connexion au serveur'
    }
    console.error('Erreur:', error)
  } finally {
    loading.value = false
  }
}

// Fonction de soumission de la demande
const soumettreDemande = async () => {
  if (!etudiantVerifie.value || !typeDocument.value) {
    erreur.value = 'Veuillez vérifier vos informations et choisir un type de document'
    return
  }

  loading.value = true
  erreur.value = ''
  succes.value = ''

  try {
    const response = await etudiantService.creerDemande(
      etudiantVerifie.value.idEtudiant,
      typeDocument.value,
      informationsSupplementaires.value
    )

    if (response.data.success) {
      succes.value = 'Demande créée avec succès! Référence: ' + response.data.demande.idDemande
      // Réinitialiser le formulaire
      reinitialiserFormulaire()
    } else {
      erreur.value = response.data.message
    }
  } catch (error) {
    erreur.value = 'Erreur lors de la création de la demande'
    console.error('Erreur:', error)
  } finally {
    loading.value = false
  }
}

// Réinitialiser le formulaire
const reinitialiserFormulaire = () => {
  email.value = ''
  numApogee.value = ''
  cin.value = ''
  typeDocument.value = ''
  etudiantVerifie.value = null
  informationsSupplementaires.value = {}
  erreur.value = ''
  succes.value = ''
}
</script>

<template>
  <div class="formulaire-etudiant">
    <!-- Header -->
    <header class="header">
      <div class="header-content">
        <div class="header-left">
          <div class="university-name">Service Étudiant</div>
        </div>
      </div>
    </header>
    
    <div class="content-wrapper">
      <div class="container">
        <h1 class="titre">Espace Étudiant</h1>
        <p class="description-page">Gérez vos demandes de documents en quelques clics</p>
        
        <!-- Messages d'erreur et succès -->
        <transition name="fade">
          <div v-if="erreur" class="message erreur" role="alert">
            {{ erreur }}
          </div>
        </transition>
        
        <transition name="fade">
          <div v-if="succes" class="message succes" role="alert">
            {{ succes }}
          </div>
        </transition>

        <!-- Section de vérification -->
        <div class="section">
          <h2 class="sous-titre">Identification</h2>
          
          <div class="form-group">
            <label for="email">Adresse email institutionnelle</label>
            <input 
              id="email"
              v-model="email" 
              type="email" 
              placeholder="exemple@univ-tn.tn"
              :disabled="estVerifie"
              class="form-input"
            />
          </div>

          <div class="form-row">
            <div class="form-group half">
              <label for="numApogee">N° Apogée</label>
              <input 
                id="numApogee"
                v-model="numApogee" 
                type="text" 
                placeholder="202512345"
                :disabled="estVerifie"
                class="form-input"
              />
            </div>

            <div class="form-group half">
              <label for="cin">CIN</label>
              <input 
                id="cin"
                v-model="cin" 
                type="text" 
                placeholder="L1234567"
                :disabled="estVerifie"
                class="form-input"
              />
            </div>
          </div>
        </div>

        
        <!-- Section choix du document -->
        <transition name="slide-fade">
          <div v-if="estVerifie" class="section">
            <h2 class="sous-titre">Type de document</h2>
            
            <div class="form-group">
              <label for="typeDocument">Sélectionnez votre document</label>
              <div class="select-wrapper">
                <select 
                  id="typeDocument"
                  v-model="typeDocument" 
                  class="form-select"
                >
                  <option value="" disabled selected>-- Choisissez une option --</option>
                  <option 
                    v-for="type in typesDocuments" 
                    :key="type.value" 
                    :value="type.value"
                  >
                    {{ type.label }}
                  </option>
                </select>
              </div>
            </div>
          </div>
        </transition>

        <!-- Informations supplémentaires -->
        <transition name="slide-fade">
          <div v-if="afficherChampsSupplementaires" class="section">
            <div class="info-box">
              <span class="info-icon">ℹ️</span>
              <p>Les informations nécessaires seront automatiquement ajoutées à votre demande.</p>
            </div>
          </div>
        </transition>

        <!-- Bouton de soumission -->
        <transition name="slide-fade">
          <div v-if="typeDocument" class="actions">
            <button 
              @click="soumettreDemande" 
              :disabled="loading"
              class="btn btn-main"
            >
              {{ loading ? 'Traitement en cours...' : 'Envoyer la demande' }}
            </button>
          </div>
        </transition>

        <!-- Bouton pour recommencer -->
        <div v-if="estVerifie" class="actions-secondary">
          <button 
            @click="reinitialiserFormulaire" 
            :disabled="loading"
            class="btn btn-ghost"
          >
            Nouvelle demande
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Palette de couleurs */
:root {
  --primary-blue: #4E7D96;
  --accent-orange: #FF844B;
  --bg-light: #E3EDF2;
  --text-dark: #0A0D25;
  --white: #ffffff;
  --shadow-soft: 0 20px 40px rgba(10, 13, 37, 0.08); 
  --shadow-hover: 0 25px 50px rgba(10, 13, 37, 0.12);
}

.formulaire-etudiant {
  min-height: 100vh;
  background-color: #E3EDF2; /* Couleur de fond principale */
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  color: #0A0D25;
}

/* Header simplifié */
.header {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  padding: 15px 0;
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 1000;
  box-shadow: 0 4px 20px rgba(78, 125, 150, 0.05);
}

.header-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
}

.university-name {
  color: #4E7D96;
  font-size: 1.25rem;
  font-weight: 800;
  letter-spacing: -0.5px;
}

/* Layout Content */
.content-wrapper {
  padding-top: 100px; /* Space for fixed header */
  padding-bottom: 60px;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  min-height: 100vh;
}

.container {
  width: 100%;
  max-width: 550px;
  background: #ffffff;
  border-radius: 30px;
  padding: 40px;
  box-shadow: 0 20px 60px rgba(78, 125, 150, 0.15);
  transition: transform 0.3s ease;
  margin: 0 20px;
}

/* Typography */
.titre {
  color: #0A0D25;
  text-align: center;
  margin-bottom: 10px;
  font-size: 2.2rem;
  font-weight: 800;
  letter-spacing: -1px;
}

.description-page {
  text-align: center;
  color: #4E7D96;
  margin-bottom: 40px;
  font-size: 1.1rem;
}

.sous-titre {
  color: #0A0D25;
  margin-bottom: 25px;
  font-size: 1.4rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 10px;
}

.sous-titre::before {
  content: '';
  display: block;
  width: 6px;
  height: 24px;
  background: #FF844B;
  border-radius: 4px;
}

.section {
  margin-bottom: 35px;
}

/* Forms */
.form-group {
  margin-bottom: 25px;
}

.form-row {
  display: flex;
  gap: 20px;
}

.half {
  flex: 1;
}

.form-group label {
  display: block;
  color: #4E7D96;
  font-weight: 600;
  font-size: 0.95rem;
  margin-bottom: 10px;
  margin-left: 5px;
}

.form-input, .form-select {
  width: 100%;
  padding: 16px 20px;
  border: 2px solid #E3EDF2;
  border-radius: 16px;
  background: #F8FBFC;
  color: #0A0D25;
  font-size: 1rem;
  transition: all 0.3s ease;
  box-sizing: border-box;
}

.form-input::placeholder {
  color: #A0B4C0;
}

.form-input:focus, .form-select:focus {
  outline: none;
  border-color: #4E7D96;
  background: #ffffff;
  box-shadow: 0 0 0 4px rgba(78, 125, 150, 0.1);
}

.form-input:disabled {
  background: #E3EDF2;
  color: #7A909C;
  cursor: not-allowed;
  border-color: transparent;
}

/* Custom Select Styling */
.select-wrapper {
  position: relative;
}

.select-wrapper::after {
  content: '▼';
  font-size: 0.8rem;
  color: #4E7D96;
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: none;
}

.form-select {
  appearance: none;
  cursor: pointer;
}

/* Buttons */
.actions {
  margin-top: 30px;
}

.btn {
  width: 100%;
  padding: 18px;
  border: none;
  border-radius: 18px;
  font-size: 1.1rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  letter-spacing: 0.5px;
  margin-top: 10px;
}

.btn-main {
  background: #FF844B;
  color: white;
  box-shadow: 0 10px 25px rgba(255, 132, 75, 0.3);
}

.btn-main:hover:not(:disabled) {
  background: #ff722e;
  transform: translateY(-3px);
  box-shadow: 0 15px 30px rgba(255, 132, 75, 0.4);
}

.btn-main:disabled {
  opacity: 0.7;
  cursor: wait;
  box-shadow: none;
}

.btn-ghost {
  background: #E3EDF2;
  color: #4E7D96;
  margin-top: 15px;
}

.btn-ghost:hover {
  background: #dbe7ed;
  color: #0A0D25;
}

/* Alerts & Messages */
.message {
  padding: 18px;
  border-radius: 16px;
  margin-bottom: 30px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 0.95rem;
}

.erreur {
  background: #FFF0ED; /* Light version of orange/red */
  color: #D64545;
  border: 1px solid rgba(214, 69, 69, 0.1);
}

.succes {
  background: #E8F5E9;
  color: #2E7D32;
  border: 1px solid rgba(46, 125, 50, 0.1);
}

.info-box {
  background: #E3EDF2;
  border-radius: 16px;
  padding: 20px;
  display: flex;
  gap: 15px;
  align-items: flex-start;
  color: #4E7D96;
}

.info-icon {
  font-size: 1.2rem;
}

.info-box p {
  margin: 0;
  font-size: 0.95rem;
  line-height: 1.5;
}

/* Transitions */
.fade-enter-active, .fade-leave-active,
.slide-fade-enter-active, .slide-fade-leave-active {
  transition: all 0.4s ease;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

.slide-fade-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

.slide-fade-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}

/* Responsive */
@media (max-width: 600px) {
  .container {
    padding: 30px 24px;
    margin: 0 16px;
  }
  
  .titre {
    font-size: 1.8rem;
  }
  
  .form-row {
    flex-direction: column;
    gap: 0;
  }

  .btn {
    padding: 16px;
  }
}
</style>
