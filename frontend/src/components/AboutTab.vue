<template>
  <div>
    <!-- Page Hero -->
    <section class="page-hero section">
      <div class="bg-glow about-glow"></div>
      <div class="container">
        <p class="section-label">{{ t.label }}</p>
        <h1 class="section-title">{{ t.title }}</h1>
        <p class="section-desc">{{ t.subtitle }}</p>
      </div>
    </section>

    <!-- Mission & Vision -->
    <section class="section">
      <div class="container">
        <div class="grid-2 mv-grid">
          <div class="card mv-card fade-in">
            <div class="icon-box">🎯</div>
            <h2 class="mv-title">{{ t.missionTitle }}</h2>
            <p class="card-desc">{{ t.missionDesc }}</p>
          </div>
          <div class="card mv-card fade-in-delay-1">
            <div class="icon-box">🔭</div>
            <h2 class="mv-title">{{ t.visionTitle }}</h2>
            <p class="card-desc">{{ t.visionDesc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Our Story -->
    <section class="section story-section">
      <div class="container">
        <div class="story-grid">
          <div class="story-text fade-in">
            <p class="section-label">{{ t.storyLabel }}</p>
            <h2 class="section-title" style="font-size:2.2rem;">{{ t.storyTitle }}</h2>
            <div class="divider"></div>
            <p class="section-desc mb-3">{{ t.storyDesc1 }}</p>
            <p class="section-desc">{{ t.storyDesc2 }}</p>
          </div>
          <div class="values-list fade-in-delay-2">
            <h3 class="values-heading">{{ t.valuesTitle }}</h3>
            <div class="value-item" v-for="val in values" :key="val.icon">
              <div class="value-icon">{{ val.icon }}</div>
              <div>
                <h4 class="value-title">{{ lang === 'ar' ? val.title_ar : val.title_en }}</h4>
                <p class="value-desc">{{ lang === 'ar' ? val.desc_ar : val.desc_en }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Team / Achievements -->
    <section class="section achievements-section">
      <div class="container">
        <div class="text-center mb-4">
          <p class="section-label" style="justify-content:center">{{ t.achieveLabel }}</p>
          <h2 class="section-title">{{ t.achieveTitle }}</h2>
        </div>
        <div class="achievements-grid">
          <div class="achieve-card card" v-for="item in achievements" :key="item.icon">
            <span class="achieve-icon">{{ item.icon }}</span>
            <div class="stat-value">{{ item.value }}</div>
            <p class="achieve-label">{{ lang === 'ar' ? item.label_ar : item.label_en }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Technology Stack -->
    <section class="section tech-stack-section">
      <div class="container">
        <div class="text-center mb-4">
          <p class="section-label" style="justify-content:center">{{ t.techLabel }}</p>
          <h2 class="section-title">{{ t.techTitle }}</h2>
        </div>
        <div class="tech-grid">
          <div class="tech-item card" v-for="tech in techs" :key="tech.name">
            <span class="tech-emoji">{{ tech.icon }}</span>
            <span class="tech-name">{{ tech.name }}</span>
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

const values = computed(() => settings.value.about?.values || [])
const achievements = computed(() => settings.value.about?.achievements || [])
const techs = computed(() => settings.value.about?.techs || [])

const t = computed(() => {
  const isAr = props.lang === 'ar'
  const about = settings.value.about || {}
  const general = settings.value.general || {}
  
  return {
    label: isAr ? 'من نحن' : 'About Us',
    title: isAr ? `نحن ${general.site_name || 'سوقي تك'}` : `We Are ${general.site_name || 'Souqi Tech'}`,
    subtitle: isAr ? `شركة تقنية متخصصة في بناء حلول ويب مبتكرة تلبي احتياجات الأعمال في العصر الرقمي.` : `A technology company specialized in building innovative web solutions that meet business needs in the digital age.`,
    missionTitle: isAr ? about.mission_title_ar : about.mission_title_en,
    missionDesc: isAr ? about.mission_desc_ar : about.mission_desc_en,
    visionTitle: isAr ? about.vision_title_ar : about.vision_title_en,
    visionDesc: isAr ? about.vision_desc_ar : about.vision_desc_en,
    storyLabel: isAr ? about.story_label_ar : about.story_label_en,
    storyTitle: isAr ? about.story_title_ar : about.story_title_en,
    storyDesc1: isAr ? about.story_desc1_ar : about.story_desc1_en,
    storyDesc2: isAr ? about.story_desc2_ar : about.story_desc2_en,
    valuesTitle: isAr ? 'قيمنا' : 'Our Values',
    achieveLabel: isAr ? 'إنجازاتنا' : 'Our Achievements',
    achieveTitle: isAr ? 'أرقام تتحدث' : 'Numbers Speak',
    techLabel: isAr ? 'تقنياتنا' : 'Technology',
    techTitle: isAr ? 'المنظومة التقنية' : 'Our Tech Stack',
  }
})
</script>

<style scoped>
.page-hero {
  padding-top: 140px;
  padding-bottom: 4rem;
  position: relative;
  overflow: hidden;
  background: linear-gradient(180deg, rgba(0, 113, 255, 0.05) 0%, transparent 100%);
  border-bottom: 1px solid var(--border-color);
}

.about-glow {
  width: 600px; height: 400px;
  background: rgba(0, 113, 255, 0.1);
  top: -100px; right: -100px;
}

.mv-grid { gap: 2rem; }

.mv-title { font-size: 1.4rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-primary); }

.story-section { background: var(--bg-secondary); }

.story-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 4rem;
  align-items: start;
}

.values-heading { font-size: 1.1rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--text-primary); }

.value-item {
  display: flex;
  gap: 1rem;
  padding: 1rem 0;
  border-bottom: 1px solid var(--border-color);
  align-items: flex-start;
}

.value-item:last-child { border-bottom: none; }

.value-icon { font-size: 1.5rem; flex-shrink: 0; margin-top: 2px; }
.value-title { font-weight: 600; margin-bottom: 0.25rem; color: var(--text-primary); }
.value-desc { font-size: 0.9rem; color: var(--text-secondary); line-height: 1.6; }

.achievements-section { }

.achievements-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
}

.achieve-card { text-align: center; padding: 2rem 1rem; }
.achieve-icon { font-size: 2rem; display: block; margin-bottom: 1rem; }
.achieve-label { color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.5rem; }

.tech-stack-section { background: var(--bg-secondary); }

.tech-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1rem;
}

.tech-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem 1.25rem;
}

.tech-emoji { font-size: 1.4rem; }
.tech-name { font-weight: 600; font-size: 0.95rem; }

.card-desc { color: var(--text-secondary); line-height: 1.7; }

@media (max-width: 1024px) {
  .achievements-grid, .tech-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
  .story-grid { grid-template-columns: 1fr; gap: 2rem; }
  .achievements-grid { grid-template-columns: repeat(2, 1fr); }
  .tech-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>
