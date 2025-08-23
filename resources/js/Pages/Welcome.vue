<template>
  <div id="app" class="app">
    <header class="header" role="banner">
      <nav class="nav container" role="navigation" aria-label="Основная навигация">
        <a 
          href="/" 
          class="logo"
          aria-label="На главную страницу"
        >
          <h1 class="logo-text">Психолог</h1>
        </a>

        <!-- Десктопная навигация -->
        <ul class="nav-list desktop-nav" role="menubar">
          <li role="none">
            <a 
              href="#about" 
              class="nav-link"
              role="menuitem"
              @click="closeMobileMenu"
            >
              Обо мне
            </a>
          </li>
          <li role="none">
            <a 
              href="#services" 
              class="nav-link"
              role="menuitem"
              @click="closeMobileMenu"
            >
              Услуги
            </a>
          </li>
          <li role="none">
            <a 
              href="#methods" 
              class="nav-link"
              role="menuitem"
              @click="closeMobileMenu"
            >
              Методы
            </a>
          </li>
          <li role="none">
            <a 
              href="#faq" 
              class="nav-link"
              role="menuitem"
              @click="closeMobileMenu"
            >
              Вопросы
            </a>
          </li>
          <li role="none">
            <a 
              href="#contact" 
              class="nav-link"
              role="menuitem"
              @click="closeMobileMenu"
            >
              Контакты
            </a>
          </li>
        </ul>

        <!-- Мобильная кнопка меню -->
        <button 
          class="mobile-menu-btn"
          @click="toggleMobileMenu"
          :aria-expanded="mobileMenuOpen"
          aria-controls="mobile-nav"
          aria-label="Переключить меню"
        >
          <span class="hamburger-line"></span>
          <span class="hamburger-line"></span>
          <span class="hamburger-line"></span>
        </button>

        <!-- Мобильная навигация -->
        <div 
          id="mobile-nav"
          class="mobile-nav"
          :class="{ 'mobile-nav--open': mobileMenuOpen }"
          role="menu"
          :aria-hidden="!mobileMenuOpen"
        >
          <ul class="mobile-nav-list" role="none">
            <li role="none">
              <a 
                href="#hero" 
                class="mobile-nav-link"
                role="menuitem"
                @click="closeMobileMenu"
              >
                Главная
              </a>
            </li>
            <li role="none">
              <a 
                href="#about" 
                class="mobile-nav-link"
                role="menuitem"
                @click="closeMobileMenu"
              >
                Обо мне
              </a>
            </li>
            <li role="none">
              <a 
                href="#services" 
                class="mobile-nav-link"
                role="menuitem"
                @click="closeMobileMenu"
              >
                Услуги
              </a>
            </li>
            <li role="none">
              <a 
                href="#methods" 
                class="mobile-nav-link"
                role="menuitem"
                @click="closeMobileMenu"
              >
                Методы
              </a>
            </li>
            <li role="none">
              <a 
                href="#faq" 
                class="mobile-nav-link"
                role="menuitem"
                @click="closeMobileMenu"
              >
                Вопросы
              </a>
            </li>
            <li role="none">
              <a 
                href="#contact" 
                class="mobile-nav-link"
                role="menuitem"
                @click="closeMobileMenu"
              >
                Контакты
              </a>
            </li>
          </ul>
        </div>
      </nav>
    </header>

    <main class="main" role="main">
      <div class="home">
        <!-- Hero Section -->
        <div id="hero">
          <HeroSection 
            :имя="content?.hero_name || 'Раца Лилия'"
            :подзаголовок="content?.hero_subtitle || 'Психологическая поддержка и сопровождение'"
            :описание="content?.hero_description || 'Помогаю людям находить внутренние ресурсы'"
            :режимРаботы="content?.hero_work_mode || 'Онлайн консультации • Индивидуальная работа'"
            :telegram-username="content?.telegram_username || 'anna_psychologist'"
            :whatsapp-number="content?.whatsapp_number || '79001234567'"
          />
        </div>

        <!-- About Section (now includes education/credentials) -->
        <div id="about">
          <AboutSection 
            :content="content"
            @show-credentials="showCertificates" 
          />
        </div>

        <!-- Services Section (what I offer) -->
        <div id="services">
          <ServicesSection :content="content" />
        </div>

        <!-- Methods Section (how I work) -->
        <div id="methods">
          <MethodsSection :content="content" />
        </div>

        <!-- FAQ Section (common questions) -->
        <div id="faq">
          <FAQSection :content="content" />
        </div>

        <!-- Contact Section -->
        <div id="contact">
          <ContactSection 
            :content="content"
            :telegram-username="content?.telegram_username || 'anna_psychologist'"
            :whatsapp-number="content?.whatsapp_number || '79001234567'"
          />
        </div>

        <!-- Certificate Gallery Modal -->
        <CertificateGallery 
          :content="content"
          :is-visible="certificateGalleryVisible"
          @close="closeCertificates"
        />
      </div>
    </main>

    <footer class="footer" role="contentinfo">
      <div class="container">
        <div class="footer-content">
          <div class="footer-section" v-if="content?.footer_phone || content?.footer_email">
            <h3>Контакты</h3>
            <p v-if="content?.footer_phone">{{ content.footer_phone }}</p>
            <p v-if="content?.footer_email">{{ content.footer_email }}</p>
          </div>
          <div class="footer-section">
            <p>{{ content?.footer_copyright || `© ${currentYear} Психолог. Все права защищены.` }}</p>
          </div>
        </div>
      </div>
    </footer>

    <BackToTopButton :content="content" />
  </div>
