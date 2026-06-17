<template>
  <div :dir="lang === 'ar' ? 'rtl' : 'ltr'" class="app-wrapper">

    <!-- ══════════ HEADER ══════════ -->
    <header v-if="activePage !== 'admin' || !isAuthenticated" class="header" :class="{ scrolled: isScrolled }">
      <div class="container header-inner">

        <!-- Logo -->
        <a href="#" class="logo" @click.prevent="activePage = 'home'">
          <span class="logo-icon">{{ settings.general.logo_emoji }}</span>
          <span class="logo-text">
            {{ getLogoText().left }}<span class="text-accent">{{ getLogoText().right }}</span>
          </span>
        </a>

        <!-- Desktop Nav -->
        <nav class="nav-desktop">
          <a v-for="item in navItems" :key="item.key"
             href="#"
             class="nav-link"
             :class="{ active: activePage === item.key }"
             @click.prevent="activePage = item.key">
            {{ item[lang] }}
          </a>
        </nav>

        <!-- Right Controls -->
        <div class="header-controls">
          <!-- Language Switcher -->
          <button class="lang-btn" @click="toggleLang" :title="lang === 'ar' ? 'English' : 'عربي'">
            <span>{{ lang === 'ar' ? 'EN' : 'عر' }}</span>
          </button>

          <!-- Hamburger -->
          <button class="hamburger" @click="menuOpen = !menuOpen" :class="{ open: menuOpen }" aria-label="Menu">
            <span></span><span></span><span></span>
          </button>
        </div>

      </div>

      <!-- Mobile Nav -->
      <transition name="slide-down">
        <div v-if="menuOpen" class="mobile-menu">
          <a v-for="item in navItems" :key="item.key"
             href="#"
             class="mobile-nav-link"
             :class="{ active: activePage === item.key }"
             @click.prevent="activePage = item.key; menuOpen = false">
            {{ item[lang] }}
          </a>
        </div>
      </transition>
    </header>

    <!-- ══════════ PAGE CONTENT ══════════ -->
    <main class="main-content" :class="{ 'dashboard-view': activePage === 'admin' && isAuthenticated }">
      <transition name="page-fade" mode="out-in">
        <HomeTab     v-if="activePage === 'home'"       :key="'home'"     :lang="lang" @navigate="activePage = $event" />
        <AboutTab    v-else-if="activePage === 'about'"    :key="'about'"    :lang="lang" />
        <ServicesTab v-else-if="activePage === 'services'" :key="'services'" :lang="lang" />
        <ContactTab  v-else-if="activePage === 'contact'"   :key="'contact'"  :lang="lang" />
        <div v-else-if="activePage === 'admin'" :key="'admin'" class="admin-page-container">
          <AdminLogin v-if="!isAuthenticated" :lang="lang" @login-success="handleLoginSuccess" />
          <AdminDashboard v-else :lang="lang" @logout="handleLogout" />
        </div>
      </transition>
    </main>

    <!-- ══════════ FOOTER ══════════ -->
    <footer v-if="activePage !== 'admin' || !isAuthenticated" class="footer">
      <div class="container">
        <div class="footer-grid">
          <!-- Brand -->
          <div class="footer-brand">
            <div class="logo mb-3">
              <span class="logo-icon">{{ settings.general.logo_emoji }}</span>
              <span class="logo-text">
                {{ getLogoText().left }}<span class="text-accent">{{ getLogoText().right }}</span>
              </span>
            </div>
            <p class="footer-desc">{{ lang === 'ar' ? settings.footer.desc_ar : settings.footer.desc_en }}</p>
          </div>

          <!-- Quick Links -->
          <div class="footer-col">
            <h4 class="footer-heading">{{ t.quickLinks }}</h4>
            <ul class="footer-links">
              <li v-for="item in navItems" :key="item.key">
                <a href="#" @click.prevent="activePage = item.key">{{ item[lang] }}</a>
              </li>
            </ul>
          </div>

          <!-- Contact -->
          <div class="footer-col">
            <h4 class="footer-heading">{{ t.contactUs }}</h4>
            <ul class="footer-contact-list">
              <li>📧 {{ settings.contact_info.email }}</li>
              <li>📱 {{ settings.contact_info.phone }}</li>
              <li>📍 {{ lang === 'ar' ? settings.contact_info.address_ar : settings.contact_info.address_en }}</li>
            </ul>
          </div>

          <!-- Social -->
          <div class="footer-col">
            <h4 class="footer-heading">{{ t.followUs }}</h4>
            <div class="social-links">
              <a v-if="settings.footer.twitter && settings.footer.twitter !== '#'" :href="settings.footer.twitter" class="social-btn" target="_blank" rel="noopener" aria-label="Twitter / X">
                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.747l7.73-8.835L1.254 2.25H8.08l4.259 5.622L18.244 2.25zm-1.161 17.52h1.833L7.084 4.126H5.117L17.083 19.77z"/></svg>
              </a>
              <a v-if="settings.footer.linkedin && settings.footer.linkedin !== '#'" :href="settings.footer.linkedin" class="social-btn" target="_blank" rel="noopener" aria-label="LinkedIn">
                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
              </a>
              <a v-if="settings.footer.facebook && settings.footer.facebook !== '#'" :href="settings.footer.facebook" class="social-btn" target="_blank" rel="noopener" aria-label="Facebook">
                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
              </a>
              <a v-if="settings.footer.instagram && settings.footer.instagram !== '#'" :href="settings.footer.instagram" class="social-btn" target="_blank" rel="noopener" aria-label="Instagram">
                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
              </a>
              <a v-if="settings.footer.tiktok && settings.footer.tiktok !== '#'" :href="settings.footer.tiktok" class="social-btn" target="_blank" rel="noopener" aria-label="TikTok">
                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34l-.01-8.83a8.18 8.18 0 004.78 1.52V4.56a4.85 4.85 0 01-1-.13z"/></svg>
              </a>
              <a v-if="settings.footer.youtube && settings.footer.youtube !== '#'" :href="settings.footer.youtube" class="social-btn" target="_blank" rel="noopener" aria-label="YouTube">
                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
              </a>
              <a v-if="settings.footer.github && settings.footer.github !== '#'" :href="settings.footer.github" class="social-btn" target="_blank" rel="noopener" aria-label="GitHub">
                <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
              </a>
            </div>
          </div>
        </div>

        <div class="footer-bottom">
          <p>© {{ new Date().getFullYear() }} {{ settings.general.site_name }}. {{ t.rights }}</p>
          <p class="footer-credit">
            {{ t.builtWith }} | 
            <a href="#" @click.prevent="activePage = 'admin'" class="admin-portal-link">
              {{ lang === 'ar' ? 'بوابة المدير' : 'Admin Portal' }}
            </a>
          </p>
        </div>
      </div>
