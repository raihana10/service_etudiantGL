<script setup>
import { ref, computed, watch } from 'vue'
import etudiantService from '../../services/etudiant'
import ErrorMessage from '../shared/ErrorMessage.vue'

// Variables réactives
const email = ref('')
const numApogee = ref('')
const cin = ref('')
const typeDocument = ref('')
const informationsSupplementaires = ref({})
const erreur = ref('')
const succes = ref('')
const loading = ref(false)
const estVerifie = ref(false)
const etudiantVerifie = ref(null)

// Variables pour la recherche de demande
const numDemandeRecherche = ref('')
const demandeRecherchee = ref(null)
const erreurRecherche = ref('')
const loadingRecherche = ref(false)

// Types de documents disponibles
const typesDocuments = etudiantService.getTypesDocuments()

// Computed pour vérifier si tous les champs sont remplis
const tousChampsRemplis = computed(() => {
  return email.value && numApogee.value && cin.value
})

// Computed pour vérifier si l'étudiant est vérifié
const etudiantEstVerifie = computed(() => etudiantVerifie.value !== null)

// Computed pour vérifier si c'est une réclamation
const estReclamation = computed(() => {
  console.log('estReclamation computed:', typeDocument.value === 'Reclamation', 'typeDocument.value:', typeDocument.value)
  return typeDocument.value === 'Reclamation'
})

// Computed pour vérifier si c'est une convention de stage
const estConventionStage = computed(() => {
  console.log('Est ConventionStage?', typeDocument.value === 'ConventionStage')
  return typeDocument.value === 'ConventionStage'
})

// Computed pour vérifier si c'est un relevé de notes
const estReleveNote = computed(() => {
  return typeDocument.value === 'ReleveNote'
})

// Computed pour afficher les champs supplémentaires
const afficherChampsSupplementaires = computed(() => {
  return estVerifie.value && typeDocument.value && !estReclamation.value && !estConventionStage.value && !estReleveNote.value
})

// Computed pour vérifier si l'étudiant a un diplôme pour l'attestation de réussite
const aDiplomePourAttestation = computed(() => {
  if (!estVerifie.value) return false
  if (typeDocument.value !== 'AttestationReussite') return true
  return etudiantVerifie.value.anneeObtentionDiplome !== null && etudiantVerifie.value.anneeObtentionDiplome !== undefined
})

// Computed pour vérifier si le bouton doit être désactivé
const boutonDesactive = computed(() => {
  if (!typeDocument.value) return true
  if (estReclamation.value && !champsReclamationRemplis.value) return true
  return false
})

// Computed pour vérifier si les champs de réclamation sont remplis
const champsReclamationRemplis = computed(() => {
  if (!estReclamation.value) return true
  return informationsSupplementaires.value.sujet && informationsSupplementaires.value.description
})

// Watcher pour vérification automatique
watch([email, numApogee, cin], () => {
  if (tousChampsRemplis.value && !estVerifie.value && !loading.value) {
    verifierEtudiant()
  }
})

// Watcher pour réinitialiser les informations supplémentaires quand le type change
watch(typeDocument, () => {
  informationsSupplementaires.value = {}
  erreur.value = ''
  succes.value = ''
  
  // Vérifier automatiquement l'année d'obtention pour l'attestation de réussite
  if (typeDocument.value === 'AttestationReussite' && estVerifie.value) {
    verifierAnneeObtention()
  }
})

