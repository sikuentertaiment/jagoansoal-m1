      <!-- Modal: Edit Kelas -->
      <div id="classEditModal" class="modal-overlay">
        <div class="topup-modal" style="max-width:420px;">
          <div class="topup-modal-header">
            <h3>Edit Kelas</h3>
            <button class="topup-modal-close" onclick="closeClassEditModal()"><i class="fas fa-times"></i></button>
          </div>
          <div class="topup-modal-body">
            <input type="hidden" id="classEditId" value="">
            <div class="form-group">
              <label class="form-label">
                <i class="fas fa-graduation-cap"></i>
                <span>Nama Kelas</span>
              </label>
              <input type="text" id="classEditName" class="form-input" placeholder="Nama kelas..." onkeydown="if(event.key==='Enter')saveClassEdit()">
            </div>
            <div id="classEditError" class="error-msg"></div>
            <div style="display:flex;gap:8px;margin-top:4px;">
              <button class="btn-secondary" onclick="saveClassEdit()">
                <i class="fas fa-save"></i> <span>Simpan</span>
              </button>
              <button class="btn-secondary" style="background:var(--gray-200);color:var(--gray-600);" onclick="closeClassEditModal()">
                Batal
              </button>
            </div>
          </div>
        </div>
      </div>
