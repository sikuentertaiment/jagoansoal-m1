<div id="page-admin" class="page max-w-6xl mx-auto">
  <div class="admin-layout">
    <aside class="admin-sidebar bg-white" id="admin-sidebar">
      <div class="admin-sidebar-header border-b pb-2" data-lang-key="admin.panel">Admin Panel</div>
      <a href="#admin-dashboard" class="admin-nav-link active" data-admin="dashboard">
        <i class="fas fa-chart-pie"></i> <span data-lang-key="admin.dashboard">Dashboard</span>
      </a>
      <a href="#admin-users" class="admin-nav-link" data-admin="users">
        <i class="fas fa-users"></i> <span data-lang-key="admin.users">Users</span>
      </a>
      <a href="#admin-transactions" class="admin-nav-link" data-admin="transactions">
        <i class="fas fa-credit-card"></i> <span data-lang-key="admin.transactions">Transactions</span>
      </a>
      <a href="#admin-reports" class="admin-nav-link" data-admin="reports">
        <i class="fas fa-bug"></i> <span data-lang-key="admin.reports">Reports</span>
      </a>
      <a href="#admin-subjects" class="admin-nav-link" data-admin="subjects">
        <i class="fas fa-book"></i> <span data-lang-key="admin.subjects">Subjects</span>
      </a>
      <a href="#admin-social" class="admin-nav-link" data-admin="social">
        <i class="fas fa-share-alt"></i> <span data-lang-key="admin.social_media">Social Media</span>
      </a>
      <a href="#admin-faq" class="admin-nav-link" data-admin="faq">
        <i class="fas fa-question-circle"></i> <span data-lang-key="admin.faq">FAQ</span>
      </a>
      <a href="#admin-tutorials" class="admin-nav-link" data-admin="tutorials">
        <i class="fas fa-video"></i> <span>Tutorials</span>
      </a>
      <a href="#admin-landing" class="admin-nav-link" data-admin="landing">
        <i class="fas fa-palette"></i> <span>Landing Page</span>
      </a>
      <div style="margin-top: auto; padding-top: 10px; border-top: 1px solid var(--gray-200);">
        <a href="#tools" class="admin-nav-link" style="font-size: 13px;">
          <i class="fas fa-arrow-left"></i> <span data-lang-key="admin.back_to_app">Back to App</span>
        </a>
      </div>
    </aside>
    <div class="admin-sidebar-overlay" id="admin-sidebar-overlay" onclick="closeAdminSidebar()"></div>

    <main class="admin-content">
      <!-- Dashboard -->
      <div id="admin-panel-dashboard" class="admin-panel active">
        <h2 class="admin-page-title"><i class="fas fa-chart-pie"></i> <span data-lang-key="admin.dashboard">Dashboard</span></h2>
        <div class="admin-stats-grid" id="adminStats">
          <div class="admin-stat-card">
            <div class="admin-stat-icon" style="background: rgba(37,99,235,0.1); color: #2563EB;"><i class="fas fa-users"></i></div>
            <div class="admin-stat-info">
              <span class="admin-stat-value" id="statTotalUsers">-</span>
              <span class="admin-stat-label" data-lang-key="admin.total_users">Total Users</span>
            </div>
          </div>
          <div class="admin-stat-card">
            <div class="admin-stat-icon" style="background: rgba(37,99,235,0.1); color: #2563EB;"><i class="fas fa-file-alt"></i></div>
            <div class="admin-stat-info">
              <span class="admin-stat-value" id="statTotalGenerated">-</span>
              <span class="admin-stat-label" data-lang-key="admin.questions_generated">Questions Generated</span>
            </div>
          </div>
          <div class="admin-stat-card">
            <div class="admin-stat-icon" style="background: rgba(37,99,235,0.1); color: #2563EB;"><i class="fas fa-check-circle"></i></div>
            <div class="admin-stat-info">
              <span class="admin-stat-value" id="statTotalTopups">-</span>
              <span class="admin-stat-label" data-lang-key="admin.successful_topups">Successful Topups</span>
            </div>
          </div>
          <div class="admin-stat-card">
            <div class="admin-stat-icon" style="background: rgba(37,99,235,0.1); color: #2563EB;"><i class="fas fa-wallet"></i></div>
            <div class="admin-stat-info">
              <span class="admin-stat-value" id="statTotalRevenue">-</span>
              <span class="admin-stat-label" data-lang-key="admin.total_revenue">Total Revenue</span>
            </div>
          </div>
          <div class="admin-stat-card">
            <div class="admin-stat-icon" style="background: rgba(37,99,235,0.1); color: #2563EB;"><i class="fas fa-coins"></i></div>
            <div class="admin-stat-info">
              <span class="admin-stat-value" id="statCreditsSold">-</span>
              <span class="admin-stat-label" data-lang-key="admin.credits_sold">Credits Sold</span>
            </div>
          </div>
          <div class="admin-stat-card">
            <div class="admin-stat-icon" style="background: rgba(37,99,235,0.1); color: #2563EB;"><i class="fas fa-clock"></i></div>
            <div class="admin-stat-info">
              <span class="admin-stat-value" id="statPendingTopups">-</span>
              <span class="admin-stat-label" data-lang-key="admin.pending_topups">Pending Topups</span>
            </div>
          </div>
        </div>

        <div class="admin-chart-section">
          <h3 class="admin-chart-title"><i class="fas fa-chart-line"></i> <span data-lang-key="admin.chart_generation">Questions Generated (Last 7 Days)</span></h3>
          <div class="admin-chart-container">
            <canvas id="generationChart"></canvas>
          </div>
        </div>

        <div class="admin-chart-section">
          <h3 class="admin-chart-title"><i class="fas fa-user-plus"></i> <span data-lang-key="admin.chart_growth">User Growth (Last 7 Days)</span></h3>
          <div class="admin-chart-container">
            <canvas id="userGrowthChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Users -->
      <div id="admin-panel-users" class="admin-panel">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
          <h2 class="admin-page-title" style="margin-bottom: 0;"><i class="fas fa-users"></i> <span data-lang-key="admin.users">Users</span></h2>
          <input type="text" id="adminUserSearch" class="admin-search" placeholder="Search by name or email..." oninput="filterAdminUsers()" data-lang-key="admin.search">
        </div>
        <div class="admin-table-wrapper">
          <table class="admin-table" id="adminUsersTable">
            <thead>
              <tr>
                <th data-lang-key="admin.th_user">User</th>
                <th data-lang-key="admin.th_email">Email</th>
                <th data-lang-key="admin.th_credits">Credits</th>
                <th data-lang-key="admin.th_generated">Generated</th>
                <th data-lang-key="admin.th_topups">Topups</th>
                <th data-lang-key="admin.th_spent">Spent</th>
                <th data-lang-key="admin.th_joined">Joined</th>
                <th data-lang-key="admin.th_action">Action</th>
              </tr>
            </thead>
            <tbody id="adminUsersBody">
              <tr><td colspan="8" class="admin-loading" data-lang-key="admin.loading">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Transactions -->
      <div id="admin-panel-transactions" class="admin-panel">
        <h2 class="admin-page-title"><i class="fas fa-credit-card"></i> <span data-lang-key="admin.transactions">Topup Transactions</span></h2>
        <div class="admin-table-wrapper">
          <table class="admin-table" id="adminTransactionsTable">
            <thead>
              <tr>
                <th data-lang-key="topup.order_id">Order ID</th>
                <th data-lang-key="admin.th_user">User</th>
                <th data-lang-key="admin.th_credits">Credits</th>
                <th data-lang-key="admin.th_spent">Amount</th>
                <th data-lang-key="topup.status">Status</th>
                <th data-lang-key="topup.payment_method">Payment</th>
                <th data-lang-key="topup.created">Date</th>
              </tr>
            </thead>
            <tbody id="adminTransactionsBody">
              <tr><td colspan="7" class="admin-loading" data-lang-key="admin.loading">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Reports -->
      <div id="admin-panel-reports" class="admin-panel">
        <h2 class="admin-page-title"><i class="fas fa-bug"></i> <span data-lang-key="admin.reports">User Reports</span></h2>
        <div class="admin-table-wrapper">
          <table class="admin-table" id="adminReportsTable">
            <thead>
              <tr>
                <th>ID</th>
                <th data-lang-key="admin.th_user">User</th>
                <th data-lang-key="report.subject">Subject</th>
                <th data-lang-key="report.desc_label">Description</th>
                <th data-lang-key="report.screenshot">Image</th>
                <th data-lang-key="topup.status">Status</th>
                <th data-lang-key="topup.created">Date</th>
                <th data-lang-key="admin.th_action">Action</th>
              </tr>
            </thead>
            <tbody id="adminReportsBody">
              <tr><td colspan="8" class="admin-loading" data-lang-key="admin.loading">Loading...</td></tr>
            </tbody>
          </table>
        </div>
        <div id="reportsPagination" class="pagination"></div>
      </div>

      <!-- Subjects -->
      <div id="admin-panel-subjects" class="admin-panel">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
          <h2 class="admin-page-title" style="margin-bottom: 0;"><i class="fas fa-book"></i> <span data-lang-key="admin.subjects">Subjects</span></h2>
          <button class="admin-action-btn" onclick="openSubjectModal()"><i class="fas fa-plus"></i> <span data-lang-key="admin.add_subject">Add Subject</span></button>
        </div>
        <div class="admin-table-wrapper">
          <table class="admin-table" id="adminSubjectsTable">
            <thead>
              <tr>
                <th>ID</th>
                <th data-lang-key="admin.subject_name">Name</th>
                <th data-lang-key="admin.subject_description">Description</th>
                <th data-lang-key="topup.created">Date</th>
                <th data-lang-key="admin.th_action">Action</th>
              </tr>
            </thead>
            <tbody id="adminSubjectsBody">
              <tr><td colspan="5" class="admin-loading" data-lang-key="admin.loading">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Social Media -->
      <div id="admin-panel-social" class="admin-panel">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
          <h2 class="admin-page-title" style="margin-bottom: 0;"><i class="fas fa-share-alt"></i> <span data-lang-key="admin.social_media">Social Media</span></h2>
          <button class="admin-action-btn" onclick="openSocialModal()"><i class="fas fa-plus"></i> <span data-lang-key="admin.add_social">Add Social Link</span></button>
        </div>
        <div class="admin-table-wrapper">
          <table class="admin-table" id="adminSocialTable">
            <thead>
              <tr>
                <th style="width:40px">No</th>
                <th style="width:60px">Icon</th>
                <th data-lang-key="admin.platform">Platform</th>
                <th data-lang-key="admin.url">URL</th>
                <th style="width:80px" data-lang-key="admin.sort">Sort</th>
                <th style="width:120px" data-lang-key="admin.th_action">Action</th>
              </tr>
            </thead>
            <tbody id="adminSocialBody">
              <tr><td colspan="6" class="admin-loading" data-lang-key="admin.loading">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- FAQ -->
      <div id="admin-panel-faq" class="admin-panel">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
          <h2 class="admin-page-title" style="margin-bottom: 0;"><i class="fas fa-question-circle"></i> <span data-lang-key="admin.faq">FAQ</span></h2>
          <button class="admin-action-btn" onclick="openFaqModal()"><i class="fas fa-plus"></i> <span data-lang-key="admin.add_faq">Add FAQ</span></button>
        </div>
        <div class="admin-table-wrapper">
          <table class="admin-table" id="adminFaqTable">
            <thead>
              <tr>
                <th style="width:40px">No</th>
                <th data-lang-key="faq.question">Question</th>
                <th data-lang-key="faq.answer">Answer</th>
                <th style="width:80px" data-lang-key="admin.sort">Sort</th>
                <th style="width:60px" data-lang-key="topup.status">Active</th>
                <th style="width:120px" data-lang-key="admin.th_action">Action</th>
              </tr>
            </thead>
            <tbody id="adminFaqBody">
              <tr><td colspan="6" class="admin-loading" data-lang-key="admin.loading">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Tutorials -->
      <div id="admin-panel-tutorials" class="admin-panel">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
          <h2 class="admin-page-title" style="margin-bottom: 0;"><i class="fas fa-video"></i> Tutorials</h2>
          <button class="admin-action-btn" onclick="openTutorialModal()"><i class="fas fa-plus"></i> Add Tutorial</button>
        </div>
        <div class="admin-table-wrapper">
          <table class="admin-table" id="adminTutorialsTable">
            <thead>
              <tr>
                <th style="width:40px">No</th>
                <th>Title</th>
                <th>Video</th>
                <th style="width:80px">Sort</th>
                <th style="width:60px">Active</th>
                <th style="width:120px">Action</th>
              </tr>
            </thead>
            <tbody id="adminTutorialsBody">
              <tr><td colspan="6" class="admin-loading">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Landing Page Settings -->
      <div id="admin-panel-landing" class="admin-panel">
        <h2 class="admin-page-title"><i class="fas fa-palette"></i> Landing Page Settings</h2>

        <div style="border-radius:var(--radius-md);padding:24px;margin-bottom:24px;" class="bg-white border">
          <h3 style="font-size:16px;font-weight:700;margin:0 0 16px 0;">Hero Section</h3>
          <div class="form-group">
            <label class="form-label">Hero Title</label>
            <input type="text" id="lndHeroTitle" class="form-input" placeholder="Hero title text">
          </div>
          <div class="form-group">
            <label class="form-label">Hero Subtitle</label>
            <textarea id="lndHeroSubtitle" class="form-input" rows="3" placeholder="Hero subtitle text" style="resize:vertical;"></textarea>
          </div>
          <div class="form-group">
            <label class="form-label">Hero Image URL</label>
            <input type="text" id="lndHeroImage" class="form-input" placeholder="/public/assets/app/landing-hero-img-mockup.png">
            <p style="font-size:12px;color:var(--gray-400);margin-top:4px;">URL gambar untuk hero section. Bisa relative path atau link eksternal.</p>
          </div>
        </div>

        <div style="border-radius:var(--radius-md);padding:24px;margin-bottom:24px;" class="bg-white border">
          <h3 style="font-size:16px;font-weight:700;margin:0 0 16px 0;">Hero Stats</h3>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="form-group">
              <label class="form-label">Stat Soal Dibuat (contoh: 50.000+)</label>
              <input type="text" id="lndStatSoal" class="form-input" placeholder="e.g. 50.000+">
            </div>
            <div class="form-group">
              <label class="form-label">Stat Guru Pengguna (contoh: 5.000+)</label>
              <input type="text" id="lndStatGuru" class="form-input" placeholder="e.g. 5.000+">
            </div>
          </div>
        </div>

        <div style="border-radius:var(--radius-md);padding:24px;margin-bottom:24px;" class="bg-white border">
          <h3 style="font-size:16px;font-weight:700;margin:0 0 16px 0;">Video Cara Kerja</h3>
          <div class="form-group">
            <label class="form-label">YouTube URL</label>
            <input type="text" id="lndVideoUrl" class="form-input" placeholder="https://www.youtube.com/watch?v=..." oninput="previewLandingVideo()">
            <p style="font-size:12px;color:var(--gray-400);margin-top:4px;">Tempel link YouTube untuk video di section Cara Kerja</p>
            <div id="lndVideoPreview" style="margin-top:10px;display:none;">
              <iframe width="100%" height="200" frameborder="0" allowfullscreen style="border-radius:8px;"></iframe>
            </div>
          </div>
        </div>

        <div style="text-align:right;margin-bottom:32px;">
          <button class="btn-generate" onclick="saveLandingSettings()">
            <i class="fas fa-save"></i> Simpan Semua Pengaturan
          </button>
          <p id="lndSettingsError" style="color:#ef4444;font-size:13px;margin:8px 0 0 0;"></p>
        </div>

        <!-- How It Works Items -->
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
          <h3 style="font-size:16px;font-weight:700;margin:0;"><i class="fas fa-list-ol"></i> Cara Kerja Items</h3>
          <button class="admin-action-btn" onclick="openHowItWorksModal()"><i class="fas fa-plus"></i> Add Item</button>
        </div>
        <div class="admin-table-wrapper">
          <table class="admin-table" id="adminHowItWorksTable">
            <thead>
              <tr>
                <th style="width:40px">No</th>
                <th style="width:80px">Step</th>
                <th>Title</th>
                <th>Description</th>
                <th style="width:120px">Action</th>
              </tr>
            </thead>
            <tbody id="adminHowItWorksBody">
              <tr><td colspan="5" class="admin-loading">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>

  <!-- Social Media Modal -->
  <div id="socialModal" class="modal-overlay">
    <div class="topup-modal" style="max-width: 500px;">
      <div class="topup-modal-header">
        <h3 id="socialModalTitle" data-lang-key="admin.add_social">Add Social Link</h3>
        <button class="topup-modal-close" onclick="closeSocialModal()"><i class="fas fa-times"></i></button>
      </div>
      <div class="topup-modal-body">
        <div class="form-group">
          <label class="form-label" data-lang-key="admin.platform">Platform</label>
          <input type="text" id="socialPlatform" class="form-input" placeholder="e.g. Twitter, Instagram" list="platform-list">
          <datalist id="platform-list">
            <option value="Twitter"><option value="Instagram"><option value="Facebook"><option value="TikTok"><option value="YouTube"><option value="LinkedIn"><option value="GitHub"><option value="WhatsApp"><option value="Telegram">
          </datalist>
        </div>
        <div class="form-group">
          <label class="form-label" data-lang-key="admin.url">URL</label>
          <input type="url" id="socialUrl" class="form-input" placeholder="https://...">
        </div>
        <div class="form-group">
          <label class="form-label" data-lang-key="admin.icon">Icon (FontAwesome class)</label>
          <input type="text" id="socialIcon" class="form-input" placeholder="fab fa-twitter" list="icon-list">
          <datalist id="icon-list">
            <option value="fab fa-twitter"><option value="fab fa-instagram"><option value="fab fa-facebook-f"><option value="fab fa-tiktok"><option value="fab fa-youtube"><option value="fab fa-linkedin-in"><option value="fab fa-github"><option value="fab fa-whatsapp"><option value="fab fa-telegram-plane">
          </datalist>
        </div>
        <div class="form-group">
          <label class="form-label" data-lang-key="admin.sort">Sort Order</label>
          <input type="number" id="socialSort" class="form-input" value="0" min="0">
        </div>
        <input type="hidden" id="socialId" value="">
        <p id="socialError" style="color:#ef4444;font-size:13px;margin:8px 0;"></p>
        <button class="btn-generate w-full" onclick="saveSocialLink()" style="margin-top:8px;">
          <i class="fas fa-save"></i> <span data-lang-key="admin.save">Save</span>
        </button>
      </div>
    </div>
  </div>

  <!-- FAQ Modal -->
  <div id="faqModal" class="modal-overlay">
    <div class="topup-modal" style="max-width: 600px;">
      <div class="topup-modal-header">
        <h3 id="faqModalTitle" data-lang-key="admin.add_faq">Add FAQ</h3>
        <button class="topup-modal-close" onclick="closeFaqModal()"><i class="fas fa-times"></i></button>
      </div>
      <div class="topup-modal-body">
        <div class="form-group">
          <label class="form-label" data-lang-key="faq.question">Question</label>
          <input type="text" id="faqQuestion" class="form-input" placeholder="Enter question...">
        </div>
        <div class="form-group">
          <label class="form-label" data-lang-key="faq.answer">Answer</label>
          <textarea id="faqAnswer" class="form-input" rows="4" placeholder="Enter answer..." style="resize:vertical;min-height:100px;"></textarea>
        </div>
        <div class="form-group">
          <label class="form-label" data-lang-key="admin.sort">Sort Order</label>
          <input type="number" id="faqSort" class="form-input" value="0" min="0">
        </div>
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
          <input type="checkbox" id="faqActive" checked style="width:16px;height:16px;">
          <label for="faqActive" data-lang-key="admin.active">Active</label>
        </div>
        <input type="hidden" id="faqId" value="">
        <p id="faqError" style="color:#ef4444;font-size:13px;margin:8px 0;"></p>
        <button class="btn-generate w-full" onclick="saveFaqItem()" style="margin-top:8px;">
          <i class="fas fa-save"></i> <span data-lang-key="admin.save">Save</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Tutorial Modal -->
  <div id="tutorialModal" class="modal-overlay">
    <div class="topup-modal" style="max-width: 600px;">
      <div class="topup-modal-header">
        <h3 id="tutorialModalTitle">Add Tutorial</h3>
        <button class="topup-modal-close" onclick="closeTutorialModal()"><i class="fas fa-times"></i></button>
      </div>
      <div class="topup-modal-body">
        <div class="form-group">
          <label class="form-label">Judul</label>
          <input type="text" id="tutorialTitle" class="form-input" placeholder="Masukkan judul tutorial...">
        </div>
        <div class="form-group">
          <label class="form-label">Deskripsi</label>
          <textarea id="tutorialDescription" class="form-input" rows="3" placeholder="Deskripsi tutorial..." style="resize:vertical;"></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">YouTube URL</label>
          <input type="text" id="tutorialVideoUrl" class="form-input" placeholder="https://www.youtube.com/watch?v=..." oninput="previewTutorialVideo()">
          <p style="font-size:12px;color:var(--gray-400);margin-top:4px;">Tempel link YouTube. Format yang didukung: youtube.com/watch?v=, youtu.be/, youtube.com/embed/, youtube.com/shorts/</p>
          <div id="tutorialVideoPreview" style="margin-top:10px;display:none;">
            <iframe width="100%" height="200" frameborder="0" allowfullscreen style="border-radius:8px;"></iframe>
          </div>
        </div>
        <div style="display:flex;gap:12px;">
          <div class="form-group" style="flex:1;">
            <label class="form-label">Sort Order</label>
            <input type="number" id="tutorialSort" class="form-input" value="0" min="0">
          </div>
          <div class="form-group" style="flex:1;display:flex;align-items:flex-end;padding-bottom:4px;">
            <div style="display:flex;align-items:center;gap:8px;">
              <input type="checkbox" id="tutorialActive" checked style="width:16px;height:16px;">
              <label for="tutorialActive">Active</label>
            </div>
          </div>
        </div>
        <input type="hidden" id="tutorialId" value="">
        <p id="tutorialError" style="color:#ef4444;font-size:13px;margin:8px 0;"></p>
        <button class="btn-generate w-full" onclick="saveTutorial()" style="margin-top:8px;">
          <i class="fas fa-save"></i> Save
        </button>
      </div>
    </div>
  </div>

  <!-- How It Works Modal -->
  <div id="howItWorksModal" class="modal-overlay">
    <div class="topup-modal" style="max-width: 550px;">
      <div class="topup-modal-header">
        <h3 id="howItWorksModalTitle">Add Cara Kerja Item</h3>
        <button class="topup-modal-close" onclick="closeHowItWorksModal()"><i class="fas fa-times"></i></button>
      </div>
      <div class="topup-modal-body">
        <div class="form-group">
          <label class="form-label">Step Number</label>
          <input type="number" id="howItWorksStep" class="form-input" value="1" min="1" max="10">
        </div>
        <div class="form-group">
          <label class="form-label">Title</label>
          <input type="text" id="howItWorksTitle" class="form-input" placeholder="Masukkan judul langkah...">
        </div>
        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea id="howItWorksDesc" class="form-input" rows="3" placeholder="Deskripsi langkah..." style="resize:vertical;"></textarea>
        </div>
        <input type="hidden" id="howItWorksId" value="">
        <p id="howItWorksError" style="color:#ef4444;font-size:13px;margin:8px 0;"></p>
        <button class="btn-generate w-full" onclick="saveHowItWorksItem()" style="margin-top:8px;">
          <i class="fas fa-save"></i> Save
        </button>
      </div>
    </div>
  </div>

  <!-- Manual Topup Modal -->
  <div id="manualTopupModal" class="modal-overlay">
    <div class="topup-modal" style="max-width: 400px;">
      <div class="topup-modal-header">
        <h3 data-lang-key="admin.topup_title">Manual Top-Up</h3>
        <button class="topup-modal-close" onclick="closeManualTopupModal()"><i class="fas fa-times"></i></button>
      </div>
      <div class="topup-modal-body">
        <p style="font-size: 14px; color: var(--gray-500); margin-bottom: 16px;" id="manualTopupUserInfo" data-lang-key="admin.topup_desc">Add credits to user</p>
        <input type="hidden" id="manualTopupUserId">
        <div class="form-group">
          <label class="form-label" data-lang-key="admin.topup_credits_label">Credits</label>
          <input type="number" id="manualTopupCredits" class="form-input" value="10" min="1" max="999">
        </div>
        <button class="btn-generate w-full text-center justify-center" onclick="confirmManualTopup()">
          <i class="fas fa-plus-circle"></i> <span data-lang-key="admin.topup_add">Add Credits</span>
        </button>
        <div id="manualTopupError" class="error-msg"></div>
      </div>
    </div>
  </div>

  <!-- Subject Modal -->
  <div id="subjectModal" class="modal-overlay">
    <div class="topup-modal" style="max-width: 450px;">
      <div class="topup-modal-header">
        <h3 id="subjectModalTitle" data-lang-key="admin.add_subject">Add Subject</h3>
        <button class="topup-modal-close" onclick="closeSubjectModal()"><i class="fas fa-times"></i></button>
      </div>
      <div class="topup-modal-body">
        <input type="hidden" id="subjectId">
        <div class="form-group">
          <label class="form-label" data-lang-key="admin.subject_name">Subject Name</label>
          <input type="text" id="subjectName" class="form-input" placeholder="e.g. Mathematics">
        </div>
        <div class="form-group">
          <label class="form-label" data-lang-key="admin.subject_description">Description</label>
          <textarea id="subjectDescription" class="form-input" rows="3" placeholder="Subject description..."></textarea>
        </div>
        <button class="btn-generate w-full text-center justify-center" onclick="saveSubject()">
          <i class="fas fa-save"></i> <span data-lang-key="admin.save">Save</span>
        </button>
        <div id="subjectError" class="error-msg"></div>
      </div>
    </div>
  </div>
