<style>
:root {
  --wave-hero:       #f8fafc;
  --wave-howitworks: #f8fafc;
  --wave-features:   #ffffff;
  --wave-pricing:    #ffffff;
  --wave-faq:        #0F172A;
}

.hero-section,
.howitworks-section,
.features-section,
.pricing-section,
.faq-section {
  position: relative;
  overflow: visible;
  z-index: 0;
}

.wave-divider-canvas {
  position: absolute;
  bottom: -1px;
  left: 0;
  width: 100%;
  pointer-events: none;
  z-index: 10;
  display: block;
}

.hero-section       .wave-divider-canvas { height: 90px;  }
.howitworks-section .wave-divider-canvas { height: 90px;  }
.features-section   .wave-divider-canvas { height: 100px; }
.pricing-section    .wave-divider-canvas { height: 110px; }
.faq-section        .wave-divider-canvas { height: 120px; }

@media (max-width: 768px) {
  .hero-section       .wave-divider-canvas { height: 55px; }
  .howitworks-section .wave-divider-canvas { height: 55px; }
  .features-section   .wave-divider-canvas { height: 60px; }
  .pricing-section    .wave-divider-canvas { height: 65px; }
  .faq-section        .wave-divider-canvas { height: 70px; }
}

@media (max-width: 480px) {
  .hero-section       .wave-divider-canvas { height: 36px; }
  .howitworks-section .wave-divider-canvas { height: 36px; }
  .features-section   .wave-divider-canvas { height: 40px; }
  .pricing-section    .wave-divider-canvas { height: 44px; }
  .faq-section        .wave-divider-canvas { height: 48px; }
}

.hero-mockup-stack {
  position: relative;
  width: 100%;
  height: auto;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: visible;
}

.hero-image {
  width: 100%;
  max-width: 400px;
  height: auto;
  border-radius: 20px;
  transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1),
              box-shadow 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  will-change: transform;
  display: block;
  cursor: pointer;
}

.hero-image:hover {
  transform: rotate(3deg) scale(1.05);
}

.video-demo-wrapper {
  max-width: 720px;
  margin: 0 auto 40px;
  padding: 0 20px;
}
.video-demo-container {
  position: relative;
  width: 100%;
  padding-bottom: 56.25%;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(0,0,0,0.1);
  background: #e5e7eb;
}
.video-demo-container iframe {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  border: none;
}

/* Testimonials */
.testimonials-section {
  padding: 80px 20px;
  background: white;
}
.testimonials-container {
  max-width: 1000px;
  margin: 0 auto;
}
.testimonials-header {
  text-align: center;
  margin-bottom: 48px;
}
.testimonials-title {
  font-size: 28px;
  font-weight: 800;
  color: var(--dark);
  font-family: 'Plus Jakarta Sans', sans-serif;
  margin-bottom: 8px;
}
.testimonials-subtitle {
  font-size: 15px;
  color: var(--gray-500);
}
.testimonials-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
}
.testimonial-card {
  background: white;
  border-radius: 16px;
  padding: 28px 24px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.06);
  border: 1px solid var(--gray-100);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  display: flex;
  flex-direction: column;
}
.testimonial-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 30px rgba(37,99,235,0.1);
}
.testimonial-stars {
  color: #f59e0b;
  font-size: 14px;
  margin-bottom: 16px;
  letter-spacing: 2px;
}
.testimonial-text {
  font-size: 14px;
  line-height: 1.7;
  color: var(--gray-600);
  flex: 1;
  margin-bottom: 20px;
  font-style: italic;
}
.testimonial-author {
  display: flex;
  align-items: center;
  gap: 12px;
  padding-top: 16px;
  border-top: 1px solid var(--gray-100);
}
.testimonial-avatar {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  font-weight: 700;
  flex-shrink: 0;
}
.testimonial-name {
  font-size: 14px;
  font-weight: 600;
  color: var(--dark);
  margin-bottom: 2px;
}
.testimonial-role {
  font-size: 12px;
  color: var(--gray-400);
}
@media (max-width: 768px) {
  .testimonials-grid {
    grid-template-columns: 1fr;
    gap: 16px;
  }
  .testimonials-section { padding: 60px 16px; }
}
</style>

