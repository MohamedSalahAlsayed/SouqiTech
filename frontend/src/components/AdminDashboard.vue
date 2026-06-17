<template>
  <div class="dashboard-wrapper">
    
    <!-- ══════════ SIDEBAR / HEADER ══════════ -->
    <aside class="dashboard-sidebar">
      <div class="sidebar-header">
        <span class="logo-icon">⬡</span>
        <span class="logo-text">Souqi<span class="text-accent">Tech</span></span>
      </div>
      
      <div class="sidebar-user">
        <div class="user-avatar">AD</div>
        <div class="user-info">
          <span class="user-name">{{ adminUser.name || 'Admin' }}</span>
          <span class="user-role">{{ t.administrator }}</span>
        </div>
      </div>

      <nav class="sidebar-nav">
        <button 
          class="nav-item" 
          :class="{ active: currentTab === 'overview' }"
          @click="currentTab = 'overview'"
        >
          <span class="nav-icon">📊</span>
          <span>{{ t.overview }}</span>
        </button>
        <button 
          class="nav-item" 
          :class="{ active: currentTab === 'inquiries' }"
          @click="currentTab = 'inquiries'"
        >
          <span class="nav-icon">📨</span>
          <span>{{ t.inquiries }}</span>
        </button>
        <button 
          class="nav-item" 
          :class="{ active: currentTab === 'cms' }"
          @click="selectCmsTabMenu"
        >
          <span class="nav-icon">⚙️</span>
          <span>{{ t.cmsSettings }}</span>
        </button>
      </nav>

      <div class="sidebar-footer">
        <button class="control-btn lang-toggle-btn" @click="toggleAppLang">
          🌐 {{ lang === 'ar' ? 'English' : 'عربي' }}
        </button>
        <button class="nav-item logout-btn" @click="handleLogoutClick">
          <span class="nav-icon">🚪</span>
          <span>{{ t.logout }}</span>
        </button>
      </div>
    </aside>

    <!-- MOBILE NAVIGATION HEADER -->
    <header class="mobile-dash-header">
      <div class="logo">
        <span class="logo-icon">⬡</span>
        <span class="logo-text">Souqi<span class="text-accent">Tech</span></span>
      </div>
      <div class="mobile-dash-actions">
        <button class="mobile-lang-btn" @click="toggleAppLang">
          {{ lang === 'ar' ? 'EN' : 'عر' }}
        </button>
        <button class="mobile-logout-btn" @click="handleLogoutClick" :title="t.logout">
          🚪
        </button>
      </div>
    </header>
    <nav class="mobile-dash-nav">
      <button 
        class="mobile-nav-item" 
        :class="{ active: currentTab === 'overview' }"
        @click="currentTab = 'overview'"
      >
        📊 {{ t.overview }}
      </button>
      <button 
        class="mobile-nav-item" 
        :class="{ active: currentTab === 'inquiries' }"
        @click="currentTab = 'inquiries'"
      >
        📨 {{ t.inquiries }}
      </button>
      <button 
        class="mobile-nav-item" 
        :class="{ active: currentTab === 'cms' }"
        @click="selectCmsTabMenu"
      >
        ⚙️ {{ t.cmsSettings }}
      </button>
    </nav>

    <!-- ══════════ MAIN CONTENT ══════════ -->
    <main class="dashboard-body">
      
      <!-- LOADING COVER -->
      <div v-if="isGlobalLoading" class="dash-loader">
        <span class="spinner"></span>
        <p>{{ t.loadingData }}</p>
      </div>

      <div v-else class="dash-content fade-in">
        
        <!-- ==================== TAB: OVERVIEW ==================== -->
        <div v-if="currentTab === 'overview'" class="tab-pane">
          <div class="page-title-wrap">
            <h2 class="dash-page-title">{{ t.overviewDashboard }}</h2>
            <p class="dash-page-desc">{{ t.welcomeBack }}, {{ adminUser.name }}</p>
          </div>

          <!-- Counter Cards -->
          <div class="stats-grid">
            <div class="stat-card total-card">
              <div class="stat-header">
                <span class="stat-label">{{ t.totalInquiries }}</span>
                <span class="stat-icon-bg">📁</span>
              </div>
              <div class="stat-num">{{ stats.counters.total }}</div>
            </div>

            <div class="stat-card pending-card">
              <div class="stat-header">
                <span class="stat-label">{{ t.pending }}</span>
                <span class="stat-icon-bg">⏳</span>
              </div>
              <div class="stat-num glow-orange">{{ stats.counters.pending }}</div>
            </div>

            <div class="stat-card read-card">
              <div class="stat-header">
                <span class="stat-label">{{ t.read }}</span>
                <span class="stat-icon-bg">👀</span>
              </div>
              <div class="stat-num glow-blue">{{ stats.counters.read }}</div>
            </div>

            <div class="stat-card replied-card">
              <div class="stat-header">
                <span class="stat-label">{{ t.replied }}</span>
                <span class="stat-icon-bg">✅</span>
              </div>
              <div class="stat-num glow-green">{{ stats.counters.replied }}</div>
            </div>
          </div>

          <!-- Charts and Activity Grid -->
          <div class="overview-grid">
            
            <!-- Custom CSS Bar Chart -->
            <div class="chart-card">
              <h3 class="card-title">{{ t.submissionHistory }}</h3>
              <p class="card-subtitle">{{ t.last7DaysSubmissions }}</p>
              
              <div class="chart-wrapper">
                <div class="chart-y-axis">
                  <span>{{ maxChartValue }}</span>
                  <span>{{ Math.round(maxChartValue / 2) }}</span>
                  <span>0</span>
                </div>
                
                <div class="chart-bars-container">
                  <div 
                    v-for="item in stats.chart" 
                    :key="item.date" 
                    class="chart-bar-column"
                  >
                    <div class="chart-bar-track">
                      <div 
                        class="chart-bar-fill"
                        :style="{ height: getBarHeightPercent(item.count) }"
                      >
                        <div class="chart-bar-tooltip">
                          {{ item.count }} {{ t.messages }}
                        </div>
                      </div>
                    </div>
                    <span class="chart-bar-label">{{ lang === 'ar' ? translateDay(item.day) : item.day }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Quick Overview Actions / Recent -->
            <div class="activity-card">
              <h3 class="card-title">{{ t.recentInquiries }}</h3>
              <p class="card-subtitle">{{ t.latestInquiriesReceived }}</p>

              <div class="recent-list" v-if="recentInquiries.length > 0">
                <div 
                  v-for="inq in recentInquiries" 
                  :key="inq.id" 
                  class="recent-item"
                  @click="openInquiryDetails(inq)"
                >
                  <div class="recent-meta">
                    <span class="recent-name">{{ inq.name }}</span>
                    <span class="recent-date">{{ formatDate(inq.created_at) }}</span>
                  </div>
                  <div class="recent-subject-wrap">
                    <span class="recent-subject">{{ inq.subject || t.noSubject }}</span>
                    <span class="badge-status" :class="inq.status">{{ t[inq.status] }}</span>
                  </div>
                </div>
              </div>
              
              <div v-else class="empty-state">
                <p>📭 {{ t.noInquiriesFound }}</p>
              </div>

              <button class="btn btn-outline view-all-btn" @click="currentTab = 'inquiries'">
                {{ t.viewAllInquiries }} &rarr;
              </button>
            </div>

          </div>
        </div>

        <!-- ==================== TAB: INQUIRIES ==================== -->
        <div v-else-if="currentTab === 'inquiries'" class="tab-pane">
          <div class="page-title-wrap">
            <h2 class="dash-page-title">{{ t.manageInquiries }}</h2>
            <p class="dash-page-desc">{{ t.manageInquiriesDesc }}</p>
          </div>

          <!-- Filters and Search Bar -->
          <div class="filters-bar">
            <!-- Search Input -->
            <div class="search-wrap">
              <span class="search-icon">🔍</span>
              <input 
                v-model="searchQuery" 
                type="text" 
                class="search-input" 
                :placeholder="t.searchPlaceholder"
                @input="debounceSearch"
              />
              <button v-if="searchQuery" class="clear-search-btn" @click="clearSearch">×</button>
            </div>

            <!-- Status Buttons -->
            <div class="status-filters">
              <button 
                class="filter-tab-btn" 
                :class="{ active: selectedFilter === 'all' }"
                @click="setFilter('all')"
              >
                {{ t.all }}
              </button>
              <button 
                class="filter-tab-btn" 
                :class="{ active: selectedFilter === 'pending' }"
                @click="setFilter('pending')"
              >
                {{ t.pending }}
              </button>
              <button 
                class="filter-tab-btn" 
                :class="{ active: selectedFilter === 'read' }"
                @click="setFilter('read')"
              >
                {{ t.read }}
              </button>
              <button 
                class="filter-tab-btn" 
                :class="{ active: selectedFilter === 'replied' }"
                @click="setFilter('replied')"
              >
                {{ t.replied }}
              </button>
            </div>
          </div>

          <!-- inquiries Content -->
          <div class="inquiries-container">
            <!-- Table View for Large Screens -->
            <div class="table-responsive d-none-mobile">
              <table class="dash-table">
                <thead>
                  <tr>
                    <th>{{ t.client }}</th>
                    <th>{{ t.subject }}</th>
                    <th>{{ t.date }}</th>
                    <th>{{ t.status }}</th>
                    <th>{{ t.actions }}</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="inq in inquiries" :key="inq.id" :class="{ unread: inq.status === 'pending' }">
                    <td>
                      <div class="client-cell">
                        <span class="client-name">{{ inq.name }}</span>
                        <span class="client-email">{{ inq.email }}</span>
                      </div>
                    </td>
                    <td>
                      <span class="subject-text" :title="inq.subject">{{ inq.subject || t.noSubject }}</span>
                    </td>
                    <td>
                      <span class="date-text">{{ formatDate(inq.created_at) }}</span>
                    </td>
                    <td>
                      <span class="badge-status" :class="inq.status">{{ t[inq.status] }}</span>
                    </td>
                    <td>
                      <div class="actions-cell">
                        <button class="action-icon-btn view" :title="t.view" @click="openInquiryDetails(inq)">
                          👁️
                        </button>
                        <button 
                          v-if="inq.status !== 'read'"
                          class="action-icon-btn read" 
                          :title="t.markAsRead" 
                          @click="updateStatus(inq.id, 'read')"
                        >
                          👀
                        </button>
                        <button 
                          v-if="inq.status !== 'replied'"
                          class="action-icon-btn replied" 
                          :title="t.markAsReplied" 
                          @click="updateStatus(inq.id, 'replied')"
                        >
                          ✅
                        </button>
                        <button class="action-icon-btn delete" :title="t.delete" @click="confirmDeleteInquiry(inq.id)">
                          🗑️
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr v-if="inquiries.length === 0">
                    <td colspan="5" class="table-empty">
                      📭 {{ t.noInquiriesFound }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Card View for Mobile Screens -->
            <div class="mobile-cards-view d-none-desktop">
              <div 
                v-for="inq in inquiries" 
                :key="inq.id" 
                class="mobile-inquiry-card"
                :class="{ unread: inq.status === 'pending' }"
              >
                <div class="card-row">
                  <span class="card-name">{{ inq.name }}</span>
                  <span class="badge-status" :class="inq.status">{{ t[inq.status] }}</span>
                </div>
                <div class="card-email">{{ inq.email }}</div>
                <div class="card-subject"><strong>{{ t.subject }}:</strong> {{ inq.subject || t.noSubject }}</div>
                <div class="card-date">{{ formatDate(inq.created_at) }}</div>
                <div class="card-actions">
                  <button class="btn btn-outline btn-sm" @click="openInquiryDetails(inq)">
                    {{ t.view }}
                  </button>
                  <div class="status-quick-actions">
                    <button 
                      v-if="inq.status !== 'read'"
                      class="btn-icon-square"
                      :title="t.markAsRead"
                      @click="updateStatus(inq.id, 'read')"
                    >
                      👀
                    </button>
                    <button 
                      v-if="inq.status !== 'replied'"
                      class="btn-icon-square"
                      :title="t.markAsReplied"
                      @click="updateStatus(inq.id, 'replied')"
                    >
                      ✅
                    </button>
                    <button 
                      class="btn-icon-square delete"
                      :title="t.delete"
                      @click="confirmDeleteInquiry(inq.id)"
                    >
                      🗑️
                    </button>
                  </div>
                </div>
              </div>
              <div v-if="inquiries.length === 0" class="empty-state">
                <p>📭 {{ t.noInquiriesFound }}</p>
              </div>
            </div>

            <!-- Pagination Controls -->
            <div class="pagination" v-if="pagination.last_page > 1">
              <button 
                class="pag-btn" 
                :disabled="pagination.current_page === 1"
                @click="changePage(pagination.current_page - 1)"
              >
                &larr; {{ t.prev }}
              </button>
              <span class="pag-info">
                {{ t.page }} {{ pagination.current_page }} / {{ pagination.last_page }}
              </span>
              <button 
                class="pag-btn" 
                :disabled="pagination.current_page === pagination.last_page"
                @click="changePage(pagination.current_page + 1)"
              >
                {{ t.next }} &rarr;
              </button>
            </div>

          </div>
        </div>

        <!-- ==================== TAB: CMS SETTINGS ==================== -->
        <div v-else-if="currentTab === 'cms'" class="tab-pane">
          <div class="page-title-wrap">
            <h2 class="dash-page-title">{{ t.cmsSettings }}</h2>
            <p class="dash-page-desc">{{ t.cmsSettingsDesc }}</p>
          </div>

          <!-- CMS Alert Toast -->
          <transition name="fade">
            <div v-if="cmsAlert.show" :class="'cms-alert-toast ' + cmsAlert.type">
              <span>{{ cmsAlert.type === 'success' ? '✅' : '❌' }}</span>
              <span>{{ cmsAlert.message }}</span>
            </div>
          </transition>

          <div class="cms-editor-layout">
            <!-- Left sub-tabs menu -->
            <div class="cms-sidebar">
              <button 
                v-for="subTab in cmsSubTabs" 
                :key="subTab.key" 
                class="cms-subtab-btn" 
                :class="{ active: activeCmsTab === subTab.key }"
                @click="selectCmsTab(subTab.key)"
              >
                {{ subTab[lang] }}
              </button>
            </div>

            <!-- Content Area Form -->
            <!-- Loading state while settings are fetched -->
            <div v-if="!cmsReady" class="cms-form-body card" style="display:flex;align-items:center;justify-content:center;min-height:200px;gap:1rem;">
              <span style="font-size:1.5rem;">⟳</span>
              <span style="color:var(--text-secondary);">{{ lang === 'ar' ? 'جاري تحميل الإعدادات...' : 'Loading settings...' }}</span>
            </div>
            <div class="cms-form-body card" v-else>
              
              <!-- ── GENERAL SETTINGS ── -->
              <div v-if="activeCmsTab === 'general'" class="cms-section-pane">
                <h3 class="cms-section-title">⚙️ {{ lang === 'ar' ? 'الإعدادات العامة للموقع' : 'General Site Settings' }}</h3>
                <div class="form-group-grid">
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'اسم الموقع' : 'Site Name' }}</label>
                    <input type="text" v-model="cmsDraft.site_name" class="form-input" />
                  </div>
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'رمز الشعار (Emoji)' : 'Logo Emoji' }}</label>
                    <input type="text" v-model="cmsDraft.logo_emoji" class="form-input text-center" style="max-width: 80px;" />
                  </div>
                </div>
                <div class="form-group-grid mt-3">
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'شعار الموقع بالعربية' : 'Tagline (Arabic)' }}</label>
                    <input type="text" v-model="cmsDraft.tagline_ar" class="form-input" />
                  </div>
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'شعار الموقع بالإنجليزية' : 'Tagline (English)' }}</label>
                    <input type="text" v-model="cmsDraft.tagline_en" class="form-input" />
                  </div>
                </div>
              </div>

              <!-- ── HERO SECTION ── -->
              <div v-else-if="activeCmsTab === 'hero'" class="cms-section-pane">
                <h3 class="cms-section-title">🏠 {{ lang === 'ar' ? 'تعديل القسم الرئيسي (Hero)' : 'Hero Section Controls' }}</h3>
                
                <div class="form-group-grid">
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'شارة الرأس بالعربية' : 'Header Badge (AR)' }}</label>
                    <input type="text" v-model="cmsDraft.badge_ar" class="form-input" />
                  </div>
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'شارة الرأس بالإنجليزية' : 'Header Badge (EN)' }}</label>
                    <input type="text" v-model="cmsDraft.badge_en" class="form-input" />
                  </div>
                </div>

                <div class="form-group-grid mt-3">
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'العنوان الأساسي - سطر 1 (عربي)' : 'Main Title Line 1 (AR)' }}</label>
                    <input type="text" v-model="cmsDraft.title1_ar" class="form-input" />
                  </div>
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'العنوان الأساسي - سطر 1 (إنجليزي)' : 'Main Title Line 1 (EN)' }}</label>
                    <input type="text" v-model="cmsDraft.title1_en" class="form-input" />
                  </div>
                </div>

                <div class="form-group-grid mt-3">
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'العنوان الملون - سطر 2 (عربي)' : 'Glow Title Line 2 (AR)' }}</label>
                    <input type="text" v-model="cmsDraft.title2_ar" class="form-input" />
                  </div>
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'العنوان الملون - سطر 2 (إنجليزي)' : 'Glow Title Line 2 (EN)' }}</label>
                    <input type="text" v-model="cmsDraft.title2_en" class="form-input" />
                  </div>
                </div>

                <div class="form-group mt-3">
                  <label class="form-label">{{ lang === 'ar' ? 'العنوان الفرعي بالعربية' : 'Subtitle (AR)' }}</label>
                  <textarea v-model="cmsDraft.sub_ar" class="form-textarea" rows="3"></textarea>
                </div>
                <div class="form-group mt-3">
                  <label class="form-label">{{ lang === 'ar' ? 'العنوان الفرعي بالإنجليزية' : 'Subtitle (EN)' }}</label>
                  <textarea v-model="cmsDraft.sub_en" class="form-textarea" rows="3"></textarea>
                </div>

                <div class="form-group-grid mt-3">
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'زر الدعوة الرئيسي (عربي)' : 'Primary CTA Button (AR)' }}</label>
                    <input type="text" v-model="cmsDraft.cta_primary_ar" class="form-input" />
                  </div>
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'زر الدعوة الرئيسي (إنجليزي)' : 'Primary CTA Button (EN)' }}</label>
                    <input type="text" v-model="cmsDraft.cta_primary_en" class="form-input" />
                  </div>
                </div>

                <div class="form-group-grid mt-3">
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'زر الدعوة الثانوي (عربي)' : 'Secondary CTA Button (AR)' }}</label>
                    <input type="text" v-model="cmsDraft.cta_secondary_ar" class="form-input" />
                  </div>
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'زر الدعوة الثانوي (إنجليزي)' : 'Secondary CTA Button (EN)' }}</label>
                    <input type="text" v-model="cmsDraft.cta_secondary_en" class="form-input" />
                  </div>
                </div>

                <!-- Tags list -->
                <div class="form-group mt-3">
                  <label class="form-label">{{ lang === 'ar' ? 'سحابة التقنيات (مفصولة بفاصلة)' : 'Floating Tech Tags (Comma Separated)' }}</label>
                  <input type="text" :value="techTagsInput" @input="updateTechTags" class="form-input" placeholder="Laravel, Vue, AWS..." />
                </div>
              </div>

              <!-- ── STATS SECTION ── -->
              <div v-else-if="activeCmsTab === 'stats'" class="cms-section-pane">
                <div class="cms-header-row mb-4">
                  <h3 class="cms-section-title">📊 {{ lang === 'ar' ? 'إدارة الإحصائيات' : 'Manage Stats Cards' }}</h3>
                  <button class="btn btn-outline btn-sm" @click="addStatDraft">➕ {{ lang === 'ar' ? 'إضافة إحصائية' : 'Add Stat' }}</button>
                </div>
                
                <div class="cms-list-container">
                  <div v-for="(stat, idx) in cmsDraft" :key="idx" class="cms-card-item">
                    <div class="cms-item-header">
                      <span>⚡ {{ lang === 'ar' ? `كرت إحصائية #${idx + 1}` : `Stat Card #${idx + 1}` }}</span>
                      <button class="btn-icon-square delete" @click="removeStatDraft(idx)">🗑️</button>
                    </div>
                    <div class="form-group-grid">
                      <div class="form-group">
                        <label class="form-label">{{ lang === 'ar' ? 'القيمة (مثال: +50)' : 'Value (e.g. +50)' }}</label>
                        <input type="text" v-model="stat.value" class="form-input" />
                      </div>
                      <div class="form-group">
                        <label class="form-label">{{ lang === 'ar' ? 'العنوان بالعربية' : 'Label (Arabic)' }}</label>
                        <input type="text" v-model="stat.label_ar" class="form-input" />
                      </div>
                      <div class="form-group">
                        <label class="form-label">{{ lang === 'ar' ? 'العنوان بالإنجليزية' : 'Label (English)' }}</label>
                        <input type="text" v-model="stat.label_en" class="form-input" />
                      </div>
                    </div>
                  </div>
                  <div v-if="cmsDraft.length === 0" class="empty-state">
                    <p>⚠️ {{ lang === 'ar' ? 'لم يتم العثور على أي إحصائيات. اضغط على إضافة إحصائية للبدء.' : 'No stats found. Click Add Stat to start.' }}</p>
                  </div>
                </div>
              </div>

              <!-- ── SERVICES SECTION ── -->
              <div v-else-if="activeCmsTab === 'services'" class="cms-section-pane">
                <div class="cms-header-row mb-4">
                  <h3 class="cms-section-title">🔧 {{ lang === 'ar' ? 'إدارة كروت الخدمات' : 'Manage Services' }}</h3>
                  <button class="btn btn-outline btn-sm" @click="addServiceDraft">➕ {{ lang === 'ar' ? 'إضافة خدمة' : 'Add Service' }}</button>
                </div>

                <div class="cms-list-container">
                  <div v-for="(srv, idx) in cmsDraft" :key="idx" class="cms-card-item">
                    <div class="cms-item-header">
                      <span>🛠️ {{ lang === 'ar' ? `الخدمة #${idx + 1}` : `Service #${idx + 1}` }}</span>
                      <div class="cms-item-header-actions">
                        <button class="btn-icon-square" :disabled="idx === 0" @click="moveServiceDraft(idx, -1)">⬆️</button>
                        <button class="btn-icon-square" :disabled="idx === cmsDraft.length - 1" @click="moveServiceDraft(idx, 1)">⬇️</button>
                        <button class="btn-icon-square delete ml-2" @click="removeServiceDraft(idx)">🗑️</button>
                      </div>
                    </div>

                    <div class="form-group-grid">
                      <div class="form-group">
                        <label class="form-label">{{ lang === 'ar' ? 'الأيقونة (Emoji)' : 'Icon Emoji' }}</label>
                        <input type="text" v-model="srv.icon" class="form-input text-center" style="max-width: 80px;" />
                      </div>
                      <div class="form-group">
                        <label class="form-label">{{ lang === 'ar' ? 'شارة الخدمة (عربي)' : 'Badge Label (AR)' }}</label>
                        <input type="text" v-model="srv.badge_ar" class="form-input" />
                      </div>
                      <div class="form-group">
                        <label class="form-label">{{ lang === 'ar' ? 'شارة الخدمة (إنجليزي)' : 'Badge Label (EN)' }}</label>
                        <input type="text" v-model="srv.badge_en" class="form-input" />
                      </div>
                    </div>

                    <div class="form-group-grid mt-3">
                      <div class="form-group">
                        <label class="form-label">{{ lang === 'ar' ? 'اسم الخدمة (عربي)' : 'Service Title (AR)' }}</label>
                        <input type="text" v-model="srv.title_ar" class="form-input" />
                      </div>
                      <div class="form-group">
                        <label class="form-label">{{ lang === 'ar' ? 'اسم الخدمة (إنجليزي)' : 'Service Title (EN)' }}</label>
                        <input type="text" v-model="srv.title_en" class="form-input" />
                      </div>
                    </div>

                    <div class="form-group mt-3">
                      <label class="form-label">{{ lang === 'ar' ? 'الوصف بالعربية' : 'Description (AR)' }}</label>
                      <textarea v-model="srv.desc_ar" class="form-textarea" rows="2"></textarea>
                    </div>
                    <div class="form-group mt-3">
                      <label class="form-label">{{ lang === 'ar' ? 'الوصف بالإنجليزية' : 'Description (EN)' }}</label>
                      <textarea v-model="srv.desc_en" class="form-textarea" rows="2"></textarea>
                    </div>

                    <!-- Features -->
                    <div class="form-group-grid mt-3">
                      <div class="form-group">
                        <label class="form-label">{{ lang === 'ar' ? 'المزايا بالعربية (مفصولة بفاصلة)' : 'Features list AR (Comma Separated)' }}</label>
                        <input type="text" :value="srv.features_ar ? srv.features_ar.join(', ') : ''" 
                               @input="e => srv.features_ar = e.target.value.split(',').map(x => x.trim()).filter(Boolean)" 
                               class="form-input" />
                      </div>
                      <div class="form-group">
                        <label class="form-label">{{ lang === 'ar' ? 'المزايا بالإنجليزية (مفصولة بفاصلة)' : 'Features list EN (Comma Separated)' }}</label>
                        <input type="text" :value="srv.features_en ? srv.features_en.join(', ') : ''" 
                               @input="e => srv.features_en = e.target.value.split(',').map(x => x.trim()).filter(Boolean)" 
                               class="form-input" />
                      </div>
                    </div>

                    <!-- Tech stack in service -->
                    <div class="form-group mt-3">
                      <label class="form-label">{{ lang === 'ar' ? 'التقنيات المستخدمة (مفصولة بفاصلة)' : 'Technologies Stack (Comma Separated)' }}</label>
                      <input type="text" :value="srv.stack ? srv.stack.join(', ') : ''" 
                             @input="e => srv.stack = e.target.value.split(',').map(x => x.trim()).filter(Boolean)" 
                             class="form-input" placeholder="Vue, Laravel, SQL..." />
                    </div>
                  </div>
                  <div v-if="cmsDraft.length === 0" class="empty-state">
                    <p>⚠️ {{ lang === 'ar' ? 'لم يتم العثور على أي خدمات بعد. اضغط على إضافة خدمة للبدء.' : 'No services found. Click Add Service to start.' }}</p>
                  </div>
                </div>
              </div>

              <!-- ── ABOUT PAGE SECTION ── -->
              <div v-else-if="activeCmsTab === 'about'" class="cms-section-pane">
                <h3 class="cms-section-title">🏢 {{ lang === 'ar' ? 'تعديل صفحة من نحن' : 'About Us Page Editor' }}</h3>
                
                <!-- story title and label -->
                <div class="form-group-grid">
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'عنوان القصة الفرعي (عربي)' : 'Story Label (AR)' }}</label>
                    <input type="text" v-model="cmsDraft.story_label_ar" class="form-input" />
                  </div>
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'عنوان القصة الفرعي (إنجليزي)' : 'Story Label (EN)' }}</label>
                    <input type="text" v-model="cmsDraft.story_label_en" class="form-input" />
                  </div>
                </div>
                <div class="form-group-grid mt-3">
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'عنوان القصة الرئيسي (عربي)' : 'Story Title (AR)' }}</label>
                    <input type="text" v-model="cmsDraft.story_title_ar" class="form-input" />
                  </div>
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'عنوان القصة الرئيسي (إنجليزي)' : 'Story Title (EN)' }}</label>
                    <input type="text" v-model="cmsDraft.story_title_en" class="form-input" />
                  </div>
                </div>

                <div class="form-group mt-3">
                  <label class="form-label">{{ lang === 'ar' ? 'القصة - فقرة 1 (عربي)' : 'Story Paragraph 1 (AR)' }}</label>
                  <textarea v-model="cmsDraft.story_desc1_ar" class="form-textarea" rows="2"></textarea>
                </div>
                <div class="form-group mt-3">
                  <label class="form-label">{{ lang === 'ar' ? 'القصة - فقرة 1 (إنجليزي)' : 'Story Paragraph 1 (EN)' }}</label>
                  <textarea v-model="cmsDraft.story_desc1_en" class="form-textarea" rows="2"></textarea>
                </div>

                <div class="form-group mt-3">
                  <label class="form-label">{{ lang === 'ar' ? 'القصة - فقرة 2 (عربي)' : 'Story Paragraph 2 (AR)' }}</label>
                  <textarea v-model="cmsDraft.story_desc2_ar" class="form-textarea" rows="2"></textarea>
                </div>
                <div class="form-group mt-3">
                  <label class="form-label">{{ lang === 'ar' ? 'القصة - فقرة 2 (إنجليزي)' : 'Story Paragraph 2 (EN)' }}</label>
                  <textarea v-model="cmsDraft.story_desc2_en" class="form-textarea" rows="2"></textarea>
                </div>

                <hr class="cms-divider" />

                <!-- Mission & Vision -->
                <div class="form-group-grid">
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'عنوان الرسالة (عربي)' : 'Mission Title (AR)' }}</label>
                    <input type="text" v-model="cmsDraft.mission_title_ar" class="form-input" />
                  </div>
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'عنوان الرسالة (إنجليزي)' : 'Mission Title (EN)' }}</label>
                    <input type="text" v-model="cmsDraft.mission_title_en" class="form-input" />
                  </div>
                </div>
                <div class="form-group mt-3">
                  <label class="form-label">{{ lang === 'ar' ? 'وصف الرسالة بالعربية' : 'Mission Description (AR)' }}</label>
                  <textarea v-model="cmsDraft.mission_desc_ar" class="form-textarea" rows="2"></textarea>
                </div>
                <div class="form-group mt-3">
                  <label class="form-label">{{ lang === 'ar' ? 'وصف الرسالة بالإنجليزية' : 'Mission Description (EN)' }}</label>
                  <textarea v-model="cmsDraft.mission_desc_en" class="form-textarea" rows="2"></textarea>
                </div>

                <hr class="cms-divider" />

                <div class="form-group-grid">
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'عنوان الرؤية (عربي)' : 'Vision Title (AR)' }}</label>
                    <input type="text" v-model="cmsDraft.vision_title_ar" class="form-input" />
                  </div>
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'عنوان الرؤية (إنجليزي)' : 'Vision Title (EN)' }}</label>
                    <input type="text" v-model="cmsDraft.vision_title_en" class="form-input" />
                  </div>
                </div>
                <div class="form-group mt-3">
                  <label class="form-label">{{ lang === 'ar' ? 'وصف الرؤية بالعربية' : 'Vision Description (AR)' }}</label>
                  <textarea v-model="cmsDraft.vision_desc_ar" class="form-textarea" rows="2"></textarea>
                </div>
                <div class="form-group mt-3">
                  <label class="form-label">{{ lang === 'ar' ? 'وصف الرؤية بالإنجليزية' : 'Vision Description (EN)' }}</label>
                  <textarea v-model="cmsDraft.vision_desc_en" class="form-textarea" rows="2"></textarea>
                </div>

                <hr class="cms-divider" />

                <!-- Values Nested List -->
                <div class="cms-header-row mb-3">
                  <h4 class="form-label-section">💡 {{ lang === 'ar' ? 'إدارة قيم الشركة' : 'Company Values Cards' }}</h4>
                  <button class="btn btn-outline btn-sm" @click="addValueDraft">➕</button>
                </div>
                <div class="cms-sublist">
                  <div v-for="(val, vIdx) in cmsDraft.values" :key="vIdx" class="cms-subcard-item">
                    <div class="cms-item-header">
                      <span>{{ lang === 'ar' ? `قيمة #${vIdx + 1}` : `Value #${vIdx + 1}` }}</span>
                      <button class="btn-icon-square delete" @click="removeValueDraft(vIdx)">🗑️</button>
                    </div>
                    <div class="form-group-grid">
                      <div class="form-group">
                        <label class="form-label">Icon</label>
                        <input type="text" v-model="val.icon" class="form-input text-center" style="max-width: 60px;" />
                      </div>
                      <div class="form-group">
                        <label class="form-label">Title (AR)</label>
                        <input type="text" v-model="val.title_ar" class="form-input" />
                      </div>
                      <div class="form-group">
                        <label class="form-label">Title (EN)</label>
                        <input type="text" v-model="val.title_en" class="form-input" />
                      </div>
                    </div>
                    <div class="form-group mt-2">
                      <label class="form-label">Description (AR)</label>
                      <input type="text" v-model="val.desc_ar" class="form-input" />
                    </div>
                    <div class="form-group mt-2">
                      <label class="form-label">Description (EN)</label>
                      <input type="text" v-model="val.desc_en" class="form-input" />
                    </div>
                  </div>
                </div>

                <hr class="cms-divider" />

                <!-- Achievements Nested List -->
                <div class="cms-header-row mb-3">
                  <h4 class="form-label-section">🏆 {{ lang === 'ar' ? 'الإنجازات والجوائز' : 'Achievements Cards' }}</h4>
                  <button class="btn btn-outline btn-sm" @click="addAchievementDraft">➕</button>
                </div>
                <div class="cms-sublist">
                  <div v-for="(ach, aIdx) in cmsDraft.achievements" :key="aIdx" class="cms-subcard-item">
                    <div class="cms-item-header">
                      <span>{{ lang === 'ar' ? `إنجاز #${aIdx + 1}` : `Achievement #${aIdx + 1}` }}</span>
                      <button class="btn-icon-square delete" @click="removeAchievementDraft(aIdx)">🗑️</button>
                    </div>
                    <div class="form-group-grid">
                      <div class="form-group">
                        <label class="form-label">Icon</label>
                        <input type="text" v-model="ach.icon" class="form-input text-center" style="max-width: 60px;" />
                      </div>
                      <div class="form-group">
                        <label class="form-label">Value (e.g. +50)</label>
                        <input type="text" v-model="ach.value" class="form-input" />
                      </div>
                      <div class="form-group">
                        <label class="form-label">Label (AR)</label>
                        <input type="text" v-model="ach.label_ar" class="form-input" />
                      </div>
                      <div class="form-group">
                        <label class="form-label">Label (EN)</label>
                        <input type="text" v-model="ach.label_en" class="form-input" />
                      </div>
                    </div>
                  </div>
                </div>

                <hr class="cms-divider" />

                <!-- Tech List -->
                <div class="cms-header-row mb-3">
                  <h4 class="form-label-section">🐳 {{ lang === 'ar' ? 'التقنيات المستخدمة في صفحة من نحن' : 'Tech Stack Logos' }}</h4>
                  <button class="btn btn-outline btn-sm" @click="addTechLogoDraft">➕</button>
                </div>
                <div class="cms-sublist tech-logos-editor">
                  <div v-for="(tech, tIdx) in cmsDraft.techs" :key="tIdx" class="cms-logo-edit-row">
                    <input type="text" v-model="tech.icon" class="form-input text-center" style="max-width: 60px;" placeholder="Icon" />
                    <input type="text" v-model="tech.name" class="form-input" placeholder="Technology Name" />
                    <button class="btn-icon-square delete" @click="removeTechLogoDraft(tIdx)">🗑️</button>
                  </div>
                </div>

              </div>

              <!-- ── CONTACT INFORMATION ── -->
              <div v-else-if="activeCmsTab === 'contact'" class="cms-section-pane">
                <h3 class="cms-section-title">📞 {{ lang === 'ar' ? 'تعديل قنوات التواصل' : 'Contact Channels Settings' }}</h3>
                <div class="form-group-grid">
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}</label>
                    <input type="email" v-model="cmsDraft.email" class="form-input" />
                  </div>
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'الهاتف' : 'Phone Number' }}</label>
                    <input type="text" v-model="cmsDraft.phone" class="form-input" />
                  </div>
                </div>
                <div class="form-group-grid mt-3">
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'رقم الواتساب (بالصيغة الدولية وبدون أصفار)' : 'WhatsApp Number (Intl format, no leading zeros)' }}</label>
                    <input type="text" v-model="cmsDraft.whatsapp" class="form-input" placeholder="966500000000" />
                  </div>
                </div>
                <div class="form-group-grid mt-3">
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'العنوان بالعربية' : 'Address (Arabic)' }}</label>
                    <input type="text" v-model="cmsDraft.address_ar" class="form-input" />
                  </div>
                  <div class="form-group">
                    <label class="form-label">{{ lang === 'ar' ? 'العنوان بالإنجليزية' : 'Address (English)' }}</label>
                    <input type="text" v-model="cmsDraft.address_en" class="form-input" />
                  </div>
                </div>
              </div>

              <!-- ── FOOTER & SOCIALS ── -->
              <div v-else-if="activeCmsTab === 'footer'" class="cms-section-pane">
                <h3 class="cms-section-title">🔗 {{ lang === 'ar' ? 'إعدادات الفوتر والروابط الاجتماعية' : 'Footer & Social Links Editor' }}</h3>
                
                <div class="form-group">
                  <label class="form-label">{{ lang === 'ar' ? 'وصف الفوتر بالعربية' : 'Footer Description (AR)' }}</label>
                  <textarea v-model="cmsDraft.desc_ar" class="form-textarea" rows="2"></textarea>
                </div>
                <div class="form-group mt-3">
                  <label class="form-label">{{ lang === 'ar' ? 'وصف الفوتر بالإنجليزية' : 'Footer Description (EN)' }}</label>
                  <textarea v-model="cmsDraft.desc_en" class="form-textarea" rows="2"></textarea>
                </div>

                <div class="form-group-grid mt-3">
                  <div class="form-group">
                    <label class="form-label">Twitter / X URL</label>
                    <input type="text" v-model="cmsDraft.twitter" class="form-input" />
                  </div>
                  <div class="form-group">
                    <label class="form-label">LinkedIn URL</label>
                    <input type="text" v-model="cmsDraft.linkedin" class="form-input" />
                  </div>
                </div>

                <div class="form-group-grid mt-3">
                  <div class="form-group">
                    <label class="form-label">Instagram URL</label>
                    <input type="text" v-model="cmsDraft.instagram" class="form-input" />
                  </div>
                  <div class="form-group">
                    <label class="form-label">GitHub URL</label>
                    <input type="text" v-model="cmsDraft.github" class="form-input" />
                  </div>
                </div>
                <div class="form-group-grid mt-3">
                  <div class="form-group">
                    <label class="form-label">Facebook URL</label>
                    <input type="text" v-model="cmsDraft.facebook" class="form-input" />
                  </div>
                  <div class="form-group">
                    <label class="form-label">TikTok URL</label>
                    <input type="text" v-model="cmsDraft.tiktok" class="form-input" />
                  </div>
                </div>

                <div class="form-group-grid mt-3">
                  <div class="form-group">
                    <label class="form-label">YouTube URL</label>
                    <input type="text" v-model="cmsDraft.youtube" class="form-input" />
                  </div>
                </div>
              </div>

              <!-- Form footer save block -->
              <div class="cms-save-footer mt-4">
                <button 
                  class="btn btn-primary cms-save-btn" 
                  :disabled="isSavingCms" 
                  @click="saveCmsSetting(activeCmsTab)"
                >
                  <span v-if="isSavingCms" class="spinner"></span>
                  <span v-else>💾</span>
                  {{ isSavingCms ? (lang === 'ar' ? 'جاري الحفظ...' : 'Saving...') : (lang === 'ar' ? 'حفظ التعديلات' : 'Save Changes') }}
                </button>
              </div>

            </div>
          </div>
        </div>

      </div>

    </main>

    <!-- ══════════ DETAILS MODAL ══════════ -->
    <transition name="fade">
      <div v-if="showModal" class="modal-overlay" @click.self="showModal = false">
        <div class="modal-card">
          <div class="modal-header">
            <h3 class="modal-title">{{ t.messageDetails }}</h3>
            <button class="modal-close-btn" @click="showModal = false">×</button>
          </div>
          
          <div class="modal-body">
            <div class="info-grid">
              <div class="info-row">
                <span class="info-label">{{ t.name }}:</span>
                <span class="info-val">{{ activeInquiry.name }}</span>
              </div>
              <div class="info-row">
                <span class="info-label">{{ t.email }}:</span>
                <a :href="`mailto:${activeInquiry.email}`" class="info-val email-link">{{ activeInquiry.email }}</a>
              </div>
              <div class="info-row">
                <span class="info-label">{{ t.subject }}:</span>
                <span class="info-val">{{ activeInquiry.subject || t.noSubject }}</span>
              </div>
              <div class="info-row">
                <span class="info-label">{{ t.date }}:</span>
                <span class="info-val">{{ formatDate(activeInquiry.created_at) }}</span>
              </div>
              <div class="info-row">
                <span class="info-label">{{ t.status }}:</span>
                <span class="badge-status" :class="activeInquiry.status">{{ t[activeInquiry.status] }}</span>
              </div>
            </div>

            <div class="msg-box">
              <h4 class="msg-box-title">{{ t.message }}:</h4>
              <p class="msg-box-text">{{ activeInquiry.message }}</p>
            </div>
          </div>

          <div class="modal-footer">
            <div class="modal-status-actions">
              <button 
                v-if="activeInquiry.status !== 'read'"
                class="btn btn-outline btn-sm"
                @click="updateStatusInModal(activeInquiry.id, 'read')"
              >
                👀 {{ t.markAsRead }}
              </button>
              <button 
                v-if="activeInquiry.status !== 'replied'"
                class="btn btn-primary btn-sm"
                @click="updateStatusInModal(activeInquiry.id, 'replied')"
              >
                ✅ {{ t.markAsReplied }}
              </button>
            </div>
            <button class="btn btn-outline btn-sm delete-modal-btn" @click="deleteInquiryInModal(activeInquiry.id)">
              🗑️ {{ t.delete }}
            </button>
          </div>
        </div>
      </div>
    </transition>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, inject, reactive } from 'vue'

