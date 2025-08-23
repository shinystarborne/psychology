<template>
  <AdminLayout>
    <div class="content-management">
      <!-- Header with Save Button -->
      <div class="page-header">
        <div class="header-content">
          <h1 class="page-title">
            <span class="title-icon">✏️</span>
            Управление контентом сайта
          </h1>
          <p class="page-subtitle">Редактируйте весь текст на сайте в одном месте</p>
        </div>
        <div class="header-actions">
          <div v-if="hasChanges" class="changes-indicator">
            <span class="indicator-dot"></span>
            Есть несохранённые изменения
          </div>
          <button 
            @click="saveAllChanges" 
            class="save-button" 
            :class="{ 'saving': saving, 'has-changes': hasChanges }"
            :disabled="!hasChanges || saving"
          >
            <span v-if="saving" class="button-icon">⏳</span>
            <span v-else-if="hasChanges" class="button-icon">💾</span>
            <span v-else class="button-icon">✅</span>
            {{ saving ? 'Сохраняется...' : hasChanges ? 'Сохранить изменения' : 'Всё сохранено' }}
          </button>
        </div>
      </div>

      <!-- Success Message -->
      <div v-if="successMessage" class="notification success">
        <span class="notification-icon">✅</span>
        <span>{{ successMessage }}</span>
      </div>

      <!-- Navigation Tabs -->
      <div class="tabs-container">
        <div class="tabs">
          <button 
            v-for="tab in tabs" 
            :key="tab.id"
            @click="activeTab = tab.id"
            class="tab"
            :class="{ 'active': activeTab === tab.id }"
          >
            <span class="tab-icon">{{ tab.icon }}</span>
            <span class="tab-label">{{ tab.label }}</span>
            <span v-if="getTabChangesCount(tab.sections) > 0" class="tab-badge">{{ getTabChangesCount(tab.sections) }}</span>
          </button>
        </div>
      </div>

      <!-- Content Sections -->
      <div class="content-sections">
        <!-- Hero Section -->
        <div v-show="activeTab === 'hero'" class="section-group">
          <div class="section">
            <div class="section-header">
              <h2 class="section-title">
                <span class="section-icon">🏠</span>
                Главный экран
                <span class="section-badge">Hero</span>
              </h2>
              <p class="section-description">Первое впечатление посетителей сайта</p>
            </div>
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label">
                  <span class="label-icon">👤</span>
                  Имя психолога
                </label>
                <input v-model="contentData.hero_name" type="text" class="form-input" placeholder="Введите имя" />
                <small class="form-hint">Отображается крупно на главном экране</small>
              </div>
              <div class="form-group">
                <label class="form-label">
                  <span class="label-icon">📝</span>
                  Подзаголовок
                </label>
                <input v-model="contentData.hero_subtitle" type="text" class="form-input" placeholder="Краткое описание" />
              </div>
              <div class="form-group full-width">
                <label class="form-label">
                  <span class="label-icon">📄</span>
                  Описание деятельности
                </label>
                <textarea v-model="contentData.hero_description" class="form-textarea" rows="3" placeholder="Расскажите о себе кратко"></textarea>
              </div>
              <div class="form-group full-width">
                <label class="form-label">
                  <span class="label-icon">⏰</span>
                  Режим работы
                </label>
                <input v-model="contentData.hero_work_mode" type="text" class="form-input" placeholder="Онлайн • Индивидуально" />
              </div>
            </div>
            <div class="section-footer">
              <button @click="saveSection('hero')" class="section-save-btn" :disabled="saving">
                <span class="btn-icon">💾</span>
                Сохранить главную страницу
              </button>
            </div>
          </div>
        </div>

        <!-- About Section -->
        <div v-show="activeTab === 'about'" class="section-group">
          <div class="section">
            <div class="section-header">
              <h2 class="section-title">
                <span class="section-icon">👤</span>
                Секция "Обо мне"
              </h2>
              <p class="section-description">Подробная информация о психологе</p>
            </div>
          <div class="form-grid">
            <div class="form-row">
              <div class="form-group">
                <label class="form-label">Заголовок секции</label>
                <input v-model="contentData.about_title" type="text" class="form-input" />
              </div>
              
              <div class="form-group image-upload-group">
                <label class="form-label">
                  <span class="label-icon">🖼️</span>
                  Фото в секции "Обо мне"
                </label>
                <div class="image-upload-container">
                  <input 
                    ref="aboutImageInput"
                    type="file" 
                    accept="image/*" 
                    @change="handleAboutImageUpload"
                    class="image-input"
                    id="about-image-upload"
                  />
                  <label for="about-image-upload" class="image-upload-btn compact">
                    <span class="upload-icon">📸</span>
                    Выбрать изображение
                  </label>
                  <div v-if="contentData.about_image_url" class="current-image-preview compact">
                    <img :src="contentData.about_image_url" alt="Preview" class="image-preview" />
                    <button @click="removeAboutImage" class="remove-image-btn" type="button">
                      <span>🗑️</span>
                      Удалить
                    </button>
                  </div>
                </div>
                <small class="form-hint">600x700px. JPG, PNG, WebP</small>
              </div>
            </div>
            <div class="form-group full-width">
              <label class="form-label">Первый абзац</label>
              <textarea v-model="contentData.about_intro_paragraph_1" class="form-textarea" rows="3"></textarea>
            </div>
            <div class="form-group full-width">
              <label class="form-label">Второй абзац</label>
              <textarea v-model="contentData.about_intro_paragraph_2" class="form-textarea" rows="3"></textarea>
            </div>
            <div class="form-group">
              <label class="form-label">Образование</label>
              <input v-model="contentData.about_credential_education" type="text" class="form-input" />
            </div>
            <div class="form-group">
              <label class="form-label">Сертификаты</label>
              <input v-model="contentData.about_credential_certificates" type="text" class="form-input" />
            </div>
            <div class="form-group">
              <label class="form-label">Кнопка "Подробнее"</label>
              <input v-model="contentData.about_button_more" type="text" class="form-input" />
            </div>
            <div class="form-group">
              <label class="form-label">Кнопка "Дипломы"</label>
              <input v-model="contentData.about_button_diplomas" type="text" class="form-input" />
            </div>
            
          </div>
          
          <div class="modal-content-section">
            <h3 class="subsection-title">
              <span class="subsection-icon">📄</span>
              Содержимое модального окна "Подробнее обо мне"
            </h3>
              
              <div class="form-group full-width">
                <label class="form-label">
                  <span class="label-icon">🎓</span>
                  Образование и подход
                </label>
                <textarea 
                  v-model="contentData.about_modal_education" 
                  class="form-textarea wysiwyg-textarea" 
                  rows="4"
                  placeholder="Расскажите о своем образовании и подходе к работе..."
                ></textarea>
              </div>
              
              <div class="form-group full-width">
                <label class="form-label">
                  <span class="label-icon">💼</span>
                  Опыт работы
                </label>
                <textarea 
                  v-model="contentData.about_modal_experience" 
                  class="form-textarea wysiwyg-textarea" 
                  rows="4"
                  placeholder="Опишите ваш опыт работы..."
                ></textarea>
              </div>
              
              <div class="form-group full-width">
                <label class="form-label">
                  <span class="label-icon">🤔</span>
                  Философия работы
                </label>
                <textarea 
                  v-model="contentData.about_modal_philosophy" 
                  class="form-textarea wysiwyg-textarea" 
                  rows="4"
                  placeholder="Расскажите о вашей философии работы..."
                ></textarea>
              </div>
              
              <div class="form-group full-width">
                <label class="form-label">
                  <span class="label-icon">📝</span>
                  Принципы работы
                </label>
                <textarea 
                  v-model="contentData.about_modal_principles" 
                  class="form-textarea wysiwyg-textarea" 
                  rows="4"
                  placeholder="Опишите ваши принципы работы..."
                ></textarea>
              </div>
              
              <div class="form-group full-width">
                <label class="form-label">
                  <span class="label-icon">⚡</span>
                  Ключевые компетенции
                </label>
                <textarea 
                  v-model="contentData.about_modal_competencies" 
                  class="form-textarea wysiwyg-textarea" 
                  rows="4"
                  placeholder="Перечислите ваши ключевые компетенции..."
                ></textarea>
              </div>
          </div>
          
          <div class="section-footer">
            <button @click="saveSection('about')" class="section-save-btn" :disabled="saving">
              <span class="btn-icon">💾</span>
              Сохранить секцию "Обо мне"
            </button>
          </div>
        </div>
        </div>

        <!-- Services Section -->
        <div v-show="activeTab === 'services'" class="section-group">
          <div class="section">
            <div class="section-header">
              <h2 class="section-title">
                <span class="section-icon">💼</span>
                Секция "Услуги"
              </h2>
              <p class="section-description">Управление форматами работы и ценами</p>
            </div>
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Заголовок секции</label>
              <input v-model="contentData.services_title" type="text" class="form-input" />
            </div>
            <div class="form-group">
              <label class="form-label">Подзаголовок "Форматы"</label>
              <input v-model="contentData.services_formats_title" type="text" class="form-input" />
            </div>
            <div class="form-group">
              <label class="form-label">Подзаголовок "Стоимость"</label>
              <input v-model="contentData.services_pricing_title" type="text" class="form-input" />
            </div>
            <div class="form-group">
              <label class="form-label">Подзаголовок "Способы"</label>
              <input v-model="contentData.services_methods_title" type="text" class="form-input" />
            </div>
            <div class="form-group full-width">
              <label class="form-label">Примечание о стоимости</label>
              <textarea v-model="contentData.services_pricing_note" class="form-textarea" rows="2"></textarea>
            </div>
            <div class="form-group full-width">
              <label class="form-label">Примечание о консультации</label>
              <textarea v-model="contentData.services_consultation_note" class="form-textarea" rows="2"></textarea>
            </div>
          </div>

          <div class="array-section">
            <h3>Форматы консультаций</h3>
            <div v-for="(format, index) in serviceFormats" :key="`format-${index}-${format.name}`" class="array-item">
              <div class="form-grid">
                <div class="form-group">
                  <label class="form-label">Название</label>
                  <input v-model="format.name" type="text" class="form-input" />
                </div>
                <div class="form-group">
                  <label class="form-label">Длительность</label>
                  <input v-model="format.duration" type="text" class="form-input" />
                </div>
                <div class="form-group">
                  <label class="form-label">Цена</label>
                  <input v-model="format.price" type="text" class="form-input" />
                </div>
                <div class="form-group full-width">
                  <label class="form-label">Описание</label>
                  <textarea v-model="format.description" class="form-textarea" rows="2"></textarea>
                </div>
              </div>
              <button @click="removeServiceFormat(index)" class="remove-button">Удалить формат</button>
            </div>
            <button @click="addServiceFormat" class="add-button">Добавить формат</button>
          </div>

          <div class="array-section">
            <h3>Способы проведения</h3>
            <div v-for="(method, index) in serviceMethods" :key="`method-${index}-${method.name}`" class="array-item">
              <div class="form-grid">
                <div class="form-group">
                  <label class="form-label">Название</label>
                  <input v-model="method.name" type="text" class="form-input" />
                </div>
                <div class="form-group full-width">
                  <label class="form-label">Описание</label>
                  <textarea v-model="method.description" class="form-textarea" rows="2"></textarea>
                </div>
              </div>
              <button @click="removeServiceMethod(index)" class="remove-button">Удалить способ</button>
            </div>
            <button @click="addServiceMethod" class="add-button">Добавить способ</button>
          </div>
          <div class="section-footer">
            <button @click="saveSection('services')" class="section-save-btn" :disabled="saving">
              <span class="btn-icon">💾</span>
              Сохранить услуги
            </button>
          </div>
          </div>
        </div>

        <!-- Methods Section -->
        <div v-show="activeTab === 'methods'" class="section-group">
          <div class="section">
            <div class="section-header">
              <h2 class="section-title">
                <span class="section-icon">🔬</span>
                Секция "Методы работы"
              </h2>
              <p class="section-description">Описание подходов к терапии</p>
            </div>
            <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Заголовок секции</label>
              <input v-model="contentData.methods_title" type="text" class="form-input" />
            </div>
            <div class="form-group">
              <label class="form-label">Подзаголовок методов</label>
              <input v-model="contentData.methods_subtitle" type="text" class="form-input" />
            </div>
            <div class="form-group">
              <label class="form-label">Заголовок принципов</label>
              <input v-model="contentData.methods_principles_title" type="text" class="form-input" />
            </div>
          </div>
          <div class="section-footer">
            <button @click="saveSection('methods')" class="section-save-btn" :disabled="saving">
              <span class="btn-icon">💾</span>
              Сохранить методы
            </button>
          </div>
        </div>
        </div>

        <!-- FAQ Section -->
        <div v-show="activeTab === 'faq'" class="section-group">
          <div class="section">
            <div class="section-header">
              <h2 class="section-title">
                <span class="section-icon">❓</span>
                Секция "FAQ"
              </h2>
              <p class="section-description">Часто задаваемые вопросы</p>
            </div>
            <div class="form-group">
            <label class="form-label">Заголовок секции</label>
            <input v-model="contentData.faq_title" type="text" class="form-input" />
          </div>

          <div class="array-section">
            <h3>Вопросы и ответы <span class="item-count">({{ faqItems.length }} вопроса)</span></h3>
            <div v-for="(faq, index) in faqItems" :key="index" class="array-item">
              <div class="form-group">
                <label class="form-label">Вопрос</label>
                <input v-model="faqItems[index].question" type="text" class="form-input" />
              </div>
              <div class="form-group">
                <label class="form-label">Ответ</label>
                <textarea v-model="faqItems[index].answer" class="form-textarea" rows="3"></textarea>
              </div>
              <button @click="removeFaqItem(index)" class="remove-button">Удалить вопрос</button>
            </div>
            <button @click="addFaqItem" class="add-button">Добавить вопрос</button>
          </div>
          <div class="section-footer">
            <button @click="saveSection('faq')" class="section-save-btn" :disabled="saving">
              <span class="btn-icon">💾</span>
              Сохранить FAQ
            </button>
          </div>
        </div>
        </div>

        <!-- Contact Section -->
        <div v-show="activeTab === 'contacts'" class="section-group">
          <div class="section">
            <div class="section-header">
              <h2 class="section-title">
                <span class="section-icon">📞</span>
                Секция "Контакты"
              </h2>
              <p class="section-description">Управление контактной информацией</p>
            </div>
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Заголовок секции</label>
              <input v-model="contentData.contact_title" type="text" class="form-input" />
            </div>
            <div class="form-group">
              <label class="form-label">
                <span class="label-icon">📧</span>
                Email адрес
              </label>
              <input v-model="contentData.contact_email" type="email" class="form-input" placeholder="example@email.com" />
            </div>
            <div class="form-group">
              <label class="form-label">
                <span class="label-icon">📱</span>
                Мобильный телефон
              </label>
              <input v-model="contentData.contact_phone" type="tel" class="form-input" placeholder="+7 (xxx) xxx-xx-xx" />
            </div>
            <div class="form-group">
              <label class="form-label">Telegram username</label>
              <input v-model="contentData.telegram_username" type="text" class="form-input" />
            </div>
            <div class="form-group">
              <label class="form-label">WhatsApp номер</label>
              <input v-model="contentData.whatsapp_number" type="text" class="form-input" />
            </div>
            <div class="form-group">
              <label class="form-label">Заголовок Telegram</label>
              <input v-model="contentData.contact_telegram_title" type="text" class="form-input" />
            </div>
            <div class="form-group">
              <label class="form-label">Описание Telegram</label>
              <input v-model="contentData.contact_telegram_description" type="text" class="form-input" />
            </div>
            <div class="form-group">
              <label class="form-label">Заголовок WhatsApp</label>
              <input v-model="contentData.contact_whatsapp_title" type="text" class="form-input" />
            </div>
            <div class="form-group">
              <label class="form-label">Описание WhatsApp</label>
              <input v-model="contentData.contact_whatsapp_description" type="text" class="form-input" />
            </div>
            <div class="form-group full-width">
              <label class="form-label">Примечание</label>
              <textarea v-model="contentData.contact_note_text" class="form-textarea" rows="2"></textarea>
            </div>
          </div>
          <div class="section-footer">
            <button @click="saveSection('contacts')" class="section-save-btn" :disabled="saving">
              <span class="btn-icon">💾</span>
              Сохранить контакты
            </button>
          </div>
        </div>
        </div>

        <!-- Education Section -->
        <div v-show="activeTab === 'education'" class="section-group">
          <div class="section">
            <div class="section-header">
              <h2 class="section-title">
                <span class="section-icon">🎓</span>
                Секция "Образование"
              </h2>
              <p class="section-description">Управление информацией об образовании</p>
            </div>
          
          <div class="gallery-management-section">
            <h3 class="subsection-title">
              <span class="subsection-icon">🖼️</span>
              Галерея сертификатов и дипломов
            </h3>
            
            <div class="gallery-uploader">
              <div 
                class="drop-zone"
                :class="{ 'drop-zone--dragover': isDragOver }"
                @dragover.prevent="handleDragOver"
                @dragleave.prevent="handleDragLeave"
                @drop.prevent="handleDrop"
                @click="triggerFileInput"
              >
                <input 
                  ref="galleryInput"
                  type="file"
                  multiple
                  accept="image/*"
                  @change="handleGalleryUpload"
                  class="gallery-input"
                />
                
                <div class="drop-zone-content">
                  <div class="drop-zone-icon">📁</div>
                  <p class="drop-zone-text">Перетащите изображения сюда или нажмите для выбора</p>
                  <small class="drop-zone-hint">Поддерживаемые форматы: JPG, PNG, WebP (до 5MB каждое)</small>
                </div>
              </div>
              
              <div v-if="galleryImages.length > 0" class="gallery-grid">
                <div 
                  v-for="(image, index) in galleryImages" 
                  :key="image.id"
                  class="gallery-item"
                  :class="{ 
                    'gallery-item--dragging': draggedIndex === index,
                    'gallery-item--drag-over': dragOverIndex === index
                  }"
                  :style="{ order: image.order }"
                  draggable="true"
                  @dragstart="handleDragStart(index, $event)"
                  @dragend="handleDragEnd"
                  @dragover.prevent="handleDragOverItem(index, $event)"
                  @dragleave="handleDragLeaveItem"
                  @drop.prevent="handleDropItem(index, $event)"
                >
                  <div class="gallery-image-container">
                    <div class="drag-handle" title="Перетащите для изменения порядка">
                      ⋮⋮
                    </div>
                    <img 
                      :src="image.url" 
                      :alt="image.alt || `Certificate ${index + 1}`"
                      class="gallery-image"
                      :style="{ transform: `rotate(${image.rotation || 0}deg)` }"
                    />
                    
                    <div class="gallery-controls">
                      <button @click="rotateImage(index, -90)" class="control-btn" title="Повернуть влево">
                        ↶
                      </button>
                      <button @click="rotateImage(index, 90)" class="control-btn" title="Повернуть вправо">
                        ↷
                      </button>
                      <button @click="removeGalleryImage(index)" class="control-btn delete-btn" title="Удалить">
                        🗑️
                      </button>
                    </div>
                  </div>
                  
                  <div class="image-meta">
                    <input 
                      v-model="image.alt"
                      type="text" 
                      placeholder="Описание изображения"
                      class="alt-input"
                    />
                  </div>
                </div>
              </div>
              
              <div v-if="galleryImages.length === 0" class="gallery-empty">
                <p>Изображения не загружены</p>
              </div>
            </div>
          </div>
          
          <div class="section-footer">
            <button @click="saveSection('education')" class="section-save-btn" :disabled="saving">
              <span class="btn-icon">💾</span>
              Сохранить образование
            </button>
          </div>
        </div>
        </div>

        <!-- Footer Section -->
        <div v-show="activeTab === 'footer'" class="section-group">
          <div class="section">
            <div class="section-header">
              <h2 class="section-title">
                <span class="section-icon">🦶</span>
                Секция "Футер"
              </h2>
              <p class="section-description">Управление текстом в нижней части сайта</p>
            </div>
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">
                <span class="label-icon">📧</span>
                Email в футере
              </label>
              <input v-model="contentData.footer_email" type="email" class="form-input" placeholder="contact@example.com" />
            </div>
            <div class="form-group">
              <label class="form-label">
                <span class="label-icon">📱</span>
                Телефон в футере
              </label>
              <input v-model="contentData.footer_phone" type="tel" class="form-input" placeholder="+7 (xxx) xxx-xx-xx" />
            </div>
            <div class="form-group full-width">
              <label class="form-label">
                <span class="label-icon">©️</span>
                Текст копирайта
              </label>
              <input v-model="contentData.footer_copyright" type="text" class="form-input" placeholder="© 2024 Психолог - Все права защищены" />
              <small class="form-hint">Отображается в самом низу страницы</small>
            </div>
          </div>
          <div class="section-footer">
            <button @click="saveSection('footer')" class="section-save-btn" :disabled="saving">
              <span class="btn-icon">💾</span>
              Сохранить футер
            </button>
          </div>
        </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/components/Admin/AdminLayout.vue'