<div id="page-landing" class="page landing-page">

  <section class="hero-section" id="section-landing">
    <div class="hero-grid">
      <div class="hero-text-col">
        <h1 class="hero-title gradient-text"><?= htmlspecialchars($landingSettings['hero_title'] ?? 'Buat Soal Ujian dengan AI dalam Hitungan Detik') ?></h1>
        <p class="hero-subtitle"><?= htmlspecialchars($landingSettings['hero_subtitle'] ?? 'Hasilkan soal ujian berkualitas dalam hitungan detik. Cukup masukkan materi atau topik, dan AI kami akan menyusun soal lengkap dengan kunci jawaban. Hemat waktu, fokus mengajar.') ?></p>
        <a href="#tools" class="hero-cta"><i class="fas fa-check-circle"></i> Mulai Generate Gratis</a>
      </div>
      <div class="hero-visual-col">
        <div class="hero-mockup-stack">
          <img
            src="<?= htmlspecialchars($landingSettings['hero_image_url'] ?? '/public/assets/app/landing-hero-img-mockup.png') ?>"
            alt="Hero"
            class="hero-image"
            onerror="this.src='https://api.dicebear.com/9.x/pixel-art/svg?seed=hero'"
          />
        </div>

        <div class="hero-stats">
          <div class="hero-stat-item">
            <div class="hero-stat-icon"><i class="fas fa-file-alt"></i></div>
            <div class="hero-stat-info">
              <span class="hero-stat-number"><?= htmlspecialchars($landingSettings['hero_stat_soal'] ?? '50.000+') ?></span>
              <span class="hero-stat-label">Soal Telah Dibuat</span>
            </div>
          </div>
          <div class="hero-stat-divider"></div>
          <div class="hero-stat-item">
            <div class="hero-stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
            <div class="hero-stat-info">
              <span class="hero-stat-number"><?= htmlspecialchars($landingSettings['hero_stat_guru'] ?? '5.000+') ?></span>
              <span class="hero-stat-label">Guru Pengguna</span>
            </div>
          </div>
        </div>

        <div class="hero-float-icon hero-float-1"><i class="fas fa-brain"></i> Cerdas</div>
        <div class="hero-float-icon hero-float-2"><i class="fas fa-graduation-cap"></i> Cepat</div>
        <div class="hero-float-icon hero-float-3"><i class="fas fa-star"></i> Murah</div>

        <div class="hero-float-icon hero-float-4"><i class="fas fa-file-pdf"></i> PDF</div>
        <div class="hero-float-icon hero-float-5"><i class="fas fa-file-word"></i> DOC</div>
        <div class="hero-float-icon hero-float-6"><i class="fab fa-google"></i> Google Form</div>
      </div>
    </div>
  </section>

  <section class="howitworks-section" id="section-howitworks">
    <div class="tutorial-container">
      <div class="tutorial-header">
        <h2 class="tutorial-title">Cara Kerja</h2>
        <p class="tutorial-subtitle">Tiga langkah mudah menghasilkan soal ujian</p>
      </div>

      <div class="video-demo-wrapper">
        <div class="video-demo-container">
          <?php
            $videoUrl = $landingSettings['hero_video_url'] ?? '';
            $videoId = '';
            if (!empty($videoUrl)) {
              preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $videoUrl, $matches);
              $videoId = $matches[1] ?? '';
            }
          ?>
          <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($videoId) ?>" title="Video Demo jagoansoal" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
      </div>

      <div class="tutorial-grid">
        <?php if (!empty($howItWorks)): ?>
          <?php foreach ($howItWorks as $hw): ?>
            <div class="tutorial-card p-4">
              <div class="tutorial-number"><?= (int)$hw['step_number'] ?></div>
              <h3 class="tutorial-card-title"><?= htmlspecialchars($hw['title']) ?></h3>
              <p class="tutorial-card-desc"><?= htmlspecialchars($hw['description']) ?></p>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="tutorial-card p-4">
            <div class="tutorial-number">1</div>
            <h3 class="tutorial-card-title">Masukkan Materi atau Topik</h3>
            <p class="tutorial-card-desc">Tulis materi pelajaran, tempelkan teks, atau unggah dokumen. AI akan menganalisis dan memahami konteks pembelajaran Anda.</p>
          </div>
          <div class="tutorial-card p-4">
            <div class="tutorial-number">2</div>
            <h3 class="tutorial-card-title">Atur Parameter Soal</h3>
            <p class="tutorial-card-desc">Pilih jumlah soal, jenis soal (pilihan ganda, esai, isian), tingkat kesulitan, dan mata pelajaran. Sesuaikan dengan kebutuhan.</p>
          </div>
          <div class="tutorial-card p-4">
            <div class="tutorial-number">3</div>
            <h3 class="tutorial-card-title">Generate & Export</h3>
            <p class="tutorial-card-desc">AI langsung menghasilkan soal lengkap dengan kunci jawaban. Siap digunakan!</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="features-section" id="section-features">
    <div class="tutorial-container">
      <div class="tutorial-header">
        <h2 class="tutorial-title">Fitur Unggulan</h2>
        <p class="tutorial-subtitle">Berbagai fitur untuk mempermudah pembuatan soal</p>
      </div>

      <div class="features-grid">
        <div class="feature-card">
          <div class="feature-icon" style="background:rgba(37,99,235,0.1);color:#2563EB;">
            <i class="fas fa-brain"></i>
          </div>
          <h3 class="feature-title">Generate Soal dengan AI</h3>
          <p class="feature-desc">AI canggih yang memahami materi dan menghasilkan soal relevan dengan tingkat kesulitan yang tepat.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon" style="background:rgba(16,185,129,0.1);color:#10B981;">
            <i class="fas fa-database"></i>
          </div>
          <h3 class="feature-title">Bank Soal</h3>
          <p class="feature-desc">Simpan dan kelola ribuan soal dalam bank soal pribadi. Akses kapan saja, kategorikan berdasarkan mata pelajaran.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon" style="background:rgba(37,99,235,0.1);color:#2563EB;">
            <i class="fas fa-file-alt"></i>
          </div>
          <h3 class="feature-title">Generate dari Materi</h3>
          <p class="feature-desc">Tempelkan teks materi AI akan membaca dan membuat soal berdasarkan isinya.</p>
        </div>
      </div>

      <div class="export-features-section">
        <h3 class="export-features-title">Export ke Berbagai Format</h3>
        <p class="export-features-subtitle">Setiap soal bisa diexport dalam format yang kamu butuhkan, dengan pengaturan tata letak yang bisa disesuaikan</p>
        <div class="export-features-grid">
          <div class="export-format-card">
            <div class="export-format-icon" style="background:#f3f4f6;color:#6b7280;">
              <i class="fas fa-file-alt"></i>
            </div>
            <h4 class="export-format-title">Plain Text (TXT)</h4>
            <p class="export-format-desc">Export soal ke file teks biasa. Ringan, cepat, dan bisa dibuka di perangkat manapun. Cocok untuk ditempel ke Google Classroom atau WhatsApp.</p>
          </div>
          <div class="export-format-card">
            <div class="export-format-icon" style="background:#fef2f2;color:#ef4444;">
              <i class="fas fa-file-pdf"></i>
            </div>
            <h4 class="export-format-title">PDF</h4>
            <p class="export-format-desc">Export soal ke PDF dengan format rapi dan siap cetak. Layout profesional, mendukung header dan footer, serta pilihan posisi kunci jawaban (per soal atau di akhir).</p>
          </div>
          <div class="export-format-card">
            <div class="export-format-icon" style="background:#eff6ff;color:#2563eb;">
              <i class="fas fa-file-word"></i>
            </div>
            <h4 class="export-format-title">DOCX</h4>
            <p class="export-format-desc">Export ke dokumen Word yang bisa diedit. Cocok jika ingin merevisi soal sebelum dicetak atau dibagikan. Semua format dan tabel tetap rapi.</p>
          </div>
          <div class="export-format-card">
            <div class="export-format-icon" style="background:#f0fdf4;color:#34a853;">
              <i class="fab fa-google"></i>
            </div>
            <h4 class="export-format-title">Google Form</h4>
            <p class="export-format-desc">Buat Google Form secara otomatis dengan sekali klik! Soal langsung terintegrasi ke Google Forms, lengkap dengan kunci jawaban dan pembahasan. Bisa langsung dibagikan ke siswa.</p>
          </div>
        </div>
      </div>

      <div class="ceo-section">
        <div class="ceo-card">
          <img src="https://pioneers.my.id/assets/team_pic_ceo.jpeg" alt="CEO" class="ceo-photo" onerror="this.src='https://api.dicebear.com/9.x/pixel-art/svg?seed=gema'">
          <div class="ceo-content">
            <p class="ceo-quote">"Kami percaya bahwa teknologi AI dapat meringankan beban guru dalam menyusun soal ujian berkualitas. Dengan jagoansoal, guru bisa fokus pada hal yang paling penting: mendidik."</p>
            <p class="ceo-name">Rahmat Agem Pratama</p>
            <p class="ceo-title">Founder & CEO jagoansoal</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="testimonials-section" id="section-testimonials">
    <div class="testimonials-container">
      <div class="testimonials-header">
        <h2 class="testimonials-title">Apa Kata Mereka</h2>
        <p class="testimonials-subtitle">Ratusan guru sudah merasakan kemudahan membuat soal dengan jagoansoal</p>
      </div>
      <div class="testimonials-grid">
        <div class="testimonial-card">
          <div class="testimonial-stars">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
          </div>
          <p class="testimonial-text">"Dulu butuh 3-4 jam buat bikin 40 soal. Sekarang cukup 5 menit. Soal yang dihasilkan relevan dan sesuai kurikulum. Ini benar-benar membantu saya sebagai guru PJOK."</p>
          <div class="testimonial-author">
            <div class="testimonial-avatar" style="background:#e8f5e9;color:#2e7d32;">SP</div>
            <div class="testimonial-info">
              <p class="testimonial-name">Siti Purwanti</p>
              <p class="testimonial-role">Guru PJOK, SMAN 2 Surakarta</p>
            </div>
          </div>
        </div>
        <div class="testimonial-card">
          <div class="testimonial-stars">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
          </div>
          <p class="testimonial-text">"Fitur generate dari materi itu paling saya suka. Tinggal copy-paste bahan ajar, langsung jadi soal. Sangat efisien untuk persiapan UTS dan UAS. Recommended banget!"</p>
          <div class="testimonial-author">
            <div class="testimonial-avatar" style="background:#e3f2fd;color:#1565c0;">AH</div>
            <div class="testimonial-info">
              <p class="testimonial-name">Ahmad Hidayat</p>
              <p class="testimonial-role">Guru Matematika, MTsN 1 Bandung</p>
            </div>
          </div>
        </div>
        <div class="testimonial-card">
          <div class="testimonial-stars">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
          </div>
          <p class="testimonial-text">"Bingung cari referensi soal HOTS? jagoansoal jawabannya. Soal yang digenerate AI-nya bagus, ada kunci jawaban dan pembahasan. Gratis pula untuk pemula. Wajib coba!"</p>
          <div class="testimonial-author">
            <div class="testimonial-avatar" style="background:#fce4ec;color:#c62828;">DN</div>
            <div class="testimonial-info">
              <p class="testimonial-name">Dewi Nuraeni</p>
              <p class="testimonial-role">Guru Bahasa Indonesia, SMA 3 Yogyakarta</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="pricing-section" id="section-pricing">
    <div class="pricing-container">
      <div class="pricing-header">
        <h2 class="pricing-title" data-lang-key="pricing.title">Harga & Kredit</h2>
        <p class="pricing-subtitle" data-lang-key="pricing.subtitle">Mulai gratis, top-up kapan saja</p>
      </div>
      <div class="pricing-grid pricing-grid-2">
        <div class="pricing-card">
          <h3 class="pricing-name" data-lang-key="pricing.free">Gratis</h3>
          <p class="pricing-price">0<span class="pricing-currency" data-lang-key="pricing.idr">IDR</span></p>
          <p class="pricing-period" data-lang-key="pricing.period.once">selamanya</p>
          <ul class="pricing-features">
            <li><i class="fas fa-gift" style="color:#2563EB;"></i> <span data-lang-key="pricing.free_credits"><strong>3 Kredit</strong> saat daftar</span></li>
            <li><i class="fas fa-check" style="color:#10B981;"></i> <span data-lang-key="pricing.features.generate">Generate soal unlimited</span></li>
            <li><i class="fas fa-check" style="color:#10B981;"></i> <span data-lang-key="pricing.features.bank">Bank soal pribadi</span></li>
            <li><i class="fas fa-check" style="color:#10B981;"></i> <span data-lang-key="pricing.features.export">Export Soal ke PDF, TXT, DOCX, dan Google Form</span></li>
          </ul>
          <a href="#login" class="pricing-btn" data-lang-key="pricing.getstarted">Daftar Gratis</a>
        </div>

        <div class="pricing-card pricing-card-featured">
          <div class="pricing-badge" data-lang-key="pricing.popular">Terpopuler</div>
          <h3 class="pricing-name" data-lang-key="pricing.topup">Top Up</h3>
          <p class="pricing-price">1.000<span class="pricing-currency" data-lang-key="pricing.idr">IDR</span></p>
          <p class="pricing-period" data-lang-key="pricing.3credits">3 Kredit</p>
          <ul class="pricing-features">
            <li><i class="fas fa-bolt" style="color:#F59E0B;"></i> <span data-lang-key="pricing.instant"><strong>Instant</strong> - langsung aktif</span></li>
            <li><i class="fas fa-infinity" style="color:#2563EB;"></i> <span data-lang-key="pricing.no_expiry"><strong>Tanpa Kedaluwarsa</strong> - kredit tidak hangus</span></li>
            <li><i class="fas fa-clock" style="color:#10B981;"></i> <span data-lang-key="pricing.use_anytime"><strong>Kapan Saja</strong> - gunakan sesuai kebutuhan</span></li>
            <li><i class="fas fa-check" style="color:#10B981;"></i> <span data-lang-key="pricing.features.priority">Prioritas generate</span></li>
          </ul>
          <a href="#tools-account" class="pricing-btn" data-lang-key="pricing.topup_now">Top Up Sekarang</a>
        </div>
      </div>
    </div>
  </section>

  <section class="tutorial-section" id="section-tutorial">
    <div class="tutorial-container">
      <div class="tutorial-header">
        <h2 class="tutorial-title">Video Tutorial</h2>
        <p class="tutorial-subtitle">Pelajari cara menggunakan jagoansoal melalui video panduan berikut</p>
      </div>
      <?php if (!empty($tutorials)): ?>
        <div class="tutorial-video-grid">
          <?php foreach ($tutorials as $t): ?>
            <div class="tutorial-video-card">
              <div class="tutorial-video-wrapper">
                <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($t['video_id']) ?>" title="<?= htmlspecialchars($t['title']) ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
              </div>
              <div class="tutorial-video-body">
                <h3 class="tutorial-video-title"><?= htmlspecialchars($t['title']) ?></h3>
                <?php if (!empty($t['description'])): ?>
                  <p class="tutorial-video-desc"><?= htmlspecialchars($t['description']) ?></p>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="tutorial-empty">
          <i class="fas fa-video-slash"></i>
          <p>Belum ada tutorial. Silakan cek kembali nanti.</p>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <section class="faq-section" id="section-faq">
    <div class="faq-container">
      <div class="faq-header">
        <h2 class="faq-title" data-lang-key="faq.title">Pertanyaan Umum</h2>
        <p class="faq-subtitle" data-lang-key="faq.subtitle">Ada pertanyaan? Kami siap membantu.</p>
      </div>
      <div class="faq-list">
        <?php if (!empty($faqItems)): ?>
          <?php foreach ($faqItems as $faq): ?>
            <div class="faq-item">
              <button class="faq-question" onclick="this.parentElement.classList.toggle('open')">
                <span><?= htmlspecialchars($faq['question']) ?></span>
                <i class="fas fa-chevron-down"></i>
              </button>
              <div class="faq-answer">
                <div class="faq-answer-content"><?= htmlspecialchars($faq['answer']) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="faq-item">
            <button class="faq-question" onclick="this.parentElement.classList.toggle('open')">
              <span data-lang-key="faq.q1">Apa itu jagoansoal?</span>
              <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
              <div class="faq-answer-content" data-lang-key="faq.a1">jagoansoal adalah platform berbasis AI yang membantu guru dan pendidik membuat soal ujian secara otomatis. Cukup masukkan materi, AI kami akan menghasilkan soal lengkap dengan kunci jawaban dalam hitungan detik.</div>
            </div>
          </div>
          <div class="faq-item">
            <button class="faq-question" onclick="this.parentElement.classList.toggle('open')">
              <span data-lang-key="faq.q2">Apakah benar-benar gratis?</span>
              <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
              <div class="faq-answer-content" data-lang-key="faq.a2">Ya! Setiap pengguna baru mendapatkan 3 kredit gratis yang bisa digunakan untuk generate soal. Jika ingin lebih, Anda bisa top up dengan harga Rp 1.000 untuk 3 kredit, tanpa kedaluwarsa.</div>
            </div>
          </div>
          <div class="faq-item">
            <button class="faq-question" onclick="this.parentElement.classList.toggle('open')">
              <span data-lang-key="faq.q3">Berapa lama proses generate soal?</span>
              <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
              <div class="faq-answer-content" data-lang-key="faq.a3">Proses generate soal biasanya hanya memakan waktu 10-30 detik, tergantung pada jumlah soal dan kompleksitas materi. Jauh lebih cepat daripada menyusun soal secara manual.</div>
            </div>
          </div>
          <div class="faq-item">
            <button class="faq-question" onclick="this.parentElement.classList.toggle('open')">
              <span data-lang-key="faq.q4">Format soal apa saja yang tersedia?</span>
              <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
              <div class="faq-answer-content" data-lang-key="faq.a4">Kami mendukung berbagai jenis soal: Pilihan Ganda, Esai, Isian Singkat, Menjodohkan, dan Benar/Salah. Semua bisa dikombinasikan dalam satu paket ujian.</div>
            </div>
          </div>
          <div class="faq-item">
            <button class="faq-question" onclick="this.parentElement.classList.toggle('open')">
              <span data-lang-key="faq.q5">Mata pelajaran apa saja yang didukung?</span>
              <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
              <div class="faq-answer-content" data-lang-key="faq.a5">jagoansoal mendukung berbagai mata pelajaran termasuk Matematika, Fisika, Kimia, Biologi, Bahasa Indonesia, Bahasa Inggris, Sejarah, Geografi, Ekonomi, dan masih banyak lagi.</div>
            </div>
          </div>
          <div class="faq-item">
            <button class="faq-question" onclick="this.parentElement.classList.toggle('open')">
              <span data-lang-key="faq.q6">Apakah data saya aman?</span>
              <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
              <div class="faq-answer-content" data-lang-key="faq.a6">Keamanan data adalah prioritas kami. Semua soal dan materi yang Anda buat bersifat pribadi dan tidak dibagikan ke pihak lain. Kami menggunakan enkripsi untuk melindungi data Anda.</div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <footer class="landing-footer">
    <div class="landing-footer-container">
      <div class="landing-footer-grid">
        <div class="landing-footer-col">
          <div class="landing-footer-brand">
            <img src="/public/assets/app/icon.png" alt="jagoansoal" class="h-[48px]">
          </div>
          <p class="landing-footer-desc" data-lang-key="footer.desc">Platform AI untuk membantu guru dan pendidik membuat soal ujian berkualitas dengan cepat dan mudah. buat soal dalam hitungan detik.</p>
          <div class="landing-footer-social">
            <?php if (!empty($socialLinks)): ?>
              <?php foreach ($socialLinks as $sl): ?>
                <a href="<?= htmlspecialchars($sl['url']) ?>" target="_blank" rel="noopener" title="<?= htmlspecialchars($sl['platform']) ?>"><i class="<?= htmlspecialchars($sl['icon']) ?>"></i></a>
              <?php endforeach; ?>
            <?php else: ?>
              <a href="#" data-lang-title="footer.twitter"><i class="fab fa-twitter"></i></a>
              <a href="#" data-lang-title="footer.instagram"><i class="fab fa-instagram"></i></a>
              <a href="#" data-lang-title="footer.facebook"><i class="fab fa-facebook-f"></i></a>
              <a href="#" data-lang-title="footer.tiktok"><i class="fab fa-tiktok"></i></a>
            <?php endif; ?>
          </div>
        </div>
        <div class="landing-footer-col">
          <h4 class="landing-footer-title" data-lang-key="footer.company">Perusahaan</h4>
          <ul class="landing-footer-links">
            <li><a href="#about" data-lang-key="footer.about">Tentang Kami</a></li>
            <li><a href="#careers" data-lang-key="footer.careers">Karir</a></li>
            <li><a href="#blog" data-lang-key="footer.blog">Blog</a></li>
            <li><a href="#pers" data-lang-key="footer.press">Pers</a></li>
          </ul>
        </div>
        <div class="landing-footer-col">
          <h4 class="landing-footer-title" data-lang-key="footer.support">Bantuan</h4>
          <ul class="landing-footer-links">
            <li><a href="#help" data-lang-key="footer.help">Pusat Bantuan</a></li>
            <li><a href="#contact" data-lang-key="footer.contact">Hubungi Kami</a></li>
            <li><a href="#privacy" data-lang-key="footer.privacy">Kebijakan Privasi</a></li>
            <li><a href="#terms" data-lang-key="footer.terms">Syarat & Ketentuan</a></li>
          </ul>
        </div>
      </div>
      <div class="landing-footer-bottom">
        <p class="landing-footer-copyright" data-lang-key="footer.copyright">&copy; <?= date('Y') ?> jagoansoal. All rights reserved.</p>
      </div>
    </div>
  </footer>

