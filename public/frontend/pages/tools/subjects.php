      <!-- Panel: Mata Pelajaran -->
      <div id="panel-subjects" class="tools-panel">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
          <h2 class="panel-header" style="margin-bottom:0;">
            <i class="fas fa-book"></i>
            <span data-lang-key="tools.subjects">Mata Pelajaran</span>
          </h2>
        </div>

        <div>
          <div style="display:flex;gap:8px;margin-bottom:14px;flex-wrap:wrap;">
            <input type="text" id="subjectInput" class="form-input" placeholder="Nama mata pelajaran..." style="flex:1;min-width:160px;" onkeydown="if(event.key==='Enter')addSubject()">
            <select id="subjectClassSelect" class="form-select" style="width:auto;min-width:140px;">
              <option value="">-- Tanpa Kelas --</option>
            </select>
            <button class="btn-secondary" style="width:auto;padding:8px 16px;" onclick="addSubject()">
              <i class="fas fa-plus"></i> <span data-lang-key="materials.add">Tambah</span>
            </button>
          </div>
          <div id="subjectInputError" class="error-msg mb-3"></div>

          <div id="subjectListContainer" class="tx-table-wrapper">
            <table class="tx-table">
              <thead>
                <tr>
                  <th style="width:40px;text-align:center;">No</th>
                  <th data-lang-key="settings.subjects">Mata Pelajaran</th>
                  <th style="width:100px;text-align:center;" data-lang-key="materials.actions">Aksi</th>
                </tr>
              </thead>
              <tbody id="subjectTableBody" class="bg-white">
                <tr>
                  <td colspan="3">
                    <div class="mat-empty">
                      <div class="loading-spinner"></div>
                      <p>Memuat...</p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
