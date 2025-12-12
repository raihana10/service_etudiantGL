<template>
  <div class="success-message" :class="{ small: small, large: large }">
    <div class="success-icon">
      <i class="fas fa-check-circle"></i>
    </div>
    <div class="success-content">
      <h4 v-if="title" class="success-title">{{ title }}</h4>
      <p class="success-text">{{ message }}</p>
      <button v-if="showClose" @click="$emit('close')" class="close-btn">
        <i class="fas fa-times"></i>
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
  showClose: Boolean
})

defineEmits(['close'])
</script>

<style scoped>
.success-message {
  display: flex;
  align-items: flex-start;
  gap: 15px;
  background: #d1fae5;
  border: 1px solid #a7f3d0;
  border-radius: 8px;
  padding: 15px;
  color: #065f46;
}

.success-icon {
  color: #059669;
  font-size: 1.2rem;
  margin-top: 2px;
}

.success-content {
  flex: 1;
  position: relative;
}

.success-title {
  margin: 0 0 5px;
  font-size: 1rem;
  font-weight: 600;
}

.success-text {
  margin: 0;
  font-size: 0.9rem;
  line-height: 1.4;
}

.close-btn {
  position: absolute;
  top: 0;
  right: 0;
  background: none;
  border: none;
  color: #065f46;
  cursor: pointer;
  padding: 5px;
  font-size: 0.8rem;
  opacity: 0.7;
  transition: opacity 0.3s ease;
}

.close-btn:hover {
  opacity: 1;
}

.small {
  padding: 10px;
  gap: 10px;
}

.small .success-icon {
  font-size: 1rem;
}

.small .success-title {
  font-size: 0.9rem;
}

.small .success-text {
  font-size: 0.8rem;
}

.large {
  padding: 20px;
  gap: 20px;
}

.large .success-icon {
  font-size: 1.5rem;
}

.large .success-title {
  font-size: 1.2rem;
}

.large .success-text {
  font-size: 1rem;
}
</style>
