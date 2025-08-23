<template>
  <div class="admin-layout">
    <header class="admin-header">
      <div class="header-content">
        <h1 class="admin-logo">Управление контентом сайта</h1>
        <div class="header-actions">
          <Link href="/" class="view-site-link">Посмотреть сайт</Link>
          <form @submit.prevent="logout" class="logout-form">
            <button type="submit" class="logout-button">Выйти</button>
          </form>
        </div>
      </div>
    </header>

    <main class="admin-main">
      <slot />
    </main>

    <footer class="admin-footer">
      <p>&copy; 2024 Психолог - Админ панель</p>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3'

const logoutForm = useForm({})

const logout = () => {
  logoutForm.post('/logout')
}
</script>

<style scoped>
.admin-layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: #f3f4f6;
}

.admin-header {
  background: white;
  border-bottom: 1px solid #e5e7eb;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.header-content {
  max-width: 1600px;
  margin: 0 auto;
  padding: 1rem 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.admin-logo {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
  margin: 0;
}

.admin-nav {
  display: flex;
  gap: 2rem;
}

.nav-link {
  color: #6b7280;
  text-decoration: none;
  font-weight: 500;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  transition: all 0.2s;
}

.nav-link:hover,
.nav-link.active {
  color: #667eea;
  background: #f3f4f6;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.view-site-link {
  color: #667eea;
  text-decoration: none;
  font-weight: 500;
  padding: 0.5rem 1rem;
  border: 1px solid #667eea;
  border-radius: 6px;
  transition: all 0.2s;
}

.view-site-link:hover {
  background: #667eea;
  color: white;
}

.logout-form {
  margin: 0;
}

.logout-button {
  background: #ef4444;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.logout-button:hover {
  background: #dc2626;
}

.admin-main {
  flex: 1;
  max-width: 1600px;
  margin: 0 auto;
  width: 100%;
}

.admin-footer {
  background: white;
  border-top: 1px solid #e5e7eb;
  padding: 1rem 2rem;
  text-align: center;
  color: #6b7280;
  font-size: 0.875rem;
}

@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    gap: 1rem;
    align-items: stretch;
  }

  .admin-nav {
    justify-content: center;
  }

  .header-actions {
    justify-content: center;
  }
}
</style>