<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. General Settings
        SiteSetting::updateOrCreate(
            ['key' => 'general'],
            ['value' => [
                'site_name' => 'SouqiTech',
                'tagline_ar' => 'حلول برمجية متكاملة',
                'tagline_en' => 'Integrated Software Solutions',
                'logo_emoji' => '⬡',
            ]]
        );

        // 2. Hero Section
        SiteSetting::updateOrCreate(
            ['key' => 'hero'],
            ['value' => [
                'badge_ar' => 'شركة تقنية رائدة في المنطقة',
                'badge_en' => 'Leading tech company in the region',
                'title1_ar' => 'نبني مواقع وتطبيقات',
                'title1_en' => 'We Build Websites & Apps',
                'title2_ar' => 'تُغيّر قواعد اللعبة',
                'title2_en' => 'That Change the Game',
                'sub_ar' => 'نحوّل أفكارك الجريئة إلى منتجات رقمية عالية الأداء باستخدام أحدث تقنيات الويب وأسرعها نموًا.',
                'sub_en' => 'We transform bold ideas into high-performance digital products using the latest and fastest-growing web technologies.',
                'cta_primary_ar' => 'استكشف خدماتنا',
                'cta_primary_en' => 'Explore Services',
                'cta_secondary_ar' => 'ابدأ مشروعك',
                'cta_secondary_en' => 'Start Your Project',
                'tech_tags' => ['Laravel', 'Vue.js', 'React', 'Node.js', 'MySQL', 'REST API', 'Docker'],
            ]]
        );

        // 3. Stats Section
        SiteSetting::updateOrCreate(
            ['key' => 'stats'],
            ['value' => [
                [
                    'value' => '+50',
                    'label_ar' => 'مشروع ناجح',
                    'label_en' => 'Successful Projects',
                ],
                [
                    'value' => '+30',
                    'label_ar' => 'عميل راضٍ',
                    'label_en' => 'Happy Clients',
                ],
                [
                    'value' => '5★',
                    'label_ar' => 'تقييم العملاء',
                    'label_en' => 'Client Rating',
                ],
                [
                    'value' => '+3',
                    'label_ar' => 'سنوات خبرة',
                    'label_en' => 'Years of Experience',
                ],
            ]]
        );

        // 4. Features Section
        SiteSetting::updateOrCreate(
            ['key' => 'features'],
            ['value' => [
                [
                    'icon' => '🚀',
                    'title_ar' => 'أداء عالي وسرعة تحميل',
                    'title_en' => 'High Performance & Fast Loading',
                    'desc_ar' => 'نضمن تجربة مستخدم سلسة وسرعة استجابة ممتازة لموقعك أو تطبيقك.',
                    'desc_en' => 'We ensure smooth user experience and excellent response time for your site or app.',
                ],
                [
                    'icon' => '🔒',
                    'title_ar' => 'أمان قوي وحماية بيانات',
                    'title_en' => 'Strong Security & Data Protection',
                    'desc_ar' => 'نطبق أفضل معايير الحماية لضمان أمن المعلومات وحماية المستخدمين.',
                    'desc_en' => 'We apply top security standards to safeguard information and protect users.',
                ],
                [
                    'icon' => '💡',
                    'title_ar' => 'حلول مبتكرة حسب الاحتياج',
                    'title_en' => 'Custom Innovative Solutions',
                    'desc_ar' => 'نصمم حلول برمجية مخصصة تلبي أهداف مؤسستك بشكل احترافي.',
                    'desc_en' => 'We design tailored software solutions that professionally meet your business goals.',
                ],
            ]]
        );

        // 5. Services Section
        SiteSetting::updateOrCreate(
            ['key' => 'services'],
            ['value' => [
                [
                    'icon' => '🌐',
                    'badge_ar' => 'الأكثر طلباً',
                    'badge_en' => 'Most Requested',
                    'title_ar' => 'مواقع الشركات والمؤسسات',
                    'title_en' => 'Corporate & Business Websites',
                    'desc_ar' => 'نصمم ونطوّر مواقع شركات احترافية تعكس هويتك التجارية بأعلى معايير التصميم الحديث وتجربة المستخدم.',
                    'desc_en' => 'We design and develop professional corporate websites that reflect your brand identity with the highest standards of modern design and user experience.',
                    'features_ar' => ['تصميم UI/UX عصري ومخصص', 'تحسين محركات البحث (SEO)', 'واجهة إدارة محتوى سهلة', 'دعم كامل للغة العربية (RTL)', 'أداء عالٍ وسرعة تحميل مثالية'],
                    'features_en' => ['Modern & custom UI/UX design', 'Search Engine Optimization (SEO)', 'Easy content management interface', 'Full Arabic (RTL) language support', 'High performance and optimal load speed'],
                    'stack' => ['HTML5', 'CSS3', 'Vue.js', 'Laravel', 'MySQL'],
                ],
                [
                    'icon' => '🛒',
                    'badge_ar' => 'حل متكامل',
                    'badge_en' => 'Complete Solution',
                    'title_ar' => 'المتاجر والمنصات الإلكترونية',
                    'title_en' => 'E-Commerce & Online Platforms',
                    'desc_ar' => 'نبني منصات تجارة إلكترونية متكاملة مع نظام دفع آمن وإدارة مخزون ذكية ولوحة تحكم احترافية.',
                    'desc_en' => 'We build integrated e-commerce platforms with secure payment systems, smart inventory management, and a professional admin dashboard.',
                    'features_ar' => ['بوابات دفع متعددة وآمنة', 'نظام إدارة المخزون الذكي', 'لوحة تحليلات المبيعات', 'نظام كوبونات وعروض ترويجية', 'تكامل مع شركات الشحن'],
                    'features_en' => ['Multiple secure payment gateways', 'Smart inventory management system', 'Sales analytics dashboard', 'Coupons and promotional system', 'Integration with shipping companies'],
                    'stack' => ['Laravel', 'Vue.js', 'Stripe', 'PayPal', 'Redis'],
                ],
                [
                    'icon' => '⚡',
                    'badge_ar' => 'مقياسية',
                    'badge_en' => 'Scalable',
                    'title_ar' => 'تطبيقات الويب المتطورة',
                    'title_en' => 'Advanced Web Applications',
                    'desc_ar' => 'نطوّر تطبيقات ويب قابلة للتوسع بأعلى معايير الأداء والأمان لخدمة الأعمال في بيئات الإنتاج الحقيقية.',
                    'desc_en' => 'We develop scalable web applications with the highest performance and security standards to serve businesses in real production environments.',
                    'features_ar' => ['بنية microservices مرنة', 'REST API متكاملة وموثقة', 'أنظمة مصادقة JWT/OAuth2', 'نشر تلقائي CI/CD', 'رصد الأداء والتنبيهات'],
                    'features_en' => ['Flexible microservices architecture', 'Integrated and documented REST API', 'JWT/OAuth2 authentication systems', 'Automated CI/CD deployment', 'Performance monitoring and alerts'],
                    'stack' => ['Laravel API', 'Vue 3', 'Docker', 'PostgreSQL', 'AWS'],
                ],
            ]]
        );

        // 5. About Section
        SiteSetting::updateOrCreate(
            ['key' => 'about'],
            ['value' => [
                'mission_title_ar' => 'رسالتنا',
                'mission_title_en' => 'Our Mission',
                'mission_desc_ar' => 'نلتزم بتقديم حلول تقنية متكاملة تمكّن الشركات من التحول الرقمي الفعّال، وتضمن لها حضوراً رقمياً قوياً ومستداماً يدعم نموها وأهدافها الاستراتيجية.',
                'mission_desc_en' => 'We are committed to providing integrated technical solutions that enable companies to undergo effective digital transformation, ensuring a strong and sustainable digital presence that supports their growth and strategic goals.',
                'vision_title_ar' => 'رؤيتنا',
                'vision_title_en' => 'Our Vision',
                'vision_desc_ar' => 'نطمح أن نكون الشريك التقني الأول والأكثر ثقة في المنطقة، من خلال تقديم منتجات رقمية ترسم ملامح مستقبل التقنية وتستشرف متطلبات السوق.',
                'vision_desc_en' => 'We aspire to be the first and most trusted technical partner in the region, delivering digital products that shape the future of technology and anticipate market needs.',
                'story_label_ar' => 'قصتنا',
                'story_label_en' => 'Our Story',
                'story_title_ar' => 'رحلة البناء والإبداع',
                'story_title_en' => 'A Journey of Building & Innovation',
                'story_desc1_ar' => 'بدأت سوقي تك كفكرة جريئة: لماذا لا توجد شركة تقنية متخصصة تفهم الأسواق المحلية وتقدم حلولاً عالمية المستوى؟ انطلقنا من هذه الرؤية لنبني فريقاً متميزاً من المطورين والمصممين الشغوفين.',
                'story_desc1_en' => 'Souqi Tech started as a bold idea: why isn\'t there a specialized tech company that understands local markets and delivers world-class solutions? We launched from this vision to build a distinguished team of passionate developers and designers.',
                'story_desc2_ar' => 'اليوم، نفخر بخدمة عشرات الشركات من مختلف القطاعات، ونواصل مسيرتنا في تطوير حلول رقمية تجمع بين التصميم الجمالي الاستثنائي والهندسة التقنية الصلبة.',
                'story_desc2_en' => 'Today, we are proud to serve dozens of companies from various sectors, continuing our journey in developing digital solutions that combine exceptional aesthetic design with solid technical engineering.',
                'values' => [
                    [
                        'icon' => '💡',
                        'title_ar' => 'الابتكار',
                        'title_en' => 'Innovation',
                        'desc_ar' => 'نتبنى التقنيات الحديثة ونبحث دائمًا عن أفضل الحلول.',
                        'desc_en' => 'We embrace modern technologies and always seek the best solutions.',
                    ],
                    [
                        'icon' => '⚡',
                        'title_ar' => 'السرعة',
                        'title_en' => 'Speed',
                        'desc_ar' => 'نلتزم بالمواعيد ونسلّم مشاريع عالية الجودة في وقتها.',
                        'desc_en' => 'We meet deadlines and deliver high-quality projects on time.',
                    ],
                    [
                        'icon' => '🔒',
                        'title_ar' => 'الأمان',
                        'title_en' => 'Security',
                        'desc_ar' => 'نحمي بيانات عملائنا بأعلى معايير الأمن السيبراني.',
                        'desc_en' => 'We protect our clients\' data with the highest cybersecurity standards.',
                    ],
                    [
                        'icon' => '🤝',
                        'title_ar' => 'الشراكة',
                        'title_en' => 'Partnership',
                        'desc_ar' => 'نعتبر نجاح عملائنا نجاحًا لنا ونبني علاقات طويلة الأمد.',
                        'desc_en' => 'We consider our clients\' success our success and build long-term relationships.',
                    ],
                ],
                'achievements' => [
                    [
                        'icon' => '🏆',
                        'value' => '+50',
                        'label_ar' => 'مشروع مكتمل',
                        'label_en' => 'Completed Projects',
                    ],
                    [
                        'icon' => '👥',
                        'value' => '+30',
                        'label_ar' => 'عميل راضٍ',
                        'label_en' => 'Happy Clients',
                    ],
                    [
                        'icon' => '🌍',
                        'value' => '5+',
                        'label_ar' => 'دول نخدمها',
                        'label_en' => 'Countries Served',
                    ],
                    [
                        'icon' => '⭐',
                        'value' => '5★',
                        'label_ar' => 'تقييم العملاء',
                        'label_en' => 'Client Rating',
                    ],
                ],
                'techs' => [
                    ['icon' => '🟢', 'name' => 'Laravel'],
                    ['icon' => '🟦', 'name' => 'Vue.js'],
                    ['icon' => '⚛️', 'name' => 'React'],
                    ['icon' => '🔷', 'name' => 'TypeScript'],
                    ['icon' => '🐘', 'name' => 'MySQL'],
                    ['icon' => '🟠', 'name' => 'Redis'],
                    ['icon' => '🐳', 'name' => 'Docker'],
                    ['icon' => '☁️', 'name' => 'AWS'],
                ],
            ]]
        );

        // 6. Contact Info
        SiteSetting::updateOrCreate(
            ['key' => 'contact_info'],
            ['value' => [
                'email' => 'info@souqitech.com',
                'phone' => '+966 50 000 0000',
                'whatsapp' => '+966 50 000 0000',
                'address_ar' => 'الرياض، المملكة العربية السعودية',
                'address_en' => 'Riyadh, Saudi Arabia',
            ]]
        );

        // 7. Footer
        SiteSetting::updateOrCreate(
            ['key' => 'footer'],
            ['value' => [
                'desc_ar' => 'نصنع تجارب رقمية استثنائية تجمع بين الإبداع والتقنية المتقدمة.',
                'desc_en' => 'We craft exceptional digital experiences combining creativity and advanced technology.',
                'twitter' => '#',
                'linkedin' => '#',
                'instagram' => '#',
                'facebook' => '#',
                'tiktok' => '#',
                'youtube' => '#',
                'github' => '#',
            ]]
        );
    }
}