// Fonction pour vérifier l'année d'obtention
const verifierAnneeObtention = async () => {
  if (!etudiantVerifie.value) return
  
  try {
    const response = await etudiantService.verifierAttestationReussite(etudiantVerifie.value.idEtudiant)
    
    if (!response.data.has_diploma) {
      erreur.value = 'Vous n\'avez pas encore obtenu votre diplôme. Cette attestation n\'est disponible que pour les diplômés.'
    }
  } catch (error) {
    console.error('Erreur lors de la vérification du diplôme:', error)
  }
}

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
      estVerifie.value = true
      succes.value = 'Informations vérifiées avec succès!'
    } else {
      erreur.value = response.data.message
    }
  } catch (error) {
    if (error.response && error.response.status === 404) {
      erreur.value = error.response.data.message || 'Informations incorrectes. Vérifiez vos données.'
    } else {
      erreur.value = 'Erreur de connexion au serveur'
    }
    console.error('Erreur:', error)
  } finally {
    loading.value = false
  }
}

// Fonction pour formater les dates
const formatDate = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Fonction pour rechercher une demande
const rechercherDemande = async () => {
  console.log('Début de la recherche - numéro:', numDemandeRecherche.value)
  
  if (!numDemandeRecherche.value) {
    console.log('Aucun numéro de demande fourni')
    return
  }
  
  loadingRecherche.value = true
  erreurRecherche.value = ''
  demandeRecherchee.value = null
  
  try {
    console.log('Appel du service de recherche...')
    const response = await etudiantService.rechercherDemandeParNumero(numDemandeRecherche.value)
    console.log('Réponse reçue:', response)
    
    if (response.data.success) {
      console.log('Succès - demande trouvée:', response.data.demande)
      demandeRecherchee.value = response.data.demande
    } else {
      console.log('Échec - message:', response.data.message)
      erreurRecherche.value = response.data.message
    }
  } catch (error) {
    console.error('Erreur lors de la recherche:', error)
    console.error('Détails de l\'erreur:', error.response)
    console.error('Status:', error.response?.status)
    console.error('Data:', error.response?.data)
    console.error('Message:', error.message)
    erreurRecherche.value = 'Erreur lors de la recherche de la demande'
  } finally {
    loadingRecherche.value = false
  }
}

