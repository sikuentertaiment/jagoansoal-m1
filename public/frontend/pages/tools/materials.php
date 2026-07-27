      <!-- Panel: Materi -->
      <div id="panel-materials" class="tools-panel">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
          <h2 class="panel-header" style="margin-bottom:0;">
            <i class="fas fa-folder"></i>
            <span data-lang-key="tools.materials">Materi</span>
          </h2>
        </div>

        <div class="filter-bar flex flex-wrap gap-3" style="margin-bottom:14px;">
          <select id="matFilterSubject" onchange="loadMaterials(1)">
            <option value="" data-lang-key="generate.all_subjects">Semua Pelajaran</option>
          </select>
          <input type="text" id="matFilterSearch" class="filter-search" data-lang-key="materials.search_placeholder" placeholder="Cari judul materi..." oninput="debounceMatSearch()">
          <div class="flex gap-2">
            <button class="btn-secondary" style="width:auto;padding:8px 16px;background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:#fff;border:none;" onclick="openGenerateMaterialModal()">
              <i class="fas fa-wand-magic-sparkles"></i>
              <span>Generate AI</span>
            </button>
            <button class="btn-secondary" style="width:auto;padding:8px 16px;" onclick="openAddMaterialModal()">
              <i class="fas fa-plus"></i>
              <span data-lang-key="materials.add">Tambah Materi</span>
            </button>
          </div>
        </div>

        <div id="materialsTableWrapper" class="tx-table-wrapper">
          <table class="tx-table">
            <thead>
              <tr>
                <th style="width:40px;text-align:center;">No</th>
                <th data-lang-key="materials.title">Judul</th>
                <th class="mat-hide-mobile" data-lang-key="generate.subject">Mata Pelajaran</th>
                <th class="mat-hide-mobile" style="width:100px;" data-lang-key="materials.date">Tanggal</th>
                <th style="width:100px;text-align:center;" data-lang-key="materials.actions">Aksi</th>
              </tr>
            </thead>
            <tbody id="materialsTableBody" class="bg-white">
              <tr>
                <td colspan="5">
                  <div class="mat-empty">
                    <div class="loading-spinner"></div>
                    <p data-lang-key="materials.loading">Memuat...</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div id="materialsPagination" class="pagination"></div>
      </div>

      <!-- Modal: Tambah/Edit Materi -->
      <div id="materialModal" class="modal-overlay">
        <div class="topup-modal" style="max-width:640px;">
          <div class="topup-modal-header">
            <h3 id="materialModalTitle" data-lang-key="materials.add">Tambah Materi</h3>
            <button class="topup-modal-close" onclick="closeMaterialModal()"><i class="fas fa-times"></i></button>
          </div>
          <div class="topup-modal-body">
            <input type="hidden" id="matEditId" value="">
            <div class="form-group">
              <label class="form-label">
                <i class="fas fa-heading"></i>
                <span data-lang-key="materials.title">Judul Materi</span>
              </label>
              <input type="text" id="matTitle" class="form-input" data-lang-key="materials.title_placeholder" placeholder="Contoh: Bab 1 - Sistem Persamaan">
            </div>
            <div class="form-group">
              <label class="form-label">
                <i class="fas fa-book"></i>
                <span data-lang-key="generate.subject">Mata Pelajaran</span>
              </label>
              <select id="matSubject" class="form-select">
                <option value="">-- <span data-lang-key="generate.select">Pilih</span> --</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">
                <i class="fas fa-file-lines"></i>
                <span data-lang-key="materials.content">Konten Materi</span>
              </label>
              <textarea id="matContent" class="form-textarea" rows="8" data-lang-key="materials.content_placeholder" placeholder="Tulis konten materi di sini..."></textarea>
            </div>
            <div id="matModalError" class="error-msg mb-3"></div>
            <button class="btn-secondary" onclick="saveMaterial()">
              <i class="fas fa-save"></i>
              <span data-lang-key="materials.save">Simpan Materi</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Modal: Detail Materi -->
      <div id="materialDetailModal" class="modal-overlay">
        <div class="topup-modal" style="max-width:600px;">
          <div class="topup-modal-header">
            <h3 id="matDetailTitle">Detail Materi</h3>
            <button class="topup-modal-close" onclick="closeMaterialDetailModal()"><i class="fas fa-times"></i></button>
          </div>
          <div id="matDetailMeta" style="padding:0 20px 10px;font-size:12px;color:var(--gray-400);"></div>
          <div id="matDetailContent" class="material-detail-content"></div>
        </div>
      </div>

      <!-- Modal: Generate Materi AI -->
      <div id="generateMaterialModal" class="modal-overlay">
        <div class="topup-modal" style="max-width:640px;">
          <div class="topup-modal-header">
            <h3><i class="fas fa-wand-magic-sparkles" style="color:var(--primary);"></i> Generate Materi dengan AI</h3>
            <button class="topup-modal-close" onclick="closeGenerateMaterialModal()"><i class="fas fa-times"></i></button>
          </div>
          <div class="topup-modal-body">
            <div id="genMatInputs">
              <div class="form-group">
                <label class="form-label"><i class="fas fa-heading"></i> Topik Materi</label>
                <input type="text" id="genMatTopic" class="form-input" placeholder="Contoh: Sistem Persamaan Linear, Teori Evolusi, Proklamasi Kemerdekaan...">
              </div>
              <div class="form-group">
                <label class="form-label"><i class="fas fa-book"></i> Mata Pelajaran</label>
                <select id="genMatSubject" class="form-select">
                  <option value="">-- Pilih --</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label"><i class="fas fa-graduation-cap"></i> Kelas</label>
                <select id="genMatClass" class="form-select">
                  <option value="">-- Pilih --</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label"><i class="fas fa-list"></i> Instruksi Tambahan <span style="color:var(--gray-400);font-weight:400;">(opsional)</span></label>
                <textarea id="genMatInstructions" class="form-textarea" rows="3" placeholder="Contoh: Fokus pada contoh soal, sertakan rumus-rumus penting..."></textarea>
              </div>
              <div id="genMatError" class="error-msg"></div>
              <div class="form-help" style="margin-bottom:10px;display:flex;align-items:center;gap:6px;">
                <i class="fas fa-coins" style="color:var(--amber-500,#F59E0B);font-size:12px;"></i>
                <span style="font-size:12px;color:var(--gray-500);">Mengenerate materi akan memakan <strong style="color:var(--gray-700);">1 kredit</strong></span>
              </div>
              <button class="btn-secondary" id="genMatBtn" onclick="generateMaterial()">
                <i class="fas fa-wand-magic-sparkles"></i> Generate Materi
              </button>
            </div>

            <div id="genMatResult" style="display:none;">
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                <h4 style="font-weight:600;color:var(--gray-800);"><i class="fas fa-check-circle" style="color:var(--secondary);"></i> Hasil Generate</h4>
                <div class="flex gap-2">
                  <button class="btn-secondary" style="width:auto;padding:6px 12px;font-size:12px;" onclick="regenerateMaterial()">
                    <i class="fas fa-rotate"></i> Generate Ulang
                  </button>
                  <button class="btn-secondary" id="genMatSaveBtn" style="width:auto;padding:6px 12px;font-size:12px;background:var(--secondary);color:#fff;border:none;" onclick="saveGeneratedMaterial()">
                    <i class="fas fa-save"></i> Simpan ke Materi
                  </button>
                </div>
              </div>
              <div id="genMatContent" class="material-detail-content" style="max-height:400px;overflow-y:auto;padding:14px;border:1px solid var(--gray-200);border-radius:8px;background:var(--gray-50);white-space:pre-wrap;font-size:13px;line-height:1.7;"></div>
            </div>
          </div>
        </div>
      </div>
