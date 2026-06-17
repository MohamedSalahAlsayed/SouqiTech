<template>
  <div>
    <!-- Page Hero -->
    <section class="page-hero section">
      <div class="bg-glow contact-glow"></div>
      <div class="container">
        <p class="section-label">{{ t.label }}</p>
        <h1 class="section-title">{{ t.title }}</h1>
        <p class="section-desc">{{ t.subtitle }}</p>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="contact-grid">

          <!-- Contact Info Panel -->
          <div class="contact-info-panel fade-in">
            <h2 class="info-title">{{ t.infoTitle }}</h2>
            <p class="info-desc">{{ t.infoDesc }}</p>

            <div class="contact-channels">
              <div class="channel-item" v-for="ch in channels" :key="ch.icon">
                <div class="channel-icon-wrap">{{ ch.icon }}</div>
                <div>
                  <div class="channel-label">{{ t[ch.labelKey] }}</div>
                  <a :href="ch.href" class="channel-value">{{ ch.value }}</a>
                </div>
              </div>
            </div>

            <div class="social-section">
              <p class="social-label">{{ t.followUs }}</p>
              <div class="social-links">
                <a :href="settings.footer.twitter" class="social-btn" aria-label="Twitter">𝕏</a>
                <a :href="settings.footer.linkedin" class="social-btn" aria-label="LinkedIn">in</a>
                <a :href="settings.footer.instagram" class="social-btn" aria-label="Instagram">📷</a>
                <a :href="settings.footer.github" class="social-btn" aria-label="Github">⌨</a>
              </div>
            </div>

            <!-- Map Placeholder -->
            <div class="map-placeholder">
              <span>📍 {{ lang === 'ar' ? settings.contact_info.address_ar : settings.contact_info.address_en }}</span>
            </div>
          </div>

          <!-- Contact Form Panel -->
          <div class="form-panel fade-in-delay-1">
            <h2 class="form-title">{{ t.formTitle }}</h2>

            <!-- Success Message -->
            <transition name="fade-msg">
              <div v-if="submitStatus === 'success'" class="alert-success">
                <span>✅</span>
                <div>
                  <strong>{{ t.successTitle }}</strong>
                  <p>{{ t.successDesc }}</p>
                </div>
              </div>
            </transition>

            <!-- Error Message -->
            <transition name="fade-msg">
              <div v-if="submitStatus === 'error'" class="alert-error">
                <span>❌</span>
                <div>
                  <strong>{{ t.errorTitle }}</strong>
                  <p>{{ errorMessage }}</p>
                </div>
              </div>
            </transition>

            <form @submit.prevent="submitForm" v-if="submitStatus !== 'success'">
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">{{ t.fieldName }} <span class="req">*</span></label>
                  <input v-model="form.name" type="text" class="form-input"
                         :placeholder="t.phName" required />
                  <span class="field-error" v-if="errors.name">{{ errors.name }}</span>
                </div>
                <div class="form-group">
                  <label class="form-label">{{ t.fieldEmail }} <span class="req">*</span></label>
                  <input v-model="form.email" type="email" class="form-input"
                         :placeholder="t.phEmail" required />
                  <span class="field-error" v-if="errors.email">{{ errors.email }}</span>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">{{ t.fieldSubject }}</label>
                <input v-model="form.subject" type="text" class="form-input"
                       :placeholder="t.phSubject" />
              </div>

              <div class="form-group">
                <label class="form-label">{{ t.fieldMessage }} <span class="req">*</span></label>
                <textarea v-model="form.message" class="form-textarea"
                          :placeholder="t.phMessage" required></textarea>
                <span class="field-error" v-if="errors.message">{{ errors.message }}</span>
              </div>

              <button type="submit" class="btn btn-primary submit-btn" :disabled="isLoading">
                <span v-if="isLoading" class="spinner"></span>
                <span v-else>📨</span>
                {{ isLoading ? t.sending : t.sendBtn }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, reactive, inject } from 'vue'

const props = defineProps({ lang: String })

const settings = inject('settings')

const form = reactive({ name: '', email: '', subject: '', message: '' })
const errors = reactive({ name: '', email: '', message: '' })
const isLoading = ref(false)
const submitStatus = ref(null) // null | 'success' | 'error'
const errorMessage = ref('')