</template>

<script setup lang="ts">
import { computed, ref, onMounted, onUnmounted } from 'vue'
import { useMainStore } from '@/stores/main'
import BackToTopButton from '@/components/BackToTopButton.vue'
import HeroSection from '@/components/HeroSection.vue'
import AboutSection from '@/components/AboutSection.vue'
import ServicesSection from '@/components/ServicesSection.vue'
import MethodsSection from '@/components/MethodsSection.vue'
import FAQSection from '@/components/FAQSection.vue'
import ContactSection from '@/components/ContactSection.vue'
import CertificateGallery from '@/components/CertificateGallery.vue'

interface ContentData {
  [key: string]: any
}

const props = defineProps<{
  content?: ContentData
}>()

const store = useMainStore()

const mobileMenuOpen = computed(() => store.mobileMenu)
const contacts = computed(() => store.contacts)
const currentYear = new Date().getFullYear()

const toggleMobileMenu = store.toggleMobileMenu
const closeMobileMenu = store.closeMobileMenu

// Certificate gallery state
const certificateGalleryVisible = ref(false)

const showCertificates = () => {
  certificateGalleryVisible.value = true
}

const closeCertificates = () => {
  certificateGalleryVisible.value = false
}

// Smooth scrolling setup
const enableSmoothScrolling = () => {
  document.documentElement.style.scrollBehavior = 'smooth'
}

onMounted(() => {
  enableSmoothScrolling()
})

onUnmounted(() => {
  document.documentElement.style.scrollBehavior = 'auto'
})
</script>

<style scoped>
.app {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.header {
  background-color: var(--color-primary);
  position: sticky;
  top: 0;
  z-index: 100;
  position: relative;
}

.header::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, 
    transparent 0%, 
    var(--color-taupe-light) 15%, 
    var(--color-taupe) 50%, 
    var(--color-taupe-dark) 85%, 
    transparent 100%
  );
}

.nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: var(--spacing-md);
  padding-bottom: var(--spacing-md);
}

.logo {
  text-decoration: none;
}

.logo-text {
  font-size: var(--font-size-2xl);
  font-weight: var(--font-weight-bold);
  color: var(--text-primary);
  margin: 0;
}

.desktop-nav {
  display: none;
  list-style: none;
  margin: 0;
  padding: 0;
  gap: var(--spacing-lg);
}

.nav-link {
  color: var(--text-primary);
  text-decoration: none;
  font-weight: var(--font-weight-medium);
  padding: var(--spacing-sm) var(--spacing-md);
  border-radius: var(--border-radius-md);
  transition: all var(--transition-fast);
  position: relative;
}

.nav-link::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 0;
  height: 2px;
  background: linear-gradient(90deg, var(--color-taupe-light), var(--color-taupe));
  border-radius: 1px;
  transition: width var(--transition-fast);
}

