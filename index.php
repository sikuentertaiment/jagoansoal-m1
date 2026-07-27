<?php
require_once __DIR__ . '/backend/env.php';

// ============================================================
// SEO: Route Detection (supports /?page=xxx and /path)
// ============================================================
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestPath = rtrim($requestPath, '/') ?: '/';
$queryPage = trim($_GET['page'] ?? '');

$seoRoutes = [
  '/'         => ['title' => 'jagoansoal — Buat Soal dalam Hitungan Detik',       'desc' => 'Platform AI untuk membuat soal ujian secara otomatis. Cukup masukkan materi atau topik, AI akan menyusun soal lengkap dengan kunci jawaban. Gratis untuk guru Indonesia!',                                                                                                                                                             'page' => 'landing'],
  '/about'    => ['title' => 'Tentang Kami - jagoansoal',                          'desc' => 'Pelajari lebih lanjut tentang jagoansoal, platform AI untuk membantu guru Indonesia membuat soal ujian berkualitas dengan cepat dan mudah.',                                                                                                                                                                                      'page' => 'about'],
  '/contact'  => ['title' => 'Hubungi Kami - jagoansoal',                          'desc' => 'Hubungi tim jagoansoal untuk pertanyaan, saran, atau bantuan terkait platform pembuatan soal ujian berbasis AI.',                                                                                                                                                                                                               'page' => 'contact'],
  '/privacy'  => ['title' => 'Kebijakan Privasi - jagoansoal',                     'desc' => 'Kebijakan privasi jagoansoal. Pelajari bagaimana kami melindungi, mengelola, dan menjaga keamanan data pribadi Anda saat menggunakan platform pembuatan soal AI.',                                                                                                                                                               'page' => 'privacy'],
  '/terms'    => ['title' => 'Syarat & Ketentuan - jagoansoal',                    'desc' => 'Syarat dan ketentuan penggunaan platform jagoansoal. Baca aturan dan kebijakan sebelum menggunakan layanan pembuatan soal ujian berbasis AI.',                                                                                                                                                                                   'page' => 'terms'],
  '/login'    => ['title' => 'Masuk - jagoansoal',                                 'desc' => 'Masuk ke akun jagoansoal menggunakan Google untuk mulai membuat soal ujian dengan AI secara otomatis.',                                                                                                                                                                                                                       'page' => 'login'],
  '/register' => ['title' => 'Daftar Gratis - jagoansoal',                          'desc' => 'Daftar akun jagoansoal gratis dan dapatkan 3 kredit untuk mulai membuat soal ujian dengan teknologi AI. Tanpa biaya, tanpa risiko.',                                                                                                                                                                                          'page' => 'register'],
  '/app'      => ['title' => 'Dashboard - jagoansoal',                             'desc' => 'Buat, kelola, dan export soal ujian dengan AI di dashboard jagoansoal. Akses bank soal, materi, dan statistik akun Anda.',                                                                                                                                                                                                       'page' => 'tools'],
  '/tools'    => ['title' => 'Dashboard - jagoansoal',                             'desc' => 'Buat, kelola, dan export soal ujian dengan AI di dashboard jagoansoal. Akses bank soal, materi, dan statistik akun Anda.',                                                                                                                                                                                                       'page' => 'tools'],
];

// Detect route from query param first, then path
$currentRoute = $seoRoutes['/'];
$matchedSlug = '';

if ($queryPage) {
  $basePage = explode('-', $queryPage)[0];
  $routeKey = '/' . $basePage;
  if (isset($seoRoutes[$routeKey])) {
    $currentRoute = $seoRoutes[$routeKey];
    $matchedSlug = $queryPage;
  }
} elseif (isset($seoRoutes[$requestPath])) {
  $currentRoute = $seoRoutes[$requestPath];
  $matchedSlug = $currentRoute['page'];
} elseif ($requestPath !== '/') {
  foreach (['/about', '/contact', '/privacy', '/terms', '/login', '/register', '/app', '/tools'] as $p) {
    if (strpos($requestPath, $p) === 0) { $currentRoute = $seoRoutes[$p]; $matchedSlug = $currentRoute['page']; break; }
  }
}

$pageTitle       = $currentRoute['title'];
$pageDescription = $currentRoute['desc'];
$pageSlug        = $currentRoute['page'];
$appUrl          = rtrim(env('APP_URL', 'http://localhost:8000'), '/');
$canonicalUrl    = $matchedSlug && $matchedSlug !== 'landing' ? $appUrl . '/?page=' . $matchedSlug : $appUrl . '/';
$ogImage         = $appUrl . '/public/assets/app/og-image.png';
$isLanding       = $pageSlug === 'landing';

