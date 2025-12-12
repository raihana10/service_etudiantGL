<template>
  <div class="home-view">
    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-content">
        <h1>Service Étudiant</h1>
        <p>Plateforme de Gestion des Attestations et Services Étudiants</p>
        <div class="hero-actions">
          <router-link to="/etudiant/access" class="btn-primary">
            <i class="fas fa-user-graduate"></i>
            Espace Étudiant
          </router-link>
          <router-link to="/admin/login" class="btn-secondary">
            <i class="fas fa-user-shield"></i>
            Espace Admin
          </router-link>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="features">
      <div class="container">
        <h2>Nos Services</h2>
        <div class="features-grid">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-file-alt"></i>
            </div>
            <h3>Attestations</h3>
            <p>Générez rapidement vos attestations de scolarité, de réussite et relevés de notes</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-briefcase"></i>
            </div>
            <h3>Conventions de Stage</h3>
            <p>Créez vos conventions de stage en quelques clics pour vos entreprises</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3>Réclamations</h3>
            <p>Soumettez vos réclamations et suivez leur traitement en temps réel</p>
          </div>
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-chart-line"></i>
            </div>
            <h3>Suivi</h3>
            <p>Suivez l'état de vos demandes et téléchargez vos documents validés</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Info Section -->
    <section class="info">
      <div class="container">
        <div class="info-content">
          <div class="info-text">
            <h2>Comment ça marche ?</h2>
            <ol>
              <li>Connectez-vous avec vos informations étudiantes</li>
              <li>Choisissez le service dont vous avez besoin</li>
              <li>Suivez le traitement de votre demande</li>
              <li>Téléchargez vos documents une fois validés</li>
            </ol>
          </div>
          <div class="info-image">
            <i class="fas fa-graduation-cap"></i>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section class="contact">
      <div class="container">
        <h2>Besoin d'aide ?</h2>
        <p>Contactez le service scolarité pour toute assistance</p>
        <div class="contact-info">
          <div class="contact-item">
            <i class="fas fa-envelope"></i>
            <span>scolarite@university.ma</span>
          </div>
          <div class="contact-item">
            <i class="fas fa-phone"></i>
            <span>+212 5XX XXX XXX</span>
          </div>
          <div class="contact-item">
            <i class="fas fa-map-marker-alt"></i>
            <span>Bâtiment A, 1er étage</span>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
// Home page logic
</script>

<style scoped>
.home-view {
  min-height: 100vh;
}

.hero {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 80px 20px;
  text-align: center;
}

.hero-content h1 {
  margin: 0 0 20px;
  font-size: 3rem;
  font-weight: bold;
}

.hero-content p {
  margin: 0 0 40px;
  font-size: 1.2rem;
  opacity: 0.9;
}

.hero-actions {
  display: flex;
  gap: 20px;
  justify-content: center;
  flex-wrap: wrap;
}

.btn-primary, .btn-secondary {
  padding: 15px 30px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 10px;
  transition: all 0.3s ease;
}

.btn-primary {
  background: white;
  color: #667eea;
}

.btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 20px rgba(255, 255, 255, 0.2);
}

.btn-secondary {
  background: rgba(255, 255, 255, 0.2);
  color: white;
  border: 2px solid white;
}

.btn-secondary:hover {
  background: rgba(255, 255, 255, 0.3);
  transform: translateY(-3px);
}

.features {
  padding: 80px 20px;
  background: #f8f9fa;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
}

.features h2 {
  text-align: center;
  margin: 0 0 50px;
  font-size: 2rem;
  color: #333;
}

.features-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 30px;
}

.feature-card {
  background: white;
  padding: 30px;
  border-radius: 12px;
  text-align: center;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
}

.feature-card:hover {
  transform: translateY(-5px);
}

.feature-icon {
  width: 60px;
  height: 60px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 20px;
  color: white;
  font-size: 1.5rem;
}

.feature-card h3 {
  margin: 0 0 15px;
  color: #333;
  font-size: 1.2rem;
}

.feature-card p {
  margin: 0;
  color: #666;
  line-height: 1.5;
}

.info {
  padding: 80px 20px;
  background: white;
}

.info-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 50px;
  align-items: center;
}

.info-text h2 {
  margin: 0 0 30px;
  color: #333;
  font-size: 1.8rem;
}

.info-text ol {
  margin: 0;
  padding-left: 20px;
  color: #666;
  line-height: 1.8;
}

.info-text li {
  margin-bottom: 15px;
  font-size: 1.1rem;
}

.info-image {
  text-align: center;
  font-size: 8rem;
  color: #667eea;
  opacity: 0.3;
}

.contact {
  padding: 80px 20px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  text-align: center;
}

.contact h2 {
  margin: 0 0 20px;
  font-size: 2rem;
}

.contact p {
  margin: 0 0 40px;
  font-size: 1.1rem;
  opacity: 0.9;
}

.contact-info {
  display: flex;
  justify-content: center;
  gap: 40px;
  flex-wrap: wrap;
}

.contact-item {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 1rem;
}

.contact-item i {
  font-size: 1.2rem;
}

@media (max-width: 768px) {
  .hero-content h1 {
    font-size: 2rem;
  }
  
  .hero-actions {
    flex-direction: column;
    align-items: center;
  }
  
  .info-content {
    grid-template-columns: 1fr;
    text-align: center;
  }
  
  .info-image {
    font-size: 4rem;
  }
  
  .contact-info {
    flex-direction: column;
    gap: 20px;
  }
}
</style>
