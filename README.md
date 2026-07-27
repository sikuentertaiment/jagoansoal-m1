# jagoansoal — AI Exam Generator

> **Platform AI untuk membuat soal ujian secara otomatis bagi guru Indonesia.**
> Cukup masukkan materi atau topik, AI akan menyusun soal lengkap dengan kunci jawaban dalam hitungan detik.

## Fitur Unggulan

| Fitur | Deskripsi |
|---|---|
| **Generate Soal via AI** | Buat soal berdasarkan topik — pilih kelas, mata pelajaran, jumlah soal, tingkat kesulitan, dan tipe soal (PG/Essay/Campuran) |
| **Generate dari Materi** | Upload atau tempel teks materi, AI membaca dan membuat soal berdasarkan isinya |
| **Bank Soal** | Simpan, cari, filter, edit, dan kelola semua soal yang pernah dibuat |
| **Quiz Mode** | Mode latihan interaktif dengan koreksi otomatis untuk siswa |
| **Export Multi-Format** | PDF, DOC, TXT, dan Google Forms — dengan kustomisasi header, info soal, biodata, petunjuk, dan posisi kunci jawaban |
| **Manajemen Kelas** | Tambah, edit, hapus kelas (jenjang pendidikan) |
| **Manajemen Mata Pelajaran** | Tambah, edit, hapus mata pelajaran per kelas |
| **Sistem Kredit** | Pay-per-use: 1 kredit = 10 soal. Dapatkan 3 gratis saat daftar, top-up via Midtrans |
| **Autentikasi Google** | Login dengan Google via Firebase Auth, tanpa password |
| **Google Forms Export** | Buat Google Form otomatis dengan sekali klik, lengkap kunci jawaban + pembahasan |
| **Panel Admin** | Dashboard statistik, manajemen user, transaksi, top-up manual, konten landing page (hero, FAQ, tutorial, sosial media) |
| **SEO Ready** | SPA dengan canonical URL, meta tags, JSON-LD (WebSite, Organization, SoftwareApplication, FAQPage) |
| **Rate Limiting** | Proteksi API dari spam (10 request per 60 detik per user) |

## Tech Stack

| Layer | Teknologi |
|---|---|
| **Backend** | PHP 8+ (Native, tanpa framework) |
| **Frontend** | Vanilla JavaScript, Tailwind CSS (CDN) |
| **Database** | MySQL dengan PDO |
| **Auth** | Firebase Authentication (Google Sign-In) |
| **AI Engine** | Pollinations AI API (Deepseek model) |
| **Payment Gateway** | Midtrans Snap |
| **PDF Export** | mPDF |
| **Google Forms** | Google Forms API v1 |
| **Chart** | Chart.js |
| **Server** | PHP built-in server atau Apache/Nginx |

## Struktur Direktori

```
jagoansoal-m1/
├── backend/                  # API endpoint backend
│   ├── .env                  # Konfigurasi environment
│   ├── config.php            # Koneksi DB, konstanta, rate limiting
│   ├── env.php               # .env loader
│   ├── auth.php              # Firebase Auth (login/register/sync/check)
│   ├── api.php               # Generate soal berdasarkan topik
│   ├── api-describe.php      # Generate soal berdasarkan materi
│   ├── question.php          # CRUD bank soal
│   ├── class.php             # CRUD kelas
│   ├── subject.php           # CRUD mata pelajaran
│   ├── export.php            # Export PDF/DOC
│   ├── export-googleform.php # Export ke Google Forms
│   ├── topup.php             # Top-up kredit via Midtrans
│   ├── upload.php            # Upload file materi + extract teks
│   ├── upload-question-image.php  # Upload gambar soal
│   ├── report.php            # Laporan bug dari user
│   ├── admin.php             # Panel admin (stats, user, transaksi)
│   ├── admin-tutorials.php   # Manajemen tutorial (YouTube)
│   └── landing-settings.php  # Landing page CMS (hero, how-it-works)
├── public/
│   ├── assets/               # Asset statis
│   │   ├── app/              # Ikon, favicon, OG image
│   │   ├── question/images/  # Gambar soal
│   │   ├── uploaded_materials/ # File materi diupload
│   │   ├── generated_questions/ # Soal yang digenerate (file)
│   │   └── questions/
│   └── frontend/
│       ├── pages/            # Halaman SPA
│       │   ├── landing.php   # Landing page (hero, fitur, testimoni, pricing, FAQ, dll)
│       │   ├── login.php     # Halaman login
│       │   ├── register.php  # Halaman register
│       │   ├── tools.php     # Dashboard utama (menginclude panel tools)
│       │   ├── admin.php     # Panel admin
│       │   ├── about.php     # Halaman tentang
│       │   ├── contact.php   # Halaman kontak
│       │   ├── privacy.php   # Kebijakan privasi
│       │   ├── terms.php     # Syarat & ketentuan
│       │   └── 404.php       # Halaman tidak ditemukan
│       │   └── tools/        # Sub-panel tools dashboard
│       │       ├── generate.php          # Form generate soal
│       │       ├── describe.php          # Form generate dari materi
│       │       ├── questions.php         # Bank soal (tabel + filter)
│       │       ├── question-detail.php   # Detail soal + export
│       │       ├── materials.php         # Manajemen materi
│       │       ├── account.php           # Akun & top-up
│       │       ├── subjects.php          # Manajemen mata pelajaran
│       │       ├── classes.php           # Manajemen kelas
│       │       ├── tutorial.php          # Tutorial penggunaan
│       │       ├── report.php            # Form laporan bug
│       │       ├── sidebar.php           # Navigasi sidebar
│       │       ├── modals.php            # Modal global
│       │       ├── edit-question-modal.php  # Modal edit soal
│       │       ├── subject-edit-modal.php   # Modal edit mapel
│       │       └── class-edit-modal.php     # Modal edit kelas
│       └── scripts/
│           ├── css/
│           │   ├── style.php   # CSS utama
│           │   └── tools.php   # CSS khusus tools
│           └── js/
│               ├── lang.php     # Multi-language support
│               ├── lang.js      # File terjemahan
│               ├── router.php   # SPA router + hash navigation
│               ├── sidebar.php  # Sidebar toggle logic
│               └── tools.php    # Tools logic (generate, export, quiz, dll)
├── helper/
│   └── utils.php             # Fungsi utility (escapeHtml, logging, dll)
├── vendor/                   # Composer dependencies (mpdf, phpword)
├── index.php                 # Entry point + SPA router + SEO + layout utama
├── composer.json             # Composer config
├── sitemap.xml               # Sitemap untuk SEO
└── sql-migrations/           # Migrasi database (kosong — gunakan file SQL manual)
```