// Fetch social links, FAQ, tutorials & landing settings from DB for landing page
$socialLinks = [];
$faqItems = [];
$tutorials = [];
$landingSettings = [];
$howItWorks = [];
if ($isLanding) {
    require_once __DIR__ . '/backend/config.php';
    $pdo = getDbConnection();
    if ($pdo) {
        try {
            $stmt = $pdo->query('SELECT * FROM social_links ORDER BY sort_order ASC');
            $socialLinks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt = $pdo->query('SELECT * FROM faq_items WHERE is_active = 1 ORDER BY sort_order ASC');
            $faqItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt = $pdo->query('SELECT * FROM tutorials WHERE is_active = 1 ORDER BY sort_order ASC');
            $tutorials = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt = $pdo->query('SELECT * FROM landing_settings');
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $landingSettings[$row['key']] = $row['value'];
            }
            $stmt = $pdo->query('SELECT * FROM how_it_works ORDER BY step_number ASC');
            $howItWorks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log('Landing page data fetch error: ' . $e->getMessage());
        }
    }
}
?><!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">

  <!-- Canonical URL -->
  <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>">

  <!-- Open Graph -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="jagoansoal">
  <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">
  <meta property="og:url" content="<?= htmlspecialchars($canonicalUrl) ?>">
  <meta property="og:image" content="<?= htmlspecialchars($ogImage) ?>">
  <meta property="og:locale" content="id_ID">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle) ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($pageDescription) ?>">
  <meta name="twitter:image" content="<?= htmlspecialchars($ogImage) ?>">

  <!-- JSON-LD: WebSite -->
  <script type="application/ld+json">{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "jagoansoal",
  "url": "<?= htmlspecialchars($appUrl) ?>",
  "description": "Platform AI untuk membuat soal ujian bagi guru Indonesia.",
  "inLanguage": "id-ID",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "<?= htmlspecialchars($appUrl) ?>/?q={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}</script>

  <!-- JSON-LD: Organization -->
  <script type="application/ld+json">{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "jagoansoal",
  "url": "<?= htmlspecialchars($appUrl) ?>",
  "logo": "<?= htmlspecialchars($ogImage) ?>",
  "description": "Platform AI pembuat soal ujian untuk guru Indonesia.",
  "foundingDate": "2025",
  "founder": { "@type": "Person", "name": "Rahmat Agem Pratama" }
}</script>

  <!-- JSON-LD: SoftwareApplication -->
  <script type="application/ld+json">{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "jagoansoal",
  "operatingSystem": "Web",
  "applicationCategory": "EducationalApplication",
  "description": "Generate soal ujian dengan AI dalam hitungan detik. Cukup masukkan materi atau topik, AI menyusun soal lengkap dengan kunci jawaban.",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "IDR"
  }
}</script>