</footer>

    <FloatingWhatsApp />

  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, provide } from 'vue'
import HomeTab     from './components/HomeTab.vue'
import AboutTab    from './components/AboutTab.vue'
import ServicesTab from './components/ServicesTab.vue'
import ContactTab  from './components/ContactTab.vue'
import AdminLogin  from './components/AdminLogin.vue'
import AdminDashboard from './components/AdminDashboard.vue'
import FloatingWhatsApp from './components/FloatingWhatsApp.vue'

const lang       = ref('ar')
const activePage = ref('home')
const menuOpen   = ref(false)
const isScrolled = ref(false)
const isAuthenticated = ref(!!localStorage.getItem('admin_token'))

// Default site settings values
const settings = ref({
  general: {
    site_name: 'SouqiTech',
    tagline_ar: 'حلول برمجية متكاملة',
    tagline_en: 'Integrated Software Solutions',
    logo_emoji: '⬡',
  },
  hero: {
    badge_ar: 'شركة تقنية رائدة في المنطقة',
    badge_en: 'Leading tech company in the region',
    title1_ar: 'نبني مواقع وتطبيقات',
    title1_en: 'We Build Websites & Apps',
    title2_ar: 'تُغيّر قواعد اللعبة',
    title2_en: 'That Change the Game',
    sub_ar: 'نحوّل أفكارك الجريئة إلى منتجات رقمية عالية الأداء باستخدام أحدث تقنيات الويب وأسرعها نموًا.',
    sub_en: 'We transform bold ideas into high-performance digital products using the latest and fastest-growing web technologies.',
    cta_primary_ar: 'استكشف خدماتنا',
    cta_primary_en: 'Explore Services',
    cta_secondary_ar: 'ابدأ مشروعك',
    cta_secondary_en: 'Start Your Project',
    tech_tags: ['Laravel', 'Vue.js', 'React', 'Node.js', 'MySQL', 'REST API', 'Docker']
  },
  stats: [
    { value: '+50', label_ar: 'مشروع ناجح', label_en: 'Successful Projects' },
    { value: '+30', label_ar: 'عميل راضٍ', label_en: 'Happy Clients' },
    { value: '5★', label_ar: 'تقييم العملاء', label_en: 'Client Rating' },
    { value: '+3', label_ar: 'سنوات خبرة', label_en: 'Years of Experience' }
  ],
  services: [
    {
      icon: '🌐',
      badge_ar: 'الأكثر طلباً',
      badge_en: 'Most Requested',
      title_ar: 'مواقع الشركات والمؤسسات',
      title_en: 'Corporate & Business Websites',
      desc_ar: 'نصمم ونطوّر مواقع شركات احترافية تعكس هويتك التجارية بأعلى معايير التصميم الحديث وتجربة المستخدم.',
      desc_en: 'We design and develop professional corporate websites that reflect your brand identity with the highest standards of modern design and user experience.',
      features_ar: ['تصميم UI/UX عصري ومخصص', 'تحسين محركات البحث (SEO)', 'واجهة إدارة محتوى سهلة', 'دعم كامل للغة العربية (RTL)', 'أداء عالٍ وسرعة تحميل مثالية'],
      features_en: ['Modern & custom UI/UX design', 'Search Engine Optimization (SEO)', 'Easy content management interface', 'Full Arabic (RTL) language support', 'High performance and optimal load speed'],
      stack: ['HTML5', 'CSS3', 'Vue.js', 'Laravel', 'MySQL']
    },
    {
      icon: '🛒',
      badge_ar: 'حل متكامل',
      badge_en: 'Complete Solution',
      title_ar: 'المتاجر والمنصات الإلكترونية',
      title_en: 'E-Commerce & Online Platforms',
      desc_ar: 'نبني منصات تجارة إلكترونية متكاملة مع نظام دفع آمن وإدارة مخزون ذكية ولوحة تحكم احترافية.',
      desc_en: 'We build integrated e-commerce platforms with secure payment systems, smart inventory management, and a professional admin dashboard.',
      features_ar: ['بوابات دفع متعددة وآمنة', 'نظام إدارة المخزون الذكي', 'لوحة تحليلات المبيعات', 'نظام كوبونات وعروض ترويجية', 'تكامل مع شركات الشحن'],
      features_en: ['Multiple secure payment gateways', 'Smart inventory management system', 'Sales analytics dashboard', 'Coupons and promotional system', 'Integration with shipping companies'],
      stack: ['Laravel', 'Vue.js', 'Stripe', 'PayPal', 'Redis']
    },
    {
      icon: '⚡',
      badge_ar: 'مقياسية',
      badge_en: 'Scalable',
      title_ar: 'تطبيقات الويب المتطورة',
      title_en: 'Advanced Web Applications',
      desc_ar: 'نطوّر تطبيقات ويب قابلة للتوسع بأعلى معايير الأداء والأمان لخدمة الأعمال في بيئات الإنتاج الحقيقية.',
      desc_en: 'We develop scalable web applications with the highest performance and security standards to serve businesses in real production environments.',
      features_ar: ['بنية microservices مرنة', 'REST API متكاملة وموثقة', 'أنظمة مصادقة JWT/OAuth2', 'نشر تلقائي CI/CD', 'رصد الأداء والتنبيهات'],
      features_en: ['Flexible microservices architecture', 'Integrated and documented REST API', 'JWT/OAuth2 authentication systems', 'Automated CI/CD deployment', 'Performance monitoring and alerts'],
      stack: ['Laravel API', 'Vue 3', 'Docker', 'PostgreSQL', 'AWS']
    }
  ],
  about: {
    mission_title_ar: 'رسالتنا',
    mission_title_en: 'Our Mission',
    mission_desc_ar: 'نلتزم بتقديم حلول تقنية متكاملة تمكّن الشركات من التحول الرقمي الفعّال، وتضمن لها حضوراً رقمياً قوياً ومستداماً يدعم نموها وأهدافها الاستراتيجية.',
    mission_desc_en: 'We are committed to providing integrated technical solutions that enable companies to undergo effective digital transformation, ensuring a strong and sustainable digital presence that supports their growth and strategic goals.',
    vision_title_ar: 'رؤيتنا',
    vision_title_en: 'Our Vision',
    vision_desc_ar: 'نطمح أن نكون الشريك التقني الأول والأكثر ثقة في المنطقة، من خلال تقديم منتجات رقمية ترسم ملامح مستقبل التقنية وتستشرف متطلبات السوق.',
    vision_desc_en: 'We aspire to be the first and most trusted technical partner in the region, delivering digital products that shape the future of technology and anticipate market needs.',
    story_label_ar: 'قصتنا',
    story_label_en: 'Our Story',
    story_title_ar: 'رحلة البناء والإبداع',
    story_title_en: 'A Journey of Building & Innovation',
    story_desc1_ar: 'بدأت سوقي تك كفكرة جريئة: لماذا لا توجد شركة تقنية متخصصة تفهم الأسواق المحلية وتقدم حلولاً عالمية المستوى؟ انطلقنا من هذه الرؤية لنبني فريقاً متميزاً من المطورين والمصممين الشغوفين.',
    story_desc1_en: 'Souqi Tech started as a bold idea: why isn\'t there a specialized tech company that understands local markets and delivers world-class solutions? We launched from this vision to build a distinguished team of passionate developers and designers.',
    story_desc2_ar: 'اليوم، نفخر بخدمة عشرات الشركات من مختلف القطاعات، ونواصل مسيرتنا في تطوير حلول رقمية تجمع بين التصميم الجمالي الاستثنائي والهندسة التقنية الصلبة.',
    story_desc2_en: 'Today, we are proud to serve dozens of companies from various sectors, continuing our journey in developing digital solutions that combine exceptional aesthetic design with solid technical engineering.',
    values: [
      { icon: '💡', title_ar: 'الابتكار', title_en: 'Innovation', desc_ar: 'نتبنى التقنيات الحديثة ونبحث دائمًا عن أفضل الحلول.', desc_en: 'We embrace modern technologies and always seek the best solutions.' },
      { icon: '⚡', title_ar: 'السرعة', title_en: 'Speed', desc_ar: 'نلتزم بالمواعيد ونسلّم مشاريع عالية الجودة في وقتها.', desc_en: 'We meet deadlines and deliver high-quality projects on time.' },
      { icon: '🔒', title_ar: 'الأمان', title_en: 'Security', desc_ar: 'نحمي بيانات عملائنا بأعلى معايير الأمن السيبراني.', desc_en: 'We protect our clients\' data with the highest cybersecurity standards.' },
      { icon: '🤝', title_ar: 'الشراكة', title_en: 'Partnership', desc_ar: 'نعتبر نجاح عملائنا نجاحًا لنا ونبني علاقات طويلة الأمد.', desc_en: 'We consider our clients\' success our success and build long-term relationships.' }
    ],
    achievements: [
      { icon: '🏆', value: '+50', label_ar: 'مشروع مكتمل', label_en: 'Completed Projects' },
      { icon: '👥', value: '+30', label_ar: 'عميل راضٍ', label_en: 'Happy Clients' },
      { icon: '🌍', value: '5+', label_ar: 'دول نخدمها', label_en: 'Countries Served' },
      { icon: '⭐', value: '5★', label_ar: 'تقييم العملاء', label_en: 'Client Rating' }
    ],
    techs: [
      { icon: '🟢', name: 'Laravel' }, { icon: '🟦', name: 'Vue.js' },
      { icon: '⚛️', name: 'React' },  { icon: '🔷', name: 'TypeScript' },
      { icon: '🐘', name: 'MySQL' },  { icon: '🟠', name: 'Redis' },
      { icon: '🐳', name: 'Docker' }, { icon: '☁️', name: 'AWS' }
    ]
  },
  contact_info: {
    email: 'info@souqitech.com',
    phone: '+966 50 000 0000',
    whatsapp: '+966 50 000 0000',
    address_ar: 'الرياض، المملكة العربية السعودية',
    address_en: 'Riyadh, Saudi Arabia'
  },
  footer: {
    desc_ar: 'نصنع تجارب رقمية استثنائية تجمع بين الإبداع والتقنية المتقدمة.',
    desc_en: 'We craft exceptional digital experiences combining creativity and advanced technology.',
    twitter: '#',
    linkedin: '#',
    instagram: '#',
    facebook: '#',
    tiktok: '#',
    youtube: '#',
    github: '#'
  }
})