const props = defineProps({
  lang: {
    type: String,
    default: 'ar'
  }
})

const emit = defineEmits(['logout'])

const settings = inject('settings')
const refreshSettings = inject('refreshSettings')

// Dashboard state
const currentTab = ref('overview')
const isGlobalLoading = ref(true)
const adminUser = ref({})
const token = ref('')

// CMS State
const activeCmsTab = ref('general')
// cmsDraft holds the current tab data. For array-based tabs (stats, services)
// the value is the array directly. For object tabs it's the settings object.
const cmsDraft = ref({})
const cmsReady = ref(false)
const isSavingCms = ref(false)
const cmsAlert = reactive({ show: false, type: 'success', message: '' })

const cmsSubTabs = [
  { key: 'general', ar: '⚙️ الإعدادات العامة', en: '⚙️ General Settings' },
  { key: 'hero', ar: '🏠 القسم الرئيسي', en: '🏠 Hero Section' },
  { key: 'stats', ar: '📊 الإحصائيات', en: '📊 Stats Cards' },
  { key: 'services', ar: '🔧 الخدمات', en: '🔧 Services' },
  { key: 'about', ar: '🏢 من نحن', en: '🏢 About Us' },
  { key: 'contact', ar: '📞 معلومات التواصل', en: '📞 Contact Info' },
  { key: 'footer', ar: '🔗 الفوتر والروابط', en: '🔗 Footer & Socials' }
]

