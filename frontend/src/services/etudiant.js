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
