      <!-- Modal: Edit Mata Pelajaran -->
      <div id="subjectEditModal" class="modal-overlay">
        <div class="topup-modal" style="max-width:420px;">
          <div class="topup-modal-header">
            <h3>Edit Mata Pelajaran</h3>
            <button class="topup-modal-close" onclick="closeSubjectEditModal()"><i class="fas fa-times"></i></button>
          </div>
          <div class="topup-modal-body">
            <input type="hidden" id="subjectEditId" value="">
            <div class="form-group">
              <label class="form-label">
                <i class="fas fa-book"></i>
                <span>Nama Mata Pelajaran</span>
              </label>
              <input type="text" id="subjectEditName" class="form-input" placeholder="Nama mata pelajaran..." onkeydown="if(event.key==='Enter')saveSubjectEdit()">
            </div>
            <div class="form-group">
              <label class="form-label">
                <i class="fas fa-graduation-cap"></i>
                <span>Kelas</span>
              </label>
              <select id="subjectEditClass" class="form-select">
                <option value="">-- Tanpa Kelas --</option>
              </select>
            </div>
            <div id="subjectEditError" class="error-msg"></div>
            <div style="display:flex;gap:8px;margin-top:4px;">
              <button class="btn-secondary" onclick="saveSubjectEdit()">
                <i class="fas fa-save"></i> <span>Simpan</span>
              </button>
              <button class="btn-secondary" style="background:var(--gray-200);color:var(--gray-600);" onclick="closeSubjectEditModal()">
                Batal
              </button>
            </div>
          </div>
        </div>
      </div>
