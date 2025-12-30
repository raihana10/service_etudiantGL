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
          <p>Chargement des données...</p>
        </div>

        <!-- Error state -->
        <div v-else-if="error" class="error-state">
          <p>{{ error }}</p>
          <button @click="loadDashboardData" class="retry-button">Réessayer</button>
        </div>

        <!-- Dashboard content -->
        <div v-else class="dashboard-stats">
          <!-- Header -->
          <div class="dashboard-header">
            <h1 class="dashboard-title">Tableau de bord</h1>
            <p class="dashboard-subtitle">Vue d'ensemble des demandes et statistiques</p>
          </div>

          <!-- Statistiques -->
          <div class="stats-grid">
            <!-- Carte Total -->
            <div class="stat-card stat-card-blue">
              <div class="stat-content">
                <div class="stat-info">
                  <p class="stat-label">Total Demandes</p>
                  <p class="stat-value">{{ stats.total }}</p>
                  <p class="stat-trend">
                    <svg class="trend-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Toutes demandes
                  </p>
                </div>
                <div class="stat-icon stat-icon-blue">
                  <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                </div>
              </div>
            </div>

            <!-- Carte En attente -->
            <div class="stat-card stat-card-orange">
              <div class="stat-content">
                <div class="stat-info">
                  <p class="stat-label">En Attente</p>
                  <p class="stat-value">{{ stats.enAttente }}</p>
                  <p class="stat-trend">
                    <svg class="trend-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    En traitement
                  </p>
                </div>
                <div class="stat-icon stat-icon-orange">
                  <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>
            </div>

            <!-- Carte Acceptées -->
            <div class="stat-card stat-card-teal">
              <div class="stat-content">
                <div class="stat-info">
                  <p class="stat-label">Acceptées</p>
                  <p class="stat-value">{{ stats.acceptees }}</p>
                  <p class="stat-trend">
                    <svg class="trend-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Validées
                  </p>
                </div>
                <div class="stat-icon stat-icon-teal">
                  <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>
            </div>

            <!-- Carte Refusées -->
            <div class="stat-card stat-card-red">
              <div class="stat-content">
                <div class="stat-info">
                  <p class="stat-label">Refusées</p>
                  <p class="stat-value">{{ stats.refusees }}</p>
                  <p class="stat-trend">
                    <svg class="trend-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Non validées
                  </p>
                </div>
                <div class="stat-icon stat-icon-red">
                  <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <!-- KPI Grid -->
          <div class="kpi-grid">
            <div class="kpi-card">
               <div class="kpi-icon">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                 </svg>
               </div>
               <div>
                 <p class="kpi-label">Temps Moyen de Traitement</p>
                 <p class="kpi-value">{{ kpis.avgProcessingTime }}h</p>
               </div>
            </div>
            <div class="kpi-card">
               <div class="kpi-icon kpi-green">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                 </svg>
               </div>
               <div>
                  <p class="kpi-label">Taux de Résolution</p>
                  <p class="kpi-value">{{ kpis.resolutionRate }}%</p>
               </div>
            </div>
          </div>



          <!-- Graphiques -->
          <div class="dashboard-grid">
            <!-- Graphique donut - Répartition par statut (Demandes) -->
            <div class="chart-card">
              <h3 class="card-title">Demandes par statut</h3>
              <div class="chart-container">
                <canvas ref="donutChartRef"></canvas>
              </div>
            </div>

            <!-- Graphique en barres - Demandes par type de document -->
            <div class="chart-card">
              <h3 class="card-title">Demandes par type</h3>
              <div class="chart-container">
                <canvas ref="barChartRef"></canvas>
              </div>
            </div>
            
            <!-- Graphique donut - Répartition par statut (Réclamations) -->
            <div class="chart-card">
              <h3 class="card-title">Réclamations par statut</h3>
              <div class="chart-container">
                <canvas ref="reclamationDonutRef"></canvas>
              </div>
            </div>

            <!-- Graphique en ligne - Statistiques des Réclamations -->
            <div class="chart-card">
              <h3 class="card-title">Evolution des Réclamations</h3>
              <div class="chart-container">
                <canvas ref="lineChartRef"></canvas>
              </div>
            </div>
          </div>

          <!-- Top Students & Recent Activity -->
          <div class="dashboard-grid stats-tables">
             <!-- Top Students -->
             <div class="chart-card">
                <h3 class="card-title">Top 5 Étudiants Actifs</h3>
                <div class="table-responsive">
                  <table class="data-table">
                    <thead>
                      <tr>
                        <th>Étudiant</th>
                        <th>N° Apogée</th>
                        <th>Demandes</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(student, index) in topStudents" :key="index">
                        <td class="font-medium">{{ student.nom }}</td>
                        <td class="text-gray">{{ student.numApogee }}</td>
                        <td class="text-center font-bold">{{ student.count }}</td>
                      </tr>
                      <tr v-if="topStudents.length === 0">
                        <td colspan="3" class="text-center py-4 text-gray">Aucune donnée disponible</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
             </div>

             <!-- Recent Demands -->
             <div class="chart-card">
                <h3 class="card-title">Demandes Récentes</h3>
                <div class="table-responsive">
                  <table class="data-table">
                    <thead>
                      <tr>
                        <th>Étudiant</th>
                        <th>Type</th>
                        <th>Statut</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(demande, index) in recentDemandes" :key="index">
                        <td class="font-medium">
                          {{ demande.etudiant }}
                          <div class="text-xs text-gray">{{ demande.datesoumission }}</div>
                        </td>
                        <td class="text-gray">{{ demande.typeDoc }}</td>
                        <td>
                          <span :class="['status-badge', getStatusClass(demande.statut)]">
                            {{ demande.statut }}
                          </span>
                        </td>
                      </tr>
                      <tr v-if="recentDemandes.length === 0">
                        <td colspan="3" class="text-center py-4 text-gray">Aucune demande récente</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
             </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import Navbar from './Navbar.vue'