</div>

<script>
(function () {

  var WAVES = [
    {
      selector : '.hero-section',
      colorVar : '--wave-hero',
      viewBox  : '0 0 1440 90',
      paths    : [
        { d: 'M0,0 C360,90 1080,0 1440,70 L1440,90 L0,90 Z',         opacity: 1    },
        { d: 'M0,30 C480,90 960,10 1440,55 L1440,90 L0,90 Z',        opacity: 0.5  }
      ]
    },
    {
      selector : '.howitworks-section',
      colorVar : '--wave-howitworks',
      viewBox  : '0 0 1440 90',
      paths    : [
        { d: 'M0,0 C360,90 1080,0 1440,70 L1440,90 L0,90 Z',         opacity: 1    },
        { d: 'M0,30 C480,90 960,10 1440,55 L1440,90 L0,90 Z',        opacity: 0.5  }
      ]
    },
    {
      selector : '.features-section',
      colorVar : '--wave-features',
      viewBox  : '0 0 1440 100',
      paths    : [
        { d: 'M0,60 C200,0 400,100 600,40 C800,-20 1000,90 1200,50 C1320,25 1400,70 1440,55 L1440,100 L0,100 Z', opacity: 1   },
        { d: 'M0,80 C300,20 600,100 900,60 C1100,30 1300,80 1440,65 L1440,100 L0,100 Z',                         opacity: 0.5 }
      ]
    },
    {
      selector : '.pricing-section',
      colorVar : '--wave-pricing',
      viewBox  : '0 0 1440 110',
      paths    : [
        { d: 'M0,20 Q180,90 360,40 Q540,-10 720,60 Q900,110 1080,45 Q1260,-10 1440,50 L1440,110 L0,110 Z', opacity: 1    },
        { d: 'M0,55 Q240,110 480,55 Q720,0 960,70 Q1200,110 1440,65 L1440,110 L0,110 Z',                   opacity: 0.45 }
      ]
    },
    {
      selector : '.faq-section',
      colorVar : '--wave-faq',
      viewBox  : '0 0 1440 120',
      paths    : [
        { d: 'M0,0 C360,120 1080,0 1440,80 L1440,120 L0,120 Z',      opacity: 1    },
        { d: 'M0,40 C480,120 960,30 1440,90 L1440,120 L0,120 Z',     opacity: 0.55 },
        { d: 'M0,70 C300,120 900,50 1440,100 L1440,120 L0,120 Z',    opacity: 0.3  }
      ]
    }
  ];

  function getCssVar(name) {
    return getComputedStyle(document.documentElement)
      .getPropertyValue(name)
      .trim();
  }

  function buildSvg(wave) {
    var color = getCssVar(wave.colorVar) || '#000000';
    var ns    = 'http://www.w3.org/2000/svg';

    var svg = document.createElementNS(ns, 'svg');
    svg.setAttribute('viewBox', wave.viewBox);
    svg.setAttribute('preserveAspectRatio', 'none');
    svg.classList.add('wave-divider-canvas');
    svg.setAttribute('aria-hidden', 'true');

    wave.paths.forEach(function (p) {
      var path = document.createElementNS(ns, 'path');
      path.setAttribute('d', p.d);
      path.setAttribute('fill', color);
      path.setAttribute('fill-opacity', p.opacity);
      svg.appendChild(path);
    });

    return svg;
  }

  function injectWaves() {
    WAVES.forEach(function (wave) {
      var section = document.querySelector(wave.selector);
      if (!section) return;

      var old = section.querySelector('.wave-divider-canvas');
      if (old) old.remove();

      section.appendChild(buildSvg(wave));
    });
  }

  function watchColorChanges() {
    var observer = new MutationObserver(function (mutations) {
      var relevant = mutations.some(function (m) {
        return m.attributeName === 'style' || m.attributeName === 'class';
      });
      if (relevant) injectWaves();
    });

    observer.observe(document.documentElement, { attributes: true });
  }

  function init() {
    injectWaves();
    watchColorChanges();

    var resizeTimer;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(injectWaves, 150);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
</script>

<script>
  (function() {
    var style = document.createElement('style');
    style.textContent = '.scroll-animate{opacity:0;transform:translateY(30px);transition:opacity 0.6s ease,transform 0.6s ease}.scroll-animate.visible{opacity:1;transform:translateY(0)}';
    document.head.appendChild(style);

    var setupScrollAnimation = function() {
      var selectors = ['.tutorial-card', '.feature-card', '.pricing-card', '.faq-item', '.ceo-card', '.hero-stats', '.landing-footer-grid .landing-footer-col'];
      var delay = 0;

      selectors.forEach(function(selector) {
        document.querySelectorAll(selector).forEach(function(el) {
          el.classList.add('scroll-animate');
          el.style.transitionDelay = (delay * 0.01) + 's';
          delay++;
        });
      });

      var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
          }
        });
      }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

      document.querySelectorAll('.scroll-animate').forEach(function(el) {
        observer.observe(el);
      });
    };

    setTimeout(setupScrollAnimation, 100);


  })();
</script>