// Provide settings to all child tabs
provide('settings', settings)

async function fetchSettings() {
  try {
    const res = await fetch('/api/site-settings')
    if (res.ok) {
      const data = await res.json()
      if (data.success && data.data) {
        Object.keys(data.data).forEach(key => {
          if (data.data[key] !== null) {
            settings.value[key] = data.data[key]
          }
        })
      }
    }
  } catch (err) {
    console.error('Failed to load site settings:', err)
  }
}

provide('refreshSettings', fetchSettings)

function getLogoText() {
  const name = settings.value.general?.site_name || 'SouqiTech'
  if (name.toLowerCase().endsWith('tech')) {
    const main = name.substring(0, name.toLowerCase().lastIndexOf('tech'))
    return { left: main, right: name.substring(name.toLowerCase().lastIndexOf('tech')) }
  }
  return { left: name, right: '' }
}

function handleLoginSuccess() {
  isAuthenticated.value = true
}

function handleLogout() {
  isAuthenticated.value = false
  activePage.value = 'home'
}

const navItems = [
  { key: 'home',     ar: 'الرئيسية',    en: 'Home' },
  { key: 'about',    ar: 'من نحن',      en: 'About Us' },
  { key: 'services', ar: 'خدماتنا',     en: 'Services' },
  { key: 'contact',  ar: 'تواصل معنا',  en: 'Contact' },
]

