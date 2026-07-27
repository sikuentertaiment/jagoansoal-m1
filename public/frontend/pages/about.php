<style>
#page-about .page-card {
  max-width: 768px;
  margin: 0 auto;
  padding: 2rem;
}
#page-about .value-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.5rem;
}
#page-about .value-card {
  background: #f9fafb;
  border-radius: 12px;
  padding: 1.5rem;
  text-align: center;
  border: 1px solid #e5e7eb;
  transition: transform 0.2s, box-shadow 0.2s;
}
#page-about .value-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
#page-about .value-card i {
  font-size: 2rem;
  color: #2563eb;
  margin-bottom: 0.75rem;
}
#page-about .value-card h4 {
  font-size: 1.125rem;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 0.5rem;
}
#page-about .value-card p {
  font-size: 0.875rem;
  color: #6b7280;
  line-height: 1.6;
}
#page-about .team-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 1.5rem;
}
#page-about .team-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 1.5rem;
  text-align: center;
  transition: box-shadow 0.2s;
}
#page-about .team-card:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
#page-about .team-card .avatar {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
  margin: 0 auto 1rem;
  background: #e5e7eb;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  color: #9ca3af;
}
#page-about .team-card h4 {
  font-size: 1rem;
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 0.25rem;
}
#page-about .team-card p {
  font-size: 0.8125rem;
  color: #6b7280;
}
@media (max-width: 640px) {
  #page-about .page-card { padding: 1.25rem; }
  #page-about .value-grid { grid-template-columns: 1fr; }
  #page-about .team-grid { grid-template-columns: 1fr; }
}
</style>

<div id="page-about" class="page">
  <div class="page-card" style="max-width:768px;margin:0 auto;padding:2rem;">

    <div style="text-align:center;margin-bottom:2.5rem;">
      <h1 style="font-size:1.875rem;font-weight:700;color:#111827;margin-bottom:0.5rem;">Tentang jagoansoal</h1>
      <p style="font-size:1rem;color:#6b7280;max-width:600px;margin:0 auto;line-height:1.7;" data-lang-key="about.subtitle">Platform AI untuk membantu guru dan pendidik membuat soal ujian berkualitas dengan cepat dan mudah.</p>
    </div>

    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:2rem;margin-bottom:2rem;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
      <h2 style="font-size:1.375rem;font-weight:600;color:#1f2937;margin-bottom:0.75rem;" data-lang-key="about.story.title">Cerita Kami</h2>
      <p style="font-size:0.9375rem;color:#4b5563;line-height:1.8;" data-lang-key="about.story.desc">jagoansoal lahir dari keprihatinan seorang guru yang menghabiskan berjam-jam setiap minggunya hanya untuk menyusun soal ujian. Kami percaya bahwa teknologi AI dapat meringankan beban administratif guru, sehingga mereka bisa kembali fokus pada esensi pendidikan. Berawal dari obrolan kecil di sebuah kedai kopi, berdirilah jagoansoal dengan misi besar: merevolusi cara guru membuat soal.</p>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:2rem;">
      <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
        <div style="width:40px;height:40px;border-radius:10px;background:rgba(37,99,235,0.1);display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
          <i class="fas fa-bullseye" style="color:#2563eb;font-size:1.125rem;"></i>
        </div>
        <h3 style="font-size:1.125rem;font-weight:600;color:#1f2937;margin-bottom:0.5rem;" data-lang-key="about.mission.title">Misi Kami</h3>
        <p style="font-size:0.875rem;color:#6b7280;line-height:1.7;" data-lang-key="about.mission.desc">Memberdayakan setiap guru dan pendidik dengan teknologi AI untuk menciptakan soal ujian berkualitas tinggi secara instan, sehingga mereka dapat fokus pada hal yang paling penting: mendidik dan menginspirasi generasi penerus.</p>
      </div>
      <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.06);">
        <div style="width:40px;height:40px;border-radius:10px;background:rgba(16,185,129,0.1);display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
          <i class="fas fa-eye" style="color:#10b981;font-size:1.125rem;"></i>
        </div>
        <h3 style="font-size:1.125rem;font-weight:600;color:#1f2937;margin-bottom:0.5rem;" data-lang-key="about.vision.title">Visi Kami</h3>
        <p style="font-size:0.875rem;color:#6b7280;line-height:1.7;" data-lang-key="about.vision.desc">Menjadi platform pembuatan soal berbasis AI terdepan di Indonesia yang membantu menciptakan ekosistem evaluasi pembelajaran yang lebih efisien, akurat, dan merata untuk semua jenjang pendidikan.</p>
      </div>
    </div>

    <div style="margin-bottom:2rem;">
      <h2 style="font-size:1.375rem;font-weight:600;color:#1f2937;margin-bottom:0.25rem;text-align:center;" data-lang-key="about.values.title">Nilai-Nilai Kami</h2>
      <p style="font-size:0.875rem;color:#6b7280;text-align:center;margin-bottom:1.5rem;" data-lang-key="about.values.subtitle">Prinsip yang menuntun setiap langkah kami</p>
      <div class="value-grid">
        <div class="value-card">
          <i class="fas fa-lightbulb"></i>
          <h4 data-lang-key="about.value.innovation">Inovasi</h4>
          <p data-lang-key="about.value.innovation.desc">Terus mendorong batasan teknologi untuk menghadirkan solusi terbaik dalam dunia pendidikan.</p>
        </div>
        <div class="value-card">
          <i class="fas fa-medal"></i>
          <h4 data-lang-key="about.value.quality">Kualitas</h4>
          <p data-lang-key="about.value.quality.desc">Setiap soal yang dihasilkan melalui proses verifikasi ketat untuk memastikan standar kualitas tertinggi.</p>
        </div>
        <div class="value-card">
          <i class="fas fa-shield-alt"></i>
          <h4 data-lang-key="about.value.privacy">Privasi</h4>
          <p data-lang-key="about.value.privacy.desc">Data dan materi Anda aman bersama kami. Kami tidak pernah membagikan informasi pribadi ke pihak ketiga.</p>
        </div>
      </div>
    </div>

    <div>
      <h2 style="font-size:1.375rem;font-weight:600;color:#1f2937;margin-bottom:0.25rem;text-align:center;" data-lang-key="about.team.title">Tim Kami</h2>
      <p style="font-size:0.875rem;color:#6b7280;text-align:center;margin-bottom:1.5rem;" data-lang-key="about.team.subtitle">Orang-orang di balik jagoansoal</p>
      <div class="team-grid">
        <div class="team-card">
          <div class="avatar"><i class="fas fa-user-tie"></i></div>
          <h4 data-lang-key="about.team.ceo">Rahmat Agem Pratama</h4>
          <p data-lang-key="about.team.ceo_role">Founder & CEO</p>
        </div>
        <div class="team-card">
          <div class="avatar"><i class="fas fa-code"></i></div>
          <h4 data-lang-key="about.team.cto">Tim Teknis</h4>
          <p data-lang-key="about.team.cto_role">AI & Engineering</p>
        </div>
        <div class="team-card">
          <div class="avatar"><i class="fas fa-paint-brush"></i></div>
          <h4 data-lang-key="about.team.design">Tim Produk</h4>
          <p data-lang-key="about.team.design_role">UI/UX & Design</p>
        </div>
      </div>
    </div>

  </div>
</div>
