import api from './api'

export default {
  // Vérifier les informations de l'étudiant
  verifier(email, numApogee, cin) {
    return api.post('/etudiant/verifier', {
      email,
      numApogee,
      cin
    })
  },

  // Créer une nouvelle demande
  creerDemande(idEtudiant, typeDoc, informations = {}) {
    return api.post('/etudiant/demande', {
      idEtudiant,
      typeDoc,
      informations
    })
  },

  // Créer une réclamation
  creerReclamation(idEtudiant, informations) {
    return api.post('/etudiant/reclamation', {
      idEtudiant,
      sujet: informations.sujet || informations.objet || '',
      description: informations.description || informations.message || ''
    })
  },

  // Rechercher une demande par numéro
  rechercherDemandeParNumero(numDemande) {
    console.log('Service: recherche de demande avec numéro:', numDemande)
    const response = api.post('/etudiant/rechercher-demande', {
      num_demande: numDemande
    })
    console.log('Service: réponse API:', response)
    return response
  },

  // Vérifier si l'étudiant peut demander une attestation de réussite
  verifierAttestationReussite(idEtudiant) {
    return api.post('/etudiant/verifier-attestation-reussite', {
      idEtudiant
    })
  },

  // Types de documents disponibles
  getTypesDocuments() {
    return [
      { value: 'AttestationScolarite', label: 'Attestation de scolarité' },
      { value: 'AttestationReussite', label: 'Attestation de réussite' },
      { value: 'ReleveNote', label: 'Relevé de notes' },
      { value: 'ConventionStage', label: 'Convention de stage' },
      { value: 'Reclamation', label: 'Réclamation' }
    ]
  }
}