const translations = {
  ar: {
    quickLinks: 'روابط سريعة',
    contactUs:  'تواصل معنا',
    followUs:   'تابعنا',
    location:   'المملكة العربية السعودية',
    rights:     'جميع الحقوق محفوظة.',
    builtWith:  'مبني بـ Laravel & Vue 3 ❤️',
  },
  en: {
    quickLinks: 'Quick Links',
    contactUs:  'Contact Us',
    followUs:   'Follow Us',
    location:   'Saudi Arabia',
    rights:     'All rights reserved.',
    builtWith:  'Built with Laravel & Vue 3 ❤️',
  }
}

const t = computed(() => translations[lang.value])

function toggleLang() {
  lang.value = lang.value === 'ar' ? 'en' : 'ar'
  document.documentElement.lang = lang.value
}

function handleScroll() {
  isScrolled.value = window.scrollY > 20
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
  fetchSettings()
})
onUnmounted(() => window.removeEventListener('scroll', handleScroll))
</script>

<style scoped>
/* ── App Wrapper ── */
.app-wrapper {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.main-content { flex: 1; }

/* ── Header ── */
.header {
  position: fixed;
  top: 0; left: 0; right: 0;
  z-index: 1000;
  padding: 1.2rem 0;
  background: rgba(10, 11, 16, 0.7);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border-bottom: 1px solid transparent;
  transition: all 0.3s ease;
}

.header.scrolled {
  background: rgba(10, 11, 16, 0.95);
  border-bottom-color: var(--border-color);
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
  padding: 0.8rem 0;
}

.header-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 2rem;
}

