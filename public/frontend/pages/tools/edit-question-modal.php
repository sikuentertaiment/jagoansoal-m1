      <!-- Modal: Edit Soal -->
      <div id="editQuestionModal" class="modal-overlay">
        <div class="topup-modal" style="max-width:560px;">
          <div class="topup-modal-header">
            <h3><i class="fas fa-pen"></i> Edit Soal</h3>
            <button class="topup-modal-close" onclick="closeEditQuestionModal()"><i class="fas fa-times"></i></button>
          </div>
          <div class="topup-modal-body">
            <input type="hidden" id="eqIndex" value="">
            <input type="hidden" id="eqSource" value="">
            <input type="hidden" id="eqBankId" value="">
            <div class="form-group">
              <label class="form-label"><i class="fas fa-question"></i> Teks Soal</label>
              <textarea id="eqQuestion" class="form-textarea" rows="3"></textarea>
            </div>
            <div id="eqOptionsSection">
              <div class="form-group">
                <label class="form-label"><i class="fas fa-list"></i> Pilihan Jawaban <span style="font-weight:400;color:var(--gray-400);">(PG saja)</span></label>
                <div id="eqOptionsList"></div>
                <button class="export-add-field" onclick="addEditOption()" style="margin-top:4px;">+ Tambah Opsi</button>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label"><i class="fas fa-check-circle"></i> Jawaban Benar</label>
              <input type="text" id="eqAnswer" class="form-input" placeholder="Contoh: A. 5">
            </div>
            <div class="form-group">
              <label class="form-label"><i class="fas fa-comment"></i> Pembahasan</label>
              <textarea id="eqExplanation" class="form-textarea" rows="2"></textarea>
            </div>
            <div class="form-group">
              <label class="form-label"><i class="fas fa-image"></i> Gambar Soal</label>
              <div style="display:flex;gap:8px;">
                <input type="text" id="eqImageUrl" class="form-input" placeholder="URL gambar" style="flex:1;">
                <button class="btn-secondary" style="width:auto;padding:8px 12px;font-size:12px;" onclick="document.getElementById('eqFileInput').click()"><i class="fas fa-upload"></i> Upload</button>
              </div>
              <input type="file" id="eqFileInput" accept="image/jpeg,image/png,image/gif,image/webp" style="display:none;" onchange="handleEditImageUpload(this)">
              <div id="eqImagePreview" style="margin-top:6px;"></div>
            </div>
            <div id="eqError" class="error-msg"></div>
            <button class="btn-secondary" onclick="saveQuestionEdit()"><i class="fas fa-save"></i> Simpan Perubahan</button>
          </div>
        </div>
      </div>