.nav-link:hover::after {
  width: 80%;
}

.nav-link:focus {
  background: linear-gradient(135deg, var(--color-warm), var(--color-accent-peach));
  color: var(--text-primary);
}


.nav-link--active {
  background: linear-gradient(135deg, var(--color-accent-soft), var(--color-accent-peach));
  color: var(--text-primary);
}

.mobile-menu-btn {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  width: 2.5rem;
  height: 2.5rem;
  background: none;
  border: none;
  cursor: pointer;
  padding: var(--spacing-sm);
  gap: 0.25rem;
}

.hamburger-line {
  width: 1.5rem;
  height: 2px;
  background-color: var(--text-primary);
  transition: all var(--transition-fast);
}

.mobile-nav {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background-color: var(--color-primary);
  border-bottom: 1px solid var(--color-accent);
  transform: translateY(-100%);
  opacity: 0;
  visibility: hidden;
  transition: all var(--transition-normal);
}

.mobile-nav--open {
  transform: translateY(0);
  opacity: 1;
  visibility: visible;
}

.mobile-nav-list {
  list-style: none;
  margin: 0;
  padding: var(--spacing-md);
}

.mobile-nav-link {
  display: block;
  color: var(--text-primary);
  text-decoration: none;
  padding: var(--spacing-md);
  font-weight: var(--font-weight-medium);
  border-radius: var(--border-radius-md);
  transition: all var(--transition-fast);
  position: relative;
}

.mobile-nav-link::before {
  content: '';
  position: absolute;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
  width: 0;
  height: 60%;
  background: linear-gradient(180deg, var(--color-taupe-light), var(--color-taupe-dark));
  border-radius: 0 3px 3px 0;
  transition: width var(--transition-fast);
}

.mobile-nav-link:hover,
.mobile-nav-link:focus {
  background: linear-gradient(135deg, var(--color-warm), var(--color-accent-peach));
  padding-left: calc(var(--spacing-md) + 8px);
}

.mobile-nav-link:hover::before,
.mobile-nav-link:focus::before {
  width: 4px;
}

.main {
  flex: 1;
}

.home {
  min-height: 100vh;
  position: relative;
}

/* Smooth scrolling for the entire page */
html {
  scroll-behavior: smooth;
}

/* Custom scrollbar styling */
:global(html) {
  scrollbar-width: thin;
  scrollbar-color: rgba(200, 200, 200, 0.5) transparent;
}

:global(::-webkit-scrollbar) {
  width: 8px;
}

:global(::-webkit-scrollbar-track) {
  background: transparent;
}

:global(::-webkit-scrollbar-thumb) {
  background-color: rgba(200, 200, 200, 0.5);
  border-radius: 4px;
  transition: background-color 0.3s ease;
}

:global(::-webkit-scrollbar-thumb:hover) {
  background-color: rgba(150, 150, 150, 0.7);
}

/* Section spacing and flow */
.home > * + * {
  position: relative;
}

/* Add subtle section separators */
.home > *:not(:last-child)::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(200, 200, 200, 0.3), transparent);
  pointer-events: none;
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
  :global(html) {
    scroll-behavior: auto;
  }
}

/* Performance optimizations */
.home {
  contain: layout style paint;
}

/* Intersection observer targets for animations */
.home > * {
  scroll-margin-top: var(--spacing-xl);
}

.footer {
  background-color: var(--color-warm-light);
  border-top: 1px solid var(--color-accent);
  margin-top: auto;
}

.footer-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: var(--spacing-xl) 0;
}

.footer-section h3 {
  font-size: var(--font-size-lg);
  margin-bottom: var(--spacing-sm);
}

.footer-section p {
  margin-bottom: var(--spacing-xs);
  color: var(--text-secondary);
}

/* Адаптивность */
@media (min-width: 768px) {
  .desktop-nav {
    display: flex;
  }

  .mobile-menu-btn {
    display: none;
  }

  .mobile-nav {
    display: none;
  }
}

@media (max-width: 767px) {
  .footer-content {
    flex-direction: column;
    text-align: center;
    gap: var(--spacing-md);
  }
}
</style>