/* ── Logo ── */
.logo {
  display: flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
  flex-shrink: 0;
}

.logo-icon {
  font-size: 1.8rem;
  color: var(--accent);
  filter: drop-shadow(0 0 8px var(--accent));
  line-height: 1;
}

.logo-text {
  font-size: 1.4rem;
  font-weight: 800;
  color: var(--text-primary);
  letter-spacing: -0.5px;
}

/* ── Desktop Nav ── */
.nav-desktop {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.nav-link {
  padding: 8px 18px;
  border-radius: 50px;
  font-weight: 500;
  font-size: 0.95rem;
  color: var(--text-secondary);
  text-decoration: none;
  transition: all 0.25s ease;
  position: relative;
}

.nav-link:hover {
  color: var(--text-primary);
  background: var(--accent-dim);
}

.nav-link.active {
  color: var(--accent);
  background: var(--accent-dim);
  font-weight: 600;
}

/* ── Header Controls ── */
.header-controls {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex-shrink: 0;
}

/* ── Language Button ── */
.lang-btn {
  padding: 8px 16px;
  background: transparent;
  border: 1.5px solid var(--border-glow);
  border-radius: 50px;
  color: var(--accent);
  font-weight: 700;
  font-size: 0.85rem;
  cursor: pointer;
  transition: all 0.25s ease;
  font-family: inherit;
}

.lang-btn:hover {
  background: var(--accent);
  color: #fff;
  box-shadow: 0 0 15px var(--accent-glow);
}

/* ── Hamburger ── */
.hamburger {
  display: none;
  flex-direction: column;
  gap: 5px;
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 5px;
}

.hamburger span {
  display: block;
  width: 22px;
  height: 2px;
  background: var(--text-primary);
  border-radius: 2px;
  transition: all 0.3s ease;
}

.hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.hamburger.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
.hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

/* ── Mobile Menu ── */
.mobile-menu {
  display: flex;
  flex-direction: column;
  padding: 1rem 0;
  border-top: 1px solid var(--border-color);
  background: rgba(10, 11, 16, 0.98);
}

.mobile-nav-link {
  padding: 14px 2rem;
  font-weight: 500;
  color: var(--text-secondary);
  text-decoration: none;
  transition: all 0.2s ease;
  border-left: 3px solid transparent;
}

[dir="rtl"] .mobile-nav-link {
  border-left: none;
  border-right: 3px solid transparent;
}

.mobile-nav-link:hover,
.mobile-nav-link.active {
  color: var(--accent);
  background: var(--accent-dim);
  border-left-color: var(--accent);
}

[dir="rtl"] .mobile-nav-link:hover,
[dir="rtl"] .mobile-nav-link.active {
  border-right-color: var(--accent);
}

/* ── Slide Down ── */
.slide-down-enter-active { animation: slideDown 0.3s ease; }
.slide-down-leave-active { animation: slideDown 0.3s ease reverse; }
@keyframes slideDown {
  from { opacity: 0; transform: translateY(-10px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── Page Fade ── */
.page-fade-enter-active, .page-fade-leave-active { transition: opacity 0.3s ease, transform 0.3s ease; }
.page-fade-enter-from { opacity: 0; transform: translateY(16px); }
.page-fade-leave-to   { opacity: 0; transform: translateY(-10px); }

/* ── Footer ── */
.footer {
  background: var(--bg-secondary);
  border-top: 1px solid var(--border-color);
  padding: 4rem 0 0;
  margin-top: auto;
}

.footer-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr;
  gap: 3rem;
  margin-bottom: 3rem;
}

.footer-desc { color: var(--text-secondary); font-size: 0.95rem; line-height: 1.8; max-width: 260px; }

.footer-heading { font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; color: var(--text-secondary); margin-bottom: 1.25rem; }

.footer-links { list-style: none; display: flex; flex-direction: column; gap: 0.75rem; }
.footer-links a { color: var(--text-secondary); font-size: 0.95rem; transition: color 0.2s; }
.footer-links a:hover { color: var(--accent); }

.footer-contact-list { list-style: none; display: flex; flex-direction: column; gap: 0.75rem; color: var(--text-secondary); font-size: 0.95rem; }

.social-links { display: flex; gap: 0.75rem; flex-wrap: wrap; }

.social-btn {
  width: 40px; height: 40px;
  border-radius: 10px;
  background: var(--bg-card);
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

.footer-bottom {
  border-top: 1px solid var(--border-color);
  padding: 1.5rem 0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 0.875rem;
  color: var(--text-muted);
}

.footer-credit { color: var(--text-muted); }

.admin-portal-link {
  color: var(--text-muted);
  text-decoration: none;
  font-weight: 600;
  transition: color 0.2s;
  margin-left: 5px;
}
.admin-portal-link:hover {
  color: var(--accent);
  text-shadow: 0 0 8px var(--accent-glow);
}
.admin-page-container {
  width: 100%;
  min-height: calc(100vh - 80px);
  display: flex;
  align-items: center;
  justify-content: center;
}
.main-content.dashboard-view {
  padding-top: 0;
  min-height: 100vh;
}

/* ── Responsive ── */
@media (max-width: 768px) {
  .nav-desktop { display: none; }
  .hamburger   { display: flex; }

  .footer-grid {
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
  }

  .footer-brand { grid-column: 1 / -1; }
  .footer-desc  { max-width: 100%; }
  .footer-bottom { flex-direction: column; gap: 0.5rem; text-align: center; }
}

@media (max-width: 480px) {
  .footer-grid { grid-template-columns: 1fr; }
}
</style>