interface ContentData {
  [key: string]: string
}

interface Contents {
  [section: string]: Array<{
    id: number
    key: string
    value: string
    type: string
    section: string
    description: string
  }>
}

const props = defineProps<{
  contents: Contents
}>()

const contentData = ref<ContentData>({})
const serviceFormats = ref<Array<{name: string, description: string, duration: string, price: string}>>([])
const serviceMethods = ref<Array<{name: string, description: string}>>([])
const faqItems = ref<Array<{question: string, answer: string}>>([])
const originalData = ref('')
const saving = ref(false)
const activeTab = ref('hero')

// Tab configuration
const tabs = [
  { 
    id: 'hero', 
    label: 'Главная', 
    icon: '🏠', 
    sections: ['hero'] 
  },
  { 
    id: 'about', 
    label: 'Обо мне', 
    icon: '👤', 
    sections: ['about'] 
  },
  { 
    id: 'services', 
    label: 'Услуги', 
    icon: '💼', 
    sections: ['services'] 
  },
  { 
    id: 'methods', 
    label: 'Методы', 
    icon: '🔬', 
    sections: ['methods'] 
  },
  { 
    id: 'faq', 
    label: 'FAQ', 
    icon: '❓', 
    sections: ['faq'] 
  },
  { 
    id: 'contacts', 
    label: 'Контакты', 
    icon: '📞', 
    sections: ['contacts'] 
  },
  { 
    id: 'education', 
    label: 'Образование', 
    icon: '🎓', 
    sections: ['education'] 
  },
  { 
    id: 'footer', 
    label: 'Футер', 
    icon: '🦶', 
    sections: ['footer'] 
  }
]
const successMessage = ref('')

