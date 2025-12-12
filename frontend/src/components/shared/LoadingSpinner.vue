<template>
  <div class="loading-spinner" :class="{ small: small, large: large }">
    <div class="spinner"></div>
    <span v-if="text" class="loading-text">{{ text }}</span>
  </div>
</template>

<script setup>
defineProps({
  text: String,
  small: Boolean,
  large: Boolean
})
</script>

<style scoped>
.loading-spinner {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 15px;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f4f6;
  border-top: 4px solid #667eea;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

.loading-text {
  color: #666;
  font-size: 0.9rem;
}

.small .spinner {
  width: 20px;
  height: 20px;
  border-width: 2px;
}

.small .loading-text {
  font-size: 0.8rem;
}

.large .spinner {
  width: 60px;
  height: 60px;
  border-width: 6px;
}

.large .loading-text {
  font-size: 1.1rem;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
