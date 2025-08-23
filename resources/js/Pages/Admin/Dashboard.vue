<template>
  <AdminLayout>
    <div class="dashboard">
      <h1 class="dashboard-title">Панель управления</h1>
      
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon">📊</div>
          <div class="stat-content">
            <h3>Статистика сайта</h3>
            <p>Просмотры, посетители, конверсия</p>
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon">📝</div>
          <div class="stat-content">
            <h3>Управление контентом</h3>
            <p>Редактирование текстов и изображений</p>
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon">📧</div>
          <div class="stat-content">
            <h3>Сообщения</h3>
            <p>Обращения от клиентов</p>
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon">⚙️</div>
          <div class="stat-content">
            <h3>Настройки</h3>
            <p>Конфигурация сайта</p>
          </div>
        </div>
      </div>

      <div class="quick-actions">
        <h2>Быстрые действия</h2>
        <div class="actions-grid">
          <Link href="/admin/content" class="action-button">
            <span class="action-icon">✏️</span>
            <span>Редактировать контент</span>
          </Link>
          
          <button class="action-button">
            <span class="action-icon">📊</span>
            <span>Просмотреть статистику</span>
          </button>
          
          <button class="action-button">
            <span class="action-icon">🔧</span>
            <span>Настройки</span>
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/components/Admin/AdminLayout.vue'
</script>

<style scoped>
.dashboard {
  padding: 2rem;
}

.dashboard-title {
  font-size: 2rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 2rem;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 3rem;
}

.stat-card {
  background: white;
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  border: 1px solid #e5e7eb;
  display: flex;
  align-items: center;
  gap: 1rem;
  transition: all 0.2s;
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.stat-icon {
  font-size: 2rem;
  width: 3rem;
  height: 3rem;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f3f4f6;
  border-radius: 8px;
}

.stat-content h3 {
  font-size: 1.125rem;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 0.5rem;
}

.stat-content p {
  color: #6b7280;
  font-size: 0.875rem;
}

.quick-actions h2 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 1.5rem;
}

.actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.action-button {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  padding: 1rem 1.5rem;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  text-decoration: none;
}

.action-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.action-icon {
  font-size: 1.25rem;
}
</style>