import Sidebar from './Sidebar.vue'
import { Chart, registerables } from 'chart.js'
import axios from 'axios'

Chart.register(...registerables)

// Données réelles pour les statistiques
const stats = ref({
  acceptees: 0,
  refusees: 0,
  enAttente: 0,
  total: 0
})

const kpis = ref({
  avgProcessingTime: 0,
  resolutionRate: 0
})

const topStudents = ref([])
const recentDemandes = ref([])

// Références pour les graphiques Chart.js
let donutChart = null
let barChart = null
let lineChart = null
let reclamationDonutChart = null

// Références pour les canvas
const donutChartRef = ref(null)
const barChartRef = ref(null)
const lineChartRef = ref(null)
const reclamationDonutRef = ref(null)

// État de chargement
const loading = ref(true)
const error = ref(null)

// Fonction pour charger les données du dashboard
const loadDashboardData = async () => {
  try {
    loading.value = true
    error.value = null
    
    console.log('Chargement des données du dashboard...')
    const response = await axios.get('http://localhost:8000/api/admin/dashboard/statistics')
    
    console.log('Réponse API:', response.data)
    
    if (response.data.success) {
      const data = response.data.data
      
      // Mettre à jour les statistiques
      stats.value = data.stats
      kpis.value = data.kpis || { avgProcessingTime: 0, resolutionRate: 0 }
      topStudents.value = data.topStudents || []
      recentDemandes.value = data.recentDemandes || []
      
      console.log('Stats chargées:', stats.value)
      console.log('Données graphiques:', data.donutChart, data.barChart, data.lineChart)
      
      // Attendre que Vue mette à jour le DOM
      await nextTick()
      
      // Mettre à jour les graphiques
      updateCharts(data)
    }
  } catch (err) {
    console.error('Erreur lors du chargement des données:', err)
    error.value = 'Impossible de charger les données du dashboard. Vérifiez que le serveur Laravel est démarré.'
  } finally {
    loading.value = false
  }
}

// Helper pour les classes de statut
const getStatusClass = (status) => {
  const classes = {
    'Validée': 'status-success',
    'Refusée': 'status-error',
    'En attente': 'status-warning',
    'En cours': 'status-warning', // Pour les réclamations si besoin
    'Résolue': 'status-success'   // Pour les réclamations si besoin
  }
  return classes[status] || 'status-default'
}

