<template>
  <div>
    <!-- Page Hero -->
    <section class="page-hero section">
      <div class="bg-glow srv-glow"></div>
      <div class="container">
        <p class="section-label">{{ t.label }}</p>
        <h1 class="section-title">{{ t.title }}</h1>
        <p class="section-desc">{{ t.subtitle }}</p>
      </div>
    </section>

    <!-- Main Services Grid -->
    <section class="section">
      <div class="container">
        <div class="services-full-grid">
          <div class="service-full-card card" v-for="(srv, idx) in services" :key="idx">
            <div class="srv-header">
              <div class="icon-box">{{ srv.icon }}</div>
              <div class="srv-badge" v-if="srv.badge_ar || srv.badge_en">
                {{ lang === 'ar' ? srv.badge_ar : srv.badge_en }}
              </div>
            </div>
            <h2 class="srv-title">{{ lang === 'ar' ? srv.title_ar : srv.title_en }}</h2>
            <p class="card-desc mb-3">{{ lang === 'ar' ? srv.desc_ar : srv.desc_en }}</p>
            <ul class="srv-features">
              <li v-for="feat in (lang === 'ar' ? srv.features_ar : srv.features_en)" :key="feat">
                <span class="check">✓</span> {{ feat }}
              </li>
            </ul>
            <div class="srv-stack">
              <span class="stack-label">{{ t.stack }}</span>
              <div class="stack-tags">
                <span class="stack-tag" v-for="tag in srv.stack" :key="tag">{{ tag }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Process Section -->
    <section class="section process-section">
      <div class="container">
        <div class="text-center mb-4">
          <p class="section-label" style="justify-content:center">{{ t.processLabel }}</p>
          <h2 class="section-title">{{ t.processTitle }}</h2>
        </div>
        <div class="process-grid">
          <div class="process-step" v-for="(step, idx) in processSteps" :key="step.titleKey">
            <div class="step-num">{{ String(idx + 1).padStart(2, '0') }}</div>
            <div class="step-icon">{{ step.icon }}</div>
            <h3 class="step-title">{{ t[step.titleKey] }}</h3>
            <p class="step-desc">{{ t[step.descKey] }}</p>
            <div class="step-connector" v-if="idx < processSteps.length - 1"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Why Us -->
    <section class="section why-section">
      <div class="container">
        <div class="why-grid">
          <div class="why-text">
            <p class="section-label">{{ t.whyLabel }}</p>
            <h2 class="section-title" style="font-size:2.2rem">{{ t.whyTitle }}</h2>
            <div class="divider"></div>
            <p class="section-desc">{{ t.whyDesc }}</p>
          </div>
          <div class="why-items">
            <div class="why-item" v-for="item in whyItems" :key="item.icon">
              <span class="why-icon">{{ item.icon }}</span>
              <div>
                <h4 class="why-item-title">{{ t[item.titleKey] }}</h4>
                <p class="why-item-desc">{{ t[item.descKey] }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script setup>
import { computed, inject } from 'vue'

const props = defineProps({ lang: String })

const settings = inject('settings')

const services = computed(() => settings.value.services || [])

const processSteps = [
  { icon: '🔍', titleKey: 'pStep1Title', descKey: 'pStep1Desc' },
  { icon: '✏️', titleKey: 'pStep2Title', descKey: 'pStep2Desc' },
  { icon: '⚙️', titleKey: 'pStep3Title', descKey: 'pStep3Desc' },
  { icon: '🚀', titleKey: 'pStep4Title', descKey: 'pStep4Desc' },
]

const whyItems = [
  { icon: '💎', titleKey: 'why1Title', descKey: 'why1Desc' },
  { icon: '⏱️', titleKey: 'why2Title', descKey: 'why2Desc' },
  { icon: '🔧', titleKey: 'why3Title', descKey: 'why3Desc' },
  { icon: '📊', titleKey: 'why4Title', descKey: 'why4Desc' },
]

const t = computed(() => {
  const isAr = props.lang === 'ar'
  return {
    label: isAr ? 'خدماتنا' : 'Services',
    title: isAr ? 'حلول رقمية متكاملة' : 'Integrated Digital Solutions',
    subtitle: isAr ? 'نقدم طيفاً واسعاً من الخدمات التقنية المتخصصة المصممة لتلبية احتياجات الأعمال الحديثة.' : 'We offer a wide range of specialized technical services designed to meet the needs of modern businesses.',
    stack: isAr ? 'التقنيات المستخدمة:' : 'Technologies Used:',
    processLabel: isAr ? 'كيف نعمل' : 'How We Work',
    processTitle: isAr ? 'منهجيتنا في العمل' : 'Our Methodology',
    pStep1Title: isAr ? 'الفهم والتحليل' : 'Understanding & Analysis',
    pStep1Desc: isAr ? 'ندرس احتياجاتك بعمق ونحلل السوق والمنافسين لنضع استراتيجية دقيقة.' : 'We deeply study your needs and analyze the market to develop a precise strategy.',
    pStep2Title: isAr ? 'التصميم والنمذجة' : 'Design & Prototyping',
    pStep2Desc: isAr ? 'نصمم نماذج أولية تفاعلية ونتحقق من توافقها مع رؤيتك قبل البدء.' : 'We design interactive prototypes and verify their alignment with your vision before starting.',
    pStep3Title: isAr ? 'التطوير والبناء' : 'Development & Building',
    pStep3Desc: isAr ? 'نكتب كوداً نظيفاً وقابلاً للصيانة باستخدام أحدث التقنيات وأفضل الممارسات.' : 'We write clean, maintainable code using the latest technologies and best practices.',
    pStep4Title: isAr ? 'الإطلاق والدعم' : 'Launch & Support',
    pStep4Desc: isAr ? 'نطلق مشروعك بأمان ونقدم دعماً تقنياً مستمراً ومتابعة للأداء.' : 'We safely launch your project and provide continuous technical support and performance monitoring.',
    whyLabel: isAr ? 'لماذا نحن' : 'Why Us',
    whyTitle: isAr ? 'لماذا تختار سوقي تك؟' : 'Why Choose Souqi Tech?',
    whyDesc: isAr ? 'نجمع بين الخبرة التقنية العميقة والفهم الحقيقي لاحتياجات السوق المحلي لنقدم حلولاً تتجاوز توقعاتك.' : 'We combine deep technical expertise with a genuine understanding of local market needs to deliver solutions that exceed your expectations.',
    why1Title: isAr ? 'جودة لا تُساوم عليها' : 'Uncompromising Quality',
    why1Desc: isAr ? 'كل سطر كود نكتبه يخضع لمراجعة صارمة لضمان أعلى معايير الجودة.' : 'Every line of code we write undergoes strict review to ensure the highest quality standards.',
    why2Title: isAr ? 'التسليم في الموعد' : 'On-Time Delivery',
    why2Desc: isAr ? 'نلتزم بالجداول الزمنية المتفق عليها ونبقيك على اطلاع دائم بالتقدم.' : 'We commit to agreed timelines and keep you constantly updated on progress.',
    why3Title: isAr ? 'دعم ما بعد البيع' : 'After-Sale Support',
    why3Desc: isAr ? 'علاقتنا لا تنتهي عند التسليم، نقدم دعماً تقنياً مستمراً لمشروعك.' : 'Our relationship doesn\'t end at delivery; we provide ongoing technical support for your project.',
    why4Title: isAr ? 'أسعار تنافسية' : 'Competitive Pricing',
    why4Desc: isAr ? 'نقدم أفضل قيمة مقابل التكلفة دون المساومة على الجودة أو الأداء.' : 'We offer the best value for cost without compromising on quality or performance.',
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

.srv-glow {
  width: 500px; height: 400px;
  background: rgba(0, 113, 255, 0.1);
  top: -100px; left: -100px;
}

.services-full-grid {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.service-full-card { padding: 2.5rem; }

.srv-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 0;
}

.srv-header .icon-box { margin-bottom: 0; }

.srv-badge {
  padding: 5px 12px;
  background: var(--accent-dim);
  border: 1px solid var(--border-glow);
  border-radius: 50px;
  font-size: 0.78rem;
  font-weight: 600;
  color: var(--accent-bright);
}

.srv-title {
  font-size: 1.6rem;
  font-weight: 800;
  margin: 1.25rem 0 1rem;
  color: var(--text-primary);
}

.card-desc { color: var(--text-secondary); line-height: 1.8; font-size: 1rem; }

.srv-features {
  list-style: none;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.75rem;
  margin: 1.5rem 0;
}

.srv-features li {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
  color: var(--text-secondary);
}

.check {
  color: var(--accent);
  font-weight: 700;
  flex-shrink: 0;
}

.srv-stack {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex-wrap: wrap;
  padding-top: 1.5rem;
  border-top: 1px solid var(--border-color);
  margin-top: 1rem;
}

.stack-label {
  font-size: 0.8rem;
  font-weight: 600;
  color: var(--text-muted);
  white-space: nowrap;
}

.stack-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; }

.stack-tag {
  padding: 4px 10px;
  background: var(--bg-primary);
  border: 1px solid var(--border-color);
  border-radius: 4px;
  font-size: 0.78rem;
  font-weight: 600;
  color: var(--text-muted);
  font-family: 'Courier New', monospace;
  transition: all 0.2s;
}

.service-full-card:hover .stack-tag { border-color: var(--accent); color: var(--accent-bright); }

/* Process */
.process-section { background: var(--bg-secondary); }

.process-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
  position: relative;
}

.process-step {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--radius);
  padding: 2rem 1.5rem;
  position: relative;
  transition: all 0.3s ease;
}

.process-step:hover {
  border-color: var(--border-glow);
  box-shadow: var(--shadow-neon);
  transform: translateY(-4px);
}

.step-num {
  font-size: 3rem;
  font-weight: 900;
  color: var(--accent-dim);
  line-height: 1;
  margin-bottom: 0.5rem;
  background: linear-gradient(135deg, var(--accent), transparent);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.step-icon { font-size: 1.8rem; margin-bottom: 1rem; }
.step-title { font-size: 1.05rem; font-weight: 700; margin-bottom: 0.75rem; color: var(--text-primary); }
.step-desc { font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6; }

/* Why */
.why-section { }

.why-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 4rem;
  align-items: center;
}

.why-items { display: flex; flex-direction: column; gap: 1.5rem; }

.why-item {
  display: flex;
  gap: 1.25rem;
  align-items: flex-start;
  padding: 1.25rem;
  border-radius: var(--radius-sm);
  border: 1px solid var(--border-color);
  background: var(--bg-card);
  transition: all 0.25s;
}

.why-item:hover {
  border-color: var(--border-glow);
  transform: translateX(4px);
}

[dir="rtl"] .why-item:hover { transform: translateX(-4px); }

.why-icon { font-size: 1.8rem; flex-shrink: 0; }
.why-item-title { font-weight: 700; margin-bottom: 0.25rem; color: var(--text-primary); }
.why-item-desc { font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6; }

@media (max-width: 1024px) {
  .process-grid { grid-template-columns: repeat(2, 1fr); }
  .srv-features { grid-template-columns: 1fr; }
}

@media (max-width: 768px) {
  .process-grid { grid-template-columns: 1fr 1fr; }
  .why-grid { grid-template-columns: 1fr; }
}

@media (max-width: 480px) {
  .process-grid { grid-template-columns: 1fr; }
}
</style>