function triggerCmsAlert(type, message) {
  cmsAlert.type = type
  cmsAlert.message = message
  cmsAlert.show = true
  setTimeout(() => {
    cmsAlert.show = false
  }, 4000)
}

// Data
const stats = ref({
  counters: { total: 0, pending: 0, read: 0, replied: 0 },
  chart: []
})
const inquiries = ref([])
const recentInquiries = ref([])

// Filters/pagination
const selectedFilter = ref('all')
const searchQuery = ref('')
const currentPage = ref(1)
const pagination = ref({
  current_page: 1,
  last_page: 1,
  total: 0
})

// Modal
const showModal = ref(false)
const activeInquiry = ref({})

// Debounce handle for search
let searchDebounceTimeout = null

// Check credentials and initialize
onMounted(() => {
  const savedToken = localStorage.getItem('admin_token')
  const savedUser = localStorage.getItem('admin_user')

  if (!savedToken) {
    handleUnauthorized()
    return
  }

  token.value = savedToken
  adminUser.value = savedUser ? JSON.parse(savedUser) : { name: 'Admin' }
  
  initializeDashboard()
})

function handleUnauthorized() {
  localStorage.removeItem('admin_token')
  localStorage.removeItem('admin_user')
  emit('logout')
}

function toggleAppLang() {
  const currentLang = props.lang === 'ar' ? 'en' : 'ar'
  document.documentElement.lang = currentLang
  // We manipulate Document element since App.vue listens to changes. 
  // Let's toggle via custom event or let App.vue trigger it if we call standard toggleLang.
  // Actually, we can click the app language toggle button programmatically, or dispatch a toggle language trigger.
  // The simplest is to click the language button in the global scope if it exists, or just let App.vue language toggle be called.
  // But wait, the language is passed down as a prop `lang`. How does the child modify the parent's lang?
  // In App.vue, toggleLang is defined. We can dispatch a click on the lang-btn, or we can just emit an event.
  // But wait, since we don't have event listeners in App.vue for lang toggle, can we find the lang-btn and trigger click?
  // Yes, in vanilla JS:
  const btn = document.querySelector('.lang-btn')
  if (btn) {
    btn.click()
  }
}

