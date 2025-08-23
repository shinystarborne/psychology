<template>
  <section class="about" aria-labelledby="about-title">
    <div class="about-container">
      <div class="about-content">
        <div class="about-visual">
          <div class="photo-container">
            <img 
              :src="props.content?.about_image_url || 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=600&h=700&fit=crop&crop=face&auto=format&q=80'"
              alt="Психолог - профессиональное фото"
              class="photo-image"
            />
          </div>
        </div>
        
        <div class="about-text">
          <h2 id="about-title" class="about-title">
            {{ props.content?.about_title || 'Обо мне' }}
          </h2>
          
          <div class="about-intro">
            <p class="intro-paragraph">
              {{ props.content?.about_intro_paragraph_1 || 'Я психолог с высшим образованием и многолетним опытом работы. Специализируюсь на работе с тревогой, отношениями и личностным ростом.' }}
            </p>
            
            <p class="intro-paragraph">
              {{ props.content?.about_intro_paragraph_2 || 'Моя цель — создать безопасное пространство, где вы сможете исследовать свои переживания и найти внутренние ресурсы для позитивных изменений.' }}
            </p>
          </div>
          
          <div class="credentials-compact">
            <div class="credential-item">
              <span class="credential-icon">🎓</span>
              <span class="credential-text">{{ props.content?.about_credential_education || 'Высшее психологическое образование' }}</span>
            </div>
            <div class="credential-item">
              <span class="credential-icon">📜</span>
              <span class="credential-text">{{ props.content?.about_credential_certificates || 'Сертификаты КПТ, гештальт-терапия' }}</span>
            </div>
          </div>

          <div class="action-buttons">
            <button 
              class="action-btn primary-btn"
              @click="openModal"
              :aria-label="props.content?.about_button_more || 'Подробнее обо мне'"
            >
              {{ props.content?.about_button_more || 'Подробнее обо мне' }}
            </button>
            
            <button 
              class="action-btn secondary-btn"
              @click="$emit('showCredentials')"
              :aria-label="props.content?.about_button_diplomas || 'Дипломы'"
            >
              {{ props.content?.about_button_diplomas || 'Дипломы' }}
            </button>
          </div>
        </div>
      </div>
    </div>

  </section>

  <!-- About Modal -->
  <Teleport to="body">
    <div 
      v-if="showAboutModal" 
      class="modal-overlay" 
      @click="showAboutModal = false"
      role="dialog"
      aria-modal="true"
      aria-labelledby="modal-title"
      @keydown.esc="showAboutModal = false"
    >
      <div 
        class="modal-content" 
        @click.stop
        tabindex="-1"
      >
        <div class="modal-header">
          <h2 id="modal-title" class="modal-title">Подробнее обо мне</h2>
          <button 
            class="close-btn" 
            @click="showAboutModal = false"
            aria-label="Закрыть модальное окно"
          >
            <span aria-hidden="true">×</span>
          </button>
        </div>
        
        <div class="modal-body">
          <div class="modal-section" v-if="props.content?.about_modal_education">
            <h4>Образование и подход</h4>
            <p>{{ props.content.about_modal_education }}</p>
          </div>

          <div class="modal-section" v-if="props.content?.about_modal_experience">
            <h4>Опыт работы</h4>
            <p>{{ props.content.about_modal_experience }}</p>
          </div>

          <div class="modal-section" v-if="props.content?.about_modal_philosophy">
            <h4>Философия работы</h4>
            <p>{{ props.content.about_modal_philosophy }}</p>
          </div>

          <div class="modal-section" v-if="props.content?.about_modal_principles">
            <h4>Принципы работы</h4>
            <p>{{ props.content.about_modal_principles }}</p>
          </div>

          <div class="modal-section" v-if="props.content?.about_modal_competencies">
            <h4>Ключевые компетенции</h4>
            <p>{{ props.content.about_modal_competencies }}</p>
          </div>
          
          <!-- Fallback content if no custom content is provided -->
          <div v-if="!hasModalContent" class="modal-section">
            <h4>Информация скоро появится</h4>
            <p>
              Подробная информация обо мне будет добавлена в ближайшее время. 
              Пожалуйста, свяжитесь со мной для получения дополнительных сведений.
            </p>
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