const hasChanges = computed(() => {
  return JSON.stringify({
    contentData: contentData.value,
    serviceFormats: serviceFormats.value,
    serviceMethods: serviceMethods.value,
    faqItems: faqItems.value,
    galleryImages: galleryImages.value
  }) !== originalData.value
})

const getTabChangesCount = (sections: string[]) => {
  // This is a placeholder - you could implement more sophisticated change tracking per section
  return hasChanges.value ? 1 : 0
}

onMounted(() => {
  // Initialize content data
  Object.values(props.contents).flat().forEach(item => {
    if (item.type === 'json') {
      try {
        let parsed = item.value
        // Handle potential double-encoding
        if (typeof parsed === 'string') {
          parsed = JSON.parse(parsed)
        }
        if (typeof parsed === 'string') {
          parsed = JSON.parse(parsed) // Parse again if still string
        }
        
        if (item.key === 'services_formats') {
          serviceFormats.value = Array.isArray(parsed) ? parsed : []
        } else if (item.key === 'services_methods') {
          serviceMethods.value = Array.isArray(parsed) ? parsed : []
        } else if (item.key === 'faq_items') {
          faqItems.value = Array.isArray(parsed) ? parsed.map((item, index) => ({
            question: item.question || '',
            answer: item.answer || ''
          })) : []
        } else if (item.key === 'gallery_images') {
          galleryImages.value = Array.isArray(parsed) ? parsed.map((item, index) => ({
            id: item.id || Date.now().toString() + Math.random().toString(36).substring(2, 11),
            url: item.url || '',
            alt: item.alt || '',
            rotation: item.rotation || 0,
            order: item.order !== undefined ? item.order : index,
            filename: item.filename || ''
          })) : []
        }
      } catch (e) {
        console.error('Error parsing JSON for', item.key, e)
        // Set defaults if parsing fails
        if (item.key === 'services_formats') {
          serviceFormats.value = []
        } else if (item.key === 'services_methods') {
          serviceMethods.value = []
        } else if (item.key === 'faq_items') {
          faqItems.value = []
        } else if (item.key === 'gallery_images') {
          galleryImages.value = []
        }
      }
    } else {
      contentData.value[item.key] = item.value
    }
  })

  originalData.value = JSON.stringify({
    contentData: contentData.value,
    serviceFormats: serviceFormats.value,
    serviceMethods: serviceMethods.value,
    faqItems: faqItems.value,
    galleryImages: galleryImages.value
  })
})

