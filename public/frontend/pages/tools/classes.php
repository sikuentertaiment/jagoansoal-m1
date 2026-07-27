      <!-- Panel: Kelas -->
      <div id="panel-classes" class="tools-panel">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
          <h2 class="panel-header" style="margin-bottom:0;">
            <i class="fas fa-graduation-cap"></i>
            <span>Kelas</span>
          </h2>
        </div>

        <div>
          <div style="display:flex;gap:8px;margin-bottom:14px;">
            <input type="text" id="classInput" class="form-input" placeholder="Nama kelas..." style="flex:1;" onkeydown="if(event.key==='Enter')addClass()">
            <button class="btn-secondary" style="width:auto;padding:8px 16px;" onclick="addClass()">
              <i class="fas fa-plus"></i> <span>Tambah</span>
            </button>
          </div>
          <div id="classInputError" class="error-msg mb-3"></div>

          <div id="classListContainer" class="tx-table-wrapper">
            <table class="tx-table">
              <thead>
                <tr>
                  <th style="width:40px;text-align:center;">No</th>
                  <th>Nama Kelas</th>
                  <th style="width:100px;text-align:center;">Aksi</th>
                </tr>
              </thead>
              <tbody id="classTableBody" class="bg-white">
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