</div>

<script>
(function() {
let adminUsers = [];
let subjectsList = [];
let tutorialList = [];
let reportsPage = 1;

  window.loadAdminDashboard = async function() {
    try {
      const res = await fetch('../backend/admin.php?action=dashboard');
      const data = await res.json();
      if (!data.success) return;
      const s = data.stats;
      document.getElementById('statTotalUsers').textContent = s.total_users;
      document.getElementById('statTotalGenerated').textContent = s.total_generated;
      document.getElementById('statTotalTopups').textContent = s.total_topups;
      document.getElementById('statTotalRevenue').textContent = 'IDR ' + new Intl.NumberFormat('id-ID').format(s.total_revenue);
      document.getElementById('statCreditsSold').textContent = s.total_credits_sold;
      document.getElementById('statPendingTopups').textContent = s.pending_topups;

      if (data.daily_generated) renderGenerationChart(data.daily_generated);
      if (data.daily_users) renderUserGrowthChart(data.daily_users);
    } catch (e) {
      console.warn('Admin dashboard error:', e);
    }
  };

  let generationChart = null;
  function renderGenerationChart(dailyData) {
    const ctx = document.getElementById('generationChart');
    if (!ctx) return;
    if (generationChart) generationChart.destroy();
    const labels = dailyData.map(d => d.label);
    const values = dailyData.map(d => d.count);
    generationChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Questions Generated',
          data: values,
          backgroundColor: 'rgba(37, 99, 235, 0.8)',
          borderColor: 'rgba(37, 99, 235, 1)',
          borderWidth: 1,
          borderRadius: 8,
          barThickness: 40,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#1f2937',
            titleColor: '#fff',
            bodyColor: '#fff',
            padding: 12,
            cornerRadius: 8,
            callbacks: {
              label: function(context) {
                return context.parsed.y + ' questions';
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { stepSize: 1, color: '#6b7280', font: { size: 12 } },
            grid: { color: '#e5e7eb' }
          },
          x: {
            ticks: { color: '#6b7280', font: { size: 12 } },
            grid: { display: false }
          }
        }
      }
    });
  }

  let userGrowthChart = null;
  function renderUserGrowthChart(dailyData) {
    const ctx = document.getElementById('userGrowthChart');
    if (!ctx) return;
    if (userGrowthChart) userGrowthChart.destroy();
    const labels = dailyData.map(d => d.label);
    const values = dailyData.map(d => d.count);
    userGrowthChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'New Users',
          data: values,
          borderColor: 'rgba(37, 99, 235, 1)',
          backgroundColor: 'rgba(37, 99, 235, 0.1)',
          fill: true,
          tension: 0.4,
          pointBackgroundColor: 'rgba(37, 99, 235, 1)',
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          pointRadius: 5,
          pointHoverRadius: 7,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#1f2937',
            titleColor: '#fff',
            bodyColor: '#fff',
            padding: 12,
            cornerRadius: 8,
            callbacks: {
              label: function(context) {
                return context.parsed.y + ' new users';
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { stepSize: 1, color: '#6b7280', font: { size: 12 } },
            grid: { color: '#e5e7eb' }
          },
          x: {
            ticks: { color: '#6b7280', font: { size: 12 } },
            grid: { display: false }
          }
        }
      }
    });
  }

  window.loadAdminUsers = async function() {
    const tbody = document.getElementById('adminUsersBody');
    try {
      const res = await fetch('../backend/admin.php?action=users');
      const data = await res.json();
      if (!data.success) throw new Error('Failed');
      adminUsers = data.users;
      renderAdminUsers(data.users);
    } catch (e) {
      tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:32px;color:var(--gray-400);">Failed to load users</td></tr>';
    }
  };

  function renderAdminUsers(users) {
    const tbody = document.getElementById('adminUsersBody');
    if (!users.length) {
      tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:32px;color:var(--gray-400);">' + window.t('admin.no_users') + '</td></tr>';
      return;
    }
    tbody.innerHTML = users.map(u => {
      const joined = new Date(u.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
      const spent = new Intl.NumberFormat('id-ID').format(u.total_spent || 0);
      return `<tr>
        <td class="admin-user-cell">
          ${u.photo_url ? `<img src="${u.photo_url}" onerror="this.src='https://api.dicebear.com/9.x/pixel-art/svg?seed=${u.display_name || 'user'}'" class="admin-user-avatar">` : '<div class="admin-user-avatar-placeholder"><i class="fas fa-user"></i></div>'}
          <span>${u.display_name || '—'}</span>
        </td>
        <td>${u.email}</td>
        <td><strong>${u.credit}</strong></td>
        <td>${u.total_generated}</td>
        <td>${u.total_topups}</td>
        <td>IDR ${spent}</td>
        <td class="admin-date">${joined}</td>
        <td><button class="admin-action-btn" onclick="openManualTopup('${u.id}', '${u.email.replace(/'/g, "\\'")}', '${(u.display_name || '').replace(/'/g, "\\'")}')">${window.t('admin.topup_btn')}</button></td>
      </tr>`;
    }).join('');
  }

  window.filterAdminUsers = function() {
    const q = document.getElementById('adminUserSearch').value.toLowerCase();
    const filtered = adminUsers.filter(u =>
      (u.email && u.email.toLowerCase().includes(q)) ||
      (u.display_name && u.display_name.toLowerCase().includes(q))
    );
    renderAdminUsers(filtered);
  };

  window.loadAdminTransactions = async function() {
    const tbody = document.getElementById('adminTransactionsBody');
    if (!tbody) return;
    tbody.innerHTML = '<tr><td colspan="7" class="admin-loading">' + window.t('admin.loading') + '</td></tr>';
    try {
      const response = await fetch('../backend/admin.php?action=transactions');
      const data = await response.json();
      if (!data.success) {
        tbody.innerHTML = '<tr><td colspan="7" class="admin-error">Failed to load</td></tr>';
        return;
      }
      const transactions = data.transactions || [];
      if (transactions.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="admin-empty">' + window.t('admin.no_transactions') + '</td></tr>';
        return;
      }
      tbody.innerHTML = transactions.map(tx => {
        const date = new Date(tx.created_at).toLocaleDateString('id-ID');
        const statusClass = tx.status === 'success' ? 'status-success' : tx.status === 'pending' ? 'status-pending' : 'status-failed';
        const amount = new Intl.NumberFormat('id-ID').format(tx.total_price);
        const name = tx.display_name || tx.email || tx.user_id;
        return `
          <tr>
            <td><code>${tx.midtrans_order_id || tx.order_id || '-'}</code></td>
            <td>${name}</td>
            <td>${tx.credits}</td>
            <td>IDR ${amount}</td>
            <td><span class="status-badge ${statusClass}">${tx.status}</span></td>
            <td>${tx.payment_method || '-'}</td>
            <td>${date}</td>
          </tr>
        `;
      }).join('');
    } catch (err) {
      tbody.innerHTML = '<tr><td colspan="7" class="admin-error">Error loading data</td></tr>';
    }
  };

  window.loadAdminReports = async function(page) {
    if (page !== undefined) reportsPage = page;
    const tbody = document.getElementById('adminReportsBody');
    const pagination = document.getElementById('reportsPagination');
    if (!tbody) return;
    tbody.innerHTML = '<tr><td colspan="8" class="admin-loading">' + window.t('admin.loading') + '</td></tr>';
    try {
      const response = await fetch('../backend/report.php?action=list&page=' + reportsPage + '&limit=10');
      const data = await response.json();
      if (!data.success) {
        tbody.innerHTML = '<tr><td colspan="8" class="admin-error">Failed to load</td></tr>';
        return;
      }
      const reports = data.reports || [];
      const paginationData = data.pagination || {};
      if (reports.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="admin-empty">' + window.t('admin.no_reports') + '</td></tr>';
        pagination.innerHTML = '';
        return;
      }
      tbody.innerHTML = reports.map(r => {
        const date = new Date(r.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' });
        const statusClass = r.status === 'new' ? 'status-pending' : r.status === 'read' ? 'status-success' : 'status-failed';
        const subjectLabel = { 'bug': 'Bug', 'feature': 'Feature', 'ui': 'UI/UX', 'other': 'Other' };
        const imageHtml = r.image_url ? `<a href="${r.image_url}" target="_blank"><img src="${r.image_url}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;"></a>` : '-';
        const actionBtn = r.status === 'new' ? `<button class="admin-action-btn" onclick="markReportRead(${r.id})"><i class="fas fa-check"></i></button>` : '<span style="color: #9ca3af;">-</span>';
        return `
          <tr>
            <td>#${r.id}</td>
            <td>${r.user_name || 'Guest'}<br><small style="color: #9ca3af;">${r.user_email || '-'}</small></td>
            <td><span class="status-badge" style="background: #e5e7eb; color: #374151;">${subjectLabel[r.subject] || r.subject}</span></td>
            <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${r.description}">${r.description}</td>
            <td>${imageHtml}</td>
            <td><span class="status-badge ${statusClass}">${r.status}</span></td>
            <td>${date}</td>
            <td>${actionBtn}</td>
          </tr>
        `;
      }).join('');
      renderAdminPagination(paginationData);
    } catch (err) {
      tbody.innerHTML = '<tr><td colspan="8" class="admin-error">Error loading data</td></tr>';
    }
  };

  window.markReportRead = async function(reportId) {
    try {
      await fetch('../backend/report.php?action=mark_read', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ report_id: reportId })
      });
      window.loadAdminReports();
    } catch (err) {
      console.error('Failed to mark report as read:', err);
    }
  };

  function renderAdminPagination(paginationData) {
    const pagination = document.getElementById('reportsPagination');
    if (!pagination || paginationData.total_pages <= 1) {
      pagination.innerHTML = '';
      return;
    }
    let html = '<div class="pagination-inner">';
    html += `<button class="pagination-btn" onclick="window.loadAdminReports(${reportsPage - 1})" ${reportsPage === 1 ? 'disabled' : ''}><i class="fas fa-chevron-left"></i></button>`;
    html += `<span style="padding: 0 12px; color: #6b7280;">Page ${paginationData.current_page} of ${paginationData.total_pages}</span>`;
    html += `<button class="pagination-btn" onclick="window.loadAdminReports(${reportsPage + 1})" ${reportsPage === paginationData.total_pages ? 'disabled' : ''}><i class="fas fa-chevron-right"></i></button>`;
    html += '</div>';
    pagination.innerHTML = html;
  }

  window.loadAdminSubjects = async function() {
    const tbody = document.getElementById('adminSubjectsBody');
    try {
      const res = await fetch('../backend/admin.php?action=subjects');
      const data = await res.json();
      if (!data.success) throw new Error('Failed');
      subjectsList = data.subjects || [];
      renderAdminSubjects(subjectsList);
    } catch (e) {
      tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:32px;color:var(--gray-400);">Failed to load subjects</td></tr>';
    }
  };

  function renderAdminSubjects(subjects) {
    const tbody = document.getElementById('adminSubjectsBody');
    if (!subjects.length) {
      tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:32px;color:var(--gray-400);">' + window.t('admin.no_subjects') + '</td></tr>';
      return;
    }
    tbody.innerHTML = subjects.map(s => {
      const created = s.created_at ? new Date(s.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
      return `<tr>
        <td>${s.id}</td>
        <td><strong>${s.name}</strong></td>
        <td>${s.description || '-'}</td>
        <td class="admin-date">${created}</td>
        <td>
          <button class="admin-action-btn" onclick="adminEditSubject(${s.id})" style="margin-right: 4px;"><i class="fas fa-edit"></i></button>
          <button class="admin-action-btn" onclick="deleteSubject(${s.id})" style="background: rgba(239,68,68,0.1); color: #ef4444;"><i class="fas fa-trash"></i></button>
        </td>
      </tr>`;
    }).join('');
  }

  window.openSubjectModal = function() {
    document.getElementById('subjectId').value = '';
    document.getElementById('subjectName').value = '';
    document.getElementById('subjectDescription').value = '';
    document.getElementById('subjectModalTitle').textContent = window.t('admin.add_subject') || 'Add Subject';
    document.getElementById('subjectModal').classList.add('active');
    document.getElementById('subjectError').textContent = '';
    document.body.style.overflow = 'hidden';
  };

  window.closeSubjectModal = function() {
    document.getElementById('subjectModal').classList.remove('active');
    document.body.style.overflow = '';
  };

  window.adminEditSubject = function(id) {
    const subject = subjectsList.find(s => s.id == id);
    if (!subject) return;
    document.getElementById('subjectId').value = subject.id;
    document.getElementById('subjectName').value = subject.name;
    document.getElementById('subjectDescription').value = subject.description || '';
    document.getElementById('subjectModalTitle').textContent = window.t('admin.edit_subject') || 'Edit Subject';
    document.getElementById('subjectModal').classList.add('active');
    document.getElementById('subjectError').textContent = '';
    document.body.style.overflow = 'hidden';
  };

  window.saveSubject = async function() {
    const id = document.getElementById('subjectId').value;
    const name = document.getElementById('subjectName').value.trim();
    const description = document.getElementById('subjectDescription').value.trim();
    const btn = document.querySelector('#subjectModal .btn-generate');
    const errEl = document.getElementById('subjectError');

    if (!name) {
      errEl.textContent = window.t('admin.subject_name_required') || 'Subject name is required';
      return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + (window.t('admin.processing') || 'Processing...');

    try {
      const action = id ? 'edit' : 'add';
      const body = { sub_action: action, name: name, description: description };
      if (id) body.id = parseInt(id);

      const res = await fetch('../backend/admin.php?action=subjects', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body)
      });
      const data = await res.json();

      if (data.success) {
        closeSubjectModal();
        window.loadAdminSubjects();
      } else {
        throw new Error(data.error || 'Failed to save subject');
      }
    } catch (err) {
      errEl.textContent = err.message;
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-save"></i> ' + (window.t('admin.save') || 'Save');
    }
  };

  window.deleteSubject = async function(id) {
    showConfirm('Hapus Mata Pelajaran', 'Yakin ingin menghapus mata pelajaran ini?', async function() {
      try {
        const res = await fetch('../backend/admin.php?action=subjects', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ sub_action: 'delete', id: id })
        });
        const data = await res.json();

        if (data.success) {
          window.loadAdminSubjects();
        } else {
          showAlert('Gagal', data.error || 'Failed to delete subject', 'warning');
        }
      } catch (err) {
        showAlert('Error', 'Error deleting subject', 'warning');
      }
    }, 'Hapus');
  };

  // ---- Social Media CRUD ----
  let socialLinksList = [];

  window.loadAdminSocialLinks = async function() {
    const tbody = document.getElementById('adminSocialBody');
    try {
      const res = await fetch('../backend/admin.php?action=social_links');
      const data = await res.json();
      if (!data.success) throw new Error('Failed');
      socialLinksList = data.items || [];
      renderAdminSocialLinks(socialLinksList);
    } catch (e) {
      tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:32px;color:var(--gray-400);">Failed to load social links</td></tr>';
    }
  };

  function renderAdminSocialLinks(items) {
    const tbody = document.getElementById('adminSocialBody');
    if (!items.length) {
      tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:32px;color:var(--gray-400);">' + (window.t('admin.no_data') || 'No data') + '</td></tr>';
      return;
    }
    tbody.innerHTML = items.map((s, i) => `<tr>
      <td>${i + 1}</td>
      <td style="font-size:20px;text-align:center;"><i class="${s.icon}"></i></td>
      <td><strong>${s.platform}</strong></td>
      <td><a href="${s.url}" target="_blank" style="color:var(--primary);">${s.url}</a></td>
      <td>${s.sort_order}</td>
      <td>
        <button class="admin-action-btn" onclick="adminEditSocial(${s.id})" style="margin-right:4px;"><i class="fas fa-edit"></i></button>
        <button class="admin-action-btn" onclick="deleteSocialLink(${s.id})" style="background:rgba(239,68,68,0.1);color:#ef4444;"><i class="fas fa-trash"></i></button>
      </td>
    </tr>`).join('');
  }

  window.openSocialModal = function() {
    document.getElementById('socialId').value = '';
    document.getElementById('socialPlatform').value = '';
    document.getElementById('socialUrl').value = '';
    document.getElementById('socialIcon').value = '';
    document.getElementById('socialSort').value = '0';
    document.getElementById('socialModalTitle').textContent = window.t('admin.add_social') || 'Add Social Link';
    document.getElementById('socialModal').classList.add('active');
    document.getElementById('socialError').textContent = '';
    document.body.style.overflow = 'hidden';
  };

  window.closeSocialModal = function() {
    document.getElementById('socialModal').classList.remove('active');
    document.body.style.overflow = '';
  };

  window.adminEditSocial = function(id) {
    const item = socialLinksList.find(s => s.id == id);
    if (!item) return;
    document.getElementById('socialId').value = item.id;
    document.getElementById('socialPlatform').value = item.platform;
    document.getElementById('socialUrl').value = item.url;
    document.getElementById('socialIcon').value = item.icon;
    document.getElementById('socialSort').value = item.sort_order;
    document.getElementById('socialModalTitle').textContent = window.t('admin.edit_social') || 'Edit Social Link';
    document.getElementById('socialModal').classList.add('active');
    document.getElementById('socialError').textContent = '';
    document.body.style.overflow = 'hidden';
  };

  window.saveSocialLink = async function() {
    const id = document.getElementById('socialId').value;
    const platform = document.getElementById('socialPlatform').value.trim();
    const url = document.getElementById('socialUrl').value.trim();
    const icon = document.getElementById('socialIcon').value.trim();
    const sort_order = parseInt(document.getElementById('socialSort').value) || 0;
    const btn = document.querySelector('#socialModal .btn-generate');
    const errEl = document.getElementById('socialError');

    if (!platform || !url || !icon) {
      errEl.textContent = window.t('admin.required_fields') || 'All fields are required';
      return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    try {
      const action = id ? 'edit' : 'add';
      const body = { sub_action: action, platform: platform, url: url, icon: icon, sort_order: sort_order };
      if (id) body.id = parseInt(id);

      const res = await fetch('../backend/admin.php?action=social_links', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body)
      });
      const data = await res.json();

      if (data.success) {
        closeSocialModal();
        window.loadAdminSocialLinks();
      } else {
        throw new Error(data.error || 'Failed to save');
      }
    } catch (err) {
      errEl.textContent = err.message;
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-save"></i> ' + (window.t('admin.save') || 'Save');
    }
  };

  window.deleteSocialLink = async function(id) {
    showConfirm('Hapus Social Link', 'Yakin ingin menghapus social link ini?', async function() {
      try {
        const res = await fetch('../backend/admin.php?action=social_links', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ sub_action: 'delete', id: id })
        });
        const data = await res.json();
        if (data.success) {
          window.loadAdminSocialLinks();
        } else {
          showAlert('Gagal', data.error || 'Failed to delete', 'warning');
        }
      } catch (err) {
        showAlert('Error', 'Error deleting social link', 'warning');
      }
    }, 'Hapus');
  };

  // ---- FAQ CRUD ----
  let faqItemsList = [];

  window.loadAdminFaqItems = async function() {
    const tbody = document.getElementById('adminFaqBody');
    try {
      const res = await fetch('../backend/admin.php?action=faq_items');
      const data = await res.json();
      if (!data.success) throw new Error('Failed');
      faqItemsList = data.items || [];
      renderAdminFaqItems(faqItemsList);
    } catch (e) {
      tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:32px;color:var(--gray-400);">Failed to load FAQ items</td></tr>';
    }
  };

  function renderAdminFaqItems(items) {
    const tbody = document.getElementById('adminFaqBody');
    if (!items.length) {
      tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:32px;color:var(--gray-400);">' + (window.t('admin.no_data') || 'No data') + '</td></tr>';
      return;
    }
    tbody.innerHTML = items.map((f, i) => {
      const q = f.question.length > 60 ? f.question.substring(0, 60) + '...' : f.question;
      const a = f.answer.length > 100 ? f.answer.substring(0, 100) + '...' : f.answer;
      const active = f.is_active == 1;
      return `<tr>
        <td>${i + 1}</td>
        <td><strong>${q}</strong></td>
        <td style="color:var(--gray-500);font-size:13px;">${a}</td>
        <td>${f.sort_order}</td>
        <td style="text-align:center;">${active ? '<span style="color:#10B981;"><i class="fas fa-check-circle"></i></span>' : '<span style="color:#ef4444;"><i class="fas fa-times-circle"></i></span>'}</td>
        <td>
          <button class="admin-action-btn" onclick="adminEditFaq(${f.id})" style="margin-right:4px;"><i class="fas fa-edit"></i></button>
          <button class="admin-action-btn" onclick="deleteFaqItem(${f.id})" style="background:rgba(239,68,68,0.1);color:#ef4444;"><i class="fas fa-trash"></i></button>
        </td>
      </tr>`;
    }).join('');
  }

  window.openFaqModal = function() {
    document.getElementById('faqId').value = '';
    document.getElementById('faqQuestion').value = '';
    document.getElementById('faqAnswer').value = '';
    document.getElementById('faqSort').value = '0';
    document.getElementById('faqActive').checked = true;
    document.getElementById('faqModalTitle').textContent = window.t('admin.add_faq') || 'Add FAQ';
    document.getElementById('faqModal').classList.add('active');
    document.getElementById('faqError').textContent = '';
    document.body.style.overflow = 'hidden';
  };

  window.closeFaqModal = function() {
    document.getElementById('faqModal').classList.remove('active');
    document.body.style.overflow = '';
  };

  window.adminEditFaq = function(id) {
    const item = faqItemsList.find(f => f.id == id);
    if (!item) return;
    document.getElementById('faqId').value = item.id;
    document.getElementById('faqQuestion').value = item.question;
    document.getElementById('faqAnswer').value = item.answer;
    document.getElementById('faqSort').value = item.sort_order;
    document.getElementById('faqActive').checked = item.is_active == 1;
    document.getElementById('faqModalTitle').textContent = window.t('admin.edit_faq') || 'Edit FAQ';
    document.getElementById('faqModal').classList.add('active');
    document.getElementById('faqError').textContent = '';
    document.body.style.overflow = 'hidden';
  };

  window.saveFaqItem = async function() {
    const id = document.getElementById('faqId').value;
    const question = document.getElementById('faqQuestion').value.trim();
    const answer = document.getElementById('faqAnswer').value.trim();
    const sort_order = parseInt(document.getElementById('faqSort').value) || 0;
    const is_active = document.getElementById('faqActive').checked ? 1 : 0;
    const btn = document.querySelector('#faqModal .btn-generate');
    const errEl = document.getElementById('faqError');

    if (!question || !answer) {
      errEl.textContent = window.t('admin.required_fields') || 'Question and answer are required';
      return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    try {
      const action = id ? 'edit' : 'add';
      const body = { sub_action: action, question: question, answer: answer, sort_order: sort_order, is_active: is_active };
      if (id) body.id = parseInt(id);

      const res = await fetch('../backend/admin.php?action=faq_items', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body)
      });
      const data = await res.json();

      if (data.success) {
        closeFaqModal();
        window.loadAdminFaqItems();
      } else {
        throw new Error(data.error || 'Failed to save');
      }
    } catch (err) {
      errEl.textContent = err.message;
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-save"></i> ' + (window.t('admin.save') || 'Save');
    }
  };

  window.deleteFaqItem = async function(id) {
    showConfirm('Hapus FAQ', 'Yakin ingin menghapus FAQ ini?', async function() {
      try {
        const res = await fetch('../backend/admin.php?action=faq_items', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ sub_action: 'delete', id: id })
        });
        const data = await res.json();
        if (data.success) {
          window.loadAdminFaqItems();
        } else {
          showAlert('Gagal', data.error || 'Failed to delete', 'warning');
        }
      } catch (err) {
        showAlert('Error', 'Error deleting FAQ item', 'warning');
      }
    }, 'Hapus');
  };

  function extractYoutubeId(url) {
    var m = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
    return m ? m[1] : '';
  }

  window.previewTutorialVideo = function() {
    var url = document.getElementById('tutorialVideoUrl').value.trim();
    var preview = document.getElementById('tutorialVideoPreview');
    var iframe = preview.querySelector('iframe');
    var id = extractYoutubeId(url);
    if (id) {
      iframe.src = 'https://www.youtube.com/embed/' + id;
      preview.style.display = 'block';
    } else {
      preview.style.display = 'none';
      iframe.src = '';
    }
  };

  window.loadAdminTutorials = async function() {
    const tbody = document.getElementById('adminTutorialsBody');
    try {
      const res = await fetch('../backend/admin.php?action=tutorials');
      const data = await res.json();
      if (!data.success) throw new Error('Failed');
      tutorialList = data.items || [];
      renderAdminTutorials(tutorialList);
    } catch (e) {
      tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:32px;color:var(--gray-400);">Failed to load tutorials</td></tr>';
    }
  };

  function renderAdminTutorials(items) {
    const tbody = document.getElementById('adminTutorialsBody');
    if (!items.length) {
      tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:32px;color:var(--gray-400);">No tutorials yet</td></tr>';
      return;
    }
    tbody.innerHTML = items.map(function(t, i) {
      var activeIcon = t.is_active == 1 ? '<i class="fas fa-check-circle" style="color:#10B981;"></i>' : '<i class="fas fa-times-circle" style="color:#ef4444;"></i>';
      return '<tr>' +
        '<td>' + (i + 1) + '</td>' +
        '<td><strong>' + t.title + '</strong></td>' +
        '<td style="font-size:12px;color:var(--gray-400);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + t.video_url + '</td>' +
        '<td>' + t.sort_order + '</td>' +
        '<td>' + activeIcon + '</td>' +
        '<td>' +
          '<button class="admin-action-btn" onclick="adminEditTutorial(' + t.id + ')" style="margin-right:4px;"><i class="fas fa-edit"></i></button>' +
          '<button class="admin-action-btn" onclick="deleteTutorial(' + t.id + ')" style="background:rgba(239,68,68,0.1);color:#ef4444;"><i class="fas fa-trash"></i></button>' +
        '</td></tr>';
    }).join('');
  }

  window.openTutorialModal = function() {
    document.getElementById('tutorialId').value = '';
    document.getElementById('tutorialTitle').value = '';
    document.getElementById('tutorialDescription').value = '';
    document.getElementById('tutorialVideoUrl').value = '';
    document.getElementById('tutorialSort').value = '0';
    document.getElementById('tutorialActive').checked = true;
    document.getElementById('tutorialVideoPreview').style.display = 'none';
    document.getElementById('tutorialVideoPreview').querySelector('iframe').src = '';
    document.getElementById('tutorialModalTitle').textContent = 'Add Tutorial';
    document.getElementById('tutorialModal').classList.add('active');
    document.getElementById('tutorialError').textContent = '';
    document.body.style.overflow = 'hidden';
  };

  window.closeTutorialModal = function() {
    document.getElementById('tutorialModal').classList.remove('active');
    document.body.style.overflow = '';
  };

  window.adminEditTutorial = function(id) {
    const t = tutorialList.find(function(item) { return item.id == id; });
    if (!t) return;
    document.getElementById('tutorialId').value = t.id;
    document.getElementById('tutorialTitle').value = t.title;
    document.getElementById('tutorialDescription').value = t.description || '';
    document.getElementById('tutorialVideoUrl').value = t.video_url || '';
    document.getElementById('tutorialSort').value = t.sort_order || 0;
    document.getElementById('tutorialActive').checked = t.is_active == 1;
    document.getElementById('tutorialModalTitle').textContent = 'Edit Tutorial';
    document.getElementById('tutorialModal').classList.add('active');
    document.getElementById('tutorialError').textContent = '';
    document.body.style.overflow = 'hidden';
    window.previewTutorialVideo();
  };

  window.saveTutorial = async function() {
    const id = document.getElementById('tutorialId').value;
    const title = document.getElementById('tutorialTitle').value.trim();
    const description = document.getElementById('tutorialDescription').value.trim();
    const videoUrl = document.getElementById('tutorialVideoUrl').value.trim();
    const sortOrder = parseInt(document.getElementById('tutorialSort').value) || 0;
    const isActive = document.getElementById('tutorialActive').checked ? 1 : 0;
    const btn = document.querySelector('#tutorialModal .btn-generate');
    const errEl = document.getElementById('tutorialError');

    if (!title) { errEl.textContent = 'Judul harus diisi'; return; }
    if (!videoUrl) { errEl.textContent = 'URL video harus diisi'; return; }
    if (!extractYoutubeId(videoUrl)) { errEl.textContent = 'URL YouTube tidak valid'; return; }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    try {
      const action = id ? 'edit' : 'add';
      const body = { sub_action: action, title: title, description: description, video_url: videoUrl, sort_order: sortOrder, is_active: isActive };
      if (id) body.id = parseInt(id);

      const res = await fetch('../backend/admin.php?action=tutorials', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body)
      });
      const data = await res.json();

      if (data.success) {
        closeTutorialModal();
        window.loadAdminTutorials();
      } else {
        throw new Error(data.error || 'Failed to save');
      }
    } catch (err) {
      errEl.textContent = err.message;
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-save"></i> Save';
    }
  };

  window.deleteTutorial = async function(id) {
    showConfirm('Hapus Tutorial', 'Yakin ingin menghapus tutorial ini?', async function() {
      try {
        const res = await fetch('../backend/admin.php?action=tutorials', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ sub_action: 'delete', id: id })
        });
        const data = await res.json();
        if (data.success) {
          window.loadAdminTutorials();
        } else {
          showAlert('Gagal', data.error || 'Failed to delete', 'warning');
        }
      } catch (err) {
        showAlert('Error', 'Error deleting tutorial', 'warning');
      }
    }, 'Hapus');
  };

  // ---- Landing Page Settings ----
  let landingSettings = {};
  let howItWorksList = [];

  window.previewLandingVideo = function() {
    var url = document.getElementById('lndVideoUrl').value.trim();
    var preview = document.getElementById('lndVideoPreview');
    var iframe = preview.querySelector('iframe');
    var id = extractYoutubeId(url);
    if (id) {
      iframe.src = 'https://www.youtube.com/embed/' + id;
      preview.style.display = 'block';
    } else {
      preview.style.display = 'none';
      iframe.src = '';
    }
  };

  window.loadAdminLanding = async function() {
    try {
      const res = await fetch('../backend/admin.php?action=landing_settings&sub=all');
      const data = await res.json();
      if (!data.success) throw new Error('Failed');
      landingSettings = data.settings || {};
      howItWorksList = data.how_it_works || [];

      document.getElementById('lndHeroTitle').value = landingSettings.hero_title || '';
      document.getElementById('lndHeroSubtitle').value = landingSettings.hero_subtitle || '';
      document.getElementById('lndHeroImage').value = landingSettings.hero_image_url || '';
      document.getElementById('lndStatSoal').value = landingSettings.hero_stat_soal || '';
      document.getElementById('lndStatGuru').value = landingSettings.hero_stat_guru || '';
      document.getElementById('lndVideoUrl').value = landingSettings.hero_video_url || '';

      if (landingSettings.hero_video_url) {
        window.previewLandingVideo();
      }

      renderHowItWorksTable(howItWorksList);
    } catch (e) {
      console.warn('Admin landing error:', e);
      document.getElementById('adminHowItWorksBody').innerHTML =
        '<tr><td colspan="5" style="text-align:center;padding:32px;color:var(--gray-400);">Failed to load landing data</td></tr>';
    }
  };

  function renderHowItWorksTable(items) {
    const tbody = document.getElementById('adminHowItWorksBody');
    if (!items.length) {
      tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:32px;color:var(--gray-400);">No items yet</td></tr>';
      return;
    }
    tbody.innerHTML = items.map(function(item, i) {
      var desc = item.description.length > 80 ? item.description.substring(0, 80) + '...' : item.description;
      return '<tr>' +
        '<td>' + (i + 1) + '</td>' +
        '<td><strong>' + item.step_number + '</strong></td>' +
        '<td><strong>' + item.title + '</strong></td>' +
        '<td style="color:var(--gray-500);font-size:13px;">' + desc + '</td>' +
        '<td>' +
          '<button class="admin-action-btn" onclick="adminEditHowItWorks(' + item.id + ')" style="margin-right:4px;"><i class="fas fa-edit"></i></button>' +
          '<button class="admin-action-btn" onclick="deleteHowItWorksItem(' + item.id + ')" style="background:rgba(239,68,68,0.1);color:#ef4444;"><i class="fas fa-trash"></i></button>' +
        '</td></tr>';
    }).join('');
  }

  window.saveLandingSettings = async function() {
    const settings = {
      hero_title: document.getElementById('lndHeroTitle').value.trim(),
      hero_subtitle: document.getElementById('lndHeroSubtitle').value.trim(),
      hero_image_url: document.getElementById('lndHeroImage').value.trim(),
      hero_stat_soal: document.getElementById('lndStatSoal').value.trim(),
      hero_stat_guru: document.getElementById('lndStatGuru').value.trim(),
      hero_video_url: document.getElementById('lndVideoUrl').value.trim()
    };
    const btn = document.querySelector('#admin-panel-landing .btn-generate');
    const errEl = document.getElementById('lndSettingsError');

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    try {
      const res = await fetch('../backend/admin.php?action=landing_settings', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ sub_action: 'update_settings', settings: settings })
      });
      const data = await res.json();
      if (data.success) {
        showAlert('Berhasil', 'Pengaturan landing page berhasil disimpan', 'success');
      } else {
        throw new Error(data.error || 'Failed to save');
      }
    } catch (err) {
      errEl.textContent = err.message;
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-save"></i> Simpan Semua Pengaturan';
    }
  };

  window.openHowItWorksModal = function() {
    document.getElementById('howItWorksId').value = '';
    document.getElementById('howItWorksStep').value = howItWorksList.length + 1;
    document.getElementById('howItWorksTitle').value = '';
    document.getElementById('howItWorksDesc').value = '';
    document.getElementById('howItWorksModalTitle').textContent = 'Add Cara Kerja Item';
    document.getElementById('howItWorksModal').classList.add('active');
    document.getElementById('howItWorksError').textContent = '';
    document.body.style.overflow = 'hidden';
  };

  window.closeHowItWorksModal = function() {
    document.getElementById('howItWorksModal').classList.remove('active');
    document.body.style.overflow = '';
  };

  window.adminEditHowItWorks = function(id) {
    const item = howItWorksList.find(function(i) { return i.id == id; });
    if (!item) return;
    document.getElementById('howItWorksId').value = item.id;
    document.getElementById('howItWorksStep').value = item.step_number;
    document.getElementById('howItWorksTitle').value = item.title;
    document.getElementById('howItWorksDesc').value = item.description;
    document.getElementById('howItWorksModalTitle').textContent = 'Edit Cara Kerja Item';
    document.getElementById('howItWorksModal').classList.add('active');
    document.getElementById('howItWorksError').textContent = '';
    document.body.style.overflow = 'hidden';
  };

  window.saveHowItWorksItem = async function() {
    const id = document.getElementById('howItWorksId').value;
    const stepNumber = parseInt(document.getElementById('howItWorksStep').value) || 0;
    const title = document.getElementById('howItWorksTitle').value.trim();
    const description = document.getElementById('howItWorksDesc').value.trim();
    const btn = document.querySelector('#howItWorksModal .btn-generate');
    const errEl = document.getElementById('howItWorksError');

    if (!stepNumber || !title || !description) {
      errEl.textContent = 'Step number, title, and description are required';
      return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    try {
      const action = id ? 'edit_how_it_works' : 'add_how_it_works';
      const body = { sub_action: action, step_number: stepNumber, title: title, description: description };
      if (id) body.id = parseInt(id);

      const res = await fetch('../backend/admin.php?action=landing_settings', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body)
      });
      const data = await res.json();

      if (data.success) {
        closeHowItWorksModal();
        window.loadAdminLanding();
      } else {
        throw new Error(data.error || 'Failed to save');
      }
    } catch (err) {
      errEl.textContent = err.message;
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-save"></i> Save';
    }
  };

  window.deleteHowItWorksItem = async function(id) {
    showConfirm('Hapus Item', 'Yakin ingin menghapus item cara kerja ini?', async function() {
      try {
        const res = await fetch('../backend/admin.php?action=landing_settings', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ sub_action: 'delete_how_it_works', id: id })
        });
        const data = await res.json();
        if (data.success) {
          window.loadAdminLanding();
        } else {
          showAlert('Gagal', data.error || 'Failed to delete', 'warning');
        }
      } catch (err) {
        showAlert('Error', 'Error deleting item', 'warning');
      }
    }, 'Hapus');
  };

  window.closeAdminSidebar = function() {
    const sidebar = document.getElementById('admin-sidebar');
    const overlay = document.getElementById('admin-sidebar-overlay');
    if (sidebar && sidebar.classList.contains('active')) {
      sidebar.classList.remove('active');
      overlay.classList.remove('active');
    }
  };

  window.toggleAdminSidebar = function() {
    const sidebar = document.getElementById('admin-sidebar');
    const overlay = document.getElementById('admin-sidebar-overlay');
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
  };

  window.openManualTopup = function(userId, userEmail, userName) {
    document.getElementById('manualTopupUserId').value = userId;
    document.getElementById('manualTopupUserInfo').textContent = userName + ' (' + userEmail + ')';
    document.getElementById('manualTopupModal').classList.add('active');
    document.body.style.overflow = 'hidden';
  };

  window.closeManualTopupModal = function() {
    document.getElementById('manualTopupModal').classList.remove('active');
    document.body.style.overflow = '';
  };

  window.confirmManualTopup = async function() {
    const userId = document.getElementById('manualTopupUserId').value;
    const credits = document.getElementById('manualTopupCredits').value;
    const btn = document.querySelector('#manualTopupModal .btn-generate');

    if (!credits || credits < 1) {
      showAlert('Input Tidak Valid', 'Jumlah credits tidak valid', 'warning');
      return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + window.t('admin.processing');

    try {
      const response = await fetch('../backend/admin.php?action=manual_topup', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ user_id: userId, credits: parseInt(credits) })
      });
      const data = await response.json();

      if (data.success) {
        showAlert('Berhasil', 'Top-up berhasil!', 'success');
        closeManualTopupModal();
        window.loadAdminUsers();
      } else {
        throw new Error(data.error || 'Failed to add credits');
      }
    } catch (err) {
      showAlert('Gagal', err.message || 'Failed to process top-up', 'warning');
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-plus-circle"></i> ' + window.t('admin.topup_add');
    }
  };

  window.switchAdminPanel = function(panel) {
    document.querySelectorAll('.admin-nav-link').forEach(l => l.classList.toggle('active', l.dataset.admin === panel));
    document.querySelectorAll('.admin-panel').forEach(p => p.classList.toggle('active', p.id === 'admin-panel-' + panel));
    if (panel === 'dashboard') window.loadAdminDashboard();
    if (panel === 'users') window.loadAdminUsers();
    if (panel === 'transactions') window.loadAdminTransactions();
    if (panel === 'reports') window.loadAdminReports();
    if (panel === 'subjects') window.loadAdminSubjects();
    if (panel === 'social') window.loadAdminSocialLinks();
    if (panel === 'faq') window.loadAdminFaqItems();
    if (panel === 'tutorials') window.loadAdminTutorials();
    if (panel === 'landing') window.loadAdminLanding();
    closeAdminSidebar();
  };

})();
</script>