const addServiceFormat = () => {
  serviceFormats.value.push({
    name: '',
    description: '',
    duration: '',
    price: ''
  })
}

const removeServiceFormat = (index: number) => {
  serviceFormats.value.splice(index, 1)
}

const addServiceMethod = () => {
  serviceMethods.value.push({
    name: '',
    description: ''
  })
}

const removeServiceMethod = (index: number) => {
  serviceMethods.value.splice(index, 1)
}

const addFaqItem = () => {
  faqItems.value.push({
    question: '',
    answer: ''
  })
}

const removeFaqItem = (index: number) => {
  faqItems.value.splice(index, 1)
}

const aboutImageInput = ref<HTMLInputElement | null>(null)
const galleryInput = ref<HTMLInputElement | null>(null)
const isDragOver = ref(false)

interface GalleryImage {
  id: string
  url: string
  alt: string
  rotation: number
  order: number
  filename: string
}

const galleryImages = ref<GalleryImage[]>([])

// Drag and drop state
const draggedIndex = ref<number | null>(null)
const dragOverIndex = ref<number | null>(null)

const handleAboutImageUpload = async (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  
  if (!file) return
  
  // Validate file type
  if (!file.type.startsWith('image/')) {
    alert('Пожалуйста, выберите файл изображения')
    return
  }
  
  // Validate file size (max 5MB)
  if (file.size > 5 * 1024 * 1024) {
    alert('Размер файла не должен превышать 5MB')
    return
  }
  
  try {
    // Delete old image first if it exists
    const oldImageUrl = contentData.value.about_image_url
    if (oldImageUrl) {
      try {
        await fetch('/admin/delete-image', {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          },
          body: JSON.stringify({
            image_url: oldImageUrl
          })
        })
      } catch (error) {
        console.warn('Failed to delete old image:', error)
        // Continue with upload even if deletion fails
      }
    }
    
    // Upload new image
    const formData = new FormData()
    formData.append('image', file)
    formData.append('section', 'about')
    
    const response = await fetch('/admin/upload-image', {
      method: 'POST',
      body: formData,
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      }
    })
    
    if (!response.ok) {
      if (response.status === 419) {
        alert('CSRF токен недействителен. Пожалуйста, обновите страницу и попробуйте снова.')
      } else {
        alert('Ошибка при загрузке изображения')
      }
      return
    }
    
    const data = await response.json()
    
    if (data.success) {
      contentData.value.about_image_url = data.url
      successMessage.value = 'Изображение успешно загружено!'
      setTimeout(() => {
        successMessage.value = ''
      }, 3000)
    } else {
      alert(data.message || 'Ошибка при загрузке изображения')
    }
  } catch (error) {
    console.error('Upload error:', error)
    alert('Ошибка при загрузке изображения')
  }
}