// Fonction pour mettre à jour les graphiques
const updateCharts = (data) => {
  // Attendre le prochain tick pour s'assurer que le DOM est prêt
  setTimeout(() => {
    // Détruire les anciens graphiques s'ils existent
    if (donutChart) {
      donutChart.destroy()
      donutChart = null
    }
    if (barChart) {
      barChart.destroy()
      barChart = null
    }
    if (lineChart) {
      lineChart.destroy()
      lineChart = null
    }
    if (reclamationDonutChart) {
      reclamationDonutChart.destroy()
      reclamationDonutChart = null
    }

    // Graphique en donut (Demandes)
    if (donutChartRef.value) {
      const ctx = donutChartRef.value.getContext('2d')
      donutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: data.donutChart.labels,
          datasets: [{
            data: data.donutChart.data,
            backgroundColor: data.donutChart.backgroundColor,
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                padding: 15,
                usePointStyle: true,
                font: {
                  size: 11,
                  family: "'Inter', sans-serif"
                },
                color: '#0A0D25'
              }
            }
          },
          cutout: '70%'
        }
      })
    }

    // Graphique en barres (Demandes)
    if (barChartRef.value) {
      const ctx = barChartRef.value.getContext('2d')
      barChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: data.barChart.labels,
          datasets: [{
            label: 'Nombre de demandes',
            data: data.barChart.data,
            backgroundColor: data.barChart.backgroundColor,
            borderRadius: 6,
            barThickness: 30
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                color: '#E3EDF2',
                drawBorder: false
              },
              ticks: {
                color: '#6b7280',
                font: {
                  size: 11
                }
              }
            },
            x: {
              grid: {
                display: false
              },
              ticks: {
                color: '#6b7280',
                font: {
                  size: 10
                }
              }
            }
          }
        }
      })
    }
    
    // Graphique Donut (Réclamations)
    if (reclamationDonutRef.value && data.reclamationDonut) {
      const ctx = reclamationDonutRef.value.getContext('2d')
      reclamationDonutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: data.reclamationDonut.labels,
          datasets: [{
            data: data.reclamationDonut.data,
            backgroundColor: data.reclamationDonut.backgroundColor,
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                padding: 15,
                usePointStyle: true,
                font: {
                  size: 11,
                  family: "'Inter', sans-serif"
                },
                color: '#0A0D25'
              }
            }
          },
          cutout: '70%'
        }
      })
    }

    // Graphique en ligne (Réclamations)
    if (lineChartRef.value && data.lineChart) {
      const ctx = lineChartRef.value.getContext('2d')
      lineChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: data.lineChart.labels,
          datasets: [{
            label: 'Nombre de réclamations',
            data: data.lineChart.data,
            borderColor: data.lineChart.borderColor,
            backgroundColor: data.lineChart.backgroundColor,
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#fff',
            pointBorderColor: data.lineChart.borderColor,
            pointBorderWidth: 2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
                mode: 'index',
                intersect: false,
                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                titleColor: '#0A0D25',
                bodyColor: '#6b7280',
                borderColor: '#E3EDF2',
                borderWidth: 1,
                padding: 10,
                displayColors: false
            }
          },
          interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                color: '#E3EDF2',
                drawBorder: false,
                borderDash: [5, 5]
              },
              ticks: {
                color: '#6b7280',
                stepSize: 1,
                font: {
                  size: 11
                }
              }
            },
            x: {
              grid: {
                display: false
              },
              ticks: {
                color: '#6b7280',
                font: {
                  size: 11
                }
              }
            }
          }
        }
      })
    }
  }, 100)
}

