      <!-- Panel: Generate Soal -->
      <div id="panel-generate" class="tools-panel active">
        <h2 class="panel-header">
          <i class="fas fa-file-pen"></i>
          <span data-lang-key="tools.generate">Generate Soal</span>
        </h2>

        <!-- Form Generate -->
        <div id="genFormSection" class="panel-card mb-4">
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-users"></i>
              <span data-lang-key="generate.class">Kelas</span>
              <span class="tip"><i class="fas fa-circle-info"></i><span class="tip-text">Pilih jenjang kelas untuk menentukan tingkat kesulitan yang sesuai</span></span>
            </label>
            <select id="genClass" class="form-select" onchange="onClassChange('gen')">
              <option value="">-- <span data-lang-key="generate.select">Pilih</span> --</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-heading"></i>
              <span data-lang-key="generate.topic">Topik / Judul Soal</span>
              <span class="tip"><i class="fas fa-circle-info"></i><span class="tip-text">Masukkan topik atau judul soal sebagai panduan AI</span></span>
            </label>
            <input type="text" id="genTopic" class="form-input" data-lang-key="generate.topic_placeholder" placeholder="Contoh: Sistem Persamaan Linear">
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">
                <i class="fas fa-book"></i>
                <span data-lang-key="generate.subject">Mata Pelajaran</span>
                <span class="tip"><i class="fas fa-circle-info"></i><span class="tip-text">Pilih mata pelajaran agar soal sesuai kurikulum</span></span>
              </label>
              <select id="genSubject" class="form-select">
                <option value="">-- <span data-lang-key="generate.select">Pilih</span> --</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">
                <i class="fas fa-list-ol"></i>
                <span data-lang-key="generate.count">Jumlah Soal</span>
                <span class="tip"><i class="fas fa-circle-info"></i><span class="tip-text">Tentukan jumlah soal yang ingin dibuat (maks. 50)</span></span>
              </label>
              <input type="number" id="genCount" class="form-input" value="5" min="1" max="50">
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-signal"></i>
              <span data-lang-key="generate.difficulty">Tingkat Kesulitan</span>
              <span class="tip"><i class="fas fa-circle-info"></i><span class="tip-text">Sesuaikan tingkat kesulitan soal dengan kemampuan siswa</span></span>
            </label>
            <div class="card-select-group" id="genDifficulty">
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
            <div class="card-select-group" id="genType">
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

          <div class="form-group" id="genMixedControls" style="display:none;">
            <label class="form-label"><i class="fas fa-sliders"></i> Komposisi Campuran</label>
            <div class="mixed-rows">
              <div class="mixed-row">
                <div class="mix-field">
                  <label>Pilihan Ganda</label>
                  <input type="number" id="genPgCount" class="form-input" min="1" value="3">
                </div>
                <div class="mix-field">
                  <label>Essay</label>
                  <input type="number" id="genEssayCount" class="form-input" min="1" value="2">
                </div>
              </div>
              <div class="mix-order">
                <label>Urutan</label>
                <select id="genQuestionOrder" class="form-select">
                  <option value="random">Acak</option>
                  <option value="pg_first">PG Dulu</option>
                  <option value="essay_first">Essay Dulu</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-align-left"></i>
              <span data-lang-key="generate.instructions">Instruksi Tambahan (opsional)</span>
              <span class="tip"><i class="fas fa-circle-info"></i><span class="tip-text">Berikan instruksi khusus agar hasil lebih spesifik</span></span>
            </label>
            <textarea id="genInstructions" class="form-textarea" rows="3" data-lang-key="generate.instructions_placeholder" placeholder="Contoh: Buat soal dengan studi kasus"></textarea>
          </div>

          <div class="form-group">
            <label class="form-label" style="display:flex;align-items:center;gap:8px;cursor:pointer;">
              <input type="checkbox" id="genDisplayAsQuiz" style="width:18px;height:18px;accent-color:var(--primary);">
              <span style="font-size:14px;font-weight:500;">Tampilkan sebagai Quiz</span>
              <span class="tip"><i class="fas fa-circle-info"></i><span class="tip-text">Soal akan ditampilkan sebagai kuis interaktif yang bisa dijawab langsung</span></span>
            </label>
          </div>

          <div class="generate-info">
            <i class="fas fa-info-circle"></i>
            <span id="genCreditInfo">Generate ini akan menggunakan <strong>1 credit</strong></span>
          </div>

          <button id="generateBtn" class="btn-generate" onclick="callGenerateAPI()">
            <i class="fas fa-wand-magic-sparkles"></i>
            <span data-lang-key="generate.btn">Generate Soal</span>
          </button>
        </div>

        <!-- Loading Card Generate -->
        <div id="genLoading" class="panel-card gen-loading-card" style="display:none">
          <div class="loading-spinner"></div>
          <p class="generation-loading-text" data-lang-key="loading.text">Sedang memproses soal...</p>
          <p class="generation-loading-hint" data-lang-key="loading.hint">AI sedang membuat soal. Harap tunggu beberapa saat.</p>
        </div>

        <!-- Result Card Generate -->
        <div id="genResult" class="result-section" style="display:none"></div>
      </div>
