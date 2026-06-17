<template>
  <div class="login-wrapper fade-in">
    <div class="login-card">
      <div class="glow-point"></div>
      
      <!-- Brand/Logo -->
      <div class="brand">
        <span class="logo-icon">⬡</span>
        <h1 class="logo-text">Souqi<span class="text-accent">Tech</span></h1>
        <p class="subtitle">{{ t.adminPortal }}</p>
      </div>

      <!-- Error Alert -->
      <div v-if="errorMsg" class="alert alert-error">
        <span>❌</span>
        <p>{{ errorMsg }}</p>
      </div>

      <!-- Login Form -->
      <form @submit.prevent="handleLogin" class="login-form">
        
        <!-- Email Input -->
        <div class="form-group">
          <label class="form-label">{{ t.email }}</label>
          <div class="input-wrap">
            <span class="input-icon">📧</span>
            <input 
              v-model="email" 
              type="email" 
              class="form-input" 
              :placeholder="t.emailPlaceholder" 
              required 
              :disabled="isLoading" 
            />
          </div>
        </div>

        <!-- Password Input -->
        <div class="form-group">
          <label class="form-label">{{ t.password }}</label>
          <div class="input-wrap">
            <span class="input-icon">🔒</span>
            <input 
              v-model="password" 
              type="password" 
              class="form-input" 
              :placeholder="t.passwordPlaceholder" 
              required 
              :disabled="isLoading" 
            />
          </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary submit-btn" :disabled="isLoading">
          <span v-if="isLoading" class="spinner"></span>
          <span>{{ isLoading ? t.loggingIn : t.loginBtn }}</span>
        </button>

      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { apiFetch } from '../api.js'

const props = defineProps({
  lang: {
    type: String,
    default: 'ar'
  }
})

const emit = defineEmits(['login-success'])

const email = ref('')
const password = ref('')
const isLoading = ref(false)
const errorMsg = ref('')

async function handleLogin() {
  isLoading.value = true
  errorMsg.value = ''

  try {
    const response = await apiFetch('/admin/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        email: email.value,
        password: password.value
      })
    })

    const data = await response.json()

    if (response.ok && data.success) {
      localStorage.setItem('admin_token', data.token)
      localStorage.setItem('admin_user', JSON.stringify(data.user))
      emit('login-success')
    } else {
      errorMsg.value = data.message || (props.lang === 'ar' ? 'حدث خطأ غير متوقع.' : 'An unexpected error occurred.')
    }
  } catch (error) {
    errorMsg.value = props.lang === 'ar' 
      ? 'فشل الاتصال بالخادم. يرجى التحقق من الشبكة.' 
      : 'Failed to connect to the server. Please check your network connection.'
  } finally {
    isLoading.value = false
  }
}

const translations = {
  ar: {
    adminPortal: 'بوابة لوحة التحكم',
    email: 'البريد الإلكتروني',
    emailPlaceholder: 'admin@souqitech.com',
    password: 'كلمة المرور',
    passwordPlaceholder: '••••••••',
    loginBtn: 'تسجيل الدخول',
    loggingIn: 'جاري تسجيل الدخول...',
  },
  en: {
    adminPortal: 'Admin Portal Login',
    email: 'Email Address',
    emailPlaceholder: 'admin@souqitech.com',
    password: 'Password',
    passwordPlaceholder: '••••••••',
    loginBtn: 'Login',
    loggingIn: 'Logging in...',
  }
}

const t = computed(() => translations[props.lang])
</script>

<style scoped>
.login-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: calc(100vh - 120px);
  width: 100%;
  padding: 2rem;
  position: relative;
}

.login-card {
  width: 100%;
  max-width: 440px;
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--radius);
  padding: 3rem 2.5rem;
  box-shadow: var(--shadow-card);
  position: relative;
  overflow: hidden;
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
}

.glow-point {
  position: absolute;
  top: -100px;
  left: 50%;
  transform: translateX(-50%);
  width: 200px;
  height: 200px;
  background: var(--accent-dim);
  filter: blur(80px);
  border-radius: 50%;
  pointer-events: none;
}

.brand {
  text-align: center;
  margin-bottom: 2rem;
}

.logo-icon {
  font-size: 2.5rem;
  color: var(--accent);
  filter: drop-shadow(0 0 10px var(--accent-glow));
  line-height: 1;
  display: inline-block;
  margin-bottom: 0.5rem;
}

.logo-text {
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--text-primary);
  margin-bottom: 0.25rem;
}

.subtitle {
  font-size: 0.95rem;
  color: var(--text-secondary);
  font-weight: 500;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-label {
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--text-secondary);
}

.input-wrap {
  position: relative;
  display: flex;
  align-items: center;
}

.input-icon {
  position: absolute;
  left: 14px;
  font-size: 1.1rem;
  color: var(--text-muted);
  pointer-events: none;
}

[dir="rtl"] .input-icon {
  left: auto;
  right: 14px;
}

.form-input {
  width: 100%;
  padding: 12px 14px 12px 42px;
  background: rgba(10, 11, 16, 0.6);
  border: 1.5px solid var(--border-color);
  border-radius: var(--radius-sm);
  color: var(--text-primary);
  font-family: inherit;
  font-size: 0.95rem;
  outline: none;
  transition: var(--transition);
}

[dir="rtl"] .form-input {
  padding: 12px 42px 12px 14px;
}

.form-input:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px var(--accent-dim);
}

.submit-btn {
  width: 100%;
  margin-top: 1rem;
  padding: 12px;
  font-size: 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none !important;
  box-shadow: none !important;
}

.spinner {
  width: 18px;
  height: 18px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top-color: #fff;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.alert {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem;
  border-radius: var(--radius-sm);
  font-size: 0.9rem;
  margin-bottom: 1.5rem;
  line-height: 1.4;
}

.alert-error {
  background: rgba(255, 60, 60, 0.1);
  border: 1px solid rgba(255, 60, 60, 0.3);
  color: #ff5555;
}

.alert-error p {
  flex: 1;
}

@media (max-width: 480px) {
  .login-card {
    padding: 2rem 1.5rem;
  }
}
</style>