const removeAboutImage = async () => {
  const imageUrl = contentData.value.about_image_url
  
  if (!imageUrl) return
  
  try {
    const response = await fetch('/admin/delete-image', {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({
        image_url: imageUrl
      })
    })
    
    if (response.ok) {
      contentData.value.about_image_url = ''
      if (aboutImageInput.value) {
        aboutImageInput.value.value = ''
      }
      successMessage.value = 'Изображение успешно удалено!'
      setTimeout(() => {
        successMessage.value = ''
      }, 3000)
    } else {
      const data = await response.json()
      console.error('Delete error:', data)
      alert(data.message || 'Ошибка при удалении изображения')
    }
  } catch (error) {
    console.error('Delete error:', error)
    alert('Ошибка при удалении изображения')
  }
}

// Gallery Management Functions
const triggerFileInput = () => {
  galleryInput.value?.click()
}

const handleDragOver = (e: DragEvent) => {
  e.preventDefault()
  isDragOver.value = true
}

const handleDragLeave = (e: DragEvent) => {
  e.preventDefault()
  isDragOver.value = false
}

const handleDrop = (e: DragEvent) => {
  e.preventDefault()
  isDragOver.value = false
  
  const files = Array.from(e.dataTransfer?.files || [])
  uploadGalleryFiles(files)
}

const handleGalleryUpload = (event: Event) => {
  const target = event.target as HTMLInputElement
  const files = Array.from(target.files || [])
  uploadGalleryFiles(files)
}

const uploadGalleryFiles = async (files: File[]) => {
  for (const file of files) {
    // Validate file
    if (!file.type.startsWith('image/')) {
      alert(`Файл ${file.name} не является изображением`)
      continue
    }
    
    if (file.size > 5 * 1024 * 1024) {
      alert(`Файл ${file.name} слишком большой (максимум 5MB)`)
      continue
    }
    
    try {
      const formData = new FormData()
      formData.append('image', file)
      formData.append('section', 'gallery')
      
      const response = await fetch('/admin/upload-image', {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        }
      })
      
      if (response.ok) {
        const data = await response.json()
        if (data.success) {
          galleryImages.value.push({
            id: Date.now().toString() + Math.random().toString(36).substring(2, 11),
            url: data.url,
            alt: '',
            rotation: 0,
            order: galleryImages.value.length,
            filename: data.filename
          })
        } else {
          alert(`Ошибка при загрузке ${file.name}: ${data.message}`)
        }
      } else {
        const errorData = await response.json().catch(() => ({ message: 'Неизвестная ошибка сервера' }))
        alert(`Ошибка при загрузке ${file.name}: ${errorData.message}`)
      }
    } catch (error) {
      console.error('Gallery upload error:', error)
      alert(`Ошибка при загрузке ${file.name}: ${error.message}`)
    }
  }
  
  // Clear file input
  if (galleryInput.value) {
    galleryInput.value.value = ''
  }
}

const rotateImage = (index: number, degrees: number) => {
  const image = galleryImages.value[index]
  if (image) {
    image.rotation = (image.rotation + degrees) % 360
  }
}

const moveImage = (index: number, direction: number) => {
  if (direction === -1 && index > 0) {
    // Move left - swap with previous
    const temp = galleryImages.value[index]
    galleryImages.value[index] = galleryImages.value[index - 1]
    galleryImages.value[index - 1] = temp
    
    // Update order values
    galleryImages.value[index].order = index
    galleryImages.value[index - 1].order = index - 1
  } else if (direction === 1 && index < galleryImages.value.length - 1) {
    // Move right - swap with next
    const temp = galleryImages.value[index]
    galleryImages.value[index] = galleryImages.value[index + 1]
    galleryImages.value[index + 1] = temp
    
    // Update order values
    galleryImages.value[index].order = index
    galleryImages.value[index + 1].order = index + 1
  }
}

// Drag and drop handlers
const handleDragStart = (index: number, event: DragEvent) => {
  draggedIndex.value = index
  if (event.dataTransfer) {
    event.dataTransfer.effectAllowed = 'move'
    event.dataTransfer.setData('text/plain', index.toString())
  }
}

const handleDragEnd = () => {
  draggedIndex.value = null
  dragOverIndex.value = null
}

const handleDragOverItem = (index: number, event: DragEvent) => {
  if (draggedIndex.value !== null && draggedIndex.value !== index) {
    dragOverIndex.value = index
    if (event.dataTransfer) {
      event.dataTransfer.dropEffect = 'move'
    }
  }
}