interface AboutProps {
  showPhoto?: boolean
  content?: { [key: string]: any }
}

defineEmits<{
  showCredentials: []
}>()

const props = withDefaults(defineProps<AboutProps>(), {
  showPhoto: true
})


const showAboutModal = ref(false)

const hasModalContent = computed(() => {
  return !!(props.content?.about_modal_education || 
           props.content?.about_modal_experience || 
           props.content?.about_modal_philosophy || 
           props.content?.about_modal_principles || 
           props.content?.about_modal_competencies)
})

const openModal = () => {
  console.log('Opening modal')
  showAboutModal.value = true
}
</script>

<style scoped>
.about {
  background: linear-gradient(135deg, #fefefe 0%, #f9f9f9 50%, #f5f5f5 100%);
  padding: var(--spacing-3xl) 0;
  position: relative;
}

.about::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(circle at 70% 80%, rgba(245, 245, 245, 0.4) 0%, transparent 50%);
  pointer-events: none;
}

.about-container {
  width: 100%;
  max-width: var(--container-max-width);
  margin: 0 auto;
}

.about-content {
  display: grid;
  grid-template-columns: 1fr 1.2fr;
  gap: var(--spacing-3xl);
  align-items: start;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 var(--spacing-lg);
  position: relative;
  z-index: 1;
}

.about-visual {
  display: flex;
  justify-content: center;
  align-items: center;
}

.photo-container {
  width: 100%;
  max-width: 400px;
  margin: 0 auto;
}

.photo-image {
  width: 100%;
  height: auto;
  aspect-ratio: 3/4;
  object-fit: cover;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  transition: transform var(--transition-normal), box-shadow var(--transition-normal);
}

.photo-image:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.about-title {
  font-size: clamp(2rem, 4vw, 2.5rem);
  font-weight: var(--font-weight-medium);
  color: var(--text-heading);
  margin: 0 0 var(--spacing-xl) 0;
  letter-spacing: -0.01em;
  line-height: 1.2;
  position: relative;
}

.about-title::after {
  content: '';
  position: absolute;
  bottom: -8px;
  left: 0;
  width: 60px;
  height: 3px;
  background: linear-gradient(90deg, var(--color-taupe-light), var(--color-taupe));
  border-radius: 2px;
}

.about-intro {
  margin-bottom: var(--spacing-xl);
}

.intro-paragraph {
  font-size: 1.125rem;
  line-height: 1.7;
  color: #555;
  margin-bottom: var(--spacing-lg);
}

.credentials-compact {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-md);
  margin-bottom: var(--spacing-xl);
  padding: var(--spacing-lg);
  background: rgba(248, 249, 250, 0.8);
  border-radius: var(--border-radius-md);
}

.credential-item {
  display: flex;
  align-items: center;
  gap: var(--spacing-sm);
}

.credential-icon {
  font-size: 1.2rem;
  flex-shrink: 0;
}

.credential-text {
  font-size: 0.95rem;
  color: #666;
  font-weight: var(--font-weight-medium);
}

.action-buttons {
  display: flex;
  flex-direction: row;
  gap: var(--spacing-md);
  align-items: flex-start;
}

.action-btn {
  padding: var(--spacing-md) var(--spacing-lg);
  border-radius: var(--border-radius-md);
  font-size: 0.95rem;
  font-weight: var(--font-weight-medium);
  cursor: pointer;
  transition: all var(--transition-fast);
  border: 1px solid;
}

.primary-btn {
  background: linear-gradient(135deg, var(--color-warm), var(--color-taupe-light));
  color: var(--text-primary);
  border-color: var(--color-taupe);
}

.primary-btn:hover {
  background: linear-gradient(135deg, var(--color-taupe-light), var(--color-taupe));
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(172, 164, 159, 0.3);
}