// Fonction de soumission de la demande
const soumettreDemande = async () => {
  console.log('Début de soumettreDemande')
  console.log('typeDocument.value:', typeDocument.value)
  console.log('etudiantVerifie.value:', etudiantVerifie.value)
  console.log('estReclamation.value:', estReclamation.value)
  
  if (!typeDocument.value) {
    erreur.value = 'Veuillez choisir un type de document'
    return
  }

  if (!champsReclamationRemplis.value) {
    erreur.value = 'Veuillez remplir tous les champs de la réclamation'
    return
  }

  console.log('Validation passée, début loading')
  loading.value = true
  erreur.value = ''
  succes.value = ''

  try {
    let response
    
    // Vérifier si l'étudiant est vérifié, sinon utiliser une valeur par défaut
    if (!etudiantVerifie.value) {
      console.log('étudiant non vérifié')
      erreur.value = 'Veuillez vérifier vos informations d\'abord'
      return
    }
    
    console.log('Appel API en cours...')
    console.log('ID étudiant:', etudiantVerifie.value.idEtudiant)
    console.log('Type document:', typeDocument.value)
    console.log('Informations:', informationsSupplementaires.value)
    
    if (estReclamation.value) {
      // Soumettre une réclamation
      console.log('Création réclamation')
      response = await etudiantService.creerReclamation(
        etudiantVerifie.value.idEtudiant,
        informationsSupplementaires.value
      )
    } else {
      // Soumettre une demande normale
      console.log('Création demande normale - avant appel API')
      response = await etudiantService.creerDemande(
        etudiantVerifie.value.idEtudiant,
        typeDocument.value,
        informationsSupplementaires.value
      )
      console.log('Création demande normale - après appel API')
    }
    
    console.log('Réponse API reçue:', response)

    if (response.data.success) {
      if (estReclamation.value) {
        succes.value = 'Réclamation créée avec succès! Référence: ' + response.data.reclamation.idReclamation
      } else {
        succes.value = 'Demande créée avec succès! Référence: ' + response.data.demande.idDemande
      }
      // Réinitialiser le formulaire
      reinitialiserFormulaire()
    } else {
      // Gérer le cas spécial pour l'attestation de réussite
      if (response.data.has_diploma === false) {
        erreur.value = 'Vous n\'avez pas encore obtenu votre diplôme. Cette attestation n\'est disponible que pour les diplômés.'
      } else {
        erreur.value = response.data.message
      }
    }
  } catch (error) {
    if (error.response && error.response.status === 422) {
      // Erreur de validation spéciale (ex: pas de diplôme)
      if (error.response.data.has_diploma === false) {
        erreur.value = 'Vous n\'avez pas encore obtenu votre diplôme. Cette attestation n\'est disponible que pour les diplômés.'
      } else {
        erreur.value = error.response.data.message || 'Erreur de validation'
      }
    } else if (error.response && error.response.status === 404) {
      erreur.value = error.response.data.message || 'Informations incorrectes. Vérifiez vos données.'
    } else {
      erreur.value = 'Erreur lors de la création de la ' + (estReclamation.value ? 'réclamation' : 'demande')
    }
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
  estVerifie.value = false
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
        
        <!-- Section de recherche de demande -->
        <div class="section search-section">
          <h2 class="sous-titre">Rechercher une demande</h2>
          
          <div class="search-wrapper">
            <div class="search-input-container">
              <label for="numDemandeRecherche" class="search-label">Numéro de demande</label>
              <div class="search-input-group">
                <input 
                  id="numDemandeRecherche"
                  v-model="numDemandeRecherche" 
                  type="text" 
                  placeholder="Ex: DEM-2025-000123"
                  class="search-input"
                />
                <button 
                  @click="rechercherDemande" 
                  :disabled="!numDemandeRecherche || loadingRecherche"
                  class="search-button"
                >
                  {{ loadingRecherche ? 'Recherche...' : 'Rechercher' }}
                </button>
              </div>
            </div>
          </div>
          
          <!-- Affichage du résultat de recherche -->
          <transition name="fade">
            <div v-if="demandeRecherchee" class="resultat-recherche">
              <p><strong>Statut:</strong> 
                <span :class="['statut', demandeRecherchee.statut.toLowerCase()]">
                  {{ demandeRecherchee.statut }}
                </span>
              </p>
            </div>
          </transition>
          
          <transition name="fade">
            <div v-if="erreurRecherche" class="message erreur" role="alert">
              {{ erreurRecherche }}
            </div>
          </transition>
        </div>
        
        <!-- Messages d'erreur et succès -->
        <transition name="fade">
          <div v-if="succes" class="message succes" role="alert">
            {{ succes }}
          </div>
        </transition>

        <!-- Section de vérification -->
        <div class="section">
          <h2 class="sous-titre">Identification</h2>
          
          <div style="margin-bottom: 15px;"></div>
          
          <!-- Message d'erreur général -->
          <transition name="fade">
            <ErrorMessage v-if="erreur" :message="erreur" />
          </transition>
          
          <div style="margin-bottom: 20px;"></div>
          
          <div class="form-group">
            <label for="email">Adresse email institutionnelle</label>
            <input 
              id="email"
              v-model="email" 
              type="email" 
              placeholder="exemple@univ-tn.tn"
              :disabled="false"
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
                :disabled="false"
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
                :disabled="false"
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

        <!-- Champs spécifiques pour les réclamations -->
        <transition name="slide-fade">
          <div v-if="estReclamation" class="section">
            <h2 class="sous-titre">Détails de la réclamation</h2>
            
            <div class="form-group">
              <label for="sujet">Sujet de la réclamation</label>
              <input 
                id="sujet"
                v-model="informationsSupplementaires.sujet" 
                type="text" 
                placeholder="Ex: Problème avec mon attestation de réussite"
                class="form-input"
                required
              />
            </div>

            <div class="form-group">
              <label for="numDocument">Numéro de document </label>
              <input 
                id="numDocument"
                v-model="informationsSupplementaires.numDocument" 
                type="text" 
                placeholder="Ex: 2025-00123"
                class="form-input"
              />
            </div>

            <div class="form-group">
              <label for="description">Description détaillée</label>
              <textarea 
                id="description"
                v-model="informationsSupplementaires.description" 
                placeholder="Décrivez votre problème en détail..."
                class="form-textarea"
                rows="5"
                required
              ></textarea>
            </div>
          </div>
        </transition>

        <!-- Champs spécifiques pour la convention de stage -->
        <transition name="slide-fade">
          <div v-if="estConventionStage" class="section">
            <h2 class="sous-titre">Informations sur l'entreprise</h2>
            
            <div class="form-group">
              <label for="cosocialreason">Raison sociale de l'entreprise</label>
              <input 
                id="cosocialreason"
                v-model="informationsSupplementaires.cosocialreason" 
                type="text" 
                placeholder="Ex: Société Marocaine d'Informatique"
                class="form-input"
                required
              />
            </div>

            <div class="form-group">
              <label for="sector">Secteur de l'entreprise</label>
              <input 
                id="sector"
                v-model="informationsSupplementaires.sector" 
                type="text" 
                placeholder="Ex: Informatique, Télécommunications, Industrie..."
                class="form-input"
                required
              />
            </div>

            <div class="form-group">
              <label for="cophone">Téléphone de l'entreprise</label>
              <input 
                id="cophone"
                v-model="informationsSupplementaires.cophone" 
                type="tel" 
                placeholder="Ex: +212 6 12 34 56 78"
                class="form-input"
                required
              />
            </div>

            <div class="form-group">
              <label for="comail">Email de l'entreprise</label>
              <input 
                id="comail"
                v-model="informationsSupplementaires.comail" 
                type="email" 
                placeholder="Ex: contact@entreprise.ma"
                class="form-input"
                required
              />
            </div>

            <div class="form-group">
              <label for="coaddress">Adresse de l'entreprise</label>
              <input 
                id="coaddress"
                v-model="informationsSupplementaires.coaddress" 
                type="text" 
                placeholder="Rue, quartier, immeuble..."
                class="form-input"
                required
              />
            </div>

            <div class="form-group">
              <label for="city">Ville de l'entreprise</label>
              <select 
                id="city"
                v-model="informationsSupplementaires.city" 
                class="form-select"
                required
              >
                <option value="" disabled selected>-- Choisissez une ville --</option>
                <option value="Agadir">Agadir</option>
                <option value="Al Hoceima">Al Hoceima</option>
                <option value="Assilah">Assilah</option>
                <option value="Azemmour">Azemmour</option>
                <option value="Beni Mellal">Beni Mellal</option>
                <option value="Berkane">Berkane</option>
                <option value="Berrechid">Berrechid</option>
                <option value="Boujdour">Boujdour</option>
                <option value="Boulemane">Boulemane</option>
                <option value="Casablanca">Casablanca</option>
                <option value="Chefchaouen">Chefchaouen</option>
                <option value="Dakhla">Dakhla</option>
                <option value="El Jadida">El Jadida</option>
                <option value="Errachidia">Errachidia</option>
                <option value="Essaouira">Essaouira</option>
                <option value="Fès">Fès</option>
                <option value="Figuig">Figuig</option>
                <option value="Guelmim">Guelmim</option>
                <option value="Guercif">Guercif</option>
                <option value="Ifrane">Ifrane</option>
                <option value="Jerada">Jerada</option>
                <option value="Kénitra">Kénitra</option>
                <option value="Khemisset">Khemisset</option>
                <option value="Khouribga">Khouribga</option>
                <option value="Laayoune">Laayoune</option>
                <option value="Larache">Larache</option>
                <option value="Marrakech">Marrakech</option>
                <option value="Meknès">Meknès</option>
                <option value="Midelt">Midelt</option>
                <option value="Mohammedia">Mohammedia</option>
                <option value="Nador">Nador</option>
                <option value="Ouarzazate">Ouarzazate</option>
                <option value="Oujda">Oujda</option>
                <option value="Rabat">Rabat</option>
                <option value="Safi">Safi</option>
                <option value="Salé">Salé</option>
                <option value="Sebou">Sebou</option>
                <option value="Settat">Settat</option>
                <option value="Sidi Ifni">Sidi Ifni</option>
                <option value="Sidi Kacem">Sidi Kacem</option>
                <option value="Sidi Slimane">Sidi Slimane</option>
                <option value="Tan-Tan">Tan-Tan</option>
                <option value="Tanger">Tanger</option>
                <option value="Taourirt">Taourirt</option>
                <option value="Taroudant">Taroudant</option>
                <option value="Taza">Taza</option>
                <option value="Tétouan">Tétouan</option>
                <option value="Tiznit">Tiznit</option>
              </select>
            </div>

            <h2 class="sous-titre">Représentants de l'entreprise</h2>

            <div class="form-group">
              <label for="corepresentative">Représentant de l'entreprise</label>
              <input 
                id="corepresentative"
                v-model="informationsSupplementaires.corepresentative" 
                type="text" 
                placeholder="Nom et prénom"
                class="form-input"
                required
              />
            </div>

            <div class="form-group">
              <label for="corepresentativefunction">Fonction du représentant</label>
              <input 
                id="corepresentativefunction"
                v-model="informationsSupplementaires.corepresentativefunction" 
                type="text" 
                placeholder="Ex: Directeur, Responsable RH..."
                class="form-input"
                required
              />
            </div>

            <div class="form-group">
              <label for="cosupervisor">Encadrant de l'entreprise</label>
              <input 
                id="cosupervisor"
                v-model="informationsSupplementaires.cosupervisor" 
                type="text" 
                placeholder="Nom et prénom"
                class="form-input"
                required
              />
            </div>

            <div class="form-group">
              <label for="cosupervisorfunction">Fonction de l'encadrant de l'entreprise</label>
              <input 
                id="cosupervisorfunction"
                v-model="informationsSupplementaires.cosupervisorfunction" 
                type="text" 
                placeholder="Ex: Ingénieur logiciel, Chef de projet..."
                class="form-input"
                required
              />
            </div>

            <div class="form-group">
              <label for="cosupervisorphone">Téléphone de l'encadrant de l'entreprise</label>
              <input 
                id="cosupervisorphone"
                v-model="informationsSupplementaires.cosupervisorphone" 
                type="tel" 
                placeholder="Ex: +212 6 XX XX XX XX"
                class="form-input"
                required
              />
            </div>

            <div class="form-group">
              <label for="cosupervisormail">Email de l'encadrant de l'entreprise</label>
              <input 
                id="cosupervisormail"
                v-model="informationsSupplementaires.cosupervisormail" 
                type="email" 
                placeholder="Ex: prenom.nom@entreprise.ma"
                class="form-input"
                required
              />
            </div>

            <h2 class="sous-titre">Encadrement académique</h2>

            <div class="form-group">
              <label for="ensasupervisor">Encadrant de l'ENSA</label>
              <select 
                id="ensasupervisor"
                v-model="informationsSupplementaires.ensasupervisor" 
                class="form-select"
                required
              >
                <option value="" disabled selected>-- Choisissez un encadrant --</option>
                <option value="Pr. STITOU Mostafa">Pr. STITOU Mostafa</option>
                <option value="Pr. BENKADDOUR Abdelhamid">Pr. BENKADDOUR Abdelhamid</option>
                <option value="Pr. ABTOY Anouar">Pr. ABTOY Anouar</option>
                <option value="Pr. ACHEGAF Zineb">Pr. ACHEGAF Zineb</option>
                <option value="Pr. AGHZOUT Otman">Pr. AGHZOUT Otman</option>
                <option value="Pr. AL ACHHAB Mohammed">Pr. AL ACHHAB Mohammed</option>
                <option value="Pr. AMELLAL Issam">Pr. AMELLAL Issam</option>
                <option value="Pr. AOULAD BENZEROUALA Rachad">Pr. AOULAD BENZEROUALA Rachad</option>
                <option value="Pr. ARIOUA Mounir">Pr. ARIOUA Mounir</option>
                <option value="Pr. ATTAJKANI Sabri">Pr. ATTAJKANI Sabri</option>
                <option value="Pr. AYTOUNA Fouad">Pr. AYTOUNA Fouad</option>
                <option value="Pr. AZEROUAL Boubker">Pr. AZEROUAL Boubker</option>
                <option value="Pr. BANNOUR Abdelilah">Pr. BANNOUR Abdelilah</option>
                <option value="Pr. EL-BAKARI Abdelali">Pr. EL-BAKARI Abdelali</option>
                <option value="Pr. BENBOUBKER Mohamed Badr">Pr. BENBOUBKER Mohamed Badr</option>
                <option value="Pr. BENTAJER Ahmed">Pr. BENTAJER Ahmed</option>
                <option value="Pr. BESRI Zineb">Pr. BESRI Zineb</option>
                <option value="Pr. BOLAJRAF Mohamed">Pr. BOLAJRAF Mohamed</option>
                <option value="Pr. BOUNAB Loubna">Pr. BOUNAB Loubna</option>
                <option value="Pr. CHAKKOR Otman">Pr. CHAKKOR Otman</option>
                <option value="Pr. CHEKOURI Mohamed Yassin">Pr. CHEKOURI Mohamed Yassin</option>
                <option value="Pr. CHERKAOUI Mohamed">Pr. CHERKAOUI Mohamed</option>
                <option value="Pr. CHRAYAH Mohamed">Pr. CHRAYAH Mohamed</option>
                <option value="Pr. DKHISSI Btissam">Pr. DKHISSI Btissam</option>
                <option value="Pr. ECH-CHERRAT Mohamed Reda">Pr. ECH-CHERRAT Mohamed Reda</option>
                <option value="Pr. EL ADIB Samir">Pr. EL ADIB Samir</option>
                <option value="Pr. EL BIARI Aouatef">Pr. EL BIARI Aouatef</option>
                <option value="Pr. EL BOUHDIDI Jaber">Pr. EL BOUHDIDI Jaber</option>
                <option value="Pr. EL FALLAHI Abdellah">Pr. EL FALLAHI Abdellah</option>
                <option value="Pr. EL HAJJAMY Oussama">Pr. EL HAJJAMY Oussama</option>
                <option value="Pr. EL KADDOURI Abdelmonaim">Pr. EL KADDOURI Abdelmonaim</option>
                <option value="Pr. EL KHAMLICHI Yasser">Pr. EL KHAMLICHI Yasser</option>
                <option value="Pr. EL KHANNOUSSI Fadoua">Pr. EL KHANNOUSSI Fadoua</option>
                <option value="Pr. EL YOUNOUSSI Yacine">Pr. EL YOUNOUSSI Yacine</option>
                <option value="Pr. HADDI Ali">Pr. HADDI Ali</option>
                <option value="Pr. HAJJAJI Anas">Pr. HAJJAJI Anas</option>
                <option value="Pr. JOURANI Reda">Pr. JOURANI Reda</option>
                <option value="Pr. KHAMLICHI Abdellatif">Pr. KHAMLICHI Abdellatif</option>
                <option value="Pr. KHOULJI Samira">Pr. KHOULJI Samira</option>
                <option value="Pr. LAJJAM Azza">Pr. LAJJAM Azza</option>
                <option value="Pr. LOULY Adil">Pr. LOULY Adil</option>
                <option value="Pr. MAHBOUB Oussama">Pr. MAHBOUB Oussama</option>
                <option value="Pr. MEDOURI Abdellatif">Pr. MEDOURI Abdellatif</option>
                <option value="Pr. MESMOUDI Yasser">Pr. MESMOUDI Yasser</option>
                <option value="Pr. NASRI Khalid">Pr. NASRI Khalid</option>
                <option value="Pr. OUAKKASSE Noureddine">Pr. OUAKKASSE Noureddine</option>
                <option value="Pr. RAISSOUNI Naoufal">Pr. RAISSOUNI Naoufal</option>
                <option value="Pr. SANBI Mustapha">Pr. SANBI Mustapha</option>
                <option value="Pr. SARIR Hicham">Pr. SARIR Hicham</option>
                <option value="Pr. SEGHIOUER Hamid">Pr. SEGHIOUER Hamid</option>
                <option value="Pr. TAHIRI Abderrahim">Pr. TAHIRI Abderrahim</option>
                <option value="Pr. ZAKRITI Alia">Pr. ZAKRITI Alia</option>
                <option value="Pr. ZLAIJI Loubna">Pr. ZLAIJI Loubna</option>
              </select>
            </div>

            <h2 class="sous-titre">Informations du stage</h2>

            <div class="form-row">
              <div class="form-group half">
                <label for="internship_start_date">Stage du</label>
                <input 
                  id="internship_start_date"
                  v-model="informationsSupplementaires.internship_start_date" 
                  type="date" 
                  class="form-input"
                  required
                />
              </div>

              <div class="form-group half">
                <label for="internship_end_date">Stage au</label>
                <input 
                  id="internship_end_date"
                  v-model="informationsSupplementaires.internship_end_date" 
                  type="date" 
                  class="form-input"
                  required
                />
              </div>
            </div>

            <div class="form-group">
              <label for="internship_subject">Sujet du stage</label>
              <textarea 
                id="internship_subject"
                v-model="informationsSupplementaires.internship_subject" 
                placeholder="Description courte et claire du thème du stage..."
                class="form-textarea"
                rows="3"
                required
              ></textarea>
            </div>
          </div>
        </transition>

        <!-- Champs spécifiques pour le relevé de notes -->
        <transition name="slide-fade">
          <div v-if="estReleveNote" class="section">
            <h2 class="sous-titre">Informations académiques</h2>
            
            <div class="form-group">
              <label for="annee_universitaire">Année universitaire</label>
              <select 
                id="annee_universitaire"
                v-model="informationsSupplementaires.annee_universitaire" 
                class="form-select"
                required
              >
                <option value="" disabled selected>-- Choisissez l'année universitaire --</option>
                <option value="2024-2025">2024-2025</option>
                <option value="2023-2024">2023-2024</option>
                <option value="2022-2023">2022-2023</option>
                <option value="2021-2022">2021-2022</option>
                <option value="2020-2021">2020-2021</option>
                <option value="2019-2020">2019-2020</option>
                <option value="2018-2019">2018-2019</option>
                <option value="2017-2018">2017-2018</option>
                <option value="2016-2017">2016-2017</option>
                <option value="2015-2016">2015-2016</option>
                <option value="2014-2015">2014-2015</option>
                <option value="2013-2014">2013-2014</option>
                <option value="2012-2013">2012-2013</option>
                <option value="2011-2012">2011-2012</option>
                <option value="2010-2011">2010-2011</option>
                <option value="2009-2010">2009-2010</option>
                <option value="2008-2009">2008-2009</option>
              </select>
            </div>
          </div>
        </transition>

        <!-- Informations supplémentaires pour documents normaux -->
        <transition name="slide-fade">
          <div v-if="afficherChampsSupplementaires" class="section">
            <div class="info-box">
              <span class="info-icon">ℹ️</span>
              <p>Les informations nécessaires seront automatiquement ajoutées à votre demande.</p>
            </div>
          </div>
        </transition>

        <!-- Message d'erreur pour attestation de réussite -->
        <transition name="fade">
          <div v-if="erreur && typeDocument === 'AttestationReussite' && estVerifie && (etudiantVerifie.anneeObtentionDiplome === null || etudiantVerifie.anneeObtentionDiplome === undefined || etudiantVerifie.anneeObtentionDiplome === '')" class="message erreur" role="alert">
            {{ erreur }}
          </div>
        </transition>

        <!-- Bouton de soumission -->
        <transition name="slide-fade">
          <div v-if="typeDocument" class="actions">
            <button 
              @click="soumettreDemande" 
              :disabled="boutonDesactive || loading"
              class="btn btn-main"
            >
              {{ loading ? 'Traitement en cours...' : estReclamation ? 'Envoyer la réclamation' : 'Envoyer la demande' }}
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

.form-input, .form-select, .form-textarea {
  width: 100%;
  padding: 16px 20px;
  border: 2px solid #E3EDF2;
  border-radius: 16px;
  background: #F8FBFC;
  color: #0A0D25;
  font-size: 1rem;
  transition: all 0.3s ease;
  box-sizing: border-box;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.form-textarea {
  resize: vertical;
  min-height: 120px;
  line-height: 1.6;
}

.form-input::placeholder, .form-textarea::placeholder {
  color: #A0B4C0;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
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

/* Search Section Styles */
.search-section {
  background: linear-gradient(135deg, #f8fafb 0%, #e3edf2 100%);
  border-radius: 20px;
  padding: 25px;
  margin-bottom: 30px;
  border: 1px solid #e3edf2;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.search-wrapper {
  max-width: 600px;
  margin: 0 auto;
}

.search-input-container {
  width: 100%;
}

.search-label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #2c3e50;
  font-size: 0.95rem;
}

.search-input-group {
  display: flex;
  gap: 12px;
  align-items: stretch;
}

.search-input {
  flex: 1;
  padding: 14px 18px;
  border: 2px solid #e3edf2;
  border-radius: 12px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: white;
  outline: none;
}

.search-input:focus {
  border-color: #FF844B;
  box-shadow: 0 0 0 3px rgba(255, 132, 75, 0.1);
  transform: translateY(-1px);
}

.search-input::placeholder {
  color: #8ca0b3;
}

.search-button {
  padding: 14px 24px;
  background: #FF844B;
  color: white;
  border: none;
  border-radius: 12px;
  font-weight: 600;
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.3s ease;
  white-space: nowrap;
  min-width: 120px;
  box-shadow: 0 4px 15px rgba(255, 132, 75, 0.2);
}

.search-button:hover:not(:disabled) {
  background: #ff722e;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(255, 132, 75, 0.3);
}

.search-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.resultat-recherche {
  margin-top: 20px;
  padding: 15px 20px;
  background: white;
  border-radius: 12px;
  border-left: 4px solid #FF844B;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.resultat-recherche p {
  margin: 0;
  font-size: 1rem;
}

.resultat-recherche .statut {
  font-weight: 600;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.9rem;
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
  cursor: not-allowed;
  box-shadow: none;
}

.btn-ghost {
  background: #E3EDF2;
  color: #4E7D96;
  margin-top: 15px;
}

.btn-ghost:hover:not(:disabled) {
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
  
  /* Search responsive styles */
  .search-section {
    padding: 20px;
    margin-bottom: 25px;
  }
  
  .search-input-group {
    flex-direction: column;
    gap: 12px;
  }
  
  .search-input {
    padding: 16px;
  }
  
  .search-button {
    padding: 16px;
    min-width: auto;
    width: 100%;
  }
  
  .resultat-recherche {
    padding: 12px 16px;
  }
}
</style>