async function initializeDashboard() {
  isGlobalLoading.value = true
  try {
    await Promise.all([
      fetchStats(),
      fetchInquiries()
    ])
  } catch (error) {
    console.error('Initialization error:', error)
  } finally {
    isGlobalLoading.value = false
  }
}

// Fetch stats overview
async function fetchStats() {
  try {
    const res = await fetch('/api/admin/stats', {
      headers: {
        'Authorization': `Bearer ${token.value}`,
        'Accept': 'application/json'
      }
    })
    
    if (res.status === 401) {
      handleUnauthorized()
      return
    }

    const json = await res.json()
    if (res.ok && json.success) {
      stats.value = json.data
    }
  } catch (e) {
    console.error('Fetch stats failed:', e)
  }
}

// Fetch inquiries list
async function fetchInquiries(page = 1) {
  try {
    let url = `/api/admin/inquiries?page=${page}`
    
    if (selectedFilter.value !== 'all') {
      url += `&status=${selectedFilter.value}`
    }
    
    if (searchQuery.value.trim() !== '') {
      url += `&q=${encodeURIComponent(searchQuery.value)}`
    }

    const res = await fetch(url, {
      headers: {
        'Authorization': `Bearer ${token.value}`,
        'Accept': 'application/json'
      }
    })

    if (res.status === 401) {
      handleUnauthorized()
      return
    }

    const json = await res.json()
    if (res.ok && json.success) {
      inquiries.value = json.data.data
      pagination.value = {
        current_page: json.data.current_page,
        last_page: json.data.last_page,
        total: json.data.total
      }
      
      // Update recent inquiries for the overview
      if (page === 1 && selectedFilter.value === 'all' && searchQuery.value === '') {
        recentInquiries.value = inquiries.value.slice(0, 5)
      }
    }
  } catch (e) {
    console.error('Fetch inquiries failed:', e)
  }
}

