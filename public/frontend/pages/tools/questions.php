      <!-- Panel: Bank Soal -->
      <div id="panel-questions" class="tools-panel">
        <h2 class="panel-header">
          <i class="fas fa-layer-group"></i>
          <span data-lang-key="tools.questions">Bank Soal</span>
        </h2>

        <div class="filter-bar">
          <select id="qFilterSubject" onchange="loadQuestions(1)">
            <option value="" data-lang-key="generate.all_subjects">Semua Pelajaran</option>
          </select>
          <select id="qFilterClass" onchange="loadQuestions(1)">
            <option value="" data-lang-key="generate.all_classes">Semua Kelas</option>
            <option value="10">Kelas 10</option>
            <option value="11">Kelas 11</option>
            <option value="12">Kelas 12</option>
          </select>
          <select id="qFilterType" onchange="loadQuestions(1)">
            <option value="" data-lang-key="generate.all_types">Semua Tipe</option>
            <option value="multiple_choice" data-lang-key="type.multiple_choice">Pilihan Ganda</option>
            <option value="essay" data-lang-key="type.essay">Essay</option>
            <option value="mixed" data-lang-key="type.mixed">Campuran</option>
          </select>
          <input type="text" id="qFilterSearch" class="filter-search mt-2 md:mt-0" data-lang-key="questions.search_placeholder" placeholder="Cari judul soal..." oninput="debounceSearch()">
        </div>

        <div id="questionsTableWrapper" class="tx-table-wrapper mt-[14px]">
          <table class="tx-table">
            <thead>
              <tr>
                <th style="width:40px;text-align:center;">No</th>
                <th>Judul Soal</th>
                <th>Pelajaran</th>
                <th>Kelas</th>
                <th>Tipe</th>
                <th style="text-align:center;">Jumlah</th>
                <th>Tanggal</th>
                <th style="text-align:center;">Aksi</th>
              </tr>
            </thead>
            <tbody id="questionsTableBody" class="bg-white">
              <tr>
                <td colspan="8">
                  <div class="mat-empty">
                    <div class="loading-spinner"></div>
                    <p data-lang-key="questions.loading">Memuat...</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div id="questionsPagination" class="pagination"></div>
      </div>