const handleDragLeaveItem = () => {
  dragOverIndex.value = null
}

const handleDropItem = (dropIndex: number, _event: DragEvent) => {
  const dragIndex = draggedIndex.value
  
  if (dragIndex !== null && dragIndex !== dropIndex) {
    // Reorder the array
    const draggedItem = galleryImages.value[dragIndex]
    
    // Remove dragged item from its original position
    galleryImages.value.splice(dragIndex, 1)
    
    // Insert at new position (adjust index if we removed an item before the drop position)
    const adjustedDropIndex = dragIndex < dropIndex ? dropIndex - 1 : dropIndex
    galleryImages.value.splice(adjustedDropIndex, 0, draggedItem)
    
    // Update order values for all items
    galleryImages.value.forEach((img, idx) => {
      img.order = idx
    })
  }
  
  draggedIndex.value = null
  dragOverIndex.value = null
}

const removeGalleryImage = async (index: number) => {
  const image = galleryImages.value[index]
  if (!image) return
  
  try {
    // Delete image from server
    const response = await fetch('/admin/delete-image', {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({
        image_url: image.url
      })
    })
    
    if (response.ok) {
      galleryImages.value.splice(index, 1)
      // Update order values for remaining images
      galleryImages.value.forEach((img, idx) => {
        img.order = idx
      })
      successMessage.value = 'Изображение удалено из галереи!'
      setTimeout(() => {
        successMessage.value = ''
      }, 3000)
    } else {
      alert('Ошибка при удалении изображения')
    }
  } catch (error) {
    console.error('Delete error:', error)
    alert('Ошибка при удалении изображения')
  }
}

const saveSection = async (sectionName: string) => {
  saving.value = true
  
  const contents: Array<{key: string, value: string, type: string}> = []
  
  // Define which keys belong to each section
  const sectionKeys: Record<string, string[]> = {
    hero: ['hero_name', 'hero_subtitle', 'hero_description', 'hero_work_mode'],
    about: ['about_title', 'about_intro_paragraph_1', 'about_intro_paragraph_2', 'about_credential_education', 'about_credential_certificates', 'about_button_more', 'about_button_diplomas', 'about_image_url', 'about_modal_education', 'about_modal_experience', 'about_modal_philosophy', 'about_modal_principles', 'about_modal_competencies'],
    services: ['services_title', 'services_formats_title', 'services_pricing_title', 'services_methods_title', 'services_duration_label', 'services_pricing_note', 'services_consultation_note'],
    methods: ['methods_title', 'methods_subtitle', 'methods_principles_title'],
    faq: ['faq_title'],
    contacts: ['contact_title', 'contact_telegram_title', 'contact_telegram_description', 'contact_whatsapp_title', 'contact_whatsapp_description', 'contact_note_text', 'telegram_username', 'whatsapp_number'],
    education: ['education_title', 'education_main_title', 'education_additional_title', 'education_certificates_button', 'gallery_images'],
    footer: ['footer_email', 'footer_phone', 'footer_copyright']
  }
  
  // Add text content for the section
  if (sectionKeys[sectionName]) {
    sectionKeys[sectionName].forEach(key => {
      if (contentData.value[key] !== undefined) {
        contents.push({
          key,
          value: String(contentData.value[key]),
          type: 'text'
        })
      }
    })
  }
  
  // Add JSON content for services section
  if (sectionName === 'services') {
    contents.push({
      key: 'services_formats',
      value: JSON.stringify(serviceFormats.value),
      type: 'json'
    })
    
    contents.push({
      key: 'services_methods',
      value: JSON.stringify(serviceMethods.value),
      type: 'json'
    })
  }
  
  // Add FAQ items for FAQ section
  if (sectionName === 'faq') {
    contents.push({
      key: 'faq_items',
      value: JSON.stringify(faqItems.value),
      type: 'json'
    })
  }
  
  // Add gallery images for education section
  if (sectionName === 'education') {
    contents.push({
      key: 'gallery_images',
      value: JSON.stringify(galleryImages.value),
      type: 'json'
    })
  }
  
  try {
    const response = await fetch('/admin/content/save', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      body: JSON.stringify({
        contents
      })
    })
    
    if (response.ok) {
      successMessage.value = `Секция "${sectionName}" успешно сохранена!`
      
      setTimeout(() => {
        successMessage.value = ''
      }, 3000)
    } else {
      successMessage.value = 'Ошибка при сохранении'
    }
    
  } catch (error) {
    console.error('Save error:', error)
    successMessage.value = 'Ошибка при сохранении'
  }
  
  saving.value = false
}

const saveAllChanges = async () => {
  saving.value = true
  
  const contents: Array<{key: string, value: string, type: string}> = []
  
  // Add text content
  Object.entries(contentData.value).forEach(([key, value]) => {
    contents.push({
      key,
      value: String(value),
      type: 'text'
    })
  })
  
  // Add JSON content
  contents.push({
    key: 'services_formats',
    value: JSON.stringify(serviceFormats.value),
    type: 'json'
  })
  
  contents.push({
    key: 'services_methods',
    value: JSON.stringify(serviceMethods.value),
    type: 'json'
  })
  
  contents.push({
    key: 'faq_items',
    value: JSON.stringify(faqItems.value),
    type: 'json'
  })
  
  contents.push({
    key: 'gallery_images',
    value: JSON.stringify(galleryImages.value),
    type: 'json'
  })

  const form = useForm({
    contents
  })

  form.post('/admin/content', {
    onSuccess: () => {
      originalData.value = JSON.stringify({
        contentData: contentData.value,
        serviceFormats: serviceFormats.value,
        serviceMethods: serviceMethods.value,
        faqItems: faqItems.value,
        galleryImages: galleryImages.value
      })
      successMessage.value = 'Контент успешно обновлен!'
      setTimeout(() => {
        successMessage.value = ''
      }, 3000)
    },
    onFinish: () => {
      saving.value = false
    }
  })
}
</script>

<style scoped>
.content-management {
  min-height: 100vh;
  background: #f8fafc;
  padding: 0.5rem;
  max-width: none;
  min-width: 1600px;
  width: 100%;
}

.page-header {
  background: white;
  border-radius: 12px;
  padding: 2rem;
  margin-bottom: 1rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  border: 1px solid #e2e8f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 2rem;
}

.header-content h1 {
  font-size: 2.5rem;
  font-weight: 700;
  color: #1a202c;
  margin: 0 0 0.5rem 0;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.title-icon {
  font-size: 2rem;
}

.page-subtitle {
  color: #718096;
  font-size: 1.1rem;
  margin: 0;
  font-weight: 400;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 1rem;
  flex-direction: column;
}

.changes-indicator {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #f56565;
  font-size: 0.875rem;
  font-weight: 500;
}

.indicator-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #f56565;
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.5; }
}

