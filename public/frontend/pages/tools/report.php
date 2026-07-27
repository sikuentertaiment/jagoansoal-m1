      <!-- Panel: Laporan -->
      <div id="panel-report" class="tools-panel">
        <h2 class="panel-header">
          <i class="fas fa-bug"></i>
          <span data-lang-key="tools.report">Laporan</span>
        </h2>

        <div class="panel-card report-form">
          <p style="font-size:14px;color:var(--gray-500);margin-bottom:16px;" data-lang-key="report.desc">Menemukan bug atau masalah? Laporkan dan kami akan perbaiki!</p>

          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-tag"></i>
              <span data-lang-key="report.subject">Subjek</span>
            </label>
            <select id="reportSubject" class="form-select">
              <option value="bug" data-lang-key="report.bug">Bug / Error</option>
              <option value="feature" data-lang-key="report.feature">Permintaan Fitur</option>
              <option value="ui" data-lang-key="report.ui">Masalah UI/UX</option>
              <option value="other" data-lang-key="report.other">Lainnya</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-align-left"></i>
              <span data-lang-key="report.description">Deskripsi</span>
            </label>
            <textarea id="reportDescription" class="form-textarea" rows="4" data-lang-key="report.description_placeholder" placeholder="Jelaskan masalah yang Anda temui..."></textarea>
          </div>

          <div class="form-group">
            <label class="form-label">
              <i class="fas fa-image"></i>
              <span data-lang-key="report.screenshot">Screenshot (opsional)</span>
            </label>
            <div id="reportUploadArea" class="upload-area" onclick="document.getElementById('reportUploadInput').click()" style="padding:16px;">
              <div id="reportUploadPlaceholder">
                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                <p class="upload-text" data-lang-key="input.click_upload">Klik untuk upload</p>
                <p class="upload-hint" data-lang-key="upload.hint_report">JPEG, PNG (max 5MB)</p>
              </div>
              <div id="reportUploadPreview" class="upload-preview" style="display:none;">
                <img id="reportUploadImg" src="" alt="Preview" style="margin: auto;max-width:200px;max-height:150px;border-radius:8px;">
                <button type="button" class="upload-remove" onclick="event.stopPropagation(); removeReportUpload()">
                  <i class="fas fa-times"></i> <span data-lang-key="report.remove"></span>
                </button>
              </div>
            </div>
            <input type="file" id="reportUploadInput" class="upload-input" accept="image/jpeg,image/png,image/webp" onchange="handleReportUpload(this)">
            <input type="hidden" id="reportImageUrl">
          </div>

          <button class="btn-generate" onclick="submitReport()">
            <i class="fas fa-paper-plane"></i>
            <span data-lang-key="report.submit">Kirim Laporan</span>
          </button>
          <div id="reportSuccess" class="success-msg"></div>
          <div id="reportError" class="error-msg"></div>
        </div>
      </div>
