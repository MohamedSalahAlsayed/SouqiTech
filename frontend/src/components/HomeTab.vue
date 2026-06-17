<template>
  <div>
    <!-- ══════════ HERO ══════════ -->
    <section class="hero">
      <div class="bg-glow glow-1"></div>
      <div class="bg-glow glow-2"></div>
      <div class="container hero-content">
        <div class="badge fade-in mb-4">
          <span class="dot"></span>
          {{ t.badge }}
        </div>
        <h1 class="hero-title fade-in-delay-1">
          {{ t.heroTitle1 }}
          <br />
          <span class="glow-text">{{ t.heroTitle2 }}</span>
        </h1>
        <p class="hero-sub fade-in-delay-2">{{ t.heroSub }}</p>
        <div class="hero-ctas fade-in-delay-3">
          <button class="btn btn-primary" @click="$emit('navigate', 'services')">
            🚀 {{ t.ctaPrimary }}
          </button>
          <button class="btn btn-outline" @click="$emit('navigate', 'contact')">
            💬 {{ t.ctaSecondary }}
          </button>
        </div>

        <!-- Floating Tech Tags -->
        <div class="tech-tags fade-in-delay-4">
          <span class="tech-tag" v-for="tag in techTags" :key="tag">{{ tag }}</span>
        </div>
      </div>

      <!-- Animated Grid Background -->
      <div class="hero-grid"></div>
    </section>

    <!-- ══════════ ABOUT (Brief) ══════════ -->
    <section class="section">
      <div class="container">
        <div class="grid-2 about-grid" style="align-items:center; gap:4rem;">
          <!-- Text -->
          <div>
            <p class="section-label">{{ t.aboutLabel }}</p>
            <h2 class="section-title">{{ t.aboutTitle }}</h2>
            <div class="divider"></div>
            <p class="section-desc">{{ t.aboutDesc }}</p>
            <button class="btn btn-outline mt-4" @click="$emit('navigate', 'about')">
              {{ t.learnMore }} →
            </button>
          </div>
          <!-- Stats -->
          <div class="stats-box">
            <div class="stat-item card" v-for="(stat, idx) in stats" :key="idx">
              <div class="stat-value">{{ stat.value }}</div>
              <div class="stat-label">{{ lang === 'ar' ? stat.label_ar : stat.label_en }}</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════ SERVICES (Brief) ══════════ -->
    <section class="section services-brief">
      <div class="bg-glow glow-3"></div>
      <div class="container">
        <div class="text-center mb-4">
          <p class="section-label" style="justify-content:center">{{ t.servicesLabel }}</p>
          <h2 class="section-title">{{ t.servicesTitle }}</h2>
        </div>
        <div class="grid-3">
          <div class="card service-card" v-for="(srv, idx) in services" :key="idx">
            <div class="icon-box">{{ srv.icon }}</div>
            <h3 class="card-title">{{ lang === 'ar' ? srv.title_ar : srv.title_en }}</h3>
            <p class="card-desc">{{ lang === 'ar' ? srv.desc_ar : srv.desc_en }}</p>
            <button class="btn btn-outline btn-sm mt-4" @click="$emit('navigate', 'services')">
              {{ t.seeMore }}
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- ══════════ CTA BANNER ══════════ -->
    <section class="cta-banner">
      <div class="container">
        <div class="cta-inner card">
          <div class="bg-glow cta-glow"></div>
          <div class="cta-text">
            <h2 class="cta-title">{{ t.ctaBannerTitle }}</h2>
            <p class="cta-sub">{{ t.ctaBannerSub }}</p>
          </div>
          <button class="btn btn-primary cta-btn" @click="$emit('navigate', 'contact')">
            ✉️ {{ t.ctaBannerBtn }}
          </button>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { computed, inject } from 'vue'

const props = defineProps({ lang: String })
defineEmits(['navigate'])

const settings = inject('settings')

const techTags = computed(() => settings.value.hero?.tech_tags || [])
const stats = computed(() => settings.value.stats || [])
const services = computed(() => settings.value.services || [])

const t = computed(() => {
  const isAr = props.lang === 'ar'
  const hero = settings.value.hero || {}
  const about = settings.value.about || {}
  
  return {
    badge: isAr ? hero.badge_ar : hero.badge_en,
    heroTitle1: isAr ? hero.title1_ar : hero.title1_en,
    heroTitle2: isAr ? hero.title2_ar : hero.title2_en,
    heroSub: isAr ? hero.sub_ar : hero.sub_en,
    ctaPrimary: isAr ? hero.cta_primary_ar : hero.cta_primary_en,
    ctaSecondary: isAr ? hero.cta_secondary_ar : hero.cta_secondary_en,
    
    aboutLabel: isAr ? 'من نحن' : 'About Us',
    aboutTitle: isAr ? 'خبرة حقيقية، نتائج قابلة للقياس' : 'Real Expertise, Measurable Results',
    aboutDesc: isAr ? about.story_desc2_ar : about.story_desc2_en,
    learnMore: isAr ? 'اقرأ المزيد' : 'Read More',
    
    servicesLabel: isAr ? 'خدماتنا' : 'Our Services',
    servicesTitle: isAr ? 'ما نقدمه لك' : 'What We Offer',
    seeMore: isAr ? 'اعرف أكثر' : 'Learn More',
    
    ctaBannerTitle: isAr ? 'هل أنت مستعد لبناء مشروعك الرقمي؟' : 'Ready to Build Your Digital Project?',
    ctaBannerSub: isAr ? 'تواصل معنا اليوم واحصل على استشارة تقنية مجانية لمشروعك.' : 'Contact us today and get a free technical consultation for your project.',
    ctaBannerBtn: isAr ? 'احصل على عرض مجاني' : 'Get a Free Quote',
  }
})
</script>