.save-button {
  background: #10b981;
  color: white;
  border: none;
  padding: 0.875rem 1.75rem;
  border-radius: 8px;
  font-weight: 600;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
  min-width: 180px;
  justify-content: center;
}

.save-button:hover:not(:disabled) {
  background: #059669;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.save-button.has-changes {
  animation: glow 2s ease-in-out infinite alternate;
}

@keyframes glow {
  from { box-shadow: 0 4px 20px rgba(72, 187, 120, 0.3); }
  to { box-shadow: 0 4px 20px rgba(72, 187, 120, 0.6); }
}

.save-button:disabled {
  background: #9ca3af;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
  animation: none;
}

.save-button.saving {
  background: #3b82f6;
}

.button-icon {
  font-size: 1.2rem;
}

.notification {
  border-radius: 12px;
  padding: 1rem 1.5rem;
  margin-bottom: 2rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-weight: 500;
  animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

.notification.success {
  background: #10b981;
  color: white;
  box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.notification-icon {
  font-size: 1.2rem;
}

/* Tabs */
.tabs-container {
  background: white;
  border-radius: 12px;
  padding: 1rem;
  margin-bottom: 1rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  border: 1px solid #e2e8f0;
}

.tabs {
  display: flex;
  gap: 0.5rem;
  overflow-x: auto;
  padding-bottom: 0.5rem;
}

.tab {
  background: transparent;
  border: none;
  padding: 1rem 1.5rem;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-weight: 500;
  color: #718096;
  white-space: nowrap;
  position: relative;
  min-width: fit-content;
}

.tab:hover {
  background: #f7fafc;
  color: #4a5568;
}

.tab.active {
  background: #4f46e5;
  color: white;
  box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
}

.tab-icon {
  font-size: 1.2rem;
}

.tab-label {
  font-weight: 600;
}

.tab-badge {
  background: rgba(245, 101, 101, 0.9);
  color: white;
  border-radius: 10px;
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
  font-weight: 600;
  min-width: 1.5rem;
  text-align: center;
}

.tab.active .tab-badge {
  background: rgba(255, 255, 255, 0.3);
}

/* Content Sections */
.content-sections {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.section-group {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.section {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  border: 1px solid #e2e8f0;
  margin-bottom: 0;
}

.section-header {
  background: #f8fafc;
  padding: 1.5rem 2rem;
  border-bottom: 1px solid #e2e8f0;
}

.section-title {
  font-size: 1.75rem;
  font-weight: 700;
  color: #1a202c;
  margin: 0 0 0.5rem 0;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.section-icon {
  font-size: 1.5rem;
}

.section-badge {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.section-description {
  color: #718096;
  font-size: 1rem;
  margin: 0;
  font-weight: 400;
}

/* Section Footer */
.section-footer {
  padding: 1.5rem 2rem;
  background: #f8fafc;
  border-top: 1px solid #e2e8f0;
  display: flex;
  justify-content: flex-end;
}

.section-save-btn {
  background: #10b981;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
}

.section-save-btn:hover:not(:disabled) {
  background: #059669;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.section-save-btn:disabled {
  background: #9ca3af;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.btn-icon {
  font-size: 1rem;
}

/* Forms */
.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 1.5rem;
  padding: 2rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-label {
  font-weight: 600;
  color: #2d3748;
  font-size: 0.95rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.label-icon {
  font-size: 1rem;
}

.form-hint {
  font-size: 0.8rem;
  color: #a0aec0;
  font-style: italic;
  margin-top: 0.25rem;
}

.form-input,
.form-textarea {
  padding: 1rem 1.25rem;
  border: 2px solid #e2e8f0;
  border-radius: 12px;
  font-size: 1rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  background: #f8fafc;
  color: #2d3748;
  font-weight: 500;
}

.form-input:focus,
.form-textarea:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
  background: white;
  transform: translateY(-1px);
}

.form-input::placeholder,
.form-textarea::placeholder {
  color: #a0aec0;
  font-weight: 400;
}

.form-textarea {
  resize: vertical;
  font-family: inherit;
  min-height: 120px;
}

/* Array Sections */
.array-section {
  margin-top: 2rem;
  padding: 2rem;
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  border-radius: 16px;
  border: 1px solid #e2e8f0;
}

.array-section h3 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1a202c;
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.item-count {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
}

.array-item {
  background: white;
  padding: 2rem;
  border-radius: 16px;
  margin-bottom: 1.5rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
}

.array-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
}

.add-button {
  background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
  color: white;
  border: none;
  padding: 1rem 2rem;
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
}

.add-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(72, 187, 120, 0.4);
}

.add-button::before {
  content: '+ ';
  font-weight: 700;
  font-size: 1.2rem;
}

.remove-button {
  background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 10px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: absolute;
  top: 1rem;
  right: 1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  box-shadow: 0 2px 10px rgba(245, 101, 101, 0.3);
}

.remove-button:hover {
  transform: scale(1.05);
  box-shadow: 0 4px 15px rgba(245, 101, 101, 0.4);
}

.remove-button::before {
  content: '🗑️';
  font-size: 1rem;
}

/* Form Row Layout */
.form-row {
  display: flex;
  gap: 3rem;
  align-items: flex-start;
  margin-bottom: 1.5rem;
  width: 100%;
}

.form-row .form-group {
  flex: 2;
  margin-bottom: 0;
  min-width: 300px;
}

.form-row .image-upload-group {
  flex: 3;
  min-width: 500px;
}

.image-upload-group .form-label {
  font-size: 0.9rem;
  font-weight: 600;
  margin-bottom: 0.75rem;
}

.image-upload-group .form-hint {
  font-size: 0.8rem;
  color: #6b7280;
  margin-top: 0.5rem;
}

.image-upload-btn.compact {
  padding: 0.75rem 1rem;
  font-size: 0.875rem;
}

.current-image-preview.compact {
  padding: 0.75rem;
}

.current-image-preview.compact .image-preview {
  width: 60px;
  height: 60px;
}

.current-image-preview.compact .remove-image-btn {
  padding: 0.375rem 0.75rem;
  font-size: 0.8rem;
}

/* Responsive behavior for form row */
@media (max-width: 1024px) {
  .form-row {
    flex-direction: column;
    gap: 1.5rem;
  }
  
  .form-row .image-upload-group {
    flex: none;
    width: 100%;
  }
}

/* Gallery Management Styles */
.gallery-management-section {
  margin-top: 2rem;
  padding: 2rem;
  background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
  border-radius: 16px;
  border: 1px solid #e2e8f0;
}

.drop-zone {
  border: 2px dashed #cbd5e0;
  border-radius: 12px;
  padding: 3rem;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  background: #f7fafc;
  margin-bottom: 2rem;
}

.drop-zone:hover,
.drop-zone--dragover {
  border-color: #4f46e5;
  background: #eef2ff;
  transform: translateY(-2px);
}

.gallery-input {
  display: none;
}

.drop-zone-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}

.drop-zone-icon {
  font-size: 3rem;
  opacity: 0.6;
}

.drop-zone-text {
  font-size: 1.125rem;
  font-weight: 600;
  color: #4a5568;
  margin: 0;
}

.drop-zone-hint {
  color: #718096;
  font-size: 0.875rem;
}

.gallery-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
  margin-top: 2rem;
}

.gallery-item {
  background: white;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  overflow: hidden;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.gallery-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.gallery-image-container {
  position: relative;
  aspect-ratio: 4/3;
  overflow: hidden;
}

.gallery-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.gallery-controls {
  position: absolute;
  top: 8px;
  right: 8px;
  display: flex;
  flex-direction: column;
  gap: 4px;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.gallery-item:hover .gallery-controls {
  opacity: 1;
}

.control-btn {
  width: 32px;
  height: 32px;
  background: rgba(0, 0, 0, 0.7);
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  transition: all 0.2s ease;
}

.control-btn:hover {
  background: rgba(0, 0, 0, 0.9);
  transform: scale(1.05);
}

.control-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none;
}

.control-btn.delete-btn:hover {
  background: #ef4444;
}

.image-meta {
  padding: 1rem;
}

.alt-input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 0.875rem;
  transition: border-color 0.2s ease;
}

.alt-input:focus {
  outline: none;
  border-color: #4f46e5;
  box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.gallery-empty {
  text-align: center;
  padding: 2rem;
  color: #6b7280;
  font-style: italic;
}

/* Modal Content Section Styles */
.modal-content-section {
  margin-top: 2rem;
  padding: 2rem;
  background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
  border-radius: 16px;
  border: 1px solid #e2e8f0;
  width: 100%;
  max-width: none;
}

.subsection-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1a202c;
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.subsection-icon {
  font-size: 1.2rem;
}

.wysiwyg-textarea {
  min-height: 120px;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  line-height: 1.6;
  resize: vertical;
}

.wysiwyg-textarea:focus {
  min-height: 150px;
}

/* Image Upload Styles */
.image-upload-container {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  align-items: flex-start;
}

.image-input {
  display: none;
}

.image-upload-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 1rem 1.5rem;
  background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
  color: white;
  border: none;
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
}

.image-upload-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
}

.upload-icon {
  font-size: 1.2rem;
}

.current-image-preview {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  background: #f8fafc;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
}

.image-preview {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 8px;
  border: 2px solid #e2e8f0;
}

.remove-image-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 0.875rem;
}

