<script>
const pages = ['landing', 'login', 'register', 'tools', 'admin', 'about', 'contact', 'privacy', 'terms', 'notfound'];
const toolsPages = ['generate', 'describe', 'questions', 'materials', 'subjects', 'classes', 'account', 'tutorial', 'report', 'logout'];
const adminPages = ['dashboard', 'users', 'transactions', 'reports', 'subjects', 'social', 'faq', 'tutorials', 'landing'];
const protectedPages = ['tools', 'admin'];
const guestOnlyPages = ['login', 'register'];
const noNavPages = ['login', 'register', 'about', 'contact', 'privacy', 'terms'];

function checkAuth() {
  return window.currentUser !== null && window.currentUser !== undefined;
}

function getPageFromUrl() {
  var params = new URLSearchParams(window.location.search);
  var page = params.get('page') || '';

  if (!page && window.location.hash) {
    page = window.location.hash.replace('#', '');
  }

  return page || 'landing';
}

function navigate(page, isFirstLoad) {
  var tool = null;
  var cleanPage = page;

  if ((page === 'pricing' || page === 'faq') && !page.startsWith('admin-')) {
    cleanPage = 'landing';
  }

  if (cleanPage.includes('-')) {
    var parts = cleanPage.split('-');
    if (parts[0] === 'tools' && toolsPages.includes(parts[1])) {
      cleanPage = 'tools';
      tool = parts[1];
    }
    if (parts[0] === 'admin' && adminPages.includes(parts[1])) {
      cleanPage = 'admin';
      tool = parts[1];
    }
  }

  if (!pages.includes(cleanPage)) {
    navigate('notfound');
    return;
  }

  if (protectedPages.includes(cleanPage) && !checkAuth()) {
    navigate('login');
    return;
  }

  if (cleanPage === 'admin' && !window.isAdmin) {
    navigate('landing');
    return;
  }

  if (guestOnlyPages.includes(cleanPage) && checkAuth()) {
    navigate('tools');
    return;
  }

  var menu = document.getElementById('landingNavbarMenu');
  if (menu) menu.classList.remove('open');
  var landing_bar_open = document.getElementById('landing-bar-open');
  var landing_bar_close = document.getElementById('landing-bar-close');
  if (landing_bar_open) landing_bar_open.style.display = '';
  if (landing_bar_close) landing_bar_close.style.display = 'none';
  document.querySelectorAll('.landing-navbar-link').forEach(function(l) { l.classList.remove('active'); });

  var currentPage = document.querySelector('.page.active');
  var newPage = document.getElementById('page-' + cleanPage);

  if (cleanPage === 'admin' && currentPage && currentPage.id === 'page-admin') {
    if (newPage) newPage.classList.add('active');
    var adminTool = tool || 'dashboard';
    if (window.switchAdminPanel) window.switchAdminPanel(adminTool);
  } else if (cleanPage === 'tools' && currentPage && currentPage.id === 'page-tools') {
    if (newPage) newPage.classList.add('active');
    if (!tool) tool = 'generate';
    if (window.switchToolPanel) window.switchToolPanel(tool, isFirstLoad);
  } else {
    if (currentPage) {
      currentPage.classList.remove('active');
      currentPage.classList.add('exiting');
      setTimeout(function() { if (currentPage) currentPage.classList.remove('exiting'); }, 300);
    }
    if (newPage) {
      setTimeout(function() {
        newPage.classList.add('active');
        newPage.classList.add('entering');
        setTimeout(function() { newPage.classList.remove('entering'); }, 400);
      }, 150);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    if (cleanPage === 'tools') {
      if (!tool) tool = 'generate';
      if (window.switchToolPanel) window.switchToolPanel(tool, isFirstLoad);
    }
    if (cleanPage === 'admin') {
      var adminTool = tool || 'dashboard';
      if (window.switchAdminPanel) window.switchAdminPanel(adminTool);
    }
  }

  var landingNavbar = document.getElementById('landingNavbar');
  var appNavbar = document.getElementById('appNavbar');

  if (cleanPage === 'landing' || cleanPage === 'notfound') {
    document.body.classList.add('landing');
    if (landingNavbar) landingNavbar.classList.add('visible');
    if (appNavbar) appNavbar.classList.add('hidden');
    updateLandingNav();
  } else {
    document.body.classList.remove('landing');
    if (landingNavbar) landingNavbar.classList.remove('visible');
    if (appNavbar && !noNavPages.includes(cleanPage)) appNavbar.classList.remove('hidden');
  }

  if (!isFirstLoad) {
    var qs = page === 'landing' ? '/' : '/?page=' + page;
    window.history.pushState({ page: page }, '', qs);
  }
}

window.toggleLandingMenu = function(el) {
  var menu = document.getElementById('landingNavbarMenu');
  if (menu) menu.classList.toggle('open');
  var landing_bar_open = document.getElementById('landing-bar-open');
  var landing_bar_close = document.getElementById('landing-bar-close');
  if (landing_bar_open) {
    landing_bar_open.style.display = landing_bar_open.style.display === 'none' ? '' : 'none';
  }
  if (landing_bar_close) {
    landing_bar_close.style.display = landing_bar_close.style.display === 'none' ? '' : 'none';
  }
};

// Landing page smooth scroll (sections within landing)
document.addEventListener('click', function(e) {
  var link = e.target.closest('.landing-navbar-link');
  if (link) {
    var href = link.getAttribute('href');
    if (href && href.startsWith('#')) {
      var targetId = href.replace('#', '');
      var targetSection = document.getElementById('section-' + targetId);
      if (targetSection) {
        e.preventDefault();
        targetSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        document.querySelectorAll('.landing-navbar-link').forEach(function(l) { l.classList.remove('active'); });
        link.classList.add('active');
        var m = document.getElementById('landingNavbarMenu');
        if (m) m.classList.remove('open');
        var ob = document.getElementById('landing-bar-open');
        var cb = document.getElementById('landing-bar-close');
        if (ob) ob.style.display = '';
        if (cb) cb.style.display = 'none';
        return;
      }
    }
  }
});

// Landing page scroll spy — active nav indicator
function updateLandingNav(activeOnLoad = null) {
  console.log('called');
  if (!document.body.classList.contains('landing')) return;
  var scrollY = window.scrollY + 100;
  var activeLink = null;
  var links = document.querySelectorAll('.landing-navbar-link');
  for(let i=0;i<links.length;i++){
    const link = links[i];
    var href = link.getAttribute('href');
    if (!href || !href.startsWith('#')) break;
    console.log(activeOnLoad, href.replace('#', ''));
    if(activeOnLoad && href.replace('#', '') === activeOnLoad){
      activeLink = link;
      break;
    }
    var section = document.getElementById('section-' + href.replace('#', ''));
    if (section && scrollY >= section.offsetTop) {
      activeLink = link;
    }
  }
  document.querySelectorAll('.landing-navbar-link').forEach(function(l) {
    l.classList.toggle('active', l === activeLink);
  });
}
window.addEventListener('scroll', updateLandingNav, { passive: true });

// Intercept all hash links for SPA navigation
document.addEventListener('click', function(e) {
  var link = e.target.closest('a[href^="#"]');
  if (!link) return;

  var href = link.getAttribute('href');
  var targetId = href.replace('#', '');

  // Skip if it's a landing section (handled above)
  if (document.getElementById('section-' + targetId)) return;

  e.preventDefault();
  if (targetId) navigate(targetId);
});

// Handle browser back/forward
window.addEventListener('popstate', function(e) {
  var page = getPageFromUrl();
  navigate(page, true);
});

// Initial load
(function() {
  var page = getPageFromUrl();
  navigate(page, true);
  updateLandingNav('landing');
})();
</script>
