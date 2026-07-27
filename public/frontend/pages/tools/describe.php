      <!-- Panel: Generate dari Materi -->
      <div id="panel-describe" class="tools-panel">
        <h2 class="panel-header">
          <i class="fas fa-book-open"></i>
          <span data-lang-key="tools.describe">Generate dari Materi</span>
        </h2>

        <!-- Form Describe -->
        <div id="descFormSection" class="panel-card mb-4">
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-users"></i>
              <span data-lang-key="generate.class">Kelas</span>
              <span class="tip"><i class="fas fa-circle-info"></i><span class="tip-text">Pilih jenjang kelas untuk menentukan tingkat kesulitan yang sesuai</span></span>
            </label>
            <select id="descClass" class="form-select" onchange="onClassChange('desc')">
              <option value="">-- <span data-lang-key="generate.select">Pilih</span> --</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-heading"></i>
              <span data-lang-key="generate.topic">Topik / Judul Soal</span>
              <span class="tip"><i class="fas fa-circle-info"></i><span class="tip-text">Masukkan topik atau judul soal sebagai panduan AI</span></span>
            </label>
            <input type="text" id="descTopic" class="form-input" data-lang-key="generate.topic_placeholder" placeholder="Contoh: Sistem Persamaan Linear">
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">
                <i class="fas fa-book"></i>
                <span data-lang-key="generate.subject">Mata Pelajaran</span>
                <span class="tip"><i class="fas fa-circle-info"></i><span class="tip-text">Pilih mata pelajaran agar soal sesuai kurikulum</span></span>
              </label>
              <select id="descSubject" class="form-select">
                <option value="">-- <span data-lang-key="generate.select">Pilih</span> --</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">
                <i class="fas fa-list-ol"></i>
                <span data-lang-key="generate.count">Jumlah Soal</span>
                <span class="tip"><i class="fas fa-circle-info"></i><span class="tip-text">Tentukan jumlah soal yang ingin dibuat (maks. 50)</span></span>
              </label>
              <input type="number" id="descCount" class="form-input" value="5" min="1" max="50">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-signal"></i>
              <span data-lang-key="generate.difficulty">Tingkat Kesulitan</span>
              <span class="tip"><i class="fas fa-circle-info"></i><span class="tip-text">Sesuaikan tingkat kesulitan soal dengan kemampuan siswa</span></span>
            </label>
            <div class="card-select-group" id="descDifficulty">
              <div class="card-select selected" data-value="easy">
                <span class="card-select-icon">🌱</span>
                <span class="card-select-label" data-lang-key="difficulty.easy">Mudah</span>
              </div>
              <div class="card-select" data-value="medium">
                <span class="card-select-icon">⚡</span>
                <span class="card-select-label" data-lang-key="difficulty.medium">Sedang</span>
              </div>
              <div class="card-select" data-value="hard">
                <span class="card-select-icon">🔥</span>
                <span class="card-select-label" data-lang-key="difficulty.hard">Sulit</span>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-file-lines"></i>
              <span data-lang-key="generate.type">Tipe Soal</span>
              <span class="tip"><i class="fas fa-circle-info"></i><span class="tip-text">Pilih format soal: Pilihan Ganda, Essay, atau Campuran</span></span>
            </label>
            <div class="card-select-group" id="descType">
              <div class="card-select selected" data-value="multiple_choice">
                <span class="card-select-icon">🔘</span>
                <span class="card-select-label" data-lang-key="type.multiple_choice">Pilihan Ganda</span>
              </div>
              <div class="card-select" data-value="essay">
                <span class="card-select-icon">✍️</span>
                <span class="card-select-label" data-lang-key="type.essay">Essay</span>
              </div>
              <div class="card-select" data-value="mixed">
                <span class="card-select-icon">🔀</span>
                <span class="card-select-label" data-lang-key="type.mixed">Campuran</span>
              </div>
            </div>
          </div>

          <div class="form-group" id="descMixedControls" style="display:none;">
            <label class="form-label"><i class="fas fa-sliders"></i> Komposisi Campuran</label>
            <div class="mixed-rows">
              <div class="mixed-row">
                <div class="mix-field">
                  <label>Pilihan Ganda</label>
                  <input type="number" id="descPgCount" class="form-input" min="1" value="3">
                </div>
                <div class="mix-field">
                  <label>Essay</label>
                  <input type="number" id="descEssayCount" class="form-input" min="1" value="2">
                </div>
              </div>
              <div class="mix-order">
                <label>Urutan</label>
                <select id="descQuestionOrder" class="form-select">
                  <option value="random">Acak</option>
                  <option value="pg_first">PG Dulu</option>
                  <option value="essay_first">Essay Dulu</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-folder-open"></i>
              <span data-lang-key="describe.saved_material">Pilih Materi Tersimpan (opsional)</span>
              <span class="tip"><i class="fas fa-circle-info"></i><span class="tip-text">Gunakan materi yang sudah pernah Anda simpan sebelumnya</span></span>
            </label>
            <select id="descSavedMaterial" class="form-select" onchange="onSelectSavedMaterial(this)">
              <option value="">-- <span data-lang-key="generate.select">Pilih</span> --</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-file-lines"></i>
              <span data-lang-key="describe.material_label">Materi / Bahan Ajar</span>
              <span class="tip"><i class="fas fa-circle-info"></i><span class="tip-text">Tempelkan teks materi ajar di sini untuk dianalisis oleh AI</span></span>
            </label>
            <p class="form-help">Tempelkan teks materi / bahan ajar di sini</p>

            <div id="descMethodText" class="input-method-content active" style="padding:0;">
              <textarea id="descMaterialText" class="form-textarea" rows="6" data-lang-key="describe.text_placeholder" placeholder="Tempelkan teks materi di sini..."></textarea>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-align-left"></i>
              <span data-lang-key="generate.instructions">Instruksi Tambahan (opsional)</span>
              <span class="tip"><i class="fas fa-circle-info"></i><span class="tip-text">Berikan instruksi khusus agar hasil lebih spesifik</span></span>
            </label>
            <textarea id="descInstructions" class="form-textarea" rows="2" data-lang-key="generate.instructions_placeholder" placeholder="Contoh: Buat soal dengan studi kasus"></textarea>
          </div>

          <div class="form-group">
            <label class="form-label" style="display:flex;align-items:center;gap:8px;cursor:pointer;">
              <input type="checkbox" id="descDisplayAsQuiz" style="width:18px;height:18px;accent-color:var(--primary);">
              <span style="font-size:14px;font-weight:500;">Tampilkan sebagai Quiz</span>
              <span class="tip"><i class="fas fa-circle-info"></i><span class="tip-text">Soal akan ditampilkan sebagai kuis interaktif yang bisa dijawab langsung</span></span>
            </label>
          </div>

          <div class="generate-info">
            <i class="fas fa-info-circle"></i>
            <span id="descCreditInfo">Generate ini akan menggunakan <strong>3 credits</strong></span>
          </div>

          <button id="descGenerateBtn" class="btn-generate" onclick="callDescribeAPI()">
            <i class="fas fa-wand-magic-sparkles"></i>
            <span data-lang-key="describe.btn">Generate Soal dari Materi</span>
          </button>
        </div>

        <!-- Loading Card Describe -->
        <div id="descLoading" class="panel-card gen-loading-card" style="display:none">
          <div class="loading-spinner"></div>
          <p class="generation-loading-text" data-lang-key="loading.text">Sedang memproses materi...</p>
          <p class="generation-loading-hint" data-lang-key="loading.hint">AI menganalisis materi dan membuat soal. Harap tunggu.</p>
        </div>

        <!-- Result Card Describe -->
        <div id="descResult" class="result-section" style="display:none"></div>
      </div>
