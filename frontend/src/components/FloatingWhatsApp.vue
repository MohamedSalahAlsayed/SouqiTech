<template>
  <a
    v-if="phoneDigits"
    :href="waLink"
    class="floating-whatsapp"
    target="_blank"
    rel="noopener noreferrer"
    :aria-label="ariaLabel"
    @click="onClick"
  >
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <path d="M20.52 3.48A11.95 11.95 0 0012 0C5.373 0 0 5.373 0 12c0 2.11.55 4.08 1.6 5.84L0 24l6.45-1.61A11.95 11.95 0 0012 24c6.627 0 12-5.373 12-12 0-3.2-1.24-6.16-3.48-8.52z" fill="#25D366"/>
      <path d="M17.37 14.35c-.29-.14-1.7-.84-1.96-.94-.27-.11-.47-.14-.67.14-.2.29-.77.94-.94 1.13-.17.2-.34.22-.63.08-.29-.14-1.22-.45-2.33-1.44-.86-.77-1.44-1.72-1.61-2.01-.17-.29-.02-.45.13-.59.13-.13.29-.35.43-.53.14-.18.19-.29.29-.48.1-.2.04-.37-.02-.52-.06-.14-.67-1.6-.92-2.19-.24-.58-.49-.5-.67-.51-.17 0-.37-.01-.57-.01-.2 0-.52.07-.79.37-.27.29-1.03 1.01-1.03 2.47 0 1.46 1.06 2.87 1.21 3.07.15.2 2.09 3.2 5.07 4.49 0 0 .23.09.42.04.19-.05 1.24-.51 1.42-.9.18-.38.18-.7.13-.77-.05-.07-.19-.12-.38-.26z" fill="#fff"/>
    </svg>
  </a>
</template>

<script setup>
import { inject, computed } from 'vue'

const settings = inject('settings')

const phoneDigits = computed(() => {
  const raw = settings?.value?.contact_info?.whatsapp || ''
  if (!raw) return ''
  // Remove non-digits
  const digits = raw.replace(/[^0-9]/g, '')
  return digits || ''
})

const waLink = computed(() => {
  if (!phoneDigits.value) return '#'
  // Use wa.me format without plus or spaces
  const text = ''
  return `https://wa.me/${phoneDigits.value}${text ? `?text=${encodeURIComponent(text)}` : ''}`
})

const ariaLabel = computed(() => {
  const site = settings?.value?.general?.site_name || 'Website'
  return `Open WhatsApp chat with ${site}`
})

function onClick() {
  // no-op for now; link opens in new tab
}
</script>

<style scoped>
.floating-whatsapp {
  position: fixed;
  right: 20px;
  bottom: 24px;
  width: 56px;
  height: 56px;
  border-radius: 999px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 6px 18px rgba(2, 6, 23, 0.45);
  z-index: 9999;
  background: linear-gradient(180deg,#25D366,#128C7E);
  color: white;
  text-decoration: none;
  transition: transform 0.12s ease, box-shadow 0.12s ease;
}
.floating-whatsapp:hover { transform: translateY(-4px); box-shadow: 0 10px 28px rgba(2,6,23,0.55); }

/* RTL support */
[dir="rtl"] .floating-whatsapp { right: auto; left: 20px; }
</style>