onMounted(() => {
  loadDashboardData()
})
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.dashboard-layout {
  min-height: 100vh;
  background: linear-gradient(135deg, #E3EDF2 0%, #F8FBFC 100%);
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  color: #0A0D25;
}

.dashboard-main {
  display: flex;
}

.dashboard-content {
  flex: 1;
  margin-left: 16rem;
  margin-top: 104px; /* Espace pour le header fixe */
  background: transparent;
  min-height: 100vh;
  padding: 2rem;
}

.dashboard-stats {
  width: 100%;
}

/* Header */
.dashboard-header {
  margin-bottom: 2rem;
}

.dashboard-title {
  color: #0A0D25;
  font-size: 2.2rem;
  font-weight: 700;
  letter-spacing: -0.5px;
  margin-bottom: 0.5rem;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.dashboard-subtitle {
  color: #6b7280;
  font-size: 1rem;
  font-weight: 400;
  margin-bottom: 2rem;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.stats-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
  margin-bottom: 2rem;
}

@media (min-width: 640px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .stats-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

.stat-card {
  background: white;
  border-radius: 1rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  padding: 1.75rem;
  border-top-width: 4px;
  border-top-style: solid;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  opacity: 0.05;
  pointer-events: none;
}

.stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.stat-card-blue {
  border-top-color: #4E7D96;
}

.stat-card-blue::before {
  background: #4E7D96;
}

.stat-card-orange {
  border-top-color: #FF844B;
}

.stat-card-orange::before {
  background: #FF844B;
}

.stat-card-teal {
  border-top-color: #4E7D96;
}

.stat-card-teal::before {
  background: linear-gradient(135deg, #4E7D96 0%, #5a8fa8 100%);
}

.stat-card-red {
  border-top-color: #ef4444;
}

.stat-card-red::before {
  background: #ef4444;
}

.stat-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: relative;
  z-index: 1;
}

.stat-info {
  flex: 1;
}

.stat-label {
  color: #6b7280;
  font-size: 0.875rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 0.5rem;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.stat-value {
  font-size: 2.5rem;
  font-weight: 700;
  color: #0A0D25;
  margin-top: 0.5rem;
  margin-bottom: 0.5rem;
  line-height: 1;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.stat-trend {
  font-size: 0.875rem;
  margin-top: 0.75rem;
  display: flex;
  align-items: center;
  color: #6b7280;
  font-weight: 500;
}

.trend-icon {
  width: 1rem;
  height: 1rem;
  margin-right: 0.375rem;
}

.stat-icon {
  width: 4rem;
  height: 4rem;
  border-radius: 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.3s ease;
}

.stat-card:hover .stat-icon {
  transform: scale(1.1) rotate(5deg);
}

.stat-icon-blue {
  background: linear-gradient(135deg, rgba(78, 125, 150, 0.15) 0%, rgba(78, 125, 150, 0.25) 100%);
}

.stat-icon-blue .icon {
  color: #4E7D96;
}

.stat-icon-orange {
  background: linear-gradient(135deg, rgba(255, 132, 75, 0.15) 0%, rgba(255, 132, 75, 0.25) 100%);
}

.stat-icon-orange .icon {
  color: #FF844B;
}

.stat-icon-teal {
  background: linear-gradient(135deg, rgba(78, 125, 150, 0.15) 0%, rgba(90, 143, 168, 0.25) 100%);
}

.stat-icon-teal .icon {
  color: #4E7D96;
}

.stat-icon-red {
  background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(239, 68, 68, 0.25) 100%);
}

.stat-icon-red .icon {
  color: #ef4444;
}

.icon {
  width: 2rem;
  height: 2rem;
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

.dashboard-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
  margin-bottom: 2rem;
}

@media (min-width: 1024px) {
  .dashboard-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

.full-width-chart {
  width: 100%;
  grid-column: 1 / -1;
}

.chart-card {
  background: white;
  border-radius: 1rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  padding: 2rem;
  transition: box-shadow 0.3s ease;
}

.chart-card:hover {
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.card-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #0A0D25;
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 2px solid #E3EDF2;
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.chart-container {
  height: 300px;
  position: relative;
}

/* New KPI and Table styles */
.kpi-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.kpi-card {
  background: white;
  padding: 1.5rem;
  border-radius: 1rem;
  display: flex;
  align-items: center;
  gap: 1.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s;
}

.kpi-card:hover {
  transform: translateY(-2px);
}

.kpi-icon {
  width: 3.5rem;
  height: 3.5rem;
  border-radius: 0.75rem;
  background: #E3EDF2;
  color: #4E7D96;
  display: flex;
  align-items: center;
  justify-content: center;
}

.kpi-green {
  background: #d1fae5;
  color: #059669;
}

.kpi-label {
  color: #6b7280;
  font-size: 0.875rem;
  font-weight: 500;
  margin-bottom: 0.25rem;
}

.kpi-value {
  color: #0A0D25;
  font-size: 1.5rem;
  font-weight: 700;
}

.stats-tables {
  grid-template-columns: 1fr;
  margin-bottom: 2rem;
}

@media (min-width: 1024px) {
  .stats-tables {
    grid-template-columns: repeat(2, 1fr);
  }
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th {
  text-align: left;
  padding: 0.75rem 1rem;
  color: #6b7280;
  font-weight: 600;
  font-size: 0.875rem;
  border-bottom: 1px solid #E3EDF2;
}

.data-table td {
  padding: 1rem;
  border-bottom: 1px solid #f3f4f6;
  color: #0A0D25;
  font-size: 0.875rem;
}

.data-table tr:last-child td {
  border-bottom: none;
}

.font-medium {
  font-weight: 500;
}

.text-gray {
  color: #6b7280;
}

.text-center {
  text-align: center;
}

.font-bold {
  font-weight: 700;
}

.text-xs {
  font-size: 0.75rem;
}

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
  display: inline-block;
}

.status-success {
  background: #d1fae5;
  color: #059669;
}

.status-error {
  background: #fee2e2;
  color: #dc2626;
}

.status-warning {
  background: #fef3c7;
  color: #d97706;
}

.status-default {
  background: #e5e7eb;
  color: #4b5563;
}
</style>