<style scoped>
/* ── Hero ── */
.hero {
  min-height: 100vh;
  display: flex;
  align-items: center;
  position: relative;
  overflow: hidden;
  padding-top: 100px;
}

.glow-1 {
  width: 600px; height: 600px;
  background: rgba(0, 113, 255, 0.12);
  top: -100px; right: -100px;
}

.glow-2 {
  width: 400px; height: 400px;
  background: rgba(0, 113, 255, 0.08);
  bottom: 0; left: -50px;
}

.glow-3 {
  width: 500px; height: 500px;
  background: rgba(0, 113, 255, 0.06);
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
}

/* Hero Grid Background */
.hero-grid {
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(0, 113, 255, 0.05) 1px, transparent 1px),
    linear-gradient(90deg, rgba(0, 113, 255, 0.05) 1px, transparent 1px);
  background-size: 60px 60px;
  pointer-events: none;
}

.hero-content {
  position: relative;
  z-index: 1;
  padding-top: 2rem;
  padding-bottom: 4rem;
}

.hero-title {
  font-size: clamp(2.5rem, 6vw, 5rem);
  font-weight: 900;
  line-height: 1.1;
  margin-bottom: 1.5rem;
  color: var(--text-primary);
  letter-spacing: -1px;
}

.hero-sub {
  font-size: 1.2rem;
  color: var(--text-secondary);
  max-width: 580px;
  line-height: 1.8;
  margin-bottom: 2.5rem;
}

.hero-ctas {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  margin-bottom: 3rem;
}

/* Tech Tags */
.tech-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
}

.tech-tag {
  padding: 6px 14px;
  border-radius: 6px;
  background: rgba(18, 20, 28, 0.8);
  border: 1px solid var(--border-color);
  font-size: 0.8rem;
  font-weight: 600;
  color: var(--text-muted);
  font-family: 'Courier New', monospace;
  transition: all 0.2s;
}

.tech-tag:hover {
  border-color: var(--accent);
  color: var(--accent);
}

/* About Grid */
.about-grid { }

/* Stats Box */
.stats-box {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.25rem;
}

.stat-item {
  text-align: center;
  padding: 1.5rem 1rem;
}

.stat-label {
  font-size: 0.875rem;
  color: var(--text-secondary);
  margin-top: 0.5rem;
  font-weight: 500;
}

/* Services Brief */
.services-brief { background: var(--bg-secondary); position: relative; overflow: hidden; }

.card-title {
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 0.75rem;
  color: var(--text-primary);
}

.card-desc { color: var(--text-secondary); font-size: 0.95rem; line-height: 1.7; }

.btn-sm { padding: 8px 18px; font-size: 0.85rem; }

/* CTA Banner */
.cta-banner { padding: 4rem 0; }

.cta-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 2rem;
  padding: 3rem;
  position: relative;
  overflow: hidden;
  background: linear-gradient(135deg, var(--bg-card), rgba(0, 113, 255, 0.08));
  border-color: var(--border-glow);
  animation: neon-pulse 4s ease-in-out infinite;
}

.cta-glow {
  width: 300px; height: 300px;
  background: rgba(0, 113, 255, 0.15);
  top: -100px; right: -50px;
}

.cta-text { position: relative; z-index: 1; }

.cta-title {
  font-size: clamp(1.5rem, 3vw, 2rem);
  font-weight: 800;
  margin-bottom: 0.5rem;
}

.cta-sub { color: var(--text-secondary); font-size: 1rem; }

.cta-btn {
  flex-shrink: 0;
  position: relative;
  z-index: 1;
  padding: 14px 32px;
  font-size: 1rem;
}

@media (max-width: 768px) {
  .hero { padding-top: 80px; }
  .stats-box { grid-template-columns: 1fr 1fr; }
  .cta-inner { flex-direction: column; text-align: center; }
  .cta-btn { width: 100%; justify-content: center; }
}

@media (max-width: 480px) {
  .hero-title { font-size: 2.2rem; }
  .stats-box { grid-template-columns: 1fr 1fr; }
}
</style>
