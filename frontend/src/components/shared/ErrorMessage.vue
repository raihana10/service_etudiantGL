<template>
  <div class="error-message" :class="{ small: small, large: large }">
    <div class="error-icon">
      <i class="fas fa-exclamation-circle"></i>
    </div>
    <div class="error-content">
      <h4 v-if="title" class="error-title">{{ title }}</h4>
      <p class="error-text">{{ message }}</p>
      <button v-if="showRetry" @click="$emit('retry')" class="retry-btn">
        <i class="fas fa-redo"></i>
        Réessayer
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  message: String,
  title: String,
  small: Boolean,
  large: Boolean,
  showRetry: Boolean
})

defineEmits(['retry'])
</script>

<style scoped>
.error-message {
  display: flex;
  align-items: flex-start;
  gap: 15px;
  background: #fee2e2;
  border: 1px solid #fecaca;
  border-radius: 8px;
  padding: 15px;
  color: #991b1b;
}

.error-icon {
  color: #dc2626;
  font-size: 1.2rem;
  margin-top: 2px;
}

.error-content {
  flex: 1;
}

.error-title {
  margin: 0 0 5px;
  font-size: 1rem;
  font-weight: 600;
}

.error-text {
  margin: 0 0 10px;
  font-size: 0.9rem;
  line-height: 1.4;
}

.retry-btn {
  background: #dc2626;
  color: white;
  border: none;
  padding: 8px 15px;
  border-radius: 6px;
  font-size: 0.8rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
  transition: background 0.3s ease;
}

.retry-btn:hover {
  background: #b91c1c;
}

.small {
  padding: 10px;
  gap: 10px;
}

.small .error-icon {
  font-size: 1rem;
}

.small .error-title {
  font-size: 0.9rem;
}

.small .error-text {
  font-size: 0.8rem;
}

.large {
  padding: 20px;
  gap: 20px;
}

.large .error-icon {
  font-size: 1.5rem;
}

.large .error-title {
  font-size: 1.2rem;
}

.large .error-text {
  font-size: 1rem;
}
</style>
