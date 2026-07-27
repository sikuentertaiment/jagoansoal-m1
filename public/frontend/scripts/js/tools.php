<script>
(function () {
  let selectedDifficulty = 'easy';
  let selectedType = 'multiple_choice';
  let selectedDescDifficulty = 'easy';
  let selectedDescType = 'multiple_choice';
  
  let currentQuestions = [];
  let currentGenerateTitle = '';
  let questionsSearchTimeout;
  let materialsSearchTimeout;
  let allSubjects = [];

  let quizState = {
    active: false,
    containerId: '',
    answers: [],
    submitted: false,
    results: [],
    mcCorrect: 0,
    mcTotal: 0,
    questions: []
  };

  function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>]/g, m => m === '&' ? '&amp;' : m === '<' ? '&lt;' : '&gt;');
  }

  function setError(el, msg) {
    if (!el) return;
    el.textContent = msg;
    el.classList.add('show');
  }

  window.showFailureCard = function (containerId, message) {
    const container = document.getElementById(containerId);
    if (!container) return;
    const prefix = containerId === 'genResult' ? 'gen' : 'desc';
    const viewValue = '';
    console.log(viewValue)
    container.innerHTML = `
      <div class="panel-card" style="text-align:center;padding:32px 16px;">
        <div style="font-size:40px;margin-bottom:12px;color:var(--red-500);"><i class="fas fa-circle-exclamation"></i></div>
        <h3 style="font-size:16px;font-weight:600;color:var(--gray-800);margin-bottom:8px;">Generate Gagal</h3>
        <p style="font-size:13px;color:var(--gray-500);margin-bottom:20px;">${escapeHtml(message)}</p>
        </div>
        <button class="btn-outline mt-2" onclick="window.resetGenerateForm('${prefix}')"><i class="fas fa-arrow-left"></i> Kembali ke Form</button>
    `;
    container.style.display = 'block';
  };

  window.resetGenerateForm = function (prefix) {
    window.__currentBankId = null;
    const formSection = document.getElementById(prefix + 'FormSection');
    const loading = document.getElementById(prefix + 'Loading');
    const result = document.getElementById(prefix + 'Result');
    if (formSection) formSection.style.display = 'block';
    if (loading) loading.style.display = 'none';
    if (result) { result.style.display = 'none'; result.innerHTML = ''; }
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  function toggleMixedControls(groupId, isMixed) {
    const controlsId = groupId === 'genType' ? 'genMixedControls' : 'descMixedControls';
    const el = document.getElementById(controlsId);
    if (el) el.style.display = isMixed ? 'block' : 'none';
  }

  function balanceMixedCounts(prefix) {
    const total = parseInt(document.getElementById(prefix + 'Count').value) || 5;
    const pg = parseInt(document.getElementById(prefix + 'PgCount').value) || 1;
    const essay = total - pg;
    document.getElementById(prefix + 'EssayCount').value = Math.max(1, Math.min(essay, total - 1));
    if (essay < 1) document.getElementById(prefix + 'PgCount').value = total - 1;
  }

  document.querySelectorAll('.card-select-group').forEach(group => {
    group.addEventListener('click', function (e) {
      const card = e.target.closest('.card-select');
      if (!card) return;
      group.querySelectorAll('.card-select').forEach(c => c.classList.remove('selected'));
      card.classList.add('selected');
      toggleMixedControls(group.id, card.dataset.value === 'mixed');
    });
  });

  ['gen', 'desc'].forEach(function (prefix) {
    document.getElementById(prefix + 'PgCount')?.addEventListener('input', function () {
      const total = parseInt(document.getElementById(prefix + 'Count').value) || 5;
      let pg = parseInt(this.value) || 1;
      pg = Math.max(1, Math.min(pg, total - 1));
      this.value = pg;
      document.getElementById(prefix + 'EssayCount').value = total - pg;
    });
    document.getElementById(prefix + 'EssayCount')?.addEventListener('input', function () {
      const total = parseInt(document.getElementById(prefix + 'Count').value) || 5;
      let essay = parseInt(this.value) || 1;
      essay = Math.max(1, Math.min(essay, total - 1));
      this.value = essay;
      document.getElementById(prefix + 'PgCount').value = total - essay;
    });
    document.getElementById(prefix + 'Count')?.addEventListener('input', function () {
      const total = parseInt(this.value) || 5;
      const pg = parseInt(document.getElementById(prefix + 'PgCount').value) || 1;
      const newPg = Math.max(1, Math.min(pg, total - 1));
      document.getElementById(prefix + 'PgCount').value = newPg;
      document.getElementById(prefix + 'EssayCount').value = total - newPg;
    });
  });

  function populateSubjectSelect(sel, subjects) {
    while (sel.options.length > 1) sel.remove(1);
    subjects.forEach(s => {
      const opt = document.createElement('option');
      opt.value = s.id;
      opt.textContent = s.name;
      sel.appendChild(opt);
    });
  }

  async function loadSubjects() {
    try {
      const res = await fetch('../backend/subject.php?action=list');
      const data = await res.json();
      if (!data.success || !data.subjects) return;
      allSubjects = data.subjects;

      populateSubjectSelect(document.getElementById('genSubject'), allSubjects);
      populateSubjectSelect(document.getElementById('descSubject'), allSubjects);
      populateSubjectSelect(document.getElementById('matSubject'), allSubjects);
      populateSubjectSelect(document.getElementById('qFilterSubject'), allSubjects);
      populateSubjectSelect(document.getElementById('matFilterSubject'), allSubjects);
      populateSubjectSelect(document.getElementById('genMatSubject'), allSubjects);
    } catch (e) {
      console.warn('Failed to load subjects:', e);
    }
  }

  async function loadClasses() {
    const selects = document.querySelectorAll('#genClass, #descClass, #qFilterClass, #subjectClassSelect, #subjectEditClass');
    selects.forEach(sel => {
      while (sel.options.length > 1) sel.remove(1);
    });

    try {
      const res = await fetch('../backend/class.php?action=list');
      const data = await res.json();
      if (!data.success || !data.classes) return;

      selects.forEach(sel => {
        data.classes.forEach(c => {
          const opt = document.createElement('option');
          opt.value = c.id;
          opt.textContent = c.name;
          sel.appendChild(opt);
        });
      });
    } catch (e) {
      console.warn('Failed to load classes:', e);
    }
  }

  function filterSubjectDropdown(classId, prefix) {
    const sel = document.getElementById(prefix + 'Subject');
    if (!sel) return;
    while (sel.options.length > 1) sel.remove(1);
    const filtered = classId ? allSubjects.filter(s => String(s.class_id) === String(classId)) : allSubjects;
    filtered.forEach(s => {
      const opt = document.createElement('option');
      opt.value = s.id;
      opt.textContent = s.name;
      sel.appendChild(opt);
    });
  }

  function getCreditCost(count, type) {
    if (type === 'gen') return Math.max(1, Math.ceil(count / 10));
    if (type === 'desc') return Math.max(3, Math.ceil(count / 5));
    return 1;
  }

  function updateCreditInfo(prefix) {
    const countEl = document.getElementById(prefix + 'Count');
    const infoEl = document.getElementById(prefix + 'CreditInfo');
    if (!infoEl || !countEl) return;
    const count = parseInt(countEl.value) || 5;
    const cost = getCreditCost(count, prefix);
    infoEl.innerHTML = 'Generate ini akan menggunakan <strong>' + cost + ' credit' + (cost > 1 ? 's' : '') + '</strong>';
  }

  document.getElementById('genCount')?.addEventListener('input', function () { updateCreditInfo('gen'); });
  document.getElementById('genCount')?.addEventListener('change', function () { updateCreditInfo('gen'); });
  document.getElementById('descCount')?.addEventListener('input', function () { updateCreditInfo('desc'); });
  document.getElementById('descCount')?.addEventListener('change', function () { updateCreditInfo('desc'); });

  window.onClassChange = function (prefix) {
    const classSel = document.getElementById(prefix + 'Class');
    const classId = classSel.value;
    filterSubjectDropdown(classId, prefix);
    if (prefix === 'desc') loadSavedMaterialsDropdown(classId);
  };

  window.switchToolPanel = async function (tool, isFirstLoad) {
    if (tool === 'logout') {
      if (window.handleLogout) window.handleLogout();
      return;
    }
    document.querySelectorAll('.tools-nav-link[data-tool]').forEach(link => {
      link.classList.toggle('active', link.dataset.tool === tool);
    });
    document.querySelectorAll('.tools-panel').forEach(p => {
      p.classList.toggle('active', p.id === 'panel-' + tool);
    });

    // hide question detail section when navigating
    var detailSection = document.getElementById('questionDetailSection');
    if (detailSection) {
      detailSection.classList.remove('active');
      detailSection.style.display = 'none';
    }
    var qFilter = document.querySelector('#panel-questions .filter-bar');
    var qTable = document.getElementById('questionsTableWrapper');
    var qPag = document.getElementById('questionsPagination');
    if (qFilter) qFilter.style.display = '';
    if (qTable) qTable.style.display = '';
    if (qPag) qPag.style.display = '';

    if (tool === 'account') {
      loadAccountPage();
      checkPendingTopUp();
    }
    if (tool === 'questions') loadQuestions(1);
    if (tool === 'materials') loadMaterials(1);
    if (tool === 'subjects') loadSubjectList();
    if (tool === 'classes') loadClassList();
    if (tool === 'generate') {
      const genClassId = document.getElementById('genClass')?.value;
      if (genClassId) filterSubjectDropdown(genClassId, 'gen');
      // make sure the genFormSection is showed
      const genFormSection = document.getElementById('genFormSection');
      if(genFormSection.style.display === 'none'){
        genFormSection.style.display = 'block'
      }
      updateCreditInfo('gen');
    }
    if (tool === 'describe') {
      const descClassId = document.getElementById('descClass')?.value;
      if (descClassId) filterSubjectDropdown(descClassId, 'desc');
      loadSavedMaterialsDropdown(descClassId);
      // make sure the genFormSection is showed
      const descFormSection = document.getElementById('descFormSection');
      if(descFormSection.style.display === 'none'){
        descFormSection.style.display = 'block'
      }
      updateCreditInfo('desc');
    }
    if (tool === 'tutorial') loadTutorials();
  };

  window.toggleToolsSidebar = function () {
    const sidebar = document.getElementById('tools-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('show');
  };

  document.querySelectorAll('.tools-nav-link').forEach(link => {
    link.addEventListener('click', function (e) {
      if (this.dataset.tool === 'logout') return;
      const tool = this.dataset.tool;
      toggleToolsSidebar();
    });
  });

  function getCardSelectValue(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return '';
    const selected = container.querySelector('.card-select.selected');
    return selected ? selected.dataset.value : '';
  }

  window.uploadQuestionImage = function (file, callback) {
    var formData = new FormData();
    formData.append('image', file);
    fetch('../backend/upload-question-image.php?action=upload', {
      method: 'POST',
      body: formData
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
      if (data.success) {
        callback(null, data.url);
      } else {
        callback(data.error || 'Upload failed');
      }
    })
    .catch(function (e) { callback(e.message); });
  };

  window.handleEditImageUpload = function (input) {
    var file = input.files[0];
    if (!file) return;
    if (file.size > 5 * 1024 * 1024) { showAlert('Error', 'File terlalu besar. Maks 5MB', 'warning'); return; }
    var preview = document.getElementById('eqImagePreview');
    preview.innerHTML = '<div class="loading-spinner" style="margin:8px auto;"></div>';
    uploadQuestionImage(file, function (err, url) {
      if (err) { showAlert('Upload Gagal', err, 'warning'); preview.innerHTML = ''; return; }
      document.getElementById('eqImageUrl').value = url;
      preview.innerHTML = '<img src="' + url + '" class="edit-modal-image-preview"><button class="biodata-field-remove" onclick="clearEditImage()" style="margin-left:6px;"><i class="fas fa-times"></i></button>';
    });
    input.value = '';
  };

  window.clearEditImage = function () {
    document.getElementById('eqImageUrl').value = '';
    document.getElementById('eqImagePreview').innerHTML = '';
  };

  window.addEditOption = function (value) {
    var list = document.getElementById('eqOptionsList');
    var row = document.createElement('div');
    row.style.cssText = 'display:flex;gap:6px;margin-bottom:4px;';
    var prefix = String.fromCharCode(65 + list.children.length);
    row.innerHTML = '<span style="font-weight:600;font-size:13px;line-height:34px;width:20px;">' + prefix + '.</span>' +
      '<input type="text" class="form-input" value="' + (value || '') + '" placeholder="Opsi ' + prefix + '" style="flex:1;">' +
      '<button class="biodata-field-remove" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>';
    list.appendChild(row);
  };

  window.openEditQuestionModal = function (index, source, bankId) {
    var questions = source === 'bank' ? window.__detailQuestions : currentQuestions;
    var q = questions[index];
    if (!q) return;

    document.getElementById('eqIndex').value = index;
    document.getElementById('eqSource').value = source;
    document.getElementById('eqBankId').value = bankId || '';
    document.getElementById('eqQuestion').value = q.question || '';

    var optSection = document.getElementById('eqOptionsSection');
    var optList = document.getElementById('eqOptionsList');
    optList.innerHTML = '';
    if (q.options && Array.isArray(q.options) && q.options.length > 0) {
      optSection.style.display = '';
      q.options.forEach(function (opt, oi) {
        var prefix = String.fromCharCode(65 + oi);
        var row = document.createElement('div');
        row.style.cssText = 'display:flex;gap:6px;margin-bottom:4px;';
        var removeBtn = oi > 0 ? '<button class="biodata-field-remove" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>' : '';
        row.innerHTML = '<span style="font-weight:600;font-size:13px;line-height:34px;width:20px;">' + prefix + '.</span>' +
          '<input type="text" class="form-input" value="' + escapeHtml(opt) + '" placeholder="Opsi ' + prefix + '" style="flex:1;">' +
          removeBtn;
        optList.appendChild(row);
      });
    } else {
      optSection.style.display = 'none';
    }

    document.getElementById('eqAnswer').value = q.answer || '';
    document.getElementById('eqExplanation').value = q.explanation || '';

    var imgPreview = document.getElementById('eqImagePreview');
    if (q.image_url) {
      document.getElementById('eqImageUrl').value = q.image_url;
      imgPreview.innerHTML = '<img src="' + q.image_url + '" class="edit-modal-image-preview"><button class="biodata-field-remove" onclick="clearEditImage()" style="margin-left:6px;"><i class="fas fa-times"></i></button>';
    } else {
      document.getElementById('eqImageUrl').value = '';
      imgPreview.innerHTML = '';
    }

    document.getElementById('eqError').classList.remove('show');
    document.getElementById('editQuestionModal').classList.add('active');
    document.body.style.overflow = 'hidden';
  };

  window.saveQuestionEdit = function () {
    var index = parseInt(document.getElementById('eqIndex').value);
    var source = document.getElementById('eqSource').value;
    var bankId = document.getElementById('eqBankId').value;

    var question = document.getElementById('eqQuestion').value.trim();
    if (!question) { showAlert('Validasi', 'Teks soal tidak boleh kosong', 'warning'); return; }

    var options = [];
    document.querySelectorAll('#eqOptionsList input').forEach(function (input) {
      var val = input.value.trim();
      if (val) options.push(val);
    });

    var answer = document.getElementById('eqAnswer').value.trim();
    var explanation = document.getElementById('eqExplanation').value.trim();
    var imageUrl = document.getElementById('eqImageUrl').value.trim();

    var updated = {
      question: question,
      options: options.length > 0 ? options : null,
      answer: answer,
      explanation: explanation,
      image_url: imageUrl || null
    };

    if (source === 'result') {
      if (!currentQuestions[index]) { showAlert('Error', 'Data soal tidak ditemukan', 'warning'); return; }
      currentQuestions[index] = updated;
      if (window.__currentBankId) {
        fetch('../backend/question.php?action=update', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: window.__currentBankId, questions_data: currentQuestions })
        }).then(function (r) { return r.json(); }).then(function (data) {
          if (!data.success) showAlert('Gagal', data.error || 'Gagal menyimpan ke database', 'warning');
        }).catch(function () { showAlert('Error', 'Gagal menyimpan perubahan ke database', 'warning'); });
      }
      var activePanel = document.querySelector('.tools-panel.active');
      var containerId = activePanel && activePanel.id === 'panel-describe' ? 'descResult' : 'genResult';
      renderQuestions(currentQuestions, containerId);
      closeEditQuestionModal();
      showAlert('Berhasil', 'Soal berhasil diperbarui', 'success');
    } else if (source === 'bank') {
      if (!bankId) { showAlert('Error', 'ID bank soal tidak ditemukan', 'warning'); return; }
      var targetQuestions = window.__detailQuestions;
      if (targetQuestions && targetQuestions[index]) {
        targetQuestions[index] = updated;
        renderQuestionDetail(targetQuestions, window.__detailTitle, window.__detailMeta);
      }
      // show detail section if hidden (e.g. edit from bank table)
      var detailSection = document.getElementById('questionDetailSection');
      if (detailSection && !detailSection.classList.contains('active')) {
        var filterBar = document.querySelector('#panel-questions .filter-bar');
        var tableWrapper = document.getElementById('questionsTableWrapper');
        var pagination = document.getElementById('questionsPagination');
        if (filterBar) filterBar.style.display = 'none';
        if (tableWrapper) tableWrapper.style.display = 'none';
        if (pagination) pagination.style.display = 'none';
        detailSection.classList.add('active');
        detailSection.style.display = 'block';
      }
      fetch('../backend/question.php?action=update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: bankId, questions_data: targetQuestions })
      })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data.success) {
          closeEditQuestionModal();
          showAlert('Berhasil', 'Soal berhasil diperbarui', 'success');
        } else {
          showAlert('Gagal', data.error || 'Gagal menyimpan', 'warning');
        }
      })
      .catch(function () { showAlert('Error', 'Gagal menyimpan perubahan', 'warning'); });
    }
  };

  window.closeEditQuestionModal = function () {
    document.getElementById('editQuestionModal').classList.remove('active');
    document.body.style.overflow = '';
  };

  document.getElementById('editQuestionModal')?.addEventListener('click', function (e) {
    if (e.target === this) closeEditQuestionModal();
  });

  function renderQuestionDetail(questions, title, metaHtml) {
    var container = document.getElementById('questionDetailContent');
    if (!container) return;
    var html = '';
    if (metaHtml) html += '<p style="font-size:13px;color:var(--gray-500);margin-bottom:12px;">' + metaHtml + '</p>';

    html += '<div class="export-panel" style="margin-bottom:16px;background:#f8fafc;padding:16px;border:1px solid var(--gray-200);">' +
      '<div class="export-opts-row">' +
        '<label class="export-opt-toggle"><input type="checkbox" id="detailExportIncludeAnswer" checked> Sertakan Jawaban</label>' +
        '<label class="export-opt-toggle"><input type="checkbox" id="detailExportIncludeExplanation" checked> Sertakan Pembahasan</label>' +
        '<select class="export-opt-select" id="detailExportAnswerPosition">' +
          '<option value="per_soal">Jawaban Per Soal</option>' +
          '<option value="akhir">Kunci Jawaban di Akhir</option>' +
        '</select>' +
      '</div>' +
      '<div class="export-advanced-toggle" onclick="toggleExportAdvanced(this)">' +
        '<i class="fas fa-chevron-right chevron"></i> Pengaturan Tambahan' +
      '</div>' +
      '<div class="export-advanced" id="detailExportAdvanced" style="display:none;">' +
        '<label class="export-opt-toggle"><input type="checkbox" id="detailExportShowHeader" checked onchange="toggleExportField(\'header\')"> Tampilkan Header</label>' +
        '<div id="detailExportHeaderInput" style="margin:4px 0 8px 20px;">' +
          '<input type="text" id="detailExportHeaderText" class="export-input" placeholder="Kosongkan untuk menggunakan judul soal">' +
        '</div>' +
        '<label class="export-opt-toggle"><input type="checkbox" id="detailExportShowInfo" checked onchange="toggleExportField(\'info\')"> Tampilkan Info Soal</label>' +
        '<div id="detailExportInfoFields" style="margin:4px 0 8px 20px;"><div id="detailInfoFieldList"></div><button class="export-add-field" onclick="addInfoField()">+ Tambah Field Info</button></div>' +
        '<label class="export-opt-toggle"><input type="checkbox" id="detailExportShowBiodata" onchange="toggleExportField(\'biodata\')"> Tampilkan Biodata Siswa</label>' +
        '<div id="detailExportBiodataFields" style="display:none;margin:4px 0 8px 20px;"><div id="detailBiodataFieldList"></div><button class="export-add-field" onclick="addBiodataField()">+ Tambah Field</button></div>' +
        '<label class="export-opt-toggle"><input type="checkbox" id="detailExportShowInstructions" checked onchange="toggleExportField(\'instructions\')"> Tampilkan Petunjuk</label>' +
        '<div id="detailExportInstructionsInput" style="margin:4px 0 8px 20px;"><textarea id="detailExportInstructionsText" class="export-textarea" rows="2">Jawablah pertanyaan berikut dengan benar!</textarea></div>' +
      '</div>' +
      '<div class="export-btns" style="margin-top:12px;">' +
        '<button class="export-btn-format txt" onclick="exportDetailTxt()"><i class="fas fa-file-alt"></i> TXT</button>' +
        '<button class="export-btn-format pdf" onclick="exportDetailPdf()"><i class="fas fa-file-pdf"></i> PDF</button>' +
        '<button class="export-btn-format doc" onclick="exportDetailDoc()"><i class="fas fa-file-word"></i> DOC</button>' +
        '<button class="export-btn-format gform" onclick="exportDetailGoogleForm()"><i class="fab fa-google"></i> Google Form</button>' +
      '</div>' +
    '</div>';

    questions.forEach(function (item, i) {
      var hasOptions = item.options && Array.isArray(item.options) && item.options.length > 0;
      var optionsHtml = hasOptions ? '<div class="q-options">' + item.options.map(function (opt, oi) {
        return '<div>' + String.fromCharCode(65 + oi) + '. ' + escapeHtml(opt) + '</div>';
      }).join('') + '</div>' : '';
      var imgHtml = item.image_url ? '<img src="' + item.image_url + '" class="q-image" alt="Gambar soal">' : '';
      var answerHtml = item.answer ? '<div class="q-answer">\u2713 ' + escapeHtml(item.answer) + '</div>' : '';
      var explHtml = item.explanation ? '<div class="q-explanation">' + escapeHtml(item.explanation) + '</div>' : '';

      html += '<div class="result-card" style="position:relative;">' +
        '<div class="q-number">Soal ' + (i + 1) + '</div>' +
        '<div class="q-text">' + escapeHtml(item.question) + '</div>' +
        imgHtml +
        optionsHtml +
        answerHtml +
        explHtml +
        '<button class="q-edit-btn" onclick="openEditQuestionModal(' + i + ', \'bank\', ' + (window.__detailBankId || '\'\'') + ')" title="Edit Soal"><i class="fas fa-pen"></i></button>' +
        '</div>';
    });

    container.innerHTML = html;
  }

  window.exportDetailTxt = function () {
    if (!window.__detailQuestions) return;
    var opts = getExportOptsDetail();
    var title = window.__detailTitle || 'Soal';
    downloadBlob(buildTxt(opts, window.__detailQuestions, title), 'text/plain', title + '.txt');
  };

  window.exportDetailPdf = function () {
    if (!window.__detailQuestions) return;
    var opts = getExportOptsDetail();
    var title = window.__detailTitle || 'Soal';
    fetch('../backend/export.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'pdf', title: title, questions: window.__detailQuestions, options: opts })
    })
    .then(function (r) { if (!r.ok) throw new Error('Export gagal'); return r.blob(); })
    .then(function (blob) { downloadBlob(blob, blob.type, title + '.pdf'); })
    .catch(function (err) { showAlert('Gagal Export', err.message, 'warning'); });
  };

  window.exportDetailDoc = function () {
    if (!window.__detailQuestions) return;
    var opts = getExportOptsDetail();
    var title = window.__detailTitle || 'Soal';
    fetch('../backend/export.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'doc', title: title, questions: window.__detailQuestions, options: opts })
    })
    .then(function (r) { if (!r.ok) throw new Error('Export gagal'); return r.blob(); })
    // .then(function (blob) { downloadBlob(blob, blob.type, title + '.doc'); })
    .then(function (blob) {
      var fixedBlob = new Blob([blob], { type: 'application/octet-stream' });
      var url = URL.createObjectURL(fixedBlob);
      var a = document.createElement('a');
      a.href = url;
      a.download = title + '.doc';
      document.body.appendChild(a);
      a.click();
      a.remove();
      URL.revokeObjectURL(url);
    })
    .catch(function (err) { showAlert('Gagal Export', err.message, 'warning'); });
  };

  window.exportDetailGoogleForm = async function () {
    if (!window.__detailQuestions) return;
    var title = window.__detailTitle || 'Soal';

    var btn = document.querySelector('#questionDetailContent .export-btn-format.gform');
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...'; }

    var showHeader = document.getElementById('detailExportShowHeader')?.checked || false;
    var headerText = document.getElementById('detailExportHeaderText')?.value.trim() || '';
    var showInfo = document.getElementById('detailExportShowInfo')?.checked || false;
    var infoFields = [];
    if (showInfo) {
      var list = document.getElementById('detailInfoFieldList');
      if (list) {
        list.querySelectorAll('.info-field-row').forEach(function (row) {
          var label = row.querySelector('.info-label')?.value.trim();
          var value = row.querySelector('.info-value')?.value.trim();
          if (label) infoFields.push({ label: label, value: value || '' });
        });
      }
    }
    var identityFields = [];
    if (document.getElementById('detailExportShowBiodata')?.checked) {
      var list = document.getElementById('detailBiodataFieldList');
      if (list) {
        list.querySelectorAll('.biodata-field-row').forEach(function (row) {
          var val = row.querySelector('input')?.value.trim();
          if (val) identityFields.push(val);
        });
      }
    }

    try {
      let accessToken = null;
      try { accessToken = await window.getGoogleAccessToken(); } catch (_) {}
      if (!accessToken) {
        accessToken = localStorage.getItem(btoa('jsat'));
      }
      if (!accessToken) { showAlert('Gagal', 'Gagal mendapatkan akses Google.', 'warning'); return; }
      var res = await fetch('../backend/export-googleform.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ title: title, questions: window.__detailQuestions, access_token: accessToken, identity_fields: identityFields, show_header: showHeader, header_text: headerText, show_info: showInfo, info_fields: infoFields })
      });
      var data = await res.json();
      if (data.success && data.form_url) {
        showModal({
          icon: 'success',
          iconHtml: '<i class="fas fa-check-circle" style="color:var(--secondary);font-size:40px;"></i>',
          title: 'Google Form Berhasil Dibuat!',
          message: '',
          buttons: [{ text: 'Buka Google Form', class: 'modal-btn-confirm', onclick: function () { window.open(data.form_url, '_blank'); } }, { text: 'Tutup', class: 'modal-btn-cancel' }]
        });
      } else {
        showAlert('Gagal', data.error || 'Gagal membuat Google Form', 'warning');
      }
    } catch (e) {
      if (e.code === 'auth/popup-closed-by-user') return;
      showAlert('Gagal', e.message || 'Terjadi kesalahan', 'warning');
    } finally {
      if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fab fa-google"></i> Google Form'; }
    }
  };

  function getExportOptsDetail() {
    var p = 'detail';

    function f(id) { return document.getElementById(p + id); }

    function readFieldList(listId, isLabelValue) {
      var fields = [];
      var list = document.getElementById(p + listId);
      if (list) {
        list.querySelectorAll(isLabelValue ? '.info-field-row' : '.biodata-field-row').forEach(function(row) {
          if (isLabelValue) {
            var label = row.querySelector('.info-label') ? row.querySelector('.info-label').value.trim() : '';
            var value = row.querySelector('.info-value') ? row.querySelector('.info-value').value.trim() : '';
            if (label) fields.push({ label: label, value: value });
          } else {
            var val = row.querySelector('input') ? row.querySelector('input').value.trim() : '';
            if (val) fields.push(val);
          }
        });
      }
      return fields;
    }

    return {
      include_answer: f('ExportIncludeAnswer') ? f('ExportIncludeAnswer').checked : true,
      include_explanation: f('ExportIncludeExplanation') ? f('ExportIncludeExplanation').checked : true,
      answer_position: f('ExportAnswerPosition') ? f('ExportAnswerPosition').value : 'per_soal',
      show_header: f('ExportShowHeader') ? f('ExportShowHeader').checked : true,
      header_text: f('ExportHeaderText') ? f('ExportHeaderText').value.trim() : '',
      show_info: f('ExportShowInfo') ? f('ExportShowInfo').checked : false,
      info_fields: readFieldList('InfoFieldList', true),
      show_biodata: f('ExportShowBiodata') ? f('ExportShowBiodata').checked : false,
      biodata_fields: readFieldList('BiodataFieldList', false),
      show_instructions: f('ExportShowInstructions') ? f('ExportShowInstructions').checked : false,
      instructions_text: f('ExportInstructionsText') ? f('ExportInstructionsText').value.trim() : ''
    };
  }

  window.backToQuestionBank = function () {
    document.getElementById('questionDetailSection').classList.remove('active');
    document.getElementById('questionDetailSection').style.display = 'none';
    document.getElementById('panel-questions').style.display = '';
    document.getElementById('questionsTableWrapper').style.display = '';
    document.getElementById('questionsPagination').style.display = '';
    document.querySelector('#panel-questions .filter-bar').style.display = '';
    loadQuestions(window.__detailBankPage || 1);
  };

  function renderQuestions(questions, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    if (!questions || questions.length === 0) {
      const prefix = containerId === 'genResult' ? 'gen' : 'desc';
      container.innerHTML =
        '<div class="panel-card" style="text-align:center;padding:32px 16px;">' +
          '<div style="font-size:40px;margin-bottom:12px;color:var(--orange-500);"><i class="fas fa-file-circle-exclamation"></i></div>' +
          '<h3 style="font-size:16px;font-weight:600;color:var(--gray-800);margin-bottom:8px;">Tidak Ada Soal</h3>' +
          '<p style="font-size:13px;color:var(--gray-500);margin-bottom:20px;">Tidak ada soal yang dihasilkan. Silakan coba lagi dengan pengaturan berbeda.</p>' +
          '<button class="btn-outline" onclick="window.resetGenerateForm(\'' + prefix + '\')"><i class="fas fa-arrow-left"></i> Kembali ke Form</button>' +
        '</div>';
      container.style.display = 'block';
      return;
    }

    currentQuestions = questions;

    let html = '<div class="result-title" data-lang-key="result.generated">📝 Soal yang Dihasilkan</div>';

    questions.forEach((q, i) => {
      const hasOptions = q.options && Array.isArray(q.options) && q.options.length > 0;
      const optionsHtml = hasOptions ? '<div class="q-options">' + q.options.map((opt, oi) => {
        const prefix = String.fromCharCode(65 + oi) + '.';
        return '<div>' + escapeHtml(prefix + ' ' + opt) + '</div>';
      }).join('') + '</div>' : '';

      const imgHtml = q.image_url ? '<img src="' + q.image_url + '" class="q-image" alt="Gambar soal">' : '';
      const answerHtml = q.answer ? '<div class="q-answer">✓ ' + escapeHtml(q.answer) + '</div>' : '';
      const explHtml = q.explanation ? '<div class="q-explanation">' + escapeHtml(q.explanation) + '</div>' : '';

      html += '<div class="result-card" style="position:relative;">' +
        '<div class="q-number">Soal ' + (i + 1) + '</div>' +
        '<div class="q-text">' + escapeHtml(q.question) + '</div>' +
        imgHtml +
        optionsHtml +
        answerHtml +
        explHtml +
        '<button class="q-edit-btn" onclick="openEditQuestionModal(' + i + ', \'result\')" title="Edit Soal"><i class="fas fa-pen"></i></button>' +
        '</div>';
    });

    html += '<div class="export-panel" id="exportPanel">' +
      '<div class="export-panel-title"><i class="fas fa-download"></i> Export Soal</div>' +
      '<div class="export-opts-row">' +
        '<label class="export-opt-toggle"><input type="checkbox" id="ExportIncludeAnswer" checked> Sertakan Jawaban</label>' +
        '<label class="export-opt-toggle"><input type="checkbox" id="ExportIncludeExplanation" checked> Sertakan Pembahasan</label>' +
        '<select class="export-opt-select" id="ExportAnswerPosition">' +
          '<option value="per_soal">Jawaban Per Soal</option>' +
          '<option value="akhir">Kunci Jawaban di Akhir</option>' +
        '</select>' +
      '</div>' +
      '<div class="export-advanced-toggle" onclick="toggleExportAdvanced(this)">' +
        '<i class="fas fa-chevron-right chevron"></i> Pengaturan Tambahan' +
      '</div>' +
      '<div class="export-advanced" id="exportAdvanced" style="display:none;">' +
        '<label class="export-opt-toggle"><input type="checkbox" id="ExportShowHeader" checked onchange="toggleExportField(\'header\')"> Tampilkan Header</label>' +
        '<div id="ExportHeaderInput" style="margin:4px 0 8px 20px;">' +
          '<input type="text" id="ExportHeaderText" class="export-input" placeholder="Kosongkan untuk menggunakan judul soal">' +
        '</div>' +
        '<label class="export-opt-toggle"><input type="checkbox" id="ExportShowInfo" checked onchange="toggleExportField(\'info\')"> Tampilkan Info Soal</label>' +
        '<div id="ExportInfoFields" style="margin:4px 0 8px 20px;">' +
          '<div id="InfoFieldList"></div>' +
          '<button class="export-add-field" onclick="addInfoField()">+ Tambah Field Info</button>' +
        '</div>' +
        '<label class="export-opt-toggle"><input type="checkbox" id="ExportShowBiodata" onchange="toggleExportField(\'biodata\')"> Tampilkan Biodata Siswa</label>' +
        '<div id="ExportBiodataFields" style="display:none;margin:4px 0 8px 20px;">' +
          '<div id="BiodataFieldList"></div>' +
          '<button class="export-add-field" onclick="addBiodataField()">+ Tambah Field</button>' +
        '</div>' +
        '<label class="export-opt-toggle"><input type="checkbox" id="ExportShowInstructions" checked onchange="toggleExportField(\'instructions\')"> Tampilkan Petunjuk</label>' +
        '<div id="ExportInstructionsInput" style="margin:4px 0 8px 20px;">' +
          '<textarea id="ExportInstructionsText" class="export-textarea" rows="2">Jawablah pertanyaan berikut dengan benar!</textarea>' +
        '</div>' +
      '</div>' +
      '<div class="export-btns" style="margin-top:12px;">' +
        '<button class="export-btn-format txt" onclick="exportQuestions(\'txt\')"><i class="fas fa-file-alt"></i> TXT</button>' +
        '<button class="export-btn-format pdf" onclick="exportQuestions(\'pdf\')"><i class="fas fa-file-pdf"></i> PDF</button>' +
        '<button class="export-btn-format doc" onclick="exportQuestions(\'doc\')"><i class="fas fa-file-word"></i> DOC</button>' +
        '<button class="export-btn-format gform" onclick="exportToGoogleForm()"><i class="fab fa-google"></i> Google Form</button>' +
      '</div>' +
    '</div>' +
    '<div style="margin-top:16px;text-align:center;">' +
      '<button class="btn-outline" onclick="window.resetGenerateForm(\'' + (containerId === 'genResult' ? 'gen' : 'desc') + '\')"><i class="fas fa-plus"></i> Buat Soal Baru</button>' +
    '</div>';

    container.innerHTML = html;
    container.style.display = 'block';
  }

  // ====== QUIZ MODE ======

  function getQuizPrefix() {
    return quizState.containerId === 'genResult' ? 'gen' : 'desc';
  }

  window.renderQuiz = function (questions, containerId) {
    if (!questions || questions.length === 0) return;
    currentQuestions = questions;
    quizState.active = true;
    quizState.containerId = containerId;
    quizState.questions = questions;
    quizState.submitted = false;
    quizState.answers = questions.map(function () { return { selectedOption: '', essayAnswer: '' }; });
    quizState.results = [];

    var container = document.getElementById(containerId);
    if (!container) return;

    var html = '<div class="quiz-header">' +
      '<h2><i class="fas fa-pencil-alt"></i> Latihan Soal</h2>' +
      '<p>Jawab soal-soal berikut, lalu klik "Submit" untuk melihat hasil</p>' +
      '</div>';

    questions.forEach(function (q, i) {
      var hasOptions = q.options && Array.isArray(q.options) && q.options.length > 0;

      html += '<div class="quiz-question-card" id="quizCard' + i + '">' +
        '<div class="quiz-question-number">Soal ' + (i + 1) + ' dari ' + questions.length + '</div>' +
        '<div class="quiz-question-text">' + escapeHtml(q.question) + '</div>';

      if (q.image_url) {
        html += '<img src="' + q.image_url + '" class="quiz-question-image" alt="Gambar soal">';
      }

      if (hasOptions) {
        html += '<div class="quiz-options" id="quizOptions' + i + '">';
        q.options.forEach(function (opt, oi) {
          var prefix = String.fromCharCode(65 + oi);
          html += '<div class="quiz-option" onclick="selectQuizOption(' + i + ', ' + oi + ')" id="quizOpt' + i + '_' + oi + '">' +
            '<span class="quiz-option-label">' + prefix + '</span>' +
            '<span class="quiz-option-text">' + escapeHtml(opt) + '</span>' +
            '</div>';
        });
        html += '</div>';
      } else {
        html += '<textarea class="quiz-essay-textarea" id="quizEssay' + i + '" placeholder="Tulis jawaban Anda di sini..." oninput="onQuizEssayInput(' + i + ')"></textarea>';
      }

      html += '</div>';
    });

    html += '<div class="quiz-submit-area">' +
      '<button class="quiz-btn-submit" id="quizSubmitBtn" onclick="submitQuiz()"><i class="fas fa-check-circle"></i> Submit Jawaban</button>' +
      '</div>';

    container.innerHTML = html;
    container.style.display = 'block';
  };

  window.selectQuizOption = function (qIndex, optIndex) {
    if (quizState.submitted) return;
    var questions = quizState.questions;
    if (!questions[qIndex]) return;
    var q = questions[qIndex];
    var hasOptions = q.options && Array.isArray(q.options) && q.options.length > 0;
    if (!hasOptions) return;

    // deselect all options for this question
    q.options.forEach(function (_, oi) {
      var el = document.getElementById('quizOpt' + qIndex + '_' + oi);
      if (el) el.classList.remove('selected');
    });

    // select chosen option
    var selectedEl = document.getElementById('quizOpt' + qIndex + '_' + optIndex);
    if (selectedEl) selectedEl.classList.add('selected');

    quizState.answers[qIndex].selectedOption = q.options[optIndex];
  };

  window.onQuizEssayInput = function (qIndex) {
    if (quizState.submitted) return;
    var el = document.getElementById('quizEssay' + qIndex);
    if (el) quizState.answers[qIndex].essayAnswer = el.value;
  };

  window.submitQuiz = function () {
    if (quizState.submitted) return;
    if (!quizState.questions || quizState.questions.length === 0) return;

    quizState.submitted = true;
    var questions = quizState.questions;
    var mcCorrect = 0;
    var mcTotal = 0;
    var results = [];

    questions.forEach(function (q, i) {
      var hasOptions = q.options && Array.isArray(q.options) && q.options.length > 0;

      if (hasOptions) {
        mcTotal++;
        var userAnswer = quizState.answers[i].selectedOption || '';
        var isCorrect = userAnswer.toLowerCase() === (q.answer || '').toLowerCase();
        if (isCorrect) mcCorrect++;

        results.push({
          type: 'mc',
          correct: isCorrect,
          userAnswer: userAnswer || '(Tidak dijawab)',
          correctAnswer: q.answer || '-'
        });

        // mark options visually
        q.options.forEach(function (opt, oi) {
          var el = document.getElementById('quizOpt' + i + '_' + oi);
          if (!el) return;
          el.classList.remove('selected');
          var optText = el.querySelector('.quiz-option-text');
          if (optText && optText.textContent === q.answer) {
            el.classList.add('correct-show');
          }
          if (opt === userAnswer && !isCorrect) {
            el.classList.add('wrong');
          }
          if (opt === userAnswer && isCorrect) {
            el.classList.add('correct');
          }
        });
      } else {
        var userAnswer = quizState.answers[i].essayAnswer || '';
        results.push({
          type: 'essay',
          correct: null,
          userAnswer: userAnswer || '(Tidak dijawab)',
          correctAnswer: q.answer || '-'
        });

        var essayEl = document.getElementById('quizEssay' + i);
        if (essayEl) {
          essayEl.classList.add('submitted');
          essayEl.disabled = true;
        }
      }
    });

    quizState.mcCorrect = mcCorrect;
    quizState.mcTotal = mcTotal;
    quizState.results = results;

    // disable submit button
    var submitBtn = document.getElementById('quizSubmitBtn');
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Selesai';
    }

    showQuizResult();
  };

  function showQuizResult() {
    var containerId = quizState.containerId;
    var container = document.getElementById(containerId);
    if (!container) return;

    var questions = quizState.questions;
    var mcCorrect = quizState.mcCorrect;
    var mcTotal = quizState.mcTotal;
    var results = quizState.results;
    var scorePercent = mcTotal > 0 ? Math.round((mcCorrect / mcTotal) * 100) : 0;
    var essayCount = results.filter(function (r) { return r.type === 'essay'; }).length;

    var html = '<div class="quiz-result-header">' +
      '<div class="quiz-result-score">' + mcCorrect + '/' + mcTotal + '</div>' +
      '<p class="quiz-result-label">Benar dari ' + mcTotal + ' soal pilihan ganda' + (essayCount > 0 ? ' (essay diperiksa manual)' : '') + '</p>' +
      '<div class="quiz-result-stats">' +
      '<div class="quiz-stat-item"><div class="quiz-stat-value" style="color:#4ade80;">' + mcCorrect + '</div><div class="quiz-stat-label">Benar</div></div>' +
      '<div class="quiz-stat-item"><div class="quiz-stat-value" style="color:#f87171;">' + (mcTotal - mcCorrect) + '</div><div class="quiz-stat-label">Salah</div></div>' +
      '<div class="quiz-stat-item"><div class="quiz-stat-value" style="color:#60a5fa;">' + scorePercent + '%</div><div class="quiz-stat-label">Skor</div></div>' +
      '</div>' +
      '</div>';

    html += '<div class="quiz-result-list">';

    questions.forEach(function (q, i) {
      var r = results[i];
      if (!r) return;
      var iconClass, iconHtml, statusText;
      if (r.type === 'mc') {
        iconClass = r.correct ? 'correct' : 'wrong';
        iconHtml = r.correct ? '<i class="fas fa-check"></i>' : '<i class="fas fa-times"></i>';
        statusText = r.correct ? 'Benar' : 'Salah';
      } else {
        iconClass = 'essay';
        iconHtml = '<i class="fas fa-pen"></i>';
        statusText = 'Essay';
      }

      html += '<div class="quiz-result-item" id="quizResultItem' + i + '">' +
        '<div class="quiz-result-item-header" onclick="toggleQuizResultItem(' + i + ')">' +
        '<div class="quiz-result-item-icon ' + iconClass + '">' + iconHtml + '</div>' +
        '<span class="quiz-result-item-num">' + statusText + '</span>' +
        '<span class="quiz-result-item-text">' + escapeHtml(q.question.substring(0, 80)) + (q.question.length > 80 ? '...' : '') + '</span>' +
        '<span class="quiz-result-item-arrow"><i class="fas fa-chevron-right"></i></span>' +
        '</div>' +
        '<div class="quiz-result-item-body">';

      // Detail
      html += '<div class="quiz-result-detail-row">' +
        '<span class="quiz-result-detail-label">Soal:</span>' +
        '<span class="quiz-result-detail-value">' + escapeHtml(q.question) + '</span>' +
        '</div>';

      if (r.type === 'mc' && q.options) {
        html += '<div class="quiz-result-detail-row">' +
          '<span class="quiz-result-detail-label">Pilihan:</span>' +
          '<span class="quiz-result-detail-value">' + q.options.map(function (opt, oi) {
            var p = String.fromCharCode(65 + oi);
            var cls = '';
            if (opt === r.correctAnswer) cls = ' correct-answer';
            if (opt === r.userAnswer && !r.correct) cls = ' user-wrong';
            return '<div' + (cls ? ' style="color:' + (opt === r.correctAnswer ? 'var(--secondary)' : 'var(--red-500)') + ';font-weight:' + (cls ? '600' : '400') + '"' : '') + '>' + p + '. ' + escapeHtml(opt) + (opt === r.correctAnswer ? ' ✓' : '') + '</div>';
          }).join('') + '</span>' +
          '</div>';
      }

      html += '<div class="quiz-result-detail-row">' +
        '<span class="quiz-result-detail-label">Jawaban:</span>' +
        '<span class="quiz-result-detail-value' + (r.type === 'mc' && !r.correct ? ' user-wrong' : '') + '">' + escapeHtml(r.userAnswer) + '</span>' +
        '</div>';

      if (r.type === 'mc') {
        html += '<div class="quiz-result-detail-row">' +
          '<span class="quiz-result-detail-label">Kunci:</span>' +
          '<span class="quiz-result-detail-value correct-answer">' + escapeHtml(r.correctAnswer) + '</span>' +
          '</div>';
      }

      if (q.explanation) {
        html += '<div class="quiz-result-detail-row">' +
          '<span class="quiz-result-detail-label">Pembahasan:</span>' +
          '<span class="quiz-result-detail-value" style="background:var(--gray-50);padding:8px 10px;border-radius:6px;border-left:2px solid var(--gray-300);font-size:12px;">' + escapeHtml(q.explanation) + '</span>' +
          '</div>';
      }

      html += '</div></div>';
    });

    html += '</div>';

    html += '<div class="quiz-result-actions">' +
      '<button class="quiz-result-btn quiz-result-btn-primary" onclick="window.resetGenerateForm(\'' + getQuizPrefix() + '\')"><i class="fas fa-plus"></i> Buat Soal Baru</button>' +
      '<button class="quiz-result-btn quiz-result-btn-outline" onclick="switchToNormalDisplay()"><i class="fas fa-list"></i> Lihat Detail Soal</button>' +
      '</div>';

    container.innerHTML = html;
    container.style.display = 'block';
  }

  window.toggleQuizResultItem = function (index) {
    var el = document.getElementById('quizResultItem' + index);
    if (el) el.classList.toggle('open');
  };

  window.switchToNormalDisplay = function () {
    if (!quizState.questions || quizState.questions.length === 0) return;
    quizState.active = false;
    renderQuestions(quizState.questions, quizState.containerId);

    // Add "Kembali ke Quiz" button
    var container = document.getElementById(quizState.containerId);
    if (container) {
      var btns = container.querySelector('.btn-outline');
      if (btns) {
        btns.textContent = '';
        btns.innerHTML = '<i class="fas fa-arrow-left"></i> Kembali ke Quiz';
        btns.onclick = function () { switchToQuizResult(); };
      }
    }
  };

  window.switchToQuizResult = function () {
    quizState.active = true;
    showQuizResult();
  };

  function getSubjectName(selectId) {
    const el = document.getElementById(selectId);
    if (!el) return '';
    const selected = el.options[el.selectedIndex];
    return selected ? selected.textContent : '';
  }

  function getSelectedText(selectId) {
    const el = document.getElementById(selectId);
    if (!el) return '';
    const selected = el.options[el.selectedIndex];
    if (!selected || !selected.value) return '';
    return selected.textContent;
  }

  window.callGenerateAPI = async function () {
    const subject = getSubjectName('genSubject');
    const topic = document.getElementById('genTopic').value.trim();
    const classVal = getSelectedText('genClass');
    const count = parseInt(document.getElementById('genCount').value) || 5;
    const instructions = document.getElementById('genInstructions').value.trim();
    const difficulty = getCardSelectValue('genDifficulty') || 'easy';
    const type = getCardSelectValue('genType') || 'multiple_choice';

    if (!classVal) { showAlert('Validasi', 'Pilih kelas'); return; }
    if (!topic) { showAlert('Validasi', 'Masukkan topik soal'); return; }
    if (!subject || subject === '-- Pilih --') { showAlert('Validasi', 'Pilih mata pelajaran'); return; }
    if (count < 1 || count > 50) { showAlert('Validasi', 'Jumlah soal antara 1-50'); return; }

    const formSection = document.getElementById('genFormSection');
    const loading = document.getElementById('genLoading');
    const result = document.getElementById('genResult');

    formSection.style.display = 'none';
    result.style.display = 'none';
    result.innerHTML = '';
    loading.style.display = 'flex';

    try {
      const res = await fetch('../backend/api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          subject, topic, class: classVal, difficulty, type, total_questions: count, extra_instructions: instructions,
          pg_count: type === 'mixed' ? parseInt(document.getElementById('genPgCount').value) : null,
          essay_count: type === 'mixed' ? parseInt(document.getElementById('genEssayCount').value) : null,
          question_order: type === 'mixed' ? document.getElementById('genQuestionOrder').value : null
        })
      });
      const data = await res.json();

      loading.style.display = 'none';

      if (data.success && data.questions) {
        currentGenerateTitle = topic;
        document.getElementById('descResult').innerHTML = '';
        document.getElementById('descResult').style.display = 'none';
        var isGenQuiz = document.getElementById('genDisplayAsQuiz') && document.getElementById('genDisplayAsQuiz').checked;
        if (isGenQuiz) {
          renderQuiz(data.questions, 'genResult');
        } else {
          renderQuestions(data.questions, 'genResult');
        }
        setTimeout(() => saveToBank(true), 100);
        if (window.refreshUserCredits) window.refreshUserCredits();
      } else if (data.error === 'insufficient_credits') {
        formSection.style.display = 'block';
        showInsufficientCreditsModal();
      } else {
        showFailureCard('genResult', data.error || 'Gagal generate soal. Silakan coba lagi.');
      }
    } catch (e) {
      loading.style.display = 'none';
      showFailureCard('genResult', 'Terjadi kesalahan. Silakan coba lagi.');
    }
  };

  window.callDescribeAPI = async function () {
    const subject = getSubjectName('descSubject');
    const topic = document.getElementById('descTopic').value.trim();
    const classVal = getSelectedText('descClass');
    const count = parseInt(document.getElementById('descCount').value) || 5;
    const instructions = document.getElementById('descInstructions').value.trim();
    const difficulty = getCardSelectValue('descDifficulty') || 'easy';
    const type = getCardSelectValue('descType') || 'multiple_choice';

    const material = document.getElementById('descMaterialText').value.trim();

    if (!classVal) { showAlert('Validasi', 'Pilih kelas'); return; }
    if (!topic) { showAlert('Validasi', 'Masukkan topik soal'); return; }
    if (!subject || subject === '-- Pilih --') { showAlert('Validasi', 'Pilih mata pelajaran'); return; }
    if (!material) { showAlert('Validasi', 'Masukkan materi atau upload file'); return; }
    if (count < 1 || count > 50) { showAlert('Validasi', 'Jumlah soal antara 1-50'); return; }

    const formSection = document.getElementById('descFormSection');
    const loading = document.getElementById('descLoading');
    const result = document.getElementById('descResult');

    formSection.style.display = 'none';
    result.style.display = 'none';
    result.innerHTML = '';
    loading.style.display = 'flex';

    try {
      const res = await fetch('../backend/api-describe.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          subject, topic, class: classVal, difficulty, type, total_questions: count, extra_instructions: instructions, material,
          pg_count: type === 'mixed' ? parseInt(document.getElementById('descPgCount').value) : null,
          essay_count: type === 'mixed' ? parseInt(document.getElementById('descEssayCount').value) : null,
          question_order: type === 'mixed' ? document.getElementById('descQuestionOrder').value : null
        })
      });
      const data = await res.json();

      loading.style.display = 'none';

      if (data.success && data.questions) {
        currentGenerateTitle = topic;
        document.getElementById('genResult').innerHTML = '';
        document.getElementById('genResult').style.display = 'none';
        var isDescQuiz = document.getElementById('descDisplayAsQuiz') && document.getElementById('descDisplayAsQuiz').checked;
        if (isDescQuiz) {
          renderQuiz(data.questions, 'descResult');
        } else {
          renderQuestions(data.questions, 'descResult');
        }
        setTimeout(() => saveToBank(true), 100);
        if (window.refreshUserCredits) window.refreshUserCredits();
      } else if (data.error === 'insufficient_credits') {
        formSection.style.display = 'block';
        showInsufficientCreditsModal();
      } else {
        showFailureCard('descResult', data.error || 'Gagal generate soal. Silakan coba lagi.');
      }
    } catch (e) {
      loading.style.display = 'none';
      showFailureCard('descResult', 'Terjadi kesalahan. Silakan coba lagi.');
    }
  };

  window.saveToBank = async function (silent) {
    const subjectEl = document.getElementById('genResult').closest('.tools-panel.active') ?
      document.getElementById('genSubject') : document.getElementById('descSubject');

    const topic = currentGenerateTitle || document.getElementById('genTopic').value.trim() || document.getElementById('descTopic').value.trim() || 'Untitled';
    const classVal = getSelectedText('genClass') || getSelectedText('descClass');
    const difficulty = getCardSelectValue('genDifficulty') || getCardSelectValue('descDifficulty') || 'easy';
    const type = getCardSelectValue('genType') || getCardSelectValue('descType') || 'multiple_choice';

    if (!currentQuestions || currentQuestions.length === 0) return;

    try {
      const res = await fetch('../backend/question.php?action=save', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          subject_id: subjectEl.value,
          title: topic,
          class: classVal,
          difficulty,
          type,
          questions_data: currentQuestions,
          prompt_used: topic
        })
      });
      const data = await res.json();
      if (data.success) {
        window.__currentBankId = data.question_id;
        if (!silent) showAlert('Berhasil', 'Soal berhasil disimpan ke Bank Soal!', 'success');
      } else {
        showSaveRetry(data.error || 'Gagal menyimpan');
      }
    } catch (e) {
      showSaveRetry('Gagal menyimpan soal');
    }
  };

  function showSaveRetry(msg) {
    var containerId = document.querySelector('.tools-panel.active')?.id === 'panel-describe' ? 'descResult' : 'genResult';
    var container = document.getElementById(containerId);
    if (!container) return;
    // remove old retry bar if exists
    var old = document.getElementById('saveRetryBar');
    if (old) old.remove();
    var div = document.createElement('div');
    div.id = 'saveRetryBar';
    div.style.cssText = 'margin-top:12px;padding:12px 16px;background:#fef3c7;border:1px solid #f59e0b;border-radius:8px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;';
    div.innerHTML = '<span style="font-size:13px;color:#92400e;"><i class="fas fa-exclamation-triangle"></i> ' + escapeHtml(msg) + '</span>' +
      '<button class="btn-outline" style="padding:6px 14px;font-size:12px;border-color:#f59e0b;color:#92400e;" onclick="saveToBank(false)"><i class="fas fa-save"></i> Simpan Ulang</button>';
    container.appendChild(div);
  }

  function getExportOpts() {
    var isModal = !!document.querySelector('.modal-overlay.active');
    var p = isModal ? 'modal' : '';

    function el(id) { return document.getElementById(p + id) || document.getElementById(id); }

    function readFieldList(listId, isLabelValue) {
      var fields = [];
      var list = document.getElementById(p + listId);
      if (list) {
        list.querySelectorAll(isLabelValue ? '.info-field-row' : '.biodata-field-row').forEach(function(row) {
          if (isLabelValue) {
            var label = row.querySelector('.info-label') ? row.querySelector('.info-label').value.trim() : '';
            var value = row.querySelector('.info-value') ? row.querySelector('.info-value').value.trim() : '';
            if (label) fields.push({ label: label, value: value });
          } else {
            var val = row.querySelector('input') ? row.querySelector('input').value.trim() : '';
            if (val) fields.push(val);
          }
        });
      }
      return fields;
    }

    return {
      include_answer: el('ExportIncludeAnswer') ? el('ExportIncludeAnswer').checked : true,
      include_explanation: el('ExportIncludeExplanation') ? el('ExportIncludeExplanation').checked : true,
      answer_position: el('ExportAnswerPosition') ? el('ExportAnswerPosition').value : 'per_soal',
      show_header: el('ExportShowHeader') ? el('ExportShowHeader').checked : true,
      header_text: el('ExportHeaderText') ? el('ExportHeaderText').value.trim() : '',
      show_info: el('ExportShowInfo') ? el('ExportShowInfo').checked : true,
      info_fields: readFieldList('InfoFieldList', true),
      show_biodata: el('ExportShowBiodata') ? el('ExportShowBiodata').checked : false,
      biodata_fields: readFieldList('BiodataFieldList', false),
      show_instructions: el('ExportShowInstructions') ? el('ExportShowInstructions').checked : true,
      instructions_text: el('ExportInstructionsText') ? el('ExportInstructionsText').value.trim() : ''
    };
  }

  function downloadBlob(blobOrString, mimeType, filename) {
    const blob = typeof blobOrString === 'string' ? new Blob([blobOrString], { type: mimeType }) : blobOrString;
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    a.click();
    URL.revokeObjectURL(url);
  }

  function buildTxt(opts, questions, title) {
    var txt = '';
    if (opts.show_header) {
      txt += (opts.header_text || title).toUpperCase() + '\n';
      txt += '='.repeat(Math.min((opts.header_text || title).length, 50)) + '\n\n';
    }
    if (opts.show_info && opts.info_fields && opts.info_fields.length > 0) {
      txt += '--- INFO SOAL ---\n';
      opts.info_fields.forEach(function(f) { txt += f.label + ': ' + f.value + '\n'; });
      txt += '\n';
    }
    if (opts.show_instructions && opts.instructions_text) {
      txt += opts.instructions_text + '\n\n';
    }
    if (opts.show_biodata && opts.biodata_fields && opts.biodata_fields.length > 0) {
      txt += 'BIODATA SISWA\n';
      txt += '------------\n';
      opts.biodata_fields.forEach(function(f) { txt += f + ': ___________________________\n'; });
      txt += '\n';
    }
    questions.forEach(function(q, i) {
      txt += (i + 1) + '. ' + (q.question || '') + '\n';
      if (q.image_url) {
        txt += '   [Gambar: ' + q.image_url + ']\n';
      }
      if (q.options && Array.isArray(q.options)) {
        q.options.forEach(function(opt, oi) {
          txt += '   ' + String.fromCharCode(65 + oi) + '. ' + (opt || '') + '\n';
        });
      }
      if (opts.include_answer) {
        txt += '\n   Jawaban: ' + (q.answer || '') + '\n';
        if (opts.include_explanation && q.explanation) {
          txt += '   Pembahasan: ' + q.explanation + '\n';
        }
      }
      txt += '\n';
    });
    return txt;
  }

  window.exportToGoogleForm = async function () {
    if (!currentQuestions || currentQuestions.length === 0) return;
    const title = currentGenerateTitle || document.getElementById('genTopic')?.value.trim() || document.getElementById('descTopic')?.value.trim() || 'Soal';

    const btn = document.querySelector('.export-btn-format.gform');
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...'; }

    const showHeader = document.getElementById('ExportShowHeader')?.checked || false;
    const headerText = document.getElementById('ExportHeaderText')?.value.trim() || '';
    const showInfo = document.getElementById('ExportShowInfo')?.checked || false;
    let infoFields = [];
    if (showInfo) {
      const list = document.getElementById('InfoFieldList');
      if (list) {
        list.querySelectorAll('.info-field-row').forEach(function (row) {
          const label = row.querySelector('.info-label')?.value.trim();
          const value = row.querySelector('.info-value')?.value.trim();
          if (label) infoFields.push({ label: label, value: value || '' });
        });
      }
    }
    let identityFields = [];
    if (document.getElementById('ExportShowBiodata')?.checked) {
      const list = document.getElementById('BiodataFieldList');
      if (list) {
        list.querySelectorAll('.biodata-field-row').forEach(function (row) {
          const val = row.querySelector('input')?.value.trim();
          if (val) identityFields.push(val);
        });
      }
    }

    try {
      let accessToken = null;
      try { accessToken = await window.getGoogleAccessToken(); } catch (_) {}
      if (!accessToken) {
        accessToken = localStorage.getItem(btoa('jsat'));
      }
      if (!accessToken) {
        showAlert('Gagal', 'Gagal mendapatkan akses Google. Coba lagi.', 'warning');
        return;
      }

      const res = await fetch('../backend/export-googleform.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ title, questions: currentQuestions, access_token: accessToken, identity_fields: identityFields, show_header: showHeader, header_text: headerText, show_info: showInfo, info_fields: infoFields })
      });
      const data = await res.json();

      if (data.success && data.form_url) {
        showModal({
          icon: 'success',
          iconHtml: '<i class="fas fa-check-circle" style="color:var(--secondary);font-size:40px;"></i>',
          title: 'Google Form Berhasil Dibuat!',
          message: '',
          buttons: [
            {
              text: 'Buka Google Form',
              class: 'modal-btn-confirm',
              onclick: function () { window.open(data.form_url, '_blank'); }
            },
            { text: 'Tutup', class: 'modal-btn-cancel' }
          ]
        });
      } else {
        showAlert('Gagal', data.error || 'Gagal membuat Google Form', 'warning');
      }
    } catch (e) {
      if (e.code === 'auth/popup-closed-by-user') return;
      showAlert('Gagal', e.message || 'Terjadi kesalahan', 'warning');
    } finally {
      if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fab fa-google"></i> Google Form'; }
    }
  };

  window.exportQuestions = function (format) {
    if (!currentQuestions || currentQuestions.length === 0) return;
    const opts = getExportOpts();
    const title = currentGenerateTitle || 'Soal';

    if (format === 'txt') {
      downloadBlob(buildTxt(opts, currentQuestions, title), 'text/plain', title + '.txt');
      return;
    }

    fetch('../backend/export.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        action: format,
        title: title,
        questions: currentQuestions,
        options: opts
      })
    })
    .then(function (res) {
      if (!res.ok) throw new Error('Export gagal');
      return res.blob();
    })
    .then(function (blob) {
      downloadBlob(blob, blob.type, title + '.' + format);
    })
    .catch(function (err) {
      showAlert('Gagal Export', err.message, 'warning');
    });
  };

  window.toggleExportAdvanced = function(el) {
    el.classList.toggle('open');
    var content = el.nextElementSibling;
    if (content && content.classList.contains('export-advanced')) {
      content.style.display = content.style.display === 'none' ? '' : 'none';
    }
  };

  window.toggleExportField = function(type) {
    var prefix = '';
    if (document.querySelector('.modal-overlay.active')) {
      var modalAdvanced = document.getElementById('modalExportAdvanced');
      if (modalAdvanced && modalAdvanced.style.display !== 'none') {
        prefix = 'modal';
      }
    }
    var detailSection = document.getElementById('questionDetailSection');
    if (detailSection && detailSection.classList.contains('active')) {
      prefix = 'detail';
    }
    if (type === 'header') {
      var el = document.getElementById(prefix + 'ExportHeaderInput');
      var cb = document.getElementById(prefix + 'ExportShowHeader');
      if (el) el.style.display = cb && cb.checked ? '' : 'none';
    } else if (type === 'info') {
      var elInfo = document.getElementById(prefix + 'ExportInfoFields');
      var cbInfo = document.getElementById(prefix + 'ExportShowInfo');
      if (elInfo) {
        elInfo.style.display = cbInfo && cbInfo.checked ? '' : 'none';
        if (cbInfo && cbInfo.checked) {
          var list = document.getElementById(prefix + 'InfoFieldList');
          if (list && list.children.length === 0) addInfoField();
        }
      }
    } else if (type === 'biodata') {
      var el = document.getElementById(prefix + 'ExportBiodataFields');
      var cb = document.getElementById(prefix + 'ExportShowBiodata');
      if (el) el.style.display = cb && cb.checked ? '' : 'none';
    } else if (type === 'instructions') {
      var el = document.getElementById(prefix + 'ExportInstructionsInput');
      var cb = document.getElementById(prefix + 'ExportShowInstructions');
      if (el) el.style.display = cb && cb.checked ? '' : 'none';
    }
  };

  function getExportPrefix() {
    if (document.querySelector('.modal-overlay.active')) {
      var modalAdvanced = document.getElementById('modalExportAdvanced');
      if (modalAdvanced && modalAdvanced.style.display !== 'none') return 'modal';
    }
    var detailSection = document.getElementById('questionDetailSection');
    if (detailSection && detailSection.classList.contains('active')) {
      return 'detail';
    }
    return '';
  }

  window.addInfoField = function(label, value) {
    var prefix = getExportPrefix();
    var list = document.getElementById(prefix + 'InfoFieldList');
    if (!list) return;
    var row = document.createElement('div');
    row.className = 'info-field-row';
    row.innerHTML = '<input type="text" class="export-input info-label" placeholder="Label (contoh: Mata Pelajaran)" value="' + (label || '') + '">' +
      '<input type="text" class="export-input info-value" placeholder="Nilai" value="' + (value || '') + '">' +
      '<button class="biodata-field-remove" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>';
    list.appendChild(row);
    if (!label) row.querySelector('.info-label').focus();
  };

  window.addBiodataField = function() {
    var prefix = getExportPrefix();
    var list = document.getElementById(prefix + 'BiodataFieldList');
    if (!list) return;
    var row = document.createElement('div');
    row.className = 'biodata-field-row';
    row.innerHTML = '<input type="text" class="export-input" placeholder="Nama field (contoh: Nama, Kelas)" value="">' +
      '<button class="biodata-field-remove" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>';
    list.appendChild(row);
    row.querySelector('input').focus();
  };

  // stores current modal question data for export
  window.__modalQuestionData = null;

  window.viewQuestionDetail = async function (id) {
    try {
      const res = await fetch('../backend/question.php?action=get&id=' + id);
      const data = await res.json();
      if (!data.success || !data.question) { showAlert('Tidak Ditemukan', 'Soal tidak ditemukan', 'info'); return; }

      const q = data.question;
      const questions = q.questions_data || [];

      window.__detailQuestions = questions;
      window.__detailTitle = q.title || 'Soal';
      window.__detailMeta = q.subject_name ? escapeHtml(q.subject_name) + ' | Kelas ' + escapeHtml(q.class) + ' | ' + questions.length + ' soal' : '';
      window.__detailBankId = id;
      window.__detailBankPage = document.querySelector('.pagination-btn.active') ? parseInt(document.querySelector('.pagination-btn.active').textContent) : 1;

      var filterBar = document.querySelector('#panel-questions .filter-bar');
      var tableWrapper = document.getElementById('questionsTableWrapper');
      var pagination = document.getElementById('questionsPagination');
      var section = document.getElementById('questionDetailSection');
      var container = document.getElementById('questionDetailContent');

      if (filterBar) filterBar.style.display = 'none';
      if (tableWrapper) tableWrapper.style.display = 'none';
      if (pagination) pagination.style.display = 'none';

      renderQuestionDetail(questions, window.__detailTitle, window.__detailMeta);

      section.classList.add('active');
      section.style.display = 'block';
    }catch(e){
      console.log(e);
    };
  };

  window.loadQuestions = async function (page) {
    console.log('called questions load');
    const tbody = document.getElementById('questionsTableBody');
    const pagination = document.getElementById('questionsPagination');
    if (!tbody) return;

    const subject = document.getElementById('qFilterSubject').value;
    const classVal = getSelectedText('qFilterClass');
    const type = document.getElementById('qFilterType').value;
    const search = document.getElementById('qFilterSearch').value.trim();

    tbody.innerHTML = '<tr><td colspan="8"><div class="mat-empty"><div class="loading-spinner"></div><p>Memuat...</p></div></td></tr>';
    pagination.innerHTML = '';

    try {
      let url = '../backend/question.php?action=list&page=' + page + '&limit=10';
      if (subject) url += '&subject=' + encodeURIComponent(subject);
      if (classVal) url += '&class=' + encodeURIComponent(classVal);
      if (type) url += '&type=' + encodeURIComponent(type);
      if (search) url += '&search=' + encodeURIComponent(search);

      const res = await fetch(url);
      const data = await res.json();

      if (!data.success) {
        tbody.innerHTML = '<tr><td colspan="8"><div class="mat-empty"><p>Gagal memuat data</p></div></td></tr>';
        return;
      }

      const questions = data.questions || [];
      const pag = data.pagination || {};

      if (questions.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8"><div class="mat-empty"><i class="fas fa-inbox"></i><p>Belum ada soal tersimpan</p></div></td></tr>';
        pagination.innerHTML = '';
        return;
      }

      const typeLabels = { 'multiple_choice': 'PG', 'essay': 'Essay', 'mixed': 'Campuran' };
      const difficultyLabels = { 'easy': 'Mudah', 'medium': 'Sedang', 'hard': 'Sulit' };

      tbody.innerHTML = questions.map((q, i) => {
        const date = new Date(q.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        const typeLabel = typeLabels[q.type] || q.type;
        const diffLabel = difficultyLabels[q.difficulty] || q.difficulty;
        const count = q.total_questions || 0;
        return '<tr>' +
          '<td style="text-align:center;color:var(--gray-400);font-size:12px;">' + ((pag.current_page - 1) * 12 + i + 1) + '</td>' +
          '<td style="font-weight:500;color:var(--gray-800);cursor:pointer;" onclick="viewQuestionDetail(' + q.id + ')">' + escapeHtml(q.title || 'Untitled') + '</td>' +
          '<td>' + (q.subject_name ? escapeHtml(q.subject_name) : '-') + '</td>' +
          '<td>' + (q.class ? escapeHtml(q.class) : '-') + '</td>' +
          '<td><span class="tag tag-' + q.type + '">' + typeLabel + '</span> <span class="tag tag-' + q.difficulty + '">' + diffLabel + '</span></td>' +
          '<td style="text-align:center;">' + count + '</td>' +
          '<td style="font-size:12px;color:var(--gray-500);">' + date + '</td>' +
          '<td style="text-align:center;"><div class="mat-actions" style="justify-content:center;">' +
          '<button class="mat-btn mat-btn-view" onclick="viewQuestionDetail(' + q.id + ')" title="Lihat Detail"><i class="fas fa-eye"></i></button>' +
          '<button class="mat-btn mat-btn-delete" onclick="deleteQuestion(' + q.id + ')" title="Hapus"><i class="fas fa-trash"></i></button>' +
          '</div></td>' +
          '</tr>';
      }).join('');

      renderPaginationQuestion(pag);
    } catch (e) {
      tbody.innerHTML = '<tr><td colspan="8"><div class="mat-empty"><p>Error memuat data</p></div></td></tr>';
    }
  };

  function renderPaginationQuestion(pag) {
    const el = document.getElementById('questionsPagination');
    if (!el || !pag.total_pages || pag.total_pages <= 1) { el.innerHTML = ''; return; }

    let html = '<div class="pagination-inner">';
    const current = pag.current_page || 1;
    const total = pag.total_pages;

    html += '<button class="pagination-btn" onclick="loadQuestions(' + (current - 1) + ')" ' + (current === 1 ? 'disabled' : '') + '><i class="fas fa-chevron-left"></i></button>';

    let start = Math.max(1, current - 2);
    let end = Math.min(total, start + 4);
    if (end - start < 4) start = Math.max(1, end - 4);

    if (start > 1) {
      html += '<button class="pagination-btn" onclick="loadQuestions(1)">1</button>';
      if (start > 2) html += '<span class="pagination-ellipsis">...</span>';
    }
    for (let i = start; i <= end; i++) {
      html += '<button class="pagination-btn ' + (current === i ? 'active' : '') + '" onclick="loadQuestions(' + i + ')">' + i + '</button>';
    }
    if (end < total) {
      if (end < total - 1) html += '<span class="pagination-ellipsis">...</span>';
      html += '<button class="pagination-btn" onclick="loadQuestions(' + total + ')">' + total + '</button>';
    }

    html += '<button class="pagination-btn" onclick="loadQuestions(' + (current + 1) + ')" ' + (current === total ? 'disabled' : '') + '><i class="fas fa-chevron-right"></i></button>';
    html += '</div>';
    el.innerHTML = html;
  }

  window.exportQuestionDetailModal = function (id, format) {
    const data = window.__modalQuestionData;
    if (!data || data.id !== id) {
      showAlert('Tidak Tersedia', 'Data soal tidak tersedia. Silakan tutup dan buka kembali detail soal.', 'info');
      return;
    }

    const opts = getExportOpts();
    const title = data.title || 'Soal';

    if (format === 'txt') {
      downloadBlob(buildTxt(opts, data.questions, title), 'text/plain', title + '.txt');
      return;
    }

    fetch('../backend/export.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        action: format,
        title: title,
        questions: data.questions,
        options: opts
      })
    })
    .then(function (res) {
      if (!res.ok) throw new Error('Export gagal');
      return res.blob();
    })
    .then(function (blob) {
      downloadBlob(blob, blob.type, title + '.' + format);
    })
    .catch(function (err) {
      showAlert('Gagal Export', err.message, 'warning');
    });
  };

  window.deleteQuestion = async function (id) {
    showConfirm('Hapus Soal', 'Hapus soal ini?', async function() {
      try {
        const res = await fetch('../backend/question.php?action=delete', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id })
        });
        const data = await res.json();
        if (data.success) {
          document.querySelectorAll('.modal-overlay.active').forEach(el => { el.remove(); });
          document.body.style.overflow = '';
          loadQuestions(1);
        } else {
          showAlert('Gagal', data.error || 'Gagal menghapus', 'warning');
        }
      } catch (e) {
        showAlert('Error', 'Error menghapus soal', 'warning');
      }
    }, 'Hapus');
  };

  window.debounceSearch = function () {
    clearTimeout(questionsSearchTimeout);
    questionsSearchTimeout = setTimeout(() => loadQuestions(1), 400);
  };

  var creditPage = 1;
  var topupPage = 1;
  var creditTotalPages = 1;
  var topupTotalPages = 1;

  function renderPagination(currentPage, totalPages, loadFn) {
    if (totalPages <= 1) return '';
    var prevDisabled = currentPage <= 1 ? 'disabled' : '';
    var nextDisabled = currentPage >= totalPages ? 'disabled' : '';
    return '<div class="pagination-inner" style="display:flex;align-items:center;justify-content:center;gap:8px;padding:12px 0;">' +
      '<button class="pagination-btn" onclick="' + loadFn + '(' + (currentPage - 1) + ')" ' + prevDisabled + '><i class="fas fa-chevron-left"></i></button>' +
      '<span style="font-size:12px;color:var(--gray-500);">' + currentPage + ' / ' + totalPages + '</span>' +
      '<button class="pagination-btn" onclick="' + loadFn + '(' + (currentPage + 1) + ')" ' + nextDisabled + '><i class="fas fa-chevron-right"></i></button>' +
      '</div>';
  }

  window.loadCreditHistory = async function (page) {
    creditPage = page || 1;
    try {
      var res = await fetch('../backend/topup.php?action=credit_history&page=' + creditPage + '&limit=10');
      var data = await res.json();
      var list = document.getElementById('creditHistoryList');
      if (!list) return;
      var history = data.success ? data.history : [];
      creditTotalPages = data.total_pages || 1;

      var html = history.length > 0
        ? '<div class="tx-table-wrapper"><table class="tx-table"><thead><tr>' +
          '<th>Kredit</th>' +
          '<th>Keterangan</th>' +
          '<th>Tanggal</th>' +
          '</tr></thead><tbody class="bg-white">' +
          history.map(function(ch) {
            var date = new Date(ch.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
            var amount = parseInt(ch.amount);
            var amountClass = amount >= 0 ? 'tx-credits-plus' : 'tx-credits-minus';
            var amountDisplay = (amount >= 0 ? '+' : '') + amount;
            return '<tr class="tx-row">' +
              '<td class="tx-cell"><span class="' + amountClass + '">' + amountDisplay + '</span></td>' +
              '<td class="tx-cell" style="color:var(--gray-600);font-size:13px;">' + escapeHtml(ch.description || '-') + '</td>' +
              '<td class="tx-cell tx-date">' + date + '</td>' +
              '</tr>';
          }).join('') +
          '</tbody></table></div>'
        : '<div class="tx-empty">Belum ada riwayat kredit</div>';

      html += renderPagination(creditPage, creditTotalPages, 'window.loadCreditHistory');
      list.innerHTML = html;
    } catch (e) {
      console.warn('Credit history load error:', e);
    }
  };

  window.loadTopupHistory = async function (page) {
    topupPage = page || 1;
    try {
      var res = await fetch('../backend/topup.php?action=history&page=' + topupPage + '&limit=10');
      var data = await res.json();
      var list = document.getElementById('topupHistoryList');
      if (!list) return;
      var transactions = data.success ? data.transactions : [];
      topupTotalPages = data.total_pages || 1;

      var html = transactions.length > 0
        ? '<div class="tx-table-wrapper"><table class="tx-table"><thead><tr>' +
          '<th data-lang-key="topup.table.credits">Kredit</th>' +
          '<th data-lang-key="topup.table.price">Harga</th>' +
          '<th data-lang-key="topup.table.status">Status</th>' +
          '<th data-lang-key="topup.table.date">Tanggal</th>' +
          '</tr></thead><tbody class="bg-white">' +
          transactions.map(function(tx) {
            var statusClass = tx.status === 'success' ? 'tx-status-success' : tx.status === 'pending' ? 'tx-status-pending' : 'tx-status-failed';
            var date = new Date(tx.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
            var price = new Intl.NumberFormat('id-ID').format(tx.total_price);
            return '<tr class="tx-row" onclick="openTxDetail(' + tx.id + ')">' +
              '<td class="tx-cell tx-credits">' + tx.credits + ' credits</td>' +
              '<td class="tx-cell tx-price">IDR ' + price + '</td>' +
              '<td class="tx-cell"><span class="tx-status ' + statusClass + '">' + tx.status + '</span></td>' +
              '<td class="tx-cell tx-date">' + date + '</td>' +
              '</tr>';
          }).join('') +
          '</tbody></table></div>'
        : '<div class="tx-empty">Belum ada riwayat top-up</div>';

      html += renderPagination(topupPage, topupTotalPages, 'window.loadTopupHistory');
      list.innerHTML = html;
    } catch (e) {
      console.warn('Topup history load error:', e);
    }
  };

  window.loadAccountPage = async function () {
    const content = document.getElementById('accountContent');
    if (!content) return;
    try {
      const userRes = await fetch('../backend/auth.php?action=user');
      const userData = await userRes.json();

      if (!userData.success || !userData.user) {
        content.innerHTML = '<p>Gagal memuat data akun</p>';
        return;
      }
      const user = userData.user;

      window.currentUser = user;
      if (typeof updateNavbarCredit === 'function') updateNavbarCredit();

      creditPage = 1;
      topupPage = 1;

      content.innerHTML =
        '<div class="account-header">' +
        (user.photo_url
          ? '<img src="https://api.dicebear.com/9.x/pixel-art/svg?seed=' + user.display_name + '" alt="Avatar" class="account-avatar" onerror="this.src=\'https://api.dicebear.com/9.x/pixel-art/svg?seed=' + escapeHtml(user.display_name || 'user') + '\'">'
          : '<div class="account-avatar-placeholder"><i class="fas fa-user"></i></div>') +
        '<div><h3 class="account-name">' + escapeHtml(user.display_name || 'User') + '</h3><p class="account-email">' + escapeHtml(user.email) + '</p></div>' +
        '</div>' +
        '<div class="credit-card">' +
        '<div class="credit-card-top">' +
        '<div class="credit-card-left"><div class="credit-card-icon">💰</div><div><div class="credit-card-title">Credits</div><div class="credit-card-balance">' + (user.credit || 0) + '</div></div></div>' +
        '<button class="credit-topup-btn" onclick="openTopUpModal()"><i class="fas fa-plus"></i> Top Up</button>' +
        '</div></div>' +
        (user.total_generated !== undefined ? '<div class="border" style="padding:12px 16px;background:var(--gray-50);border-radius:10px;margin-bottom:16px;display:flex;justify-content:space-between;"><span style="font-size:13px;color:var(--gray-500);">Total Generate</span><span style="font-size:14px;font-weight:600;color:var(--gray-700);">' + user.total_generated + ' soal</span></div>' : '') +
        '<div class="topup-history-section"><h3 class="topup-history-title">Riwayat Kredit</h3><div id="creditHistoryList"><div class="gen-loading-card" style="padding:20px 0;"><div class="loading-spinner"></div></div></div></div>' +
        '<div class="topup-history-section mt-3"><h3 class="topup-history-title">Riwayat Top-Up</h3><div id="topupHistoryList"><div class="gen-loading-card" style="padding:20px 0;"><div class="loading-spinner"></div></div></div></div>';

      window.loadCreditHistory(1);
      window.loadTopupHistory(1);
    } catch (e) {
      content.innerHTML = '<p>Error memuat data akun</p>';
    }
  };

  window.openTopUpModal = function () {
    document.getElementById('topupModal').classList.add('active');
    document.body.style.overflow = 'hidden';
    updateTopUpPrice();
  };

  window.closeTopUpModal = function () {
    document.getElementById('topupModal').classList.remove('active');
    document.body.style.overflow = '';
  };

  window.setTopUpAmount = function (amount) {
    document.getElementById('topupSlider').value = amount;
    updateTopUpPrice();
  };

  window.updateTopUpPrice = function () {
    const slider = document.getElementById('topupSlider');
    const credits = parseInt(slider.value);
    const packages = credits / 3;
    const totalPrice = packages * 1000;

    document.getElementById('topupCreditsDisplay').textContent = credits;
    document.getElementById('topupTotalPrice').textContent = 'IDR ' + new Intl.NumberFormat('id-ID').format(totalPrice);

    const percent = ((credits - 3) / (99 - 3)) * 100;
    slider.style.setProperty('--slider-fill', percent + '%');
  };

  window.handleTopUp = async function () {
    const btn = document.getElementById('topupNowBtn');
    const errorEl = document.getElementById('topupError');
    const credits = parseInt(document.getElementById('topupSlider').value);

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    errorEl.classList.remove('show');

    try {
      const res = await fetch('../backend/topup.php?action=create', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ credits })
      });
      const data = await res.json();
      if (data.success && data.redirect_url) {
        window.location.href = data.redirect_url;
      } else {
        throw new Error(data.error || 'Gagal membuat top-up');
      }
    } catch (e) {
      errorEl.textContent = e.message;
      errorEl.classList.add('show');
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-credit-card"></i> Top Up Now';
    }
  };

  document.getElementById('topupModal')?.addEventListener('click', function (e) {
    if (e.target === this) closeTopUpModal();
  });

  window.openTxDetail = async function (txId) {
    const content = document.getElementById('txDetailContent');
    await renderTxDetail(txId, content);
    document.getElementById('txDetailModal').classList.add('active');
    document.body.style.overflow = 'hidden';
  };

  window.renderTxDetail = async function (txId, content) {
    content.innerHTML = '<div class="loading-spinner"></div><p>Loading...</p>';
    try {
      const res = await fetch('../backend/topup.php?action=history');
      const data = await res.json();
      if (!data.success) throw new Error('Gagal memuat');

      const tx = data.transactions.find(t => parseInt(t.id) === parseInt(txId));
      if (!tx) throw new Error('Transaksi tidak ditemukan');

      const date = new Date(tx.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
      const paidDate = tx.paid_at
        ? new Date(tx.paid_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' })
        : '-';
      const price = new Intl.NumberFormat('id-ID').format(tx.total_price);
      const paymentMethod = tx.payment_method
        ? tx.payment_method.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
        : '-';

      let actionsHtml = '';
      if (tx.status === 'pending' && tx.redirect_url) {
        actionsHtml = '<div style="display:flex;gap:8px;margin-top:20px;">' +
          '<button class="btn-generate" style="justify-content:center;" onclick="window.location.href=\'' + tx.redirect_url + '\'"><i class="fas fa-credit-card"></i> Lanjut Bayar</button>' +
          '<button class="btn-generate" style="padding:10px 16px;justify-content:center;" onclick="refreshTxStatus(' + tx.id + ')" id="refreshTxBtn' + tx.id + '"><i class="fas fa-sync-alt"></i></button>' +
          '</div>';
      }

      content.innerHTML =
        '<div class="tx-detail-row" style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100);"><span style="color:var(--gray-500);font-size:13px;">Order ID</span><span style="font-size:13px;font-weight:500;">' + escapeHtml(tx.midtrans_order_id) + '</span></div>' +
        '<div class="tx-detail-row" style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100);"><span style="color:var(--gray-500);font-size:13px;">Credits</span><span style="font-size:13px;font-weight:500;">' + tx.credits + '</span></div>' +
        '<div class="tx-detail-row" style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100);"><span style="color:var(--gray-500);font-size:13px;">Total</span><span style="font-size:13px;font-weight:500;">IDR ' + price + '</span></div>' +
        '<div class="tx-detail-row" style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100);"><span style="color:var(--gray-500);font-size:13px;">Status</span><span style="font-size:13px;font-weight:500;">' + tx.status + '</span></div>' +
        '<div class="tx-detail-row" style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100);"><span style="color:var(--gray-500);font-size:13px;">Metode</span><span style="font-size:13px;font-weight:500;">' + paymentMethod + '</span></div>' +
        '<div class="tx-detail-row" style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray-100);"><span style="color:var(--gray-500);font-size:13px;">Dibuat</span><span style="font-size:13px;font-weight:500;">' + date + '</span></div>' +
        '<div class="tx-detail-row" style="display:flex;justify-content:space-between;padding:8px 0;"><span style="color:var(--gray-500);font-size:13px;">Dibayar</span><span style="font-size:13px;font-weight:500;">' + paidDate + '</span></div>' +
        actionsHtml;
    } catch (e) {
      content.innerHTML = '<p>Error memuat detail transaksi</p>';
    }
  };

  window.refreshTxStatus = async function (txId) {
    const btn = document.getElementById('refreshTxBtn' + txId);
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; }
    try {
      const res = await fetch('../backend/topup.php?action=history');
      const data = await res.json();
      if (!data.success) throw new Error('Failed');
      const tx = data.transactions.find(t => parseInt(t.id) === parseInt(txId));
      if (tx && tx.midtrans_order_id) {
        await fetch('../backend/topup.php?action=verify&order_id=' + encodeURIComponent(tx.midtrans_order_id));
      }
      if (window.refreshUserCredits) window.refreshUserCredits();
      const content = document.getElementById('txDetailContent');
      await renderTxDetail(txId, content);
    } catch (e) {
      console.warn('Refresh failed:', e);
    }
    if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-sync-alt"></i>'; }
  };

  window.closeTxDetailModal = function () {
    document.getElementById('txDetailModal').classList.remove('active');
    document.body.style.overflow = '';
  };

  document.getElementById('txDetailModal')?.addEventListener('click', function (e) {
    if (e.target === this) closeTxDetailModal();
  });

  window.checkPendingTopUp = function () {
    const orderId = sessionStorage.getItem('topup_order_id');
    if (!orderId) return;
    sessionStorage.removeItem('topup_order_id');
    fetch('../backend/topup.php?action=verify&order_id=' + encodeURIComponent(orderId))
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          loadAccountPage();
          if (data.transaction && data.transaction.status === 'success') {
            if (window.showModal) {
              showModal({
                icon: 'success',
                iconHtml: '<i class="fas fa-check-circle"></i>',
                title: 'Top-Up Berhasil!',
                message: data.transaction.credits + ' credits telah ditambahkan.',
                buttons: [{ text: 'OK', class: 'modal-btn-success', closeOnClick: true }]
              });
            } else {
              showAlert('Berhasil', 'Top-Up berhasil! ' + data.transaction.credits + ' credits telah ditambahkan.', 'success');
            }
          }
        }
      })
      .catch(console.warn);
  };

  window.loadTutorials = async function () {
    const container = document.getElementById('tutorialList');
    const empty = document.getElementById('tutorialEmpty');
    if (!container) return;

    container.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:40px 0;"><div class="loading-spinner"></div><p style="color:var(--gray-400);font-size:13px;margin-top:12px;">Memuat tutorial...</p></div>';
    if (empty) empty.style.display = 'none';

    try {
      const res = await fetch('../backend/admin.php?action=tutorials');
      const data = await res.json();

      if (!data.success || !data.items || data.items.length === 0) {
        container.innerHTML = '';
        if (empty) empty.style.display = 'block';
        return;
      }

      container.innerHTML = data.items.map(function (t) {
        var videoId = t.video_id || '';
        var desc = t.description || '';
        return '<div class="tutorial-card">' +
          '<div class="tutorial-card-video">' +
          (videoId ? '<iframe src="https://www.youtube.com/embed/' + videoId + '" allowfullscreen loading="lazy"></iframe>' : '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--gray-400);"><i class="fas fa-video" style="font-size:32px;"></i></div>') +
          '</div>' +
          '<div class="tutorial-card-body">' +
          '<h3>' + escapeHtml(t.title || '') + '</h3>' +
          (desc ? '<p>' + escapeHtml(desc) + '</p>' : '') +
          '</div>' +
          '</div>';
      }).join('');
    } catch (e) {
      console.warn('Load tutorials error:', e);
      container.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:40px 16px;color:var(--red-500);"><i class="fas fa-exclamation-circle"></i><p style="margin-top:8px;">Gagal memuat tutorial.</p></div>';
    }
  };

  window.loadMaterials = async function (page) {
    page = page || 1;
    const tbody = document.getElementById('materialsTableBody');
    const pagination = document.getElementById('materialsPagination');
    if (!tbody) return;

    const search = document.getElementById('matFilterSearch').value.trim();
    const subject = document.getElementById('matFilterSubject').value;

    tbody.innerHTML = '<tr><td colspan="5"><div class="mat-empty"><div class="loading-spinner"></div><p>Memuat...</p></div></td></tr>';
    pagination.innerHTML = '';

    try {
      let url = '../backend/upload.php?action=list_materials&page=' + page + '&limit=10';
      if (search) url += '&search=' + encodeURIComponent(search);
      if (subject) url += '&subject=' + encodeURIComponent(subject);

      const res = await fetch(url);
      const data = await res.json();

      if (!data.success) {
        tbody.innerHTML = '<tr><td colspan="5"><div class="mat-empty"><p>Gagal memuat data</p></div></td></tr>';
        return;
      }

      const materials = data.materials || [];
      const pag = data.pagination || {};

      if (materials.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5"><div class="mat-empty"><i class="fas fa-folder-open"></i><p>Belum ada materi</p></div></td></tr>';
        pagination.innerHTML = '';
        return;
      }

      tbody.innerHTML = materials.map((m, i) => {
        const no = ((pag.current_page || 1) - 1) * (pag.limit || 10) + i + 1;
        const date = new Date(m.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        return '<tr>' +
          '<td style="text-align:center;color:var(--gray-400);font-size:12px;">' + no + '</td>' +
          '<td style="font-weight:500;color:var(--gray-800);">' + escapeHtml(m.title) + '</td>' +
          '<td class="mat-hide-mobile" style="color:var(--gray-500);font-size:13px;">' + (m.subject_name ? escapeHtml(m.subject_name) : '-') + '</td>' +
          '<td class="mat-hide-mobile" style="color:var(--gray-400);font-size:12px;">' + date + '</td>' +
          '<td style="text-align:center;"><div class="mat-actions" style="justify-content:center;">' +
          '<button class="mat-btn mat-btn-view" onclick="viewMaterialDetail(' + m.id + ')" title="Lihat"><i class="fas fa-eye"></i></button>' +
          '<button class="mat-btn mat-btn-edit" onclick="openEditMaterialModal(' + m.id + ')" title="Edit"><i class="fas fa-pen"></i></button>' +
          '<button class="mat-btn mat-btn-delete" onclick="deleteMaterial(' + m.id + ')" title="Hapus"><i class="fas fa-trash"></i></button>' +
          '</div></td>' +
          '</tr>';
      }).join('');

      renderMaterialPagination(pag);
    } catch (e) {
      tbody.innerHTML = '<tr><td colspan="5"><div class="mat-empty"><p>Error memuat data</p></div></td></tr>';
    }
  };

  function renderMaterialPagination(pag) {
    const el = document.getElementById('materialsPagination');
    if (!el || !pag.total_pages || pag.total_pages <= 1) { el.innerHTML = ''; return; }

    let html = '<div class="pagination-inner">';
    const current = pag.current_page || 1;
    const total = pag.total_pages;

    html += '<button class="pagination-btn" onclick="loadMaterials(' + (current - 1) + ')" ' + (current === 1 ? 'disabled' : '') + '><i class="fas fa-chevron-left"></i></button>';

    let start = Math.max(1, current - 2);
    let end = Math.min(total, start + 4);
    if (end - start < 4) start = Math.max(1, end - 4);

    if (start > 1) {
      html += '<button class="pagination-btn" onclick="loadMaterials(1)">1</button>';
      if (start > 2) html += '<span class="pagination-ellipsis">...</span>';
    }
    for (let i = start; i <= end; i++) {
      html += '<button class="pagination-btn ' + (current === i ? 'active' : '') + '" onclick="loadMaterials(' + i + ')">' + i + '</button>';
    }
    if (end < total) {
      if (end < total - 1) html += '<span class="pagination-ellipsis">...</span>';
      html += '<button class="pagination-btn" onclick="loadMaterials(' + total + ')">' + total + '</button>';
    }

    html += '<button class="pagination-btn" onclick="loadMaterials(' + (current + 1) + ')" ' + (current === total ? 'disabled' : '') + '><i class="fas fa-chevron-right"></i></button>';
    html += '</div>';
    el.innerHTML = html;
  }

  window.openAddMaterialModal = function () {
    document.getElementById('matEditId').value = '';
    document.getElementById('matTitle').value = '';
    document.getElementById('matSubject').value = '';
    document.getElementById('matContent').value = '';
    document.getElementById('matModalError').classList.remove('show');
    document.getElementById('materialModalTitle').innerHTML = '<i class="fas fa-plus"></i> Tambah Materi';
    document.getElementById('materialModal').classList.add('active');
    document.body.style.overflow = 'hidden';
  };

  window.openEditMaterialModal = async function (id) {
    try {
      const res = await fetch('../backend/upload.php?action=get_material&id=' + id);
      const data = await res.json();
      if (!data.success || !data.material) { showAlert('Tidak Ditemukan', 'Materi tidak ditemukan', 'info'); return; }
      const m = data.material;
      document.getElementById('matEditId').value = m.id;
      document.getElementById('matTitle').value = m.title;
      document.getElementById('matSubject').value = m.subject_id || '';
      document.getElementById('matContent').value = m.content || '';
      document.getElementById('matModalError').classList.remove('show');
      document.getElementById('materialModalTitle').innerHTML = '<i class="fas fa-pen"></i> Edit Materi';
      document.getElementById('materialModal').classList.add('active');
      document.body.style.overflow = 'hidden';
    } catch (e) {
      showAlert('Error', 'Error memuat materi', 'warning');
    }
  };

  window.closeMaterialModal = function () {
    document.getElementById('materialModal').classList.remove('active');
    document.body.style.overflow = '';
  };

  document.getElementById('materialModal')?.addEventListener('click', function (e) {
    if (e.target === this) closeMaterialModal();
  });

  window.viewMaterialDetail = async function (id) {
    try {
      const res = await fetch('../backend/upload.php?action=get_material&id=' + id);
      const data = await res.json();
      if (!data.success || !data.material) { showAlert('Tidak Ditemukan', 'Materi tidak ditemukan', 'info'); return; }
      const m = data.material;
      document.getElementById('matDetailTitle').textContent = m.title || 'Detail Materi';
      document.getElementById('matDetailMeta').innerHTML = (m.subject_name ? '<span>' + escapeHtml(m.subject_name) + '</span>' : '') +
        ' &middot; ' + new Date(m.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
      document.getElementById('matDetailContent').textContent = m.content || '(Konten kosong)';
      document.getElementById('materialDetailModal').classList.add('active');
      document.body.style.overflow = 'hidden';
    } catch (e) {
      showAlert('Error', 'Error memuat detail materi', 'warning');
    }
  };

  window.closeMaterialDetailModal = function () {
    document.getElementById('materialDetailModal').classList.remove('active');
    document.body.style.overflow = '';
  };

  document.getElementById('materialDetailModal')?.addEventListener('click', function (e) {
    if (e.target === this) closeMaterialDetailModal();
  });

  window.debounceMatSearch = function () {
    clearTimeout(materialsSearchTimeout);
    materialsSearchTimeout = setTimeout(() => loadMaterials(1), 400);
  };

  window.saveMaterial = async function () {
    const id = document.getElementById('matEditId').value;
    const title = document.getElementById('matTitle').value.trim();
    const subjectId = document.getElementById('matSubject').value;
    const content = document.getElementById('matContent').value.trim();
    const errorEl = document.getElementById('matModalError');

    if (!title) { setError(errorEl, 'Masukkan judul materi'); return; }
    if (!content) { setError(errorEl, 'Masukkan konten materi'); return; }

    const btn = document.querySelector('#materialModal .btn-secondary');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    errorEl.classList.remove('show');

    try {
      const isUpdate = !!id;
      const endpoint = isUpdate ? 'update_material' : 'save_material';
      const body = isUpdate ? { id, title, subject_id: subjectId, content } : { title, subject_id: subjectId, content };

      const res = await fetch('../backend/upload.php?action=' + endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body)
      });
      const data = await res.json();
      if (data.success) {
        closeMaterialModal();
        loadMaterials(1);
        loadSavedMaterialsDropdown();
        if (window.showModal) {
          showModal({
            icon: 'success',
            iconHtml: '<i class="fas fa-check-circle"></i>',
            title: isUpdate ? 'Materi Diperbarui!' : 'Materi Disimpan!',
            message: isUpdate ? 'Materi berhasil diperbarui.' : 'Materi berhasil disimpan.',
            buttons: [{ text: 'OK', class: 'modal-btn-success', closeOnClick: true }]
          });
        }
      } else {
        setError(errorEl, data.error || 'Gagal menyimpan');
      }
    } catch (e) {
      setError(errorEl, 'Error menyimpan materi');
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-save"></i> Simpan Materi';
    }
  };

  window.deleteMaterial = async function (id) {
    showConfirm('Hapus Materi', 'Hapus materi ini?', async function() {
      try {
        const res = await fetch('../backend/upload.php?action=delete_material', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id })
        });
        const data = await res.json();
        if (data.success) {
          loadMaterials(1);
          loadSavedMaterialsDropdown();
        } else {
          showAlert('Gagal', data.error || 'Gagal menghapus', 'warning');
        }
      } catch (e) {
        showAlert('Error', 'Error menghapus materi', 'warning');
      }
    }, 'Hapus');
  };

  let generatedMaterialContent = '';

  window.openGenerateMaterialModal = async function () {
    generatedMaterialContent = '';
    document.getElementById('genMatTopic').value = '';
    document.getElementById('genMatInstructions').value = '';
    document.getElementById('genMatError').classList.remove('show');
    document.getElementById('genMatInputs').style.display = '';
    document.getElementById('genMatResult').style.display = 'none';
    document.getElementById('genMatContent').textContent = '';

    const classSelect = document.getElementById('genMatClass');
    while (classSelect.options.length > 1) classSelect.remove(1);

    try {
      const clsRes = await fetch('../backend/class.php?action=list');
      const clsData = await clsRes.json();
      if (clsData.success && clsData.classes) {
        clsData.classes.forEach(c => {
          const opt = document.createElement('option');
          opt.value = c.name;
          opt.textContent = c.name;
          classSelect.appendChild(opt);
        });
      }
    } catch (e) {
      console.error('Error loading classes:', e);
    }

    document.getElementById('generateMaterialModal').classList.add('active');
    document.body.style.overflow = 'hidden';
  };

  window.closeGenerateMaterialModal = function () {
    document.getElementById('generateMaterialModal').classList.remove('active');
    document.body.style.overflow = '';
  };

  document.getElementById('generateMaterialModal')?.addEventListener('click', function (e) {
    if (e.target === this) closeGenerateMaterialModal();
  });

  window.generateMaterial = async function () {
    const topic = document.getElementById('genMatTopic').value.trim();
    const subject = document.getElementById('genMatSubject').value;
    const cls = document.getElementById('genMatClass').value;
    const instructions = document.getElementById('genMatInstructions').value.trim();
    const errorEl = document.getElementById('genMatError');
    const btn = document.getElementById('genMatBtn');

    if (!topic) { setError(errorEl, 'Masukkan topik materi'); return; }
    if (!subject) { setError(errorEl, 'Pilih mata pelajaran'); return; }
    if (!cls) { setError(errorEl, 'Pilih kelas'); return; }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menggenerate...';
    errorEl.classList.remove('show');

    try {
      const res = await fetch('../backend/upload.php?action=generate_material', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ topic, subject, class: cls, extra_instructions: instructions })
      });
      const data = await res.json();

      if (data.success) {
        generatedMaterialContent = data.content;
        document.getElementById('genMatInputs').style.display = 'none';
        document.getElementById('genMatContent').textContent = data.content;
        document.getElementById('genMatResult').style.display = '';
        if (window.updateNavbarCredit) updateNavbarCredit();
      } else if (data.error === 'insufficient_credits') {
        setError(errorEl, data.message || 'Kredit tidak mencukupi. Silakan top-up terlebih dahulu.');
      } else {
        setError(errorEl, data.error || 'Gagal generate materi');
      }
    } catch (e) {
      setError(errorEl, 'Error saat generate materi');
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-wand-magic-sparkles"></i> Generate Materi';
    }
  };

  window.regenerateMaterial = function () {
    generatedMaterialContent = '';
    document.getElementById('genMatInputs').style.display = '';
    document.getElementById('genMatResult').style.display = 'none';
    document.getElementById('genMatError').classList.remove('show');
  };

  window.saveGeneratedMaterial = async function () {
    if (!generatedMaterialContent) return;

    const topic = document.getElementById('genMatTopic').value.trim();
    const subjectId = document.getElementById('genMatSubject').value;
    const btn = document.getElementById('genMatSaveBtn');

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    try {
      const res = await fetch('../backend/upload.php?action=save_material', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          title: topic,
          subject_id: subjectId,
          content: generatedMaterialContent
        })
      });
      const data = await res.json();

      if (data.success) {
        showModal({
          icon: 'success',
          iconHtml: '<i class="fas fa-check-circle"></i>',
          title: 'Materi Disimpan!',
          message: 'Materi berhasil disimpan dan siap digunakan.',
          buttons: [{
            text: 'OK',
            class: 'modal-btn-success',
            closeOnClick: true,
            onclick: function () {
              closeGenerateMaterialModal();
              loadMaterials(1);
              loadSavedMaterialsDropdown();
            }
          }]
        });
      } else {
        showAlert('Gagal', data.error || 'Gagal menyimpan materi', 'warning');
      }
    } catch (e) {
      showAlert('Error', 'Error menyimpan materi', 'warning');
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-save"></i> Simpan ke Materi';
    }
  };

  window.loadSavedMaterialsDropdown = async function (classId) {
    const select = document.getElementById('descSavedMaterial');
    if (!select) return;

    const currentValue = select.value;
    while (select.options.length > 1) select.remove(1);

    try {
      let url = '../backend/upload.php?action=list_materials&limit=100';
      if (classId) url += '&class_id=' + encodeURIComponent(classId);
      const res = await fetch(url);
      const data = await res.json();
      if (!data.success || !data.materials) return;

      data.materials.forEach(m => {
        const opt = document.createElement('option');
        opt.value = m.id;
        opt.textContent = m.title + (m.subject_name ? ' (' + m.subject_name + ')' : '');
        select.appendChild(opt);
      });

      if (currentValue) select.value = currentValue;
    } catch (e) {
      console.warn('Failed to load materials dropdown:', e);
    }
  };

  window.onSelectSavedMaterial = async function (select) {
    const id = select.value;
    if (!id) return;

    try {
      const res = await fetch('../backend/upload.php?action=get_material&id=' + id);
      const data = await res.json();
      if (!data.success || !data.material) return;

      document.getElementById('descMaterialText').value = data.material.content || '';
      if (data.material.subject_id && !document.getElementById('descSubject').value) {
        const subjectSelect = document.getElementById('descSubject');
        const sid = String(data.material.subject_id);
        let matchingOption = Array.from(subjectSelect.options).find(o => o.value === sid);
        if (!matchingOption) {
          matchingOption = Array.from(subjectSelect.options).find(o => o.textContent === data.material.subject_name);
        }
        if (matchingOption) subjectSelect.value = matchingOption.value;
      }
    } catch (e) {
      console.warn('Failed to load material:', e);
    }
  };

  window.loadSubjectList = async function () {
    const tbody = document.getElementById('subjectTableBody');
    if (!tbody) return;
    tbody.innerHTML = '<tr><td colspan="3"><div class="mat-empty"><div class="loading-spinner"></div><p>Memuat...</p></div></td></tr>';

    try {
      const res = await fetch('../backend/subject.php?action=list');
      const data = await res.json();
      if (!data.success || !data.subjects || data.subjects.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3"><div class="mat-empty"><i class="fas fa-book"></i><p>Belum ada mata pelajaran. Tambahkan yang pertama!</p></div></td></tr>';
        return;
      }

      let html = '';
      let currentGroup = '';
      let idx = 0;
      data.subjects.forEach(s => {
        const group = s.class_name || 'Tanpa Kelas';
        if (group !== currentGroup) {
          currentGroup = group;
          html += '<tr style="background:var(--gray-100);"><td colspan="3" style="padding:8px 12px;font-weight:600;font-size:13px;color:var(--gray-600);"><i class="fas fa-graduation-cap" style="margin-right:6px;"></i>' + escapeHtml(group) + '</td></tr>';
        }
        idx++;
        html += '<tr>' +
          '<td style="text-align:center;color:var(--gray-400);font-size:12px;">' + idx + '</td>' +
          '<td style="font-weight:500;color:var(--gray-800);">' + escapeHtml(s.name) + '</td>' +
          '<td style="text-align:center;"><div class="mat-actions" style="justify-content:center;">' +
          '<button class="mat-btn mat-btn-edit" onclick="window.editSubject(' + s.id + ')" title="Edit"><i class="fas fa-pen"></i></button>' +
          '<button class="mat-btn mat-btn-delete" onclick="window.userDeleteSubject(' + s.id + ')" title="Hapus"><i class="fas fa-trash"></i></button>' +
          '</div></td>' +
          '</tr>';
      });
      tbody.innerHTML = html;
    } catch (e) {
      tbody.innerHTML = '<tr><td colspan="3"><div class="mat-empty"><p>Error memuat data</p></div></td></tr>';
    }
  }

  window.addSubject = async function () {
    const input = document.getElementById('subjectInput');
    const name = input.value.trim();
    const classId = document.getElementById('subjectClassSelect').value;
    const errorEl = document.getElementById('subjectInputError');

    if (!name) { setError(errorEl, 'Masukkan nama mata pelajaran'); return; }
    errorEl.classList.remove('show');

    try {
      const res = await fetch('../backend/subject.php?action=add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, class_id: classId || null })
      });
      const data = await res.json();
      if (data.success) {
        input.value = '';
        loadSubjectList();
        reloadAllSubjects();
      } else {
        setError(errorEl, data.error || 'Gagal menyimpan');
      }
    } catch (e) {
      setError(errorEl, 'Error menyimpan mata pelajaran');
    }
  };

  window.editSubject = async function (id) {
    try {
      const res = await fetch('../backend/subject.php?action=get&id=' + id);
      const data = await res.json();
      if (!data.success || !data.subject) { showAlert('Tidak Ditemukan', 'Mata pelajaran tidak ditemukan', 'info'); return; }
      document.getElementById('subjectEditId').value = data.subject.id;
      document.getElementById('subjectEditName').value = data.subject.name;
      document.getElementById('subjectEditClass').value = data.subject.class_id || '';
      document.getElementById('subjectEditError').classList.remove('show');
      document.getElementById('subjectEditModal').classList.add('active');
      document.body.style.overflow = 'hidden';
      setTimeout(() => document.getElementById('subjectEditName').focus(), 100);
    } catch (e) {
      showAlert('Error', 'Error: ' + e.message, 'warning');
    }
  };

  window.saveSubjectEdit = async function () {
    const id = document.getElementById('subjectEditId').value;
    const name = document.getElementById('subjectEditName').value.trim();
    const classId = document.getElementById('subjectEditClass').value;
    const errorEl = document.getElementById('subjectEditError');
    if (!name) { setError(errorEl, 'Masukkan nama mata pelajaran'); return; }
    errorEl.classList.remove('show');
    try {
      const res = await fetch('../backend/subject.php?action=edit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, name, class_id: classId || null })
      });
      const data = await res.json();
      if (data.success) {
        closeSubjectEditModal();
        loadSubjectList();
        reloadAllSubjects();
      } else {
        setError(errorEl, data.error || 'Gagal menyimpan');
      }
    } catch (e) {
      setError(errorEl, 'Error menyimpan mata pelajaran');
    }
  };

  window.closeSubjectEditModal = function () {
    document.getElementById('subjectEditModal').classList.remove('active');
    document.body.style.overflow = '';
  };

  window.userDeleteSubject = async function (id) {
    showConfirm('Hapus Mata Pelajaran', 'Hapus mata pelajaran inis?', async function() {
      try {
        const res = await fetch('../backend/subject.php?action=delete', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id })
        });
        const data = await res.json();
        if (data.success) {
          loadSubjectList();
          reloadAllSubjects();
        } else {
          showAlert('Gagal', data.error || 'Gagal menghapus', 'warning');
        }
      } catch (e) {
        showAlert('Error', 'Error menghapus mata pelajaran', 'warning');
      }
    }, 'Hapus');
  };

  async function reloadAllSubjects() {
    await loadSubjects();
    loadClasses();
    loadSavedMaterialsDropdown(document.getElementById('descClass')?.value);
    const genClass = document.getElementById('genClass')?.value;
    const descClass = document.getElementById('descClass')?.value;
    if (genClass) filterSubjectDropdown(genClass, 'gen');
    if (descClass) filterSubjectDropdown(descClass, 'desc');
  }

  // ====== CLASSES ======

  window.loadClassList = async function () {
    const tbody = document.getElementById('classTableBody');
    if (!tbody) return;
    tbody.innerHTML = '<tr><td colspan="3"><div class="mat-empty"><div class="loading-spinner"></div><p>Memuat...</p></div></td></tr>';

    try {
      const res = await fetch('../backend/class.php?action=list');
      const data = await res.json();
      if (!data.success || !data.classes || data.classes.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3"><div class="mat-empty"><i class="fas fa-graduation-cap"></i><p>Belum ada kelas. Tambahkan yang pertama!</p></div></td></tr>';
        return;
      }

      tbody.innerHTML = data.classes.map((c, i) =>
        '<tr>' +
        '<td style="text-align:center;color:var(--gray-400);font-size:12px;">' + (i + 1) + '</td>' +
        '<td style="font-weight:500;color:var(--gray-800);">' + escapeHtml(c.name) + '</td>' +
        '<td style="text-align:center;"><div class="mat-actions" style="justify-content:center;">' +
        '<button class="mat-btn mat-btn-edit" onclick="editClass(' + c.id + ')" title="Edit"><i class="fas fa-pen"></i></button>' +
        '<button class="mat-btn mat-btn-delete" onclick="deleteClass(' + c.id + ')" title="Hapus"><i class="fas fa-trash"></i></button>' +
        '</div></td>' +
        '</tr>'
      ).join('');
    } catch (e) {
      tbody.innerHTML = '<tr><td colspan="3"><div class="mat-empty"><p>Error memuat data</p></div></td></tr>';
    }
  };

  window.addClass = async function () {
    const input = document.getElementById('classInput');
    const name = input.value.trim();
    const errorEl = document.getElementById('classInputError');

    if (!name) { setError(errorEl, 'Masukkan nama kelas'); return; }
    errorEl.classList.remove('show');

    try {
      const res = await fetch('../backend/class.php?action=add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name })
      });
      const data = await res.json();
      if (data.success) {
        input.value = '';
        loadClassList();
        loadClasses();
      } else {
        setError(errorEl, data.error || 'Gagal menyimpan');
      }
    } catch (e) {
      setError(errorEl, 'Error menyimpan kelas');
    }
  };

  window.editClass = async function (id) {
    try {
      const res = await fetch('../backend/class.php?action=get&id=' + id);
      const data = await res.json();
      if (!data.success || !data.class) { showAlert('Tidak Ditemukan', 'Kelas tidak ditemukan', 'info'); return; }
      document.getElementById('classEditId').value = data.class.id;
      document.getElementById('classEditName').value = data.class.name;
      document.getElementById('classEditError').classList.remove('show');
      document.getElementById('classEditModal').classList.add('active');
      document.body.style.overflow = 'hidden';
      setTimeout(() => document.getElementById('classEditName').focus(), 100);
    } catch (e) {
      showAlert('Error', 'Error: ' + e.message, 'warning');
    }
  };

  window.saveClassEdit = async function () {
    const id = document.getElementById('classEditId').value;
    const name = document.getElementById('classEditName').value.trim();
    const errorEl = document.getElementById('classEditError');
    if (!name) { setError(errorEl, 'Masukkan nama kelas'); return; }
    errorEl.classList.remove('show');
    try {
      const res = await fetch('../backend/class.php?action=edit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, name })
      });
      const data = await res.json();
      if (data.success) {
        closeClassEditModal();
        loadClassList();
        loadClasses();
        loadSubjectList();
      } else {
        setError(errorEl, data.error || 'Gagal menyimpan');
      }
    } catch (e) {
      setError(errorEl, 'Error menyimpan kelas');
    }
  };

  window.closeClassEditModal = function () {
    document.getElementById('classEditModal').classList.remove('active');
    document.body.style.overflow = '';
  };

  window.deleteClass = async function (id) {
    showConfirm('Hapus Kelas', 'Hapus kelas ini?', async function() {
      try {
        const res = await fetch('../backend/class.php?action=delete', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id })
        });
        const data = await res.json();
        if (data.success) {
          loadClassList();
          loadClasses();
          loadSubjectList();
        } else {
          showAlert('Gagal', data.error || 'Gagal menghapus', 'warning');
        }
      } catch (e) {
        showAlert('Error', 'Error menghapus kelas', 'warning');
      }
    }, 'Hapus');
  };

  window.handleReportUpload = async function (input) {
    const file = input.files[0];
    if (!file) return;

    if (file.size > 5 * 1024 * 1024) {
      setError(document.getElementById('reportError'), 'File terlalu besar. Maks 5MB');
      return;
    }

    const formData = new FormData();
    formData.append('image', file);
    formData.append('type', 'report');

    try {
      input.disabled = true;
      const res = await fetch('../backend/upload.php', { method: 'POST', body: formData });
      const data = await res.json();
      if (data.success && data.file_path) {
        document.getElementById('reportImageUrl').value = data.file_path;
        document.getElementById('reportUploadImg').src = data.file_path;
        document.getElementById('reportUploadPlaceholder').style.display = 'none';
        document.getElementById('reportUploadPreview').style.display = 'block';
      } else {
        throw new Error(data.error || 'Upload failed');
      }
    } catch (e) {
      setError(document.getElementById('reportError'), e.message || 'Gagal upload');
    } finally {
      input.disabled = false;
    }
    input.value = '';
  };

  window.removeReportUpload = function () {
    document.getElementById('reportImageUrl').value = '';
    document.getElementById('reportUploadImg').src = '';
    document.getElementById('reportUploadPlaceholder').style.display = 'block';
    document.getElementById('reportUploadPreview').style.display = 'none';
  };

  window.submitReport = async function () {
    const subject = document.getElementById('reportSubject').value;
    const description = document.getElementById('reportDescription').value.trim();
    const imageUrl = document.getElementById('reportImageUrl').value;
    const btn = document.querySelector('#panel-report .btn-generate');
    const success = document.getElementById('reportSuccess');
    const error = document.getElementById('reportError');

    if (!description) { setError(error, 'Masukkan deskripsi masalah'); return; }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
    error.classList.remove('show');
    success.classList.remove('show');

    try {
      const res = await fetch('../backend/report.php?action=submit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ subject, description, image_url: imageUrl })
      });
      const data = await res.json();
      if (data.success) {
        success.textContent = 'Laporan berhasil dikirim! Terima kasih.';
        success.classList.add('show');
        document.getElementById('reportDescription').value = '';
        removeReportUpload();
      } else {
        throw new Error(data.error || 'Gagal mengirim');
      }
    } catch (e) {
      setError(error, e.message || 'Gagal mengirim laporan');
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Laporan';
    }
  };

  window.showInsufficientCreditsModal = function () {
    if (window.showModal) {
      showModal({
        icon: 'warning',
        iconHtml: '<i class="fas fa-coins"></i>',
        title: 'Credits Tidak Cukup',
        message: 'Credits Anda habis. Silakan top-up untuk melanjutkan.',
        buttons: [{ text: 'OK', class: 'modal-btn-confirm', closeOnClick: true }]
      });
    } else {
      showAlert('Credits Tidak Cukup', 'Credits tidak cukup. Silakan top-up.', 'warning');
    }
  };

  window.refreshUserCredits = async function () {
    try {
      const res = await fetch('../backend/auth.php?action=user');
      const data = await res.json();
      if (data.success && data.user) {
        window.currentUser = data.user;
        if (typeof updateNavbarCredit === 'function') updateNavbarCredit();
      }
    } catch (e) {
      console.warn('Failed to refresh credits:', e);
    }
  };

  document.querySelectorAll('.upload-area').forEach(area => {
    if (!area.id || area.id === 'matFileInput') return;
    area.addEventListener('dragover', function (e) {
      e.preventDefault();
      this.classList.add('dragover');
    });
    area.addEventListener('dragleave', function (e) {
      e.preventDefault();
      this.classList.remove('dragover');
    });
    area.addEventListener('drop', function (e) {
      e.preventDefault();
      this.classList.remove('dragover');
      const files = e.dataTransfer.files;
      if (files.length > 0) {
        const fileInput = this.closest('.form-group')?.querySelector('.upload-input') ||
                          this.parentElement?.querySelector('.upload-input');
        if (fileInput) {
          fileInput.files = files;
          const evt = new Event('change', { bubbles: true });
          fileInput.dispatchEvent(evt);
          if (fileInput.onchange) fileInput.onchange(evt);
        }
      }
    });
  });

  loadSubjects();
  loadClasses();
  setTimeout(function () {
    updateCreditInfo('gen');
    updateCreditInfo('desc');
  }, 100);
  checkPendingTopUp();
})();
</script>