## API Endpoints

Semua endpoint ada di folder `backend/`, dipanggil via HTTP POST/GET.

| Endpoint | Method | Deskripsi |
|---|---|---|
| `backend/auth.php?action=login` | POST | Login/register via Firebase |
| `backend/auth.php?action=logout` | GET | Logout |
| `backend/auth.php?action=sync` | POST | Sinkron data user dari Firebase |
| `backend/auth.php?action=check` | GET | Cek status login |
| `backend/auth.php?action=user` | GET | Ambil data user |
| `backend/api.php` | POST | Generate soal berdasarkan topik |
| `backend/api-describe.php` | POST | Generate soal berdasarkan materi |
| `backend/question.php?action=list` | GET | List bank soal (paginate + filter) |
| `backend/question.php?action=get` | GET | Detail soal |
| `backend/question.php?action=save` | POST | Simpan soal ke bank |
| `backend/question.php?action=update` | POST | Update soal |
| `backend/question.php?action=delete` | POST | Hapus soal |
| `backend/class.php?action=list` | GET | List kelas |
| `backend/class.php?action=get` | GET | Detail kelas |
| `backend/class.php?action=add` | POST | Tambah kelas |
| `backend/class.php?action=edit` | POST | Edit kelas |
| `backend/class.php?action=delete` | POST | Hapus kelas |
| `backend/subject.php?action=list` | GET | List mata pelajaran |
| `backend/subject.php?action=add` | POST | Tambah mapel |
| `backend/subject.php?action=edit` | POST | Edit mapel |
| `backend/subject.php?action=delete` | POST | Hapus mapel |
| `backend/export.php` | POST | Export PDF/DOC |
| `backend/export-googleform.php` | POST | Export ke Google Forms |
| `backend/topup.php?action=create` | POST | Buat transaksi top-up |
| `backend/topup.php?action=history` | GET | Riwayat top-up |
| `backend/topup.php?action=credit_history` | GET | Riwayat penggunaan kredit |
| `backend/topup.php?action=notification` | POST | Notifikasi Midtrans |
| `backend/topup.php?action=verify` | GET | Verifikasi status transaksi |
| `backend/upload.php` | POST | Upload file materi |
| `backend/upload.php?action=list_materials` | GET | List materi tersimpan |
| `backend/upload.php?action=save_material` | POST | Simpan materi dari teks |
| `backend/upload.php?action=generate_material` | POST | Generate materi via AI |
| `backend/upload-question-image.php?action=upload` | POST | Upload gambar soal |
| `backend/report.php?action=submit` | POST | Kirim laporan bug |
| `backend/admin.php?action=check` | GET | Cek status admin |
| `backend/admin.php?action=dashboard` | GET | Statistik admin |
| `backend/admin.php?action=users` | GET | List semua user |
| `backend/admin.php?action=manual_topup` | POST | Top-up manual oleh admin |

## Instalasi

### Prasyarat

- PHP 8.0+
- MySQL
- Composer
- Ekstensi PHP: `curl`, `mbstring`, `pdo_mysql`, `gd` (untuk PDF)

### Langkah-langkah

1. **Clone repositori**

```bash
git clone https://github.com/username/jagoansoal-m1.git
cd jagoansoal-m1
```

2. **Install dependencies Composer**

```bash
composer install
```

3. **Buat database MySQL**

