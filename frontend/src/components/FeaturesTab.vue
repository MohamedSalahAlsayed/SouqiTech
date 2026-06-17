<template>
  <div class="features-page">
    <section class="section">
      <div class="container">
        <div class="text-center mb-4">
          <p class="section-label">{{ t.featuresLabel }}</p>
          <h2 class="section-title">{{ t.featuresTitle }}</h2>
          <p class="section-desc">{{ t.featuresDesc }}</p>
        </div>

        <div class="grid-3">
          <div class="card feature-card" v-for="(feature, idx) in features" :key="idx">
            <div class="feature-icon">{{ feature.icon }}</div>
            <h3 class="card-title">{{ lang === 'ar' ? feature.title_ar : feature.title_en }}</h3>
            <p class="card-desc">{{ lang === 'ar' ? feature.desc_ar : feature.desc_en }}</p>
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
const features = computed(() => settings.value.features || [])

const t = computed(() => {
  const isAr = props.lang === 'ar'
  return {
    featuresLabel: isAr ? 'مميزاتنا' : 'Our Features',
    featuresTitle: isAr ? 'لماذا تختارنا؟' : 'Why Choose Us?',
    featuresDesc: isAr ? 'اكتشف أهم المزايا التي نقدمها في حلولنا الرقمية.' : 'Explore the key features we deliver in our digital solutions.',
  }
})
</script>

<style scoped>
.feature-card {
  padding: 2rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  min-height: 280px;
}
.feature-icon {
  font-size: 2.2rem;
}
</style>