const channels = computed(() => {
  const info = settings.value.contact_info || {}
  const isAr = props.lang === 'ar'
  return [
    { icon: '📧', labelKey: 'chEmail', value: info.email, href: `mailto:${info.email}` },
    { icon: '📱', labelKey: 'chPhone', value: info.phone, href: `tel:${info.phone}` },
    { icon: '💬', labelKey: 'chWhats', value: info.whatsapp, href: `https://wa.me/${info.whatsapp ? info.whatsapp.replace(/\s+/g, '') : ''}` },
    { icon: '📍', labelKey: 'chAddr',  value: isAr ? info.address_ar : info.address_en, href: '#' },
  ]
})

function validate() {
  let valid = true
  errors.name = errors.email = errors.message = ''
  if (!form.name.trim())    { errors.name    = t.value.errRequired; valid = false }
  if (!form.email.trim())   { errors.email   = t.value.errRequired; valid = false }
  else if (!/\S+@\S+\.\S+/.test(form.email)) { errors.email = t.value.errEmail; valid = false }
  if (!form.message.trim()) { errors.message = t.value.errRequired; valid = false }
  return valid
}

async function submitForm() {
  if (!validate()) return
  isLoading.value = true
  submitStatus.value = null
  try {
    const res = await fetch('/api/contact', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ ...form }),
    })
    const data = await res.json()
    if (res.ok && data.success) {
      submitStatus.value = 'success'
      form.name = form.email = form.subject = form.message = ''
    } else {
      submitStatus.value = 'error'
      if (data.errors) {
        const msgs = Object.values(data.errors).flat()
        errorMessage.value = msgs.join(' ')
      } else {
        errorMessage.value = data.message || t.value.errorGeneric
      }
    }
  } catch (e) {
    submitStatus.value = 'error'
    errorMessage.value = t.value.errorNetwork
  } finally {
    isLoading.value = false
  }
}

const t = computed(() => {
  const isAr = props.lang === 'ar'
  return {
    label:        isAr ? 'تواصل معنا' : 'Contact Us',
    title:        isAr ? 'نحب أن نسمع منك' : 'We Love to Hear From You',
    subtitle:     isAr ? 'سواء كان لديك مشروع في ذهنك أو تريد مجرد استشارة تقنية، نحن هنا لمساعدتك.' : 'Whether you have a project in mind or just want a technical consultation, we are here to help you.',
    infoTitle:    isAr ? 'معلومات التواصل' : 'Contact Information',
    infoDesc:     isAr ? 'تواصل معنا عبر أي من القنوات التالية وسنرد عليك في أقرب وقت ممكن.' : 'Reach out to us through any of the following channels and we will respond as soon as possible.',
    chEmail:      isAr ? 'البريد الإلكتروني' : 'Email',
    chPhone:      isAr ? 'الهاتف' : 'Phone',
    chWhats:      isAr ? 'واتساب' : 'WhatsApp',
    chAddr:       isAr ? 'العنوان' : 'Address',
    followUs:     isAr ? 'تابعنا على وسائل التواصل' : 'Follow Us on Social Media',
    formTitle:    isAr ? 'أرسل لنا رسالة' : 'Send Us a Message',
    fieldName:    isAr ? 'الاسم الكامل' : 'Full Name',
    fieldEmail:   isAr ? 'البريد الإلكتروني' : 'Email Address',
    fieldSubject: isAr ? 'الموضوع' : 'Subject',
    fieldMessage: isAr ? 'الرسالة' : 'Message',
    phName:       isAr ? 'أدخل اسمك' : 'Enter your name',
    phEmail:      isAr ? 'example@email.com' : 'example@email.com',
    phSubject:    isAr ? 'ما موضوع رسالتك؟' : 'What is this about?',
    phMessage:    isAr ? 'اكتب رسالتك هنا...' : 'Write your message here...',
    sendBtn:      isAr ? 'إرسال الرسالة' : 'Send Message',
    sending:      isAr ? 'جاري الإرسال...' : 'Sending...',
    errRequired:  isAr ? 'هذا الحقل مطلوب' : 'This field is required',
    errEmail:     isAr ? 'البريد الإلكتروني غير صحيح' : 'Invalid email address',
    errorGeneric: isAr ? 'حدث خطأ. حاول مرة أخرى.' : 'An error occurred. Please try again.',
    errorNetwork: isAr ? 'خطأ في الاتصال. تحقق من الإنترنت.' : 'Connection error. Check your internet.',
    successTitle: isAr ? 'تم الإرسال بنجاح!' : 'Message Sent!',
    successDesc:  isAr ? 'شكراً لتواصلك معنا. سنرد عليك خلال 24 ساعة.' : 'Thank you for reaching out. We will reply within 24 hours.',
    errorTitle:   isAr ? 'حدث خطأ' : 'An Error Occurred',
  }
})
</script>