<?php if ($isLanding && !empty($faqItems)): ?>
  <!-- JSON-LD: FAQPage -->
  <script type="application/ld+json">{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    <?php foreach ($faqItems as $i => $faq): ?>
    { "@type": "Question", "name": <?= json_encode($faq['question']) ?>, "acceptedAnswer": { "@type": "Answer", "text": <?= json_encode($faq['answer']) ?> } }<?= $i < count($faqItems) - 1 ? ',' : '' ?>
    <?php endforeach; ?>
  ]
}</script>
<?php endif; ?>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="icon" href="/public/assets/app/favicon.png">

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
            heading: ['Plus Jakarta Sans', 'sans-serif'],
          },
          colors: {
            primary: '#2563EB',
            secondary: '#10B981',
            accent: '#F59E0B',
            dark: '#0F172A',
          }
        }
      }
    }
  </script>

  <!-- Custom CSS -->
  <?php include __DIR__ . '/public/frontend/scripts/css/style.php'; ?>

  <!-- Firebase SDK -->
  <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-auth.js"></script>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

  <!-- Google Identity Services (OAuth2 token refresh) -->
  <script src="https://accounts.google.com/gsi/client"></script>

  <script>
    const firebaseConfig = {
      apiKey: "<?= env('FIREBASE_API_KEY') ?>",
      authDomain: "<?= env('FIREBASE_AUTH_DOMAIN') ?>",
      projectId: "<?= env('FIREBASE_PROJECT_ID') ?>",
      storageBucket: "<?= env('FIREBASE_STORAGE_BUCKET') ?>",
      messagingSenderId: "<?= env('FIREBASE_MESSAGING_SENDER_ID') ?>",
      appId: "<?= env('FIREBASE_APP_ID') ?>"
    };

    firebase.initializeApp(firebaseConfig);
    const firebaseAuthProvider = new firebase.auth.GoogleAuthProvider();
    firebaseAuthProvider.addScope('https://www.googleapis.com/auth/forms.body');
  </script>

  <!-- GIS Token Management -->
  <script>
    const __GOOGLE_CLIENT_ID = '<?= addslashes(env('GOOGLE_CLIENT_ID', '')) ?>';
    let __gisTokenClient = null;
    let __gisResolve = null;
    let __gisReject = null;

    function initGIS() {
      if (!__GOOGLE_CLIENT_ID || typeof google === 'undefined' || !google.accounts) return;
      __gisTokenClient = google.accounts.oauth2.initTokenClient({
        client_id: __GOOGLE_CLIENT_ID,
        scope: 'https://www.googleapis.com/auth/forms.body',
        callback: function (response) {
          if (response.access_token) {
            var expiresIn = (response.expires_in || 3600) * 1000;
            localStorage.setItem(btoa('jsat'), response.access_token);
            localStorage.setItem(btoa('jsat_expiry'), String(Date.now() + expiresIn));
            if (__gisResolve) { __gisResolve(response.access_token); __gisResolve = null; }
          } else {
            if (__gisReject) { __gisReject(response.error || 'GIS failed'); __gisReject = null; }
          }
        }
      });
    }

    if (__GOOGLE_CLIENT_ID) {
      var gsiScript = document.querySelector('script[src*="gsi/client"]');
      if (gsiScript) gsiScript.onload = initGIS;
      else initGIS();
    }

    async function __refreshViaFirebase() {
      var user = firebase.auth().currentUser;
      if (!user) throw new Error('Not logged in');
      var result = await user.reauthenticateWithPopup(firebaseAuthProvider);
      var token = result.credential.accessToken;
      localStorage.setItem(btoa('jsat'), token);
      localStorage.setItem(btoa('jsat_expiry'), String(Date.now() + 55 * 60 * 1000));
      return token;
    }

    window.getGoogleAccessToken = function () {
      return new Promise(function (resolve, reject) {
        var stored = localStorage.getItem(btoa('jsat'));
        var expiry = localStorage.getItem(btoa('jsat_expiry'));

        if (stored && expiry && Date.now() < parseInt(expiry)) {
          resolve(stored);
          return;
        }

        if (__gisTokenClient) {
          __gisResolve = resolve;
          __gisReject = reject;
          try {
            __gisTokenClient.requestAccessToken({ prompt: '' });
          } catch (e) {
            __gisResolve = null;
            __gisReject = null;
            __refreshViaFirebase().then(resolve).catch(reject);
          }
        } else {
          __refreshViaFirebase().then(resolve).catch(reject);
        }
      });
    };
  </script>
  <style>
    * {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    *::-webkit-scrollbar {
      display: none;
    }
    body.landing .sidebar-toggle {
      display: none;
    }
  </style>
  <!-- Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-0S3TRHLSZ0"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-0S3TRHLSZ0');
  </script>
</head>
<body class="landing">
  <!-- Landing Page Navbar -->
  <nav class="landing-navbar" id="landingNavbar">
    <div class="landing-navbar-container px-4 md:px-0">
      <a href="#landing" class="landing-navbar-brand">
        <!-- <span class="text-xl font-heading font-bold text-primary">jagoansoal</span> -->
        <img src="/public/assets/app/icon.png" alt="jagoansoal" class="w-[154px] object-cover">
      </a>
      <button class="landing-navbar-toggle" onclick="toggleLandingMenu(this)" id="landing-bar-open">
        <i class="fas fa-bars"></i>
      </button>
      <button class="landing-navbar-toggle" onclick="toggleLandingMenu(this)" id="landing-bar-close" style="display:none">
        <i class="fas fa-x"></i>
      </button>
      <div class="landing-navbar-menu" id="landingNavbarMenu">
        <a href="#landing" class="landing-navbar-link active">Beranda</a>
        <a href="#howitworks" class="landing-navbar-link">Cara Kerja</a>
        <a href="#features" class="landing-navbar-link">Fitur</a>
        <a href="#testimonials" class="landing-navbar-link">Testimoni</a>
        <a href="#pricing" class="landing-navbar-link">Harga</a>
        <a href="#tutorial" class="landing-navbar-link">Tutorial</a>
        <a href="#faq" class="landing-navbar-link">FAQ</a>
      </div>
    </div>
  </nav>

  <!-- App Navbar (hidden on landing) -->
  <nav class="navbar py-0 md:py-4 hidden bg-white" id="appNavbar">
    <div class="navbar-container max-w-6xl px-3 md:px-0">
      <button class="sidebar-toggle" id="nav-sidebar-toggle" onclick="toggleAppSidebar()">
        <i class="fas fa-bars"></i>
      </button>
      <a href="/#landing" class="navbar-brand w-[200px] md:min-w-[200px] justify-start md:justify-center">
        <!-- <span class="text-xl font-heading font-bold text-primary">jagoansoal</span> -->
        <img src="/public/assets/app/icon.png" alt="jagoansoal" class="h-[48px] object-contain">
      </a>
      <div class="w-full flex gap-1 justify-end items-center">
        <a href="#admin-dashboard" class="tools-nav-link admin-link rounded-full" id="adminNavLink" style="display:none;">
          <i class="fas fa-shield-alt"></i>
        </a>
        <div class="navbar-credit rounded-full" id="navbarCredit" data-lang-title="tools.account.credits">
          <i class="fas fa-coins"></i>
          <span id="navbarCreditCount">0</span>
        </div>
        <a href="#tools-logout" class="tools-nav-link rounded-full" data-tool="logout">
          <i class="fas fa-sign-out"></i>
        </a>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main id="main-content">
    <?php include __DIR__ . '/public/frontend/pages/landing.php'; ?>
    <?php include __DIR__ . '/public/frontend/pages/login.php'; ?>
    <?php include __DIR__ . '/public/frontend/pages/register.php'; ?>
    <?php include __DIR__ . '/public/frontend/pages/tools.php'; ?>
    <?php include __DIR__ . '/public/frontend/pages/admin.php'; ?>
    <?php include __DIR__ . '/public/frontend/pages/about.php'; ?>
    <?php include __DIR__ . '/public/frontend/pages/contact.php'; ?>
    <?php include __DIR__ . '/public/frontend/pages/privacy.php'; ?>
    <?php include __DIR__ . '/public/frontend/pages/terms.php'; ?>
    <?php include __DIR__ . '/public/frontend/pages/404.php'; ?>
  </main>

  <!-- Scripts -->
  <?php include __DIR__ . '/public/frontend/scripts/js/lang.php'; ?>
  <?php include __DIR__ . '/public/frontend/scripts/js/sidebar.php'; ?>
  <?php include __DIR__ . '/public/frontend/scripts/js/router.php'; ?>

  <!-- Global Modal -->
  <div id="globalModal" class="modal-overlay">
    <div class="modal-box">
      <div class="modal-icon" id="modalIcon"></div>
      <h3 class="modal-title" id="modalTitle"></h3>
      <p class="modal-message" id="modalMessage"></p>
      <div class="modal-actions" id="modalActions"></div>
    </div>
  </div>

  <script>
    (function() {
      var params = new URLSearchParams(window.location.search);
      var topupStatus = params.get('topup_status');
      var orderId = params.get('order_id');
      if (topupStatus && orderId) {
        window.history.replaceState({}, document.title, '/');
        sessionStorage.setItem('topup_order_id', orderId);
        navigate('tools-account');
      }
    })();
  </script>

  <script>
    window.showModal = function(options) {
      const modal = document.getElementById('globalModal');
      const icon = document.getElementById('modalIcon');
      const title = document.getElementById('modalTitle');
      const message = document.getElementById('modalMessage');
      const actions = document.getElementById('modalActions');

      icon.className = 'modal-icon ' + (options.icon || 'info');
      icon.innerHTML = options.iconHtml || '';
      title.textContent = options.title || '';
      message.textContent = options.message || '';
      actions.innerHTML = '';

      if (options.buttons) {
        options.buttons.forEach(btn => {
          const button = document.createElement('button');
          button.className = 'modal-btn ' + (btn.class || 'modal-btn-confirm');
          button.textContent = btn.text || 'OK';
          button.onclick = function() {
            if (btn.onclick) btn.onclick();
            if (btn.closeOnClick !== false) closeModal();
          };
          if (btn.loading) {
            button.classList.add('modal-btn-loading');
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + btn.text;
          }
          actions.appendChild(button);
        });
      }

      modal.classList.add('active');
      document.body.style.overflow = 'hidden';
    };

    window.closeModal = function() {
      const modal = document.getElementById('globalModal');
      modal.classList.remove('active');
      document.body.style.overflow = '';
    };

    window.showAlert = function(title, message, icon, callback) {
      showModal({
        icon: icon || 'info',
        iconHtml: icon === 'warning' ? '<i class="fas fa-exclamation-triangle"></i>' : icon === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-info-circle"></i>',
        title: title,
        message: message,
        buttons: [{
          text: 'OK',
          class: 'modal-btn-confirm',
          closeOnClick: true,
          onclick: callback || null
        }]
      });
    };

    window.showConfirm = function(title, message, onConfirm, confirmText) {
      showModal({
        icon: 'warning',
        iconHtml: '<i class="fas fa-exclamation-triangle"></i>',
        title: title,
        message: message,
        buttons: [
          { text: 'Batal', class: 'modal-btn-cancel' },
          { text: confirmText || 'Hapus', class: 'modal-btn-danger', onclick: onConfirm }
        ]
      });
    };

    document.getElementById('globalModal').addEventListener('click', function(e) {
      if (e.target === this) closeModal();
    });

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        const modal = document.getElementById('globalModal');
        if (modal.classList.contains('active')) closeModal();
      }
    });
  </script>
</body>
</html>