.remove-image-btn:hover {
  transform: scale(1.05);
  box-shadow: 0 4px 15px rgba(245, 101, 101, 0.4);
}

/* Responsive Design */
@media (max-width: 768px) {
  .content-management {
    padding: 0.5rem;
  }
  
  .page-header {
    flex-direction: column;
    align-items: stretch;
    gap: 1.5rem;
    padding: 1.5rem;
  }
  
  .header-actions {
    flex-direction: row;
    justify-content: space-between;
  }
  
  .tabs {
    flex-wrap: wrap;
    justify-content: center;
  }
  
  .tab {
    flex: 1;
    min-width: 120px;
    justify-content: center;
  }
  
  .form-grid {
    grid-template-columns: 1fr;
    gap: 1rem;
    padding: 1.5rem;
  }
  
  .section-header {
    padding: 1.5rem;
  }
  
  .section-title {
    font-size: 1.5rem;
  }
  
  .array-section {
    padding: 1.5rem;
  }
  
  .array-item {
    padding: 1.5rem;
    padding-top: 3rem;
  }
  
  .remove-button {
    top: 0.5rem;
    right: 0.5rem;
    padding: 0.5rem 1rem;
  }
}

@media (max-width: 480px) {
  .page-header {
    padding: 1rem;
  }
  
  .header-content h1 {
    font-size: 1.8rem;
  }
  
  .page-subtitle {
    font-size: 1rem;
  }
  
  .save-button {
    padding: 0.875rem 1.5rem;
    font-size: 0.9rem;
  }
  
  .tabs-container {
    padding: 0.75rem;
  }
  
  .tab {
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
  }
  
  .form-grid {
    padding: 1rem;
  }
  
  .section-header {
    padding: 1rem;
  }
}

/* Drag and Drop Styles */
.gallery-item {
  transition: all 0.2s ease;
}

.gallery-item--dragging {
  opacity: 0.5;
  transform: scale(0.95);
  z-index: 1000;
}

.gallery-item--drag-over {
  border: 2px dashed #4f46e5;
  background-color: #f0f4ff;
  transform: scale(1.02);
}

.drag-handle {
  position: absolute;
  top: 8px;
  left: 8px;
  background: rgba(0, 0, 0, 0.7);
  color: white;
  border-radius: 4px;
  padding: 4px 6px;
  font-size: 12px;
  font-weight: bold;
  cursor: grab;
  user-select: none;
  line-height: 1;
  z-index: 10;
  opacity: 0;
  transition: opacity 0.2s ease;
}

.gallery-item:hover .drag-handle {
  opacity: 1;
}

.drag-handle:active {
  cursor: grabbing;
}

.gallery-image-container {
  position: relative;
}

/* Enhanced gallery controls */
.gallery-controls {
  position: absolute;
  bottom: 8px;
  right: 8px;
  display: flex;
  gap: 4px;
  opacity: 0;
  transition: opacity 0.2s ease;
  background: rgba(0, 0, 0, 0.7);
  border-radius: 6px;
  padding: 4px;
}

.gallery-item:hover .gallery-controls {
  opacity: 1;
}

.control-btn {
  background: rgba(255, 255, 255, 0.9);
  border: none;
  border-radius: 4px;
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 14px;
  transition: all 0.2s ease;
}

.control-btn:hover {
  background: white;
  transform: scale(1.1);
}

.control-btn.delete-btn {
  background: rgba(239, 68, 68, 0.9);
  color: white;
}

.control-btn.delete-btn:hover {
  background: rgb(239, 68, 68);
}
</style>