// API status updating
async function updateStatus(id, newStatus) {
  try {
    const res = await fetch(`/api/admin/inquiries/${id}`, {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${token.value}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ status: newStatus })
    })

    if (res.status === 401) {
      handleUnauthorized()
      return
    }

    const json = await res.json()
    if (res.ok && json.success) {
      // Find the inquiry in our reactive list and update it
      const inq = inquiries.value.find(i => i.id === id)
      if (inq) inq.status = newStatus
      
      const recentInq = recentInquiries.value.find(i => i.id === id)
      if (recentInq) recentInq.status = newStatus

      // Refresh overview counts
      fetchStats()
    }
  } catch (e) {
    console.error('Update status failed:', e)
  }
}

// API inquiry delete
async function deleteInquiry(id) {
  try {
    const res = await fetch(`/api/admin/inquiries/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token.value}`,
        'Accept': 'application/json'
      }
    })

    if (res.status === 401) {
      handleUnauthorized()
      return
    }

    const json = await res.json()
    if (res.ok && json.success) {
      // Remove from list
      inquiries.value = inquiries.value.filter(i => i.id !== id)
      recentInquiries.value = recentInquiries.value.filter(i => i.id !== id)
      
      // Refresh statistics and list
      fetchStats()
      if (inquiries.value.length === 0 && pagination.value.current_page > 1) {
        changePage(pagination.value.current_page - 1)
      } else {
        fetchInquiries(pagination.value.current_page)
      }
    }
  } catch (e) {
    console.error('Delete inquiry failed:', e)
  }
}

// Modal actions
function openInquiryDetails(inq) {
  activeInquiry.value = { ...inq }
  showModal.value = true
  
  // Mark as read automatically if pending
  if (inq.status === 'pending') {
    updateStatus(inq.id, 'read')
    activeInquiry.value.status = 'read'
  }
}

async function updateStatusInModal(id, newStatus) {
  await updateStatus(id, newStatus)
  activeInquiry.value.status = newStatus
}

async function deleteInquiryInModal(id) {
  const confirmMsg = props.lang === 'ar' 
    ? 'هل أنت متأكد من حذف هذه الرسالة نهائياً؟' 
    : 'Are you sure you want to permanently delete this message?'
  
  if (confirm(confirmMsg)) {
    showModal.value = false
    await deleteInquiry(id)
  }
}

function confirmDeleteInquiry(id) {
  const confirmMsg = props.lang === 'ar' 
    ? 'هل أنت متأكد من حذف هذه الرسالة نهائياً؟' 
    : 'Are you sure you want to permanently delete this message?'
  
  if (confirm(confirmMsg)) {
    deleteInquiry(id)
  }
}

// Search and filter triggers
function debounceSearch() {
  clearTimeout(searchDebounceTimeout)
  searchDebounceTimeout = setTimeout(() => {
    currentPage.value = 1
    fetchInquiries(1)
  }, 400)
}

function clearSearch() {
  searchQuery.value = ''
  currentPage.value = 1
  fetchInquiries(1)
}

function setFilter(filter) {
  selectedFilter.value = filter
  currentPage.value = 1
  fetchInquiries(1)
}

function changePage(page) {
  if (page < 1 || page > pagination.value.last_page) return
  currentPage.value = page
  fetchInquiries(page)
}

// Logout handler
async function handleLogoutClick() {
  const confirmMsg = props.lang === 'ar' ? 'هل تود تسجيل الخروج فعلاً؟' : 'Are you sure you want to logout?'
  if (!confirm(confirmMsg)) return

  try {
    await fetch('/api/admin/logout', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token.value}`,
        'Accept': 'application/json'
      }
    })
  } catch (e) {
    console.error('Logout API call failed:', e)
  } finally {
    handleUnauthorized()
  }
}

// CMS Helper functions
async function selectCmsTabMenu() {
  currentTab.value = 'cms'
  // Ensure settings are loaded before opening the CMS editor
  const hasAnyData = settings.value && Object.keys(settings.value.general || {}).length > 0
  if (!hasAnyData && refreshSettings) {
    await refreshSettings()
  }
  selectCmsTab('general')
}

// Map CMS sub-tab keys to actual settings keys (e.g. 'contact' → 'contact_info')
const cmsKeyMap = {
  contact: 'contact_info'
}

function selectCmsTab(key) {
  activeCmsTab.value = key
  const settingsKey = cmsKeyMap[key] || key
  const src = settings.value && settings.value[settingsKey]

  if (src && (Array.isArray(src) ? src.length > 0 : Object.keys(src).length > 0)) {
    cmsDraft.value = JSON.parse(JSON.stringify(src))
    cmsReady.value = true
  } else {
    // Fetch fresh from server if local cache is empty
    cmsReady.value = false
    if (refreshSettings) {
      refreshSettings().then(() => {
        const fresh = settings.value && settings.value[settingsKey]
        cmsDraft.value = fresh ? JSON.parse(JSON.stringify(fresh)) : {}
        cmsReady.value = true
      })
    } else {
      cmsDraft.value = {}
      cmsReady.value = true
    }
  }
}

const techTagsInput = computed(() => {
  if (activeCmsTab.value === 'hero' && cmsDraft.value && cmsDraft.value.tech_tags) {
    return cmsDraft.value.tech_tags.join(', ')
  }
  return ''
})

function updateTechTags(e) {
  if (cmsDraft.value) {
    cmsDraft.value.tech_tags = e.target.value.split(',').map(tag => tag.trim()).filter(Boolean)
  }
}

// Stats Helpers
function addStatDraft() {
  if (!Array.isArray(cmsDraft.value)) {
    cmsDraft.value = []
  }
  cmsDraft.value.push({ value: '+10', label_ar: 'إحصائية جديدة', label_en: 'New Stat' })
}

function removeStatDraft(idx) {
  if (Array.isArray(cmsDraft.value)) {
    cmsDraft.value.splice(idx, 1)
  }
}

// Services Helpers
function addServiceDraft() {
  if (!Array.isArray(cmsDraft.value)) {
    cmsDraft.value = []
  }
  cmsDraft.value.push({
    icon: '⚡',
    badge_ar: 'جديد',
    badge_en: 'New',
    title_ar: 'خدمة جديدة',
    title_en: 'New Service',
    desc_ar: 'تفاصيل الخدمة الجديدة هنا.',
    desc_en: 'Details of the new service here.',
    features_ar: ['ميزة 1', 'ميزة 2'],
    features_en: ['Feature 1', 'Feature 2'],
    stack: ['Vue', 'Laravel']
  })
}

function removeServiceDraft(idx) {
  if (Array.isArray(cmsDraft.value)) {
    cmsDraft.value.splice(idx, 1)
  }
}

function moveServiceDraft(idx, direction) {
  if (!Array.isArray(cmsDraft.value)) return
  const targetIdx = idx + direction
  if (targetIdx < 0 || targetIdx >= cmsDraft.value.length) return
  const temp = cmsDraft.value[idx]
  cmsDraft.value[idx] = cmsDraft.value[targetIdx]
  cmsDraft.value[targetIdx] = temp
}

// About Nested Lists Helpers
function addValueDraft() {
  if (!cmsDraft.value.values) cmsDraft.value.values = []
  cmsDraft.value.values.push({ icon: '💡', title_ar: 'قيمة جديدة', title_en: 'New Value', desc_ar: 'وصف القيمة الجديدة.', desc_en: 'Description of the new value.' })
}

function removeValueDraft(idx) {
  if (cmsDraft.value.values) {
    cmsDraft.value.values.splice(idx, 1)
  }
}

function addAchievementDraft() {
  if (!cmsDraft.value.achievements) cmsDraft.value.achievements = []
  cmsDraft.value.achievements.push({ icon: '🏆', value: '+10', label_ar: 'إنجاز جديد', label_en: 'New Achievement' })
}

function removeAchievementDraft(idx) {
  if (cmsDraft.value.achievements) {
    cmsDraft.value.achievements.splice(idx, 1)
  }
}

function addTechLogoDraft() {
  if (!cmsDraft.value.techs) cmsDraft.value.techs = []
  cmsDraft.value.techs.push({ icon: '🐳', name: 'Docker' })
}

function removeTechLogoDraft(idx) {
  if (cmsDraft.value.techs) {
    cmsDraft.value.techs.splice(idx, 1)
  }
}

// Save CMS draft to API
async function saveCmsSetting(key) {
  if (!cmsDraft.value || Object.keys(cmsDraft.value).length === 0) {
    triggerCmsAlert('error', props.lang === 'ar' ? 'لا توجد بيانات للحفظ. تأكد من تحميل الإعدادات أولاً.' : 'No data to save. Please reload the settings first.')
    return
  }
  isSavingCms.value = true
  // Use the actual API key (contact_info not contact)
  const apiKey = cmsKeyMap[key] || key
  try {
    const res = await fetch(`/api/admin/site-settings/${apiKey}`, {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${token.value}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ value: cmsDraft.value })
    })

    if (res.status === 401) {
      handleUnauthorized()
      return
    }

    const json = await res.json()
    if (res.ok && json.success) {
      if (refreshSettings) {
        await refreshSettings()
      }
      triggerCmsAlert('success', props.lang === 'ar' ? 'تم حفظ التعديلات بنجاح ونشرها بالموقع! ✨' : 'Changes saved successfully and deployed to site! ✨')
    } else {
      triggerCmsAlert('error', json.message || (props.lang === 'ar' ? 'فشل حفظ التعديلات.' : 'Failed to save changes.'))
    }
  } catch (err) {
    console.error('Save setting error:', err)
    triggerCmsAlert('error', props.lang === 'ar' ? 'حدث خطأ في الاتصال بالخادم.' : 'Server connection error.')
  } finally {
    isSavingCms.value = false
  }
}

// Chart Helpers
const maxChartValue = computed(() => {
  if (!stats.value.chart || stats.value.chart.length === 0) return 10
  const counts = stats.value.chart.map(c => c.count)
  const max = Math.max(...counts)
  return max > 0 ? max : 10
})

function getBarHeightPercent(count) {
  if (count === 0) return '3%' // small dot for zero state
  return `${(count / maxChartValue.value) * 100}%`
}

function translateDay(day) {
  const daysMap = {
    'Mon': 'الإثنين',
    'Tue': 'الثلاثاء',
    'Wed': 'الأربعاء',
    'Thu': 'الخميس',
    'Fri': 'الجمعة',
    'Sat': 'السبت',
    'Sun': 'الأحد'
  }
  return daysMap[day] || day
}

// Date formatter
function formatDate(dateString) {
  if (!dateString) return ''
  const date = new Date(dateString)
  
  if (props.lang === 'ar') {
    return date.toLocaleDateString('ar-EG', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    })
  } else {
    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    })
  }
}

// Translations
const translations = {
  ar: {
    administrator: 'مدير النظام',
    overview: 'الإحصائيات',
    inquiries: 'الرسائل',
    logout: 'تسجيل الخروج',
    overviewDashboard: 'لوحة التحكم العامة',
    welcomeBack: 'مرحباً بك مجدداً',
    totalInquiries: 'إجمالي الرسائل',
    pending: 'قيد الانتظار',
    read: 'مقروءة',
    replied: 'تم الرد',
    submissionHistory: 'مخطط الاستلام',
    last7DaysSubmissions: 'الرسائل المستلمة خلال آخر 7 أيام',
    recentInquiries: 'آخر الرسائل المستلمة',
    latestInquiriesReceived: 'أحدث 5 رسائل واردة إلى النظام',
    noSubject: '(بدون عنوان)',
    noInquiriesFound: 'لا توجد رسائل حالياً.',
    viewAllInquiries: 'عرض كافة الرسائل',
    manageInquiries: 'إدارة الرسائل الواردة',
    manageInquiriesDesc: 'استعرض، ابحث، رتب، واحذف الرسائل والطلبات الواردة من عملائك.',
    searchPlaceholder: 'ابحث بالاسم، البريد الإلكتروني، أو الموضوع...',
    all: 'الكل',
    client: 'العميل',
    subject: 'الموضوع',
    date: 'التاريخ',
    status: 'الحالة',
    actions: 'الإجراءات',
    view: 'عرض التفاصيل',
    markAsRead: 'تحديد كمقروء',
    markAsReplied: 'تحديد كتم الرد',
    delete: 'حذف',
    prev: 'السابق',
    next: 'التالي',
    page: 'صفحة',
    messageDetails: 'تفاصيل الرسالة',
    name: 'الاسم',
    email: 'البريد الإلكتروني',
    message: 'الرسالة',
    loadingData: 'جاري تحميل البيانات من الخادم...',
    messages: 'رسائل',
    cmsSettings: 'إدارة المحتوى',
    cmsSettingsDesc: 'تعديل وتحديث محتوى الويب سايت بالكامل بكل سهولة وسرعة.',
    saveChanges: 'حفظ التعديلات',
  },
  en: {
    administrator: 'System Administrator',
    overview: 'Overview',
    inquiries: 'Inquiries',
    logout: 'Logout',
    overviewDashboard: 'Dashboard Overview',
    welcomeBack: 'Welcome back',
    totalInquiries: 'Total Inquiries',
    pending: 'Pending',
    read: 'Read',
    replied: 'Replied',
    submissionHistory: 'Submissions History',
    last7DaysSubmissions: 'Inquiry submissions count over the last 7 days',
    recentInquiries: 'Recent Inquiries',
    latestInquiriesReceived: 'The latest 5 inquiry submissions received',
    noSubject: '(No Subject)',
    noInquiriesFound: 'No inquiries found.',
    viewAllInquiries: 'View All Inquiries',
    manageInquiries: 'Manage Inquiries',
    manageInquiriesDesc: 'Review, search, filter, and delete customer inquiry submissions.',
    searchPlaceholder: 'Search by client name, email, or subject...',
    all: 'All',
    client: 'Client',
    subject: 'Subject',
    date: 'Date',
    status: 'Status',
    actions: 'Actions',
    view: 'View Details',
    markAsRead: 'Mark Read',
    markAsReplied: 'Mark Replied',
    delete: 'Delete',
    prev: 'Previous',
    next: 'Next',
    page: 'Page',
    messageDetails: 'Message Details',
    name: 'Name',
    email: 'Email',
    message: 'Message',
    loadingData: 'Loading data from server...',
    messages: 'messages',
    cmsSettings: 'CMS Content',
    cmsSettingsDesc: 'Modify and update the entire website content easily.',
    saveChanges: 'Save Changes',
  }
}

const t = computed(() => translations[props.lang])
</script>

<style scoped>
.dashboard-wrapper {
  display: flex;
  min-height: 100vh;
  width: 100%;
  background: var(--bg-primary);
  color: var(--text-primary);
}

/* ── SIDEBAR STYLING ── */
.dashboard-sidebar {
  width: 280px;
  background: var(--bg-secondary);
  border-right: 1px solid var(--border-color);
  display: flex;
  flex-direction: column;
  padding: 2rem 1.5rem;
  flex-shrink: 0;
  position: sticky;
  top: 0;
  height: 100vh;
  z-index: 100;
}

[dir="rtl"] .dashboard-sidebar {
  border-right: none;
  border-left: 1px solid var(--border-color);
}

.sidebar-header {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 2.5rem;
}

.sidebar-user {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 1rem;
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--radius-sm);
  margin-bottom: 2rem;
}

.user-avatar {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  background: var(--accent-dim);
  border: 1px solid var(--border-glow);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--accent-bright);
  font-weight: 700;
  font-size: 0.95rem;
}

.user-info {
  display: flex;
  flex-direction: column;
}

.user-name {
  font-weight: 700;
  font-size: 0.9rem;
}

.user-role {
  font-size: 0.75rem;
  color: var(--text-muted);
}

.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  flex: 1;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  border-radius: 8px;
  color: var(--text-secondary);
  background: transparent;
  border: none;
  font-family: inherit;
  font-size: 0.95rem;
  font-weight: 500;
  cursor: pointer;
  text-align: left;
  transition: var(--transition);
  width: 100%;
}

[dir="rtl"] .nav-item {
  text-align: right;
}

.nav-item:hover, 
.nav-item.active {
  color: var(--accent-bright);
  background: var(--accent-dim);
}

.nav-icon {
  font-size: 1.1rem;
}

.sidebar-footer {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-top: auto;
  border-top: 1px solid var(--border-color);
  padding-top: 1.5rem;
}

.control-btn {
  background: transparent;
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-secondary);
  padding: 8px;
  cursor: pointer;
  font-family: inherit;
  font-size: 0.85rem;
  transition: var(--transition);
}

.control-btn:hover {
  border-color: var(--accent);
  color: var(--accent-bright);
  background: var(--accent-dim);
}

.logout-btn {
  color: #ff5555;
}

.logout-btn:hover {
  background: rgba(255, 60, 60, 0.1);
  color: #ff5555;
}

/* ── BODY STYLING ── */
.dashboard-body {
  flex: 1;
  padding: 2.5rem;
  min-height: 100vh;
  overflow-y: auto;
  background: var(--bg-primary);
}

.page-title-wrap {
  margin-bottom: 2.5rem;
}

.dash-page-title {
  font-size: 1.8rem;
  font-weight: 800;
  margin-bottom: 0.5rem;
  background: linear-gradient(135deg, var(--text-primary) 60%, var(--accent));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.dash-page-desc {
  color: var(--text-secondary);
  font-size: 0.95rem;
}

/* Stats Cards */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
  margin-bottom: 2.5rem;
}

.stat-card {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--radius);
  padding: 1.5rem;
  position: relative;
  overflow: hidden;
  transition: var(--transition);
}

.stat-card:hover {
  border-color: var(--border-glow);
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.stat-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.stat-label {
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--text-secondary);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.stat-icon-bg {
  font-size: 1.25rem;
  opacity: 0.8;
}

.stat-num {
  font-size: 2.2rem;
  font-weight: 800;
  line-height: 1;
}

.glow-orange { color: #ff9900; text-shadow: 0 0 15px rgba(255, 153, 0, 0.2); }
.glow-blue { color: var(--accent); text-shadow: 0 0 15px var(--accent-glow); }
.glow-green { color: #00cc66; text-shadow: 0 0 15px rgba(0, 204, 102, 0.2); }

/* Overview Layout */
.overview-grid {
  display: grid;
  grid-template-columns: 1.5fr 1fr;
  gap: 2rem;
  align-items: start;
}

.chart-card, .activity-card {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--radius);
  padding: 2rem;
}

.card-title {
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 0.25rem;
}

.card-subtitle {
  font-size: 0.85rem;
  color: var(--text-muted);
  margin-bottom: 2rem;
}

/* Custom CSS Chart styling */
.chart-wrapper {
  display: flex;
  gap: 1.5rem;
  height: 240px;
  position: relative;
  padding-bottom: 1rem;
}

.chart-y-axis {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  color: var(--text-muted);
  font-size: 0.75rem;
  font-weight: 600;
  width: 25px;
  text-align: right;
  height: calc(100% - 25px);
  padding-right: 5px;
}

[dir="rtl"] .chart-y-axis {
  text-align: left;
  padding-right: 0;
  padding-left: 5px;
}

.chart-bars-container {
  display: flex;
  flex: 1;
  justify-content: space-between;
  align-items: flex-end;
  height: 100%;
}

.chart-bar-column {
  display: flex;
  flex-direction: column;
  align-items: center;
  flex: 1;
  height: 100%;
}

.chart-bar-track {
  width: 28px;
  background: rgba(18, 20, 28, 0.5);
  border-radius: 30px;
  height: calc(100% - 25px);
  display: flex;
  align-items: flex-end;
  justify-content: center;
  position: relative;
  overflow: visible;
}

.chart-bar-fill {
  width: 100%;
  background: linear-gradient(180deg, var(--accent-bright), var(--accent));
  border-radius: 30px;
  position: relative;
  cursor: pointer;
  transition: height 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  box-shadow: 0 0 10px var(--accent-dim);
}

.chart-bar-fill:hover {
  background: #5eaeff;
  box-shadow: 0 0 15px var(--accent-glow);
}

.chart-bar-tooltip {
  position: absolute;
  top: -35px;
  left: 50%;
  transform: translateX(-50%) scale(0.8);
  background: var(--bg-secondary);
  border: 1px solid var(--border-glow);
  color: var(--text-primary);
  font-size: 0.75rem;
  font-weight: 700;
  padding: 4px 8px;
  border-radius: 4px;
  pointer-events: none;
  white-space: nowrap;
  opacity: 0;
  transition: all 0.2s ease;
  z-index: 10;
  box-shadow: 0 4px 10px rgba(0,0,0,0.5);
}

.chart-bar-fill:hover .chart-bar-tooltip {
  opacity: 1;
  transform: translateX(-50%) scale(1);
}

.chart-bar-label {
  font-size: 0.75rem;
  color: var(--text-muted);
  font-weight: 600;
  margin-top: 8px;
  height: 15px;
  line-height: 15px;
}

/* Activity Panel */
.recent-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
}

.recent-item {
  padding: 1rem;
  background: rgba(10, 11, 16, 0.4);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  cursor: pointer;
  transition: var(--transition);
}

.recent-item:hover {
  background: var(--bg-primary);
  border-color: var(--border-glow);
  transform: translateY(-2px);
}

.recent-meta {
  display: flex;
  justify-content: space-between;
  margin-bottom: 4px;
}

.recent-name {
  font-weight: 700;
  font-size: 0.88rem;
}

.recent-date {
  font-size: 0.75rem;
  color: var(--text-muted);
}

.recent-subject-wrap {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.recent-subject {
  font-size: 0.85rem;
  color: var(--text-secondary);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 170px;
}

.view-all-btn {
  width: 100%;
  padding: 10px;
  font-size: 0.88rem;
  justify-content: center;
}

.empty-state {
  text-align: center;
  padding: 2.5rem;
  color: var(--text-muted);
}

/* ==================== FILTERS BAR ==================== */
.filters-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1.5rem;
  margin-bottom: 2rem;
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  padding: 1rem 1.5rem;
  border-radius: var(--radius-sm);
}

.search-wrap {
  position: relative;
  display: flex;
  align-items: center;
  flex: 1;
  max-width: 400px;
}

.search-icon {
  position: absolute;
  left: 14px;
  color: var(--text-muted);
}

[dir="rtl"] .search-icon {
  left: auto;
  right: 14px;
}

.search-input {
  width: 100%;
  padding: 10px 14px 10px 38px;
  background: var(--bg-primary);
  border: 1px solid var(--border-color);
  border-radius: 30px;
  color: var(--text-primary);
  font-family: inherit;
  font-size: 0.9rem;
  outline: none;
  transition: var(--transition);
}

[dir="rtl"] .search-input {
  padding: 10px 38px 10px 14px;
}

.search-input:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 2px var(--accent-dim);
}

.clear-search-btn {
  position: absolute;
  right: 12px;
  background: transparent;
  border: none;
  color: var(--text-muted);
  font-size: 1.2rem;
  cursor: pointer;
}

[dir="rtl"] .clear-search-btn {
  right: auto;
  left: 12px;
}

.status-filters {
  display: flex;
  gap: 0.5rem;
}

.filter-tab-btn {
  padding: 8px 16px;
  border-radius: 30px;
  background: transparent;
  border: 1px solid transparent;
  color: var(--text-secondary);
  font-weight: 600;
  font-size: 0.85rem;
  cursor: pointer;
  transition: var(--transition);
}

.filter-tab-btn:hover {
  color: var(--text-primary);
  background: rgba(255, 255, 255, 0.03);
}

.filter-tab-btn.active {
  color: var(--accent-bright);
  background: var(--accent-dim);
  border-color: var(--border-color);
}

/* ==================== DATA TABLE ==================== */
.inquiries-container {
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--radius);
  overflow: hidden;
}

.dash-table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
}

[dir="rtl"] .dash-table {
  text-align: right;
}

.dash-table th {
  padding: 1.25rem 1.5rem;
  background: rgba(18, 20, 28, 0.8);
  border-bottom: 1px solid var(--border-color);
  color: var(--text-muted);
  font-size: 0.8rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.dash-table td {
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid var(--border-color);
  font-size: 0.9rem;
}

.dash-table tr {
  transition: background 0.2s;
}

.dash-table tr:hover {
  background: rgba(255, 255, 255, 0.01);
}

.dash-table tr.unread {
  background: rgba(0, 113, 255, 0.02);
}

.dash-table tr.unread td {
  font-weight: 600;
}

.client-cell {
  display: flex;
  flex-direction: column;
}

.client-name {
  color: var(--text-primary);
  font-weight: 700;
}

.client-email {
  color: var(--text-muted);
  font-size: 0.8rem;
  margin-top: 2px;
}

.subject-text {
  max-width: 240px;
  display: block;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  color: var(--text-secondary);
}

.date-text {
  color: var(--text-muted);
  font-size: 0.85rem;
}

/* Status Badges */
.badge-status {
  display: inline-flex;
  align-items: center;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
}

.badge-status.pending {
  background: rgba(255, 153, 0, 0.1);
  border: 1px solid rgba(255, 153, 0, 0.3);
  color: #ffaa33;
}

.badge-status.read {
  background: rgba(0, 113, 255, 0.1);
  border: 1px solid var(--border-color);
  color: var(--accent-bright);
}

.badge-status.replied {
  background: rgba(0, 204, 102, 0.1);
  border: 1px solid rgba(0, 204, 102, 0.3);
  color: #33cc88;
}

.actions-cell {
  display: flex;
  gap: 0.5rem;
}

.action-icon-btn {
  background: var(--bg-primary);
  border: 1px solid var(--border-color);
  width: 34px;
  height: 34px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.9rem;
  transition: var(--transition);
}

.action-icon-btn:hover {
  background: var(--accent-dim);
  border-color: var(--accent);
}

.action-icon-btn.delete:hover {
  background: rgba(255, 60, 60, 0.15);
  border-color: #ff4444;
}

.table-empty {
  text-align: center;
  padding: 4rem !important;
  color: var(--text-muted);
}

/* Pagination */
.pagination {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.25rem 1.5rem;
  background: rgba(18, 20, 28, 0.3);
}

.pag-btn {
  background: transparent;
  border: 1px solid var(--border-color);
  padding: 6px 14px;
  border-radius: 4px;
  color: var(--text-secondary);
  font-size: 0.85rem;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
}

.pag-btn:hover:not(:disabled) {
  border-color: var(--accent);
  color: var(--accent-bright);
  background: var(--accent-dim);
}

.pag-btn:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.pag-info {
  font-size: 0.85rem;
  color: var(--text-muted);
}

/* ==================== DETAILS MODAL ==================== */
.modal-overlay {
  position: fixed;
  inset: 0;
  z-index: 2000;
  background: rgba(6, 7, 10, 0.8);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.modal-card {
  width: 100%;
  max-width: 600px;
  background: var(--bg-card);
  border: 1px solid var(--border-glow);
  border-radius: var(--radius);
  box-shadow: var(--shadow-neon), var(--shadow-card);
  overflow: hidden;
  position: relative;
  display: flex;
  flex-direction: column;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-bottom: 1px solid var(--border-color);
}

.modal-title {
  font-size: 1.25rem;
  font-weight: 800;
}

.modal-close-btn {
  background: transparent;
  border: none;
  font-size: 1.8rem;
  color: var(--text-muted);
  cursor: pointer;
  line-height: 1;
}

.modal-close-btn:hover {
  color: var(--text-primary);
}

.modal-body {
  padding: 2rem 1.5rem;
  max-height: 60vh;
  overflow-y: auto;
}

.info-grid {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-bottom: 2rem;
  padding-bottom: 1.5rem;
  border-bottom: 1px solid var(--border-color);
}

.info-row {
  display: flex;
  align-items: center;
}

.info-label {
  width: 120px;
  font-size: 0.85rem;
  color: var(--text-muted);
  font-weight: 600;
}

[dir="rtl"] .info-label {
  width: 100px;
}

.info-val {
  font-size: 0.95rem;
  font-weight: 700;
}

.email-link {
  color: var(--accent-bright);
}

.email-link:hover {
  text-decoration: underline;
}

.msg-box {
  background: rgba(10, 11, 16, 0.6);
  border: 1px solid var(--border-color);
  padding: 1.5rem;
  border-radius: var(--radius-sm);
}

.msg-box-title {
  font-size: 0.85rem;
  color: var(--text-muted);
  margin-bottom: 0.75rem;
}

.msg-box-text {
  font-size: 0.95rem;
  line-height: 1.8;
  white-space: pre-wrap;
  color: var(--text-primary);
}

.modal-footer {
  padding: 1.25rem 1.5rem;
  background: rgba(10, 11, 16, 0.4);
  border-top: 1px solid var(--border-color);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-status-actions {
  display: flex;
  gap: 0.5rem;
}

.delete-modal-btn:hover {
  border-color: #ff4444;
  color: #ff5555;
  background: rgba(255, 60, 60, 0.05);
}

/* Mobile responsive navigation & layout */
.mobile-dash-header, .mobile-dash-nav {
  display: none;
}

/* Global loader */
.dash-loader {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 60vh;
  gap: 1.5rem;
}

.dash-loader .spinner {
  width: 40px;
  height: 40px;
  border: 3px solid rgba(0, 113, 255, 0.1);
  border-top-color: var(--accent);
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

/* CSS Utilities and Display helper classes */
.d-none-desktop { display: none !important; }
.btn-sm { padding: 8px 16px; font-size: 0.8rem; }

/* ── RESPONSIVE MEDIA QUERIES ── */
@media (max-width: 1024px) {
  .dashboard-sidebar {
    width: 240px;
  }
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .dashboard-wrapper {
    flex-direction: column;
  }
  
  .dashboard-sidebar {
    display: none;
  }

  .mobile-dash-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.2rem 1.5rem;
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-color);
  }

  .mobile-dash-actions {
    display: flex;
    gap: 0.75rem;
  }

  .mobile-lang-btn, .mobile-logout-btn {
    background: transparent;
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
    font-size: 0.85rem;
    padding: 6px 12px;
    border-radius: 30px;
    cursor: pointer;
  }

  .mobile-logout-btn {
    font-size: 1rem;
    padding: 4px 10px;
  }

  .mobile-dash-nav {
    display: flex;
    background: rgba(18, 20, 28, 0.9);
    border-bottom: 1px solid var(--border-color);
    position: sticky;
    top: 0;
    z-index: 100;
  }

  .mobile-nav-item {
    flex: 1;
    text-align: center;
    padding: 12px;
    font-family: inherit;
    font-size: 0.9rem;
    font-weight: 700;
    background: transparent;
    border: none;
    color: var(--text-secondary);
    border-bottom: 2px solid transparent;
    cursor: pointer;
  }

  .mobile-nav-item.active {
    color: var(--accent);
    border-bottom-color: var(--accent);
    background: var(--accent-dim);
  }

  .dashboard-body {
    padding: 1.5rem 1rem;
  }

  .overview-grid {
    grid-template-columns: 1fr;
  }

  .d-none-mobile { display: none !important; }
  .d-none-desktop { display: block !important; }

  /* Mobile inquiries cards layout */
  .mobile-inquiry-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 1.25rem;
    margin-bottom: 1rem;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .mobile-inquiry-card.unread {
    border-left: 3px solid var(--accent);
    background: rgba(0, 113, 255, 0.01);
  }
  
  [dir="rtl"] .mobile-inquiry-card.unread {
    border-left: none;
    border-right: 3px solid var(--accent);
  }

  .card-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .card-name {
    font-weight: 700;
    color: var(--text-primary);
  }

  .card-email {
    font-size: 0.8rem;
    color: var(--text-muted);
  }

  .card-subject {
    font-size: 0.85rem;
    color: var(--text-secondary);
  }

  .card-date {
    font-size: 0.78rem;
    color: var(--text-muted);
  }

  .card-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 8px;
    border-top: 1px solid rgba(255,255,255,0.05);
    padding-top: 8px;
  }

  .status-quick-actions {
    display: flex;
    gap: 6px;
  }

  .btn-icon-square {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: 1px solid var(--border-color);
    background: var(--bg-primary);
    cursor: pointer;
  }

  .btn-icon-square.delete {
    color: #ff5555;
  }

  .filters-bar {
    flex-direction: column;
    align-items: stretch;
    padding: 1rem;
  }

  .search-wrap {
    max-width: 100%;
  }

  .status-filters {
    justify-content: space-between;
  }

  .filter-tab-btn {
    padding: 6px 10px;
    font-size: 0.78rem;
    flex: 1;
    text-align: center;
  }
}

@media (max-width: 480px) {
  .stats-grid {
    grid-template-columns: 1fr;
    gap: 1rem;
  }
  .modal-overlay {
    padding: 1rem;
  }
  .modal-card {
    max-height: 90vh;
  }
  .modal-body {
    padding: 1rem;
  }
  .info-label {
    width: 80px;
  }
  .modal-footer {
    flex-direction: column;
    gap: 1rem;
    align-items: stretch;
  }
  .modal-status-actions {
    justify-content: space-between;
  }
}

/* ── FORM FIELDS (CMS INPUTS) ── */
.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.form-label {
  font-size: 0.78rem;
  font-weight: 600;
  color: var(--text-secondary);
  letter-spacing: 0.04em;
  text-transform: uppercase;
}

.form-input,
.form-textarea {
  width: 100%;
  background: rgba(255, 255, 255, 0.04);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  color: var(--text-primary);
  font-family: inherit;
  font-size: 0.9rem;
  padding: 10px 14px;
  outline: none;
  transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
  box-sizing: border-box;
  -webkit-user-select: text;
  user-select: text;
  pointer-events: all;
}

.form-input:focus,
.form-textarea:focus {
  border-color: var(--accent-primary);
  background: rgba(255, 255, 255, 0.07);
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
}

.form-input::placeholder,
.form-textarea::placeholder {
  color: rgba(255, 255, 255, 0.2);
}

.form-textarea {
  resize: vertical;
  min-height: 80px;
  line-height: 1.6;
}

.form-input.text-center {
  text-align: center;
}

.mt-3 { margin-top: 1rem; }
.mt-4 { margin-top: 1.5rem; }
.mb-3 { margin-bottom: 1rem; }
.mb-4 { margin-bottom: 1.5rem; }

/* ── CMS WRAPPER & LAYOUT STYLES ── */
.cms-editor-layout {
  display: grid;
  grid-template-columns: 240px 1fr;
  gap: 2rem;
  align-items: start;
  margin-top: 1.5rem;
}

.cms-sidebar {
  display: flex;
  flex-direction: column;
  gap: 6px;
  background: var(--bg-secondary);
  border: 1px solid var(--border-color);
  padding: 1rem;
  border-radius: var(--radius);
}

.cms-subtab-btn {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 16px;
  border-radius: 8px;
  border: 1px solid transparent;
  background: transparent;
  color: var(--text-secondary);
  font-family: inherit;
  font-size: 0.9rem;
  font-weight: 600;
  cursor: pointer;
  text-align: left;
  transition: var(--transition);
  width: 100%;
}

[dir="rtl"] .cms-subtab-btn {
  text-align: right;
}

.cms-subtab-btn:hover {
  background: rgba(255, 255, 255, 0.02);
  color: var(--text-primary);
}

.cms-subtab-btn.active {
  background: var(--accent-dim);
  border-color: var(--border-glow);
  color: var(--accent-bright);
  box-shadow: 0 0 10px rgba(0, 113, 255, 0.1);
}

.cms-form-body {
  padding: 2.5rem;
  background: var(--bg-card);
  border: 1px solid var(--border-color);
  border-radius: var(--radius);
  position: relative;
  min-height: 400px;
}

.cms-section-pane {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.cms-section-title {
  font-size: 1.35rem;
  font-weight: 800;
  color: var(--text-primary);
  margin-bottom: 0.5rem;
  border-bottom: 2px solid var(--border-color);
  padding-bottom: 0.75rem;
}

.form-group-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.25rem;
}

.form-label-section {
  font-size: 1rem;
  font-weight: 700;
  color: var(--text-primary);
}

.cms-divider {
  border: 0;
  height: 1px;
  background: var(--border-color);
  margin: 2rem 0;
}

/* CMS Items list */
.cms-list-container {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.cms-card-item {
  background: rgba(10, 11, 16, 0.4);
  border: 1px solid var(--border-color);
  padding: 1.5rem;
  border-radius: var(--radius-sm);
  position: relative;
  transition: all 0.25s;
}

.cms-card-item:hover {
  border-color: var(--border-glow);
  background: rgba(10, 11, 16, 0.6);
}

.cms-item-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.85rem;
  font-weight: 700;
  color: var(--accent-bright);
  margin-bottom: 1.25rem;
  border-bottom: 1px dashed rgba(255, 255, 255, 0.05);
  padding-bottom: 8px;
}

.cms-item-header-actions {
  display: flex;
  align-items: center;
  gap: 6px;
}

/* Sub lists inside items */
.cms-sublist {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  padding-left: 1rem;
  border-left: 2px solid var(--border-color);
}

[dir="rtl"] .cms-sublist {
  padding-left: 0;
  padding-right: 1rem;
  border-left: none;
  border-right: 2px solid var(--border-color);
}

.cms-subcard-item {
  background: rgba(255, 255, 255, 0.01);
  border: 1px solid rgba(255, 255, 255, 0.05);
  padding: 1rem;
  border-radius: 6px;
}

.cms-logo-edit-row {
  display: flex;
  align-items: center;
  gap: 1rem;
  background: rgba(255,255,255,0.01);
  border: 1px solid rgba(255,255,255,0.05);
  padding: 8px;
  border-radius: 6px;
}

.cms-header-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* CMS Toast Notice */
.cms-alert-toast {
  position: fixed;
  top: 24px;
  right: 24px;
  z-index: 9999;
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 1rem 1.5rem;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.95rem;
  box-shadow: 0 10px 30px rgba(0,0,0,0.5);
  animation: slide-in 0.3s ease-out;
}

[dir="rtl"] .cms-alert-toast {
  right: auto;
  left: 24px;
}

.cms-alert-toast.success {
  background: rgba(0, 200, 100, 0.95);
  border: 1px solid #00c864;
  color: #fff;
  text-shadow: 0 1px 3px rgba(0,0,0,0.3);
}

.cms-alert-toast.error {
  background: rgba(255, 60, 60, 0.95);
  border: 1px solid #ff4444;
  color: #fff;
  text-shadow: 0 1px 3px rgba(0,0,0,0.3);
}

.cms-save-footer {
  border-top: 1px solid var(--border-color);
  padding-top: 1.5rem;
  display: flex;
  justify-content: flex-end;
}

.cms-save-btn {
  padding: 14px 28px;
  font-size: 0.95rem;
  font-weight: 700;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 5px 15px rgba(0, 113, 255, 0.3);
}

.ml-2 { margin-left: 0.5rem; }
.mr-2 { margin-right: 0.5rem; }

@keyframes slide-in {
  from {
    transform: translateY(-20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

@media (max-width: 960px) {
  .cms-editor-layout {
    grid-template-columns: 1fr;
  }
  .cms-sidebar {
    flex-direction: row;
    overflow-x: auto;
    white-space: nowrap;
    padding: 8px;
  }
  .cms-subtab-btn {
    width: auto;
    padding: 10px 14px;
  }
  .cms-form-body {
    padding: 1.5rem 1rem;
  }
}
</style>
