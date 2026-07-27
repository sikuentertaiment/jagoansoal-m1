<script>
  function toggleToolsSidebar() {
    const sidebar = document.getElementById('tools-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if (sidebar && overlay) {
      if(window.innerWidth <= 760){
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
      }
    }
  }

  function closeToolsSidebar() {
    const sidebar = document.getElementById('tools-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if (sidebar) sidebar.classList.remove('open');
    if (overlay) overlay.classList.remove('open');
  }

  function updateNavbarCredit() {
    const el = document.getElementById('navbarCreditCount');
    if (el && window.currentUser) {
      el.textContent = window.currentUser.credit || 0;
    }
  }

  function toggleAppSidebar() {
    const params = new URLSearchParams(window.location.search);
    const page = params.get('page') || '';
    if (page.startsWith('admin') && window.isAdmin) {
      toggleAdminSidebar();
    } else {
      toggleToolsSidebar();
    }
  }

  (function() {
    function updateSidebarNav(user) {
    }

    window.isAdmin = false;

    const savedUser = localStorage.getItem('haujian_user');
    if (savedUser) {
      try {
        window.currentUser = JSON.parse(savedUser);
      } catch (e) {
        localStorage.removeItem('haujian_user');
      }
    }

    async function checkAdmin() {
      try {
        const res = await fetch('../backend/admin.php?action=check');
        const data = await res.json();
        window.isAdmin = data.success && data.isAdmin;
        const link = document.getElementById('adminNavLink');
        if (link) link.style.display = window.isAdmin ? '' : 'none';
        localStorage.setItem('haujian_admin', window.isAdmin ? '1' : '0');
      } catch (e) {
        window.isAdmin = false;
      }
    }

    firebase.auth().onAuthStateChanged(async (firebaseUser) => {
      if (firebaseUser) {
        const response = await fetch('../backend/auth.php?action=sync', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            uid: firebaseUser.uid,
            email: firebaseUser.email,
            displayName: firebaseUser.displayName,
            photoUrl: firebaseUser.photoURL
          })
        });

        const data = await response.json();
        if (data.success) {
          window.currentUser = data.user;
          localStorage.setItem('haujian_user', JSON.stringify(window.currentUser));
          updateNavbarCredit();
          checkAdmin();
        }
      } else {
        window.currentUser = null;
        window.isAdmin = false;
        localStorage.removeItem('haujian_user');
        localStorage.removeItem('haujian_admin');
        updateNavbarCredit();
        const params = new URLSearchParams(window.location.search);
        const currentPage = params.get('page') || '';
        if (currentPage === 'tools' || currentPage.startsWith('tools-')) {
          window.location.href = '/';
        }
      }
    });

    window.handleLogout = async function() {
      showModal({
        icon: 'warning',
        iconHtml: '<i class="fas fa-sign-out-alt"></i>',
        title: 'Konfirmasi Logout',
        message: 'Apakah Anda yakin ingin logout?',
        buttons: [
          {
            text: 'Batal',
            class: 'modal-btn-cancel'
          },
          {
            text: 'Logout',
            class: 'modal-btn-danger',
            onclick: async function() {
              try {
                await firebase.auth().signOut();
                await fetch('../backend/auth.php?action=logout');
                window.currentUser = null;
                localStorage.removeItem('haujian_user');
                localStorage.removeItem(btoa('jsat'));
                localStorage.removeItem(btoa('jsat_expiry'));
                window.location.reload();
              } catch (error) {
                console.error('Logout error:', error);
              }
            }
          }
        ]
      });
      return false;
    };

    window.updateSidebarNav = updateSidebarNav;
  })();
</script>
