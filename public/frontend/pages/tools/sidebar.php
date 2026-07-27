    <aside class="tools-sidebar" id="tools-sidebar">
      <div class="tools-sidebar-header border-b pb-2" style="color:var(--primary);" data-lang-key="tools.menu">Menu</div>

      <a href="#tools-generate" class="tools-nav-link active" data-tool="generate">
        <i class="fas fa-file-pen"></i>
        <span data-lang-key="tools.generate">Generate Soal</span>
      </a>
      <a href="#tools-describe" class="tools-nav-link" data-tool="describe">
        <i class="fas fa-book-open"></i>
        <span data-lang-key="tools.describe">Generate dari Materi</span>
      </a>
      <a href="#tools-questions" class="tools-nav-link" data-tool="questions">
        <i class="fas fa-layer-group"></i>
        <span data-lang-key="tools.questions">Bank Soal</span>
      </a>
      <a href="#tools-classes" class="tools-nav-link" data-tool="classes">
        <i class="fas fa-graduation-cap"></i>
        <span data-lang-key="tools.classes">Kelas</span>
      </a>
      <a href="#tools-subjects" class="tools-nav-link" data-tool="subjects">
        <i class="fas fa-book"></i>
        <span data-lang-key="tools.subjects">Mata Pelajaran</span>
      </a>
      <a href="#tools-materials" class="tools-nav-link" data-tool="materials">
        <i class="fas fa-folder"></i>
        <span data-lang-key="tools.materials">Materi</span>
      </a>
      <a href="#tools-account" class="tools-nav-link" data-tool="account">
        <i class="fas fa-user"></i>
        <span data-lang-key="tools.account">Akun</span>
      </a>
      <a href="#tools-tutorial" class="tools-nav-link" data-tool="tutorial">
        <i class="fas fa-video"></i>
        <span data-lang-key="tools.tutorial">Tutorial</span>
      </a>
      <a href="#tools-report" class="tools-nav-link" data-tool="report">
        <i class="fas fa-bug"></i>
        <span data-lang-key="tools.report">Laporan</span>
      </a>

      <div style="margin-top: auto; border-top: 1px solid var(--gray-200);">
        <a href="#tools-logout" class="tools-nav-link" data-tool="logout" onclick="if(window.handleLogout)window.handleLogout();return false;">
          <i class="fas fa-sign-out-alt"></i>
          <span data-lang-key="tools.logout">Logout</span>
        </a>
        <footer class="footer" style="padding:16px 20px 8px; font-size:11px; color:var(--gray-400);" data-lang-key="tools.copyright">
          &copy; jagoansoal <?= date('Y') ?>
        </footer>
      </div>
    </aside>
