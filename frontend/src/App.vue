<script setup>
import { ref, onMounted } from 'vue'
import api from './services/api'

const message = ref('')
const status = ref('')
const timestamp = ref('')

const testConnection = async () => {
  try {
    const response = await api.get('/test')
    message.value = response.data.message
    status.value = response.data.status
    timestamp.value = response.data.timestamp
  } catch (error) {
    message.value = 'Erreur de connexion avec le backend'
    status.value = 'error'
    console.error('Erreur:', error)
  }
}

onMounted(() => {
  testConnection()
})
</script>

<template>
  <div>
    <h1>Test de Communication Backend-Frontend</h1>
    <div v-if="message">
      <p><strong>Message:</strong> {{ message }}</p>
      <p><strong>Status:</strong> {{ status }}</p>
      <p><strong>Timestamp:</strong> {{ timestamp }}</p>
    </div>
    <div v-else>
      <p>Chargement...</p>
    </div>
    <button @click="testConnection">Tester la connexion</button>
  </div>
</template>

<style scoped>
div {
  text-align: center;
  padding: 20px;
}
button {
  margin-top: 20px;
  padding: 10px 20px;
  background-color: #42b883;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
button:hover {
  background-color: #369870;
}
</style>