<style scoped>
.page-hero {
  padding-top: 140px;
  padding-bottom: 4rem;
  position: relative;
  overflow: hidden;
  background: linear-gradient(180deg, rgba(0,113,255,0.05) 0%, transparent 100%);
  border-bottom: 1px solid var(--border-color);
}

.contact-glow {
  width: 500px; height: 400px;
  background: rgba(0, 113, 255, 0.1);
  top: -100px; right: -100px;
}

.contact-grid {
  display: grid;
  grid-template-columns: 1fr 1.4fr;
  gap: 3rem;
  align-items: start;
}

/* Info Panel */
.contact-info-panel {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--radius);
  padding: 2.5rem;
  position: sticky;
  top: 100px;
}

.info-title { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.75rem; }
.info-desc { color: var(--text-secondary); margin-bottom: 2rem; line-height: 1.7; font-size: 0.95rem; }

.contact-channels { display: flex; flex-direction: column; gap: 1.25rem; margin-bottom: 2rem; }

.channel-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  border-radius: var(--radius-sm);
  background: var(--bg-primary);
  border: 1px solid var(--border-color);
  transition: all 0.2s;
}

.channel-item:hover {
  border-color: var(--border-glow);
  transform: translateX(4px);
}

[dir="rtl"] .channel-item:hover { transform: translateX(-4px); }

.channel-icon-wrap { font-size: 1.4rem; flex-shrink: 0; }
.channel-label { font-size: 0.78rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
.channel-value { font-size: 0.95rem; font-weight: 600; color: var(--text-primary); text-decoration: none; transition: color 0.2s; }
.channel-value:hover { color: var(--accent); }

.social-section { margin-bottom: 1.5rem; }
.social-label { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.75rem; font-weight: 600; }
.social-links { display: flex; gap: 0.75rem; }

.social-btn {
  width: 40px; height: 40px;
  border-radius: 10px;
  background: var(--bg-primary);
  border: 1px solid var(--border-color);
  display: flex; align-items: center; justify-content: center;
  color: var(--text-secondary);
  font-size: 1rem; font-weight: 700;
  text-decoration: none;
  transition: all 0.25s;
}

.social-btn:hover {
  background: var(--accent-dim);
  border-color: var(--border-glow);
  color: var(--accent);
  transform: translateY(-3px);
  box-shadow: 0 5px 15px var(--accent-glow);
}

.map-placeholder {
  padding: 1.25rem;
  background: var(--bg-primary);
  border: 1px dashed var(--border-color);
  border-radius: var(--radius-sm);
  text-align: center;
  color: var(--text-muted);
  font-size: 0.9rem;
}

/* Form Panel */
.form-panel {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--radius);
  padding: 2.5rem;
}

.form-title { font-size: 1.5rem; font-weight: 800; margin-bottom: 2rem; }

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }

.form-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 1.25rem; }

.req { color: var(--accent); }

.field-error { font-size: 0.8rem; color: #ff4444; margin-top: -4px; }

.submit-btn {
  width: 100%;
  justify-content: center;
  padding: 15px;
  font-size: 1rem;
  margin-top: 0.5rem;
}

.submit-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none !important; }

/* Spinner */
.spinner {
  width: 16px; height: 16px;
  border: 2px solid rgba(255,255,255,0.3);
  border-top-color: #fff;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
  display: inline-block;
}

@keyframes spin { to { transform: rotate(360deg); } }

/* Alerts */
.alert-success, .alert-error {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
  padding: 1.25rem;
  border-radius: var(--radius-sm);
  margin-bottom: 1.5rem;
  font-size: 0.95rem;
}

.alert-success {
  background: rgba(0, 200, 100, 0.1);
  border: 1px solid rgba(0, 200, 100, 0.3);
  color: #00c864;
}

.alert-error {
  background: rgba(255, 60, 60, 0.1);
  border: 1px solid rgba(255, 60, 60, 0.3);
  color: #ff4444;
}

.alert-success strong, .alert-error strong { display: block; margin-bottom: 0.25rem; font-weight: 700; }
.alert-success p, .alert-error p { font-size: 0.9rem; opacity: 0.9; }
.alert-success > span, .alert-error > span { font-size: 1.4rem; flex-shrink: 0; }

.fade-msg-enter-active, .fade-msg-leave-active { transition: all 0.4s ease; }
.fade-msg-enter-from, .fade-msg-leave-to { opacity: 0; transform: translateY(-10px); }

@media (max-width: 960px) {
  .contact-grid { grid-template-columns: 1fr; }
  .contact-info-panel { position: static; }
}

@media (max-width: 480px) {
  .form-row { grid-template-columns: 1fr; }
}
</style>