Buat database baru, lalu import struktur tabel (bisa dari dump SQL atau buat manual sesuai skema di kode).

4. **Konfigurasi environment**

```bash
cp backend/.env backend/.env.local
# lalu edit .env.local
```

5. **Edit file `backend/.env`**

Isi konfigurasi berikut:

| Variable | Wajib | Deskripsi |
|---|---|---|
| `DB_HOST` | Ya | Host database |
| `DB_NAME` | Ya | Nama database |
| `DB_USER` | Ya | User database |
| `DB_PASS` | Ya | Password database |
| `AI_API_KEY` | Ya | API key untuk Pollinations AI |
| `AI_API_URL` | Tidak | URL API AI (default: Pollinations) |
| `AI_MODEL` | Tidak | Model AI (default: deepseek) |
| `FIREBASE_API_KEY` | Ya | Firebase API key |
| `FIREBASE_AUTH_DOMAIN` | Ya | Firebase auth domain |
| `FIREBASE_PROJECT_ID` | Ya | Firebase project ID |
| `FIREBASE_STORAGE_BUCKET` | Ya | Firebase storage bucket |
| `FIREBASE_MESSAGING_SENDER_ID` | Ya | Firebase sender ID |
| `FIREBASE_APP_ID` | Ya | Firebase app ID |
| `MIDTRANS_SERVER_KEY` | Ya (jika pakai top-up) | Midtrans server key |
| `MIDTRANS_CLIENT_KEY` | Ya (jika pakai top-up) | Midtrans client key |
| `MIDTRANS_IS_PRODUCTION` | Tidak | Mode produksi Midtrans (true/false) |
| `ADMIN_EMAILS` | Ya | Email admin (pisahkan dengan koma) |
| `APP_URL` | Ya | URL aplikasi (contoh: `http://localhost:8000`) |
| `GOOGLE_CLIENT_ID` | Ya (untuk Google Form) | Google OAuth client ID |
| `UPLOAD_METHOD` | Tidak | Metode upload (default: local) |

6. **Jalankan development server**

```bash
php -S localhost:8000
```

7. **Buka di browser**

```
http://localhost:8000
```

## Alur Penggunaan

### Generate Soal (berdasarkan topik)
1. Login dengan Google
2. Pilih menu **Generate Soal**
3. Pilih kelas, masukkan topik, pilih mata pelajaran
4. Atur jumlah soal, tingkat kesulitan, dan tipe soal
5. Klik **Generate** — AI akan memproses
6. Soal tampil, bisa diedit, disimpan ke bank soal, atau diexport

### Generate Soal (berdasarkan materi)
1. Login → **Generate dari Materi**
2. Upload file (TXT/PDF/DOC) atau tempel teks materi
3. Atur parameter soal
4. Klik **Generate**

### Export Soal
- Setelah soal digenerate atau dari bank soal
- Pilih format: TXT, PDF, DOC, atau Google Forms
- Atur opsi: sertakan jawaban, pembahasan, header, info soal, biodata, petunjuk
- Pilih posisi kunci jawaban: per soal atau di akhir

### Top-Up Kredit
1. Login → **Akun**
2. Masukkan jumlah kredit yang diinginkan
3. Pilih metode pembayaran via Midtrans
4. Setelah bayar, kredit otomatis bertambah

## Skema Database (tabel utama)

- `users` — Data user (id, email, display_name, photo_url, credit, created_at, last_login)
- `classes` — Kelas/jenjang (id, user_id, name)
- `subjects` — Mata pelajaran (id, user_id, name, class_id, description)
- `questions` — Bank soal (id, user_id, subject_id, title, class, difficulty, type, total_questions, questions_data, prompt_used, created_at)
- `materials` — Materi tersimpan (id, user_id, subject_id, title, content, created_at)
- `topup_transactions` — Riwayat top-up (id, user_id, credits, total_price, status, midtrans_order_id, dll)
- `credit_usage` — Riwayat penggunaan kredit (id, user_id, amount, description, created_at)
- `reports` — Laporan bug (id, user_id, subject, description, image_url, status, created_at)
- `rate_limits` — Rate limiting (id, user_id, created_at)
- `landing_settings` — Pengaturan landing page (key-value)
- `how_it_works` — Step cara kerja (id, step_number, title, description)
- `faq_items` — FAQ landing page (id, question, answer, sort_order, is_active)
- `tutorials` — Video tutorial (id, title, description, video_url, video_id, sort_order, is_active)
- `social_links` — Link sosial media (id, platform, url, icon, sort_order)

## Kontribusi

1. Fork repositori
2. Buat branch fitur (`git checkout -b fitur-keren`)
3. Commit perubahan (`git commit -m 'Tambah fitur keren'`)
4. Push ke branch (`git push origin fitur-keren`)
5. Buka Pull Request

## Lisensi

Hak cipta © 2026 Tim Decode Universitas Dehasen Bengkulu. Seluruh hak cipta dilindungi.

---

Dibuat dengan ❤️ untuk guru Indonesia.