.secondary-btn {
  background-color: var(--color-warm-light);
  color: var(--text-secondary);
  border: 1px solid var(--color-taupe-light);
  backdrop-filter: blur(10px);
  position: relative;
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.secondary-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
  transition: left 0.5s ease;
}

.secondary-btn:hover::before {
  left: 100%;
}

.secondary-btn:hover {
  background: linear-gradient(135deg, var(--color-warm), var(--color-taupe-light));
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(172, 164, 159, 0.3);
  border-color: var(--color-taupe);
  color: var(--text-primary);
}

/* Modal Styles - copied from CertificateGallery */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  animation: fadeIn 0.3s ease-out;
}

.modal-content {
  background-color: var(--color-primary);
  border-radius: var(--border-radius-lg);
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
  max-width: 900px;
  max-height: 90vh;
  width: 100%;
  overflow: hidden;
  animation: slideIn 0.3s ease-out;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: var(--spacing-xl);
  border-bottom: 1px solid var(--color-accent);
  background-color: var(--color-warm-light);
}

.modal-title {
  font-size: 1.5rem;
  font-weight: var(--font-weight-medium);
  color: var(--text-primary);
  margin: 0;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: var(--text-secondary);
  padding: var(--spacing-sm);
  border-radius: 50%;
  transition: all var(--transition-fast);
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2rem;
  height: 2rem;
}

.close-btn:hover {
  background-color: var(--color-warm);
  color: var(--text-primary);
}

.close-btn:focus {
  outline: 2px solid var(--color-accent);
  outline-offset: 2px;
}

.modal-body {
  padding: var(--spacing-xl);
  overflow-y: auto;
  max-height: calc(90vh - 100px);
}

.modal-section {
  margin-bottom: var(--spacing-xl);
}

.modal-section:last-child {
  margin-bottom: 0;
}

.modal-section h4 {
  font-size: 1.125rem;
  font-weight: var(--font-weight-medium);
  color: var(--text-primary);
  margin: 0 0 var(--spacing-md) 0;
}

.modal-section p {
  font-size: 1rem;
  line-height: 1.6;
  color: var(--text-secondary);
  margin: 0;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* Разделители секций */
.about::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 100px;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(200, 200, 200, 0.5), transparent);
}

/* Адаптивность */
@media (max-width: 1023px) {
  .about-content {
    grid-template-columns: 1fr;
    gap: var(--spacing-2xl);
    text-align: center;
  }
  
  .about-visual {
    order: -1;
  }
  
  .photo-container {
    max-width: 300px;
  }
}

@media (max-width: 767px) {
  .about {
    padding: var(--spacing-2xl) 0;
  }
  
  .about-container {
    padding: 0 var(--spacing-md);
  }
  
  .about-content {
    gap: var(--spacing-xl);
  }
  
  .photo-container {
    max-width: 280px;
  }
  
  .intro-paragraph {
    font-size: 1rem;
  }
  
  .credentials-compact {
    padding: var(--spacing-md);
  }
  
  .action-buttons {
    flex-direction: column;
    align-items: center;
  }
}

@media (max-width: 479px) {
  .modal-overlay {
    padding: var(--spacing-md);
  }
  
  .modal-header {
    padding: var(--spacing-lg);
  }
  
  .modal-body {
    padding: var(--spacing-lg);
  }
  
  .action-buttons {
    width: 100%;
  }
  
  .action-btn {
    width: 100%;
    text-align: center;
  }
}

/* Высокий контраст для доступности */
@media (prefers-contrast: high) {
  .about-title {
    color: #000000;
  }
  
  .intro-paragraph {
    color: var(--text-heading);
  }
  
  .credential-text {
    color: #333333;
  }
  
  .credentials-compact,
  .credentials-btn {
    border: 2px solid #666666;
  }
}

/* Уменьшенная анимация для пользователей с чувствительностью к движению */
@media (prefers-reduced-motion: reduce) {
  .photo-image:hover {
    transform: none;
  }
  
  .credentials-btn:hover {
    transform: none;
  }
}

/* Фокус для доступности */
.credentials-btn:focus-visible {
  outline: 2px solid #888888;
  outline-offset: 2px;
}
</style>