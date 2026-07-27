<?php
session_start();
ini_set('pcre.backtrack_limit', 10000000);
require_once __DIR__ . '/config.php';

requireLogin();

$raw = file_get_contents('php://input');
if (!empty($raw)) {
  $input = json_decode($raw, true);
} elseif (!empty($_POST['payload'])) {
  $input = json_decode($_POST['payload'], true);
}
if (!$input || !isset($input['action']) || !isset($input['questions'])) {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Invalid request']);
  exit;
}

$action           = $input['action'];
$title            = $input['title'] ?? 'Soal';
$questions        = $input['questions'];
$opts             = $input['options'] ?? [];
$includeAnswer    = $opts['include_answer']    ?? true;
$includeExplanation = $opts['include_explanation'] ?? true;
$answerPosition   = $opts['answer_position']   ?? 'per_soal';
$showHeader       = $opts['show_header']       ?? true;
$headerText       = $opts['header_text']       ?? '';
$showInfo         = $opts['show_info']         ?? true;
$infoFields       = $opts['info_fields']       ?? [];
$showBiodata      = $opts['show_biodata']      ?? false;
$biodataFields    = $opts['biodata_fields']    ?? [];
$showInstructions = $opts['show_instructions'] ?? true;
$instructionsText = $opts['instructions_text'] ?? '';

function escapeHtml($text) {
  return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}

/* ─────────────────────────────────────────────
   PDF / HTML EXPORT
   ───────────────────────────────────────────── */
function buildExportHtml(
  $questions, $title, $includeAnswer, $includeExplanation,
  $answerPosition, $showHeader, $headerText, $showInfo, $infoFields,
  $showBiodata, $biodataFields, $showInstructions, $instructionsText
) {
  /* --- HEADER ------------------------------------------------- */
  $headerHtml = '';
  if ($showHeader) {
    $hText = !empty($headerText) ? $headerText : $title;
    $headerHtml = '
    <div class="doc-header">
      <div class="doc-header__title">' . strtoupper(escapeHtml($hText)) . '</div>
    </div>';
  }

  /* --- INFO SOAL ---------------------------------------------- */
  $infoHtml = '';
  if ($showInfo && !empty($infoFields)) {
    $rows = '';
    foreach ($infoFields as $field) {
      $label = $field['label'] ?? '';
      $value = $field['value'] ?? '';
      if (!$label) continue;
      $rows .= '<tr>
        <td class="info-label">' . escapeHtml($label) . '</td>
        <td class="info-sep">:</td>
        <td class="info-value">' . escapeHtml($value) . '</td>
      </tr>';
    }
    $infoHtml = '<table class="info-table">' . $rows . '</table>';
  }

  /* --- PETUNJUK ----------------------------------------------- */
  $instructionsHtml = '';
  if ($showInstructions && !empty($instructionsText)) {
    $instructionsHtml = '
    <div class="instructions-box">
      <span class="instructions-label">Petunjuk:</span>
      ' . escapeHtml($instructionsText) . '
    </div>';
  }

  /* --- BIODATA ------------------------------------------------ */
  $biodataHtml = '';
  if ($showBiodata && !empty($biodataFields)) {
    $rows = '';
    foreach ($biodataFields as $field) {
      $rows .= '<tr>
        <td class="bio-label">' . escapeHtml($field) . '</td>
        <td class="bio-sep">:</td>
        <td class="bio-line"></td>
      </tr>';
    }
    $biodataHtml = '
    <div class="biodata-box">
      <div class="biodata-box__title">IDENTITAS PESERTA</div>
      <table class="bio-table">' . $rows . '</table>
    </div>';
  }

  /* --- SOAL --------------------------------------------------- */
  $questionsHtml = '';
  $answersList   = [];
  $optionLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

  foreach ($questions as $i => $q) {
    $num         = $i + 1;
    $questionText = escapeHtml($q['question'] ?? '');
    $answer      = $q['answer'] ?? '';
    $explanation = $q['explanation'] ?? '';

    /* opsi pilihan ganda */
    $optionsHtml = '';
    if (!empty($q['options']) && is_array($q['options'])) {
      $optRows = '';
      foreach ($q['options'] as $oi => $opt) {
        $letter  = $optionLetters[$oi] ?? chr(65 + $oi);
        $optRows .= '<tr>
          <td class="opt-key">' . $letter . '.</td>
          <td class="opt-val">' . escapeHtml($opt) . '</td>
        </tr>';
      }
      $optionsHtml = '<table class="options-table">' . $optRows . '</table>';
    }

    /* gambar */
    $imageHtml = '';
    if (!empty($q['image_url'])) {
      $imagePath = realpath(dirname(__DIR__) . '/' . ltrim($q['image_url'], '/'));
      if ($imagePath && file_exists($imagePath)) {
        $imageHtml = '<div class="soal-image"><img src="' . $imagePath . '" alt="Gambar soal"></div>';
      }
    }

    /* jawaban per soal */
    $answerHtml = '';
    if ($includeAnswer && $answerPosition === 'per_soal') {
      $answerHtml = '<div class="answer-box">
        <span class="answer-label">Jawaban:</span>
        <span class="answer-value">' . escapeHtml($answer) . '</span>';
      if ($includeExplanation && $explanation) {
        $answerHtml .= '<div class="explanation-text"><em>Pembahasan:</em> ' . escapeHtml($explanation) . '</div>';
      }
      $answerHtml .= '</div>';
    }

    if ($includeAnswer && $answerPosition === 'akhir') {
      $answersList[] = ['num' => $num, 'answer' => $answer, 'explanation' => $explanation];
    }

    $questionsHtml .= '
    <div class="soal-item">
      <div class="soal-num-row">
        <span class="soal-num">' . $num . '.</span>
        <div class="soal-body">
          <div class="soal-text">' . $questionText . '</div>
          ' . $imageHtml . '
          ' . $optionsHtml . '
          ' . $answerHtml . '
        </div>
      </div>
    </div>';
  }

  /* --- KUNCI JAWABAN (akhir) ---------------------------------- */
  $answersSection = '';
  if ($includeAnswer && $answerPosition === 'akhir' && !empty($answersList)) {
    $items = '';
    foreach ($answersList as $a) {
      $items .= '<div class="kunci-item">
        <span class="kunci-num">' . $a['num'] . '.</span>
        <span class="kunci-ans">' . escapeHtml($a['answer']) . '</span>';
      if ($includeExplanation && !empty($a['explanation'])) {
        $items .= '<span class="kunci-expl">— ' . escapeHtml($a['explanation']) . '</span>';
      }
      $items .= '</div>';
    }
    $answersSection = '
    <div class="kunci-section">
      <div class="kunci-title">KUNCI JAWABAN</div>
      <div class="kunci-grid">' . $items . '</div>
    </div>';
  }

  /* --- CSS ---------------------------------------------------- */
  $css = '
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: "Times New Roman", Times, serif;
      font-size: 12pt;
      line-height: 1.6;
      color: #111;
      background: #fff;
      padding: 32px 48px;
    }

    /* ── HEADER ── */
    .doc-header {
      text-align: center;
      margin-bottom: 20px;
      padding: 10px 0;
      border-top: 3px double #111;
      border-bottom: 3px double #111;
    }
    .doc-header__title {
      font-size: 15pt;
      font-weight: 700;
      letter-spacing: 2px;
    }

    /* ── INFO TABLE ── */
    .info-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 14px;
      font-size: 11pt;
    }
    .info-table td { padding: 2px 4px; vertical-align: top; }
    .info-label { width: 160px; font-weight: 600; color: #222; }
    .info-sep   { width: 14px; text-align: center; }
    .info-value { color: #333; }

    /* ── PETUNJUK ── */
    .instructions-box {
      margin-bottom: 14px;
      padding: 8px 12px;
      border-left: 4px solid #1d4ed8;
      background: #eff6ff;
      font-size: 11pt;
      color: #1e3a5f;
    }
    .instructions-label { font-weight: 700; margin-right: 6px; }

    /* ── BIODATA ── */
    .biodata-box {
      margin-bottom: 18px;
      padding: 12px 14px;
      border: 1px solid #ccc;
      border-radius: 3px;
    }
    .biodata-box__title {
      font-size: 11pt;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 8px;
      border-bottom: 1px solid #ddd;
      padding-bottom: 4px;
    }
    .bio-table { width: 100%; border-collapse: collapse; }
    .bio-table td { padding: 4px 4px; font-size: 11pt; }
    .bio-label { width: 160px; font-weight: 500; }
    .bio-sep   { width: 14px; text-align: center; }
    .bio-line  { border-bottom: 1px solid #555; min-width: 200px; }

    /* ── SOAL ITEM ── */
    .soal-item { margin-bottom: 18px; page-break-inside: avoid; }
    .soal-num-row { display: flex; align-items: flex-start; gap: 6px; }
    .soal-num {
      font-weight: 700;
      font-size: 12pt;
      min-width: 28px;
      flex-shrink: 0;
      padding-top: 1px;
    }
    .soal-body { flex: 1; }
    .soal-text { text-align: justify; margin-bottom: 6px; }
    .soal-image { margin: 6px 0; }
    .soal-image img { max-width: 220px; height: auto; border: 1px solid #ddd; }

    /* ── OPSI ── */
    .options-table {
      border-collapse: collapse;
      margin: 4px 0 6px 4px;
      width: 100%;
    }
    .options-table td { padding: 2px 4px; vertical-align: top; font-size: 11.5pt; }
    .opt-key { width: 26px; font-weight: 600; white-space: nowrap; }

    /* ── JAWABAN PER SOAL ── */
    .answer-box {
      margin-top: 6px;
      padding: 5px 10px;
      background: #f0fdf4;
      border-left: 4px solid #16a34a;
      border-radius: 2px;
      font-size: 11pt;
    }
    .answer-label { font-weight: 700; color: #166534; margin-right: 6px; }
    .answer-value { font-weight: 700; color: #15803d; }
    .explanation-text { margin-top: 4px; font-size: 10.5pt; color: #4b5563; }

    /* ── KUNCI JAWABAN (akhir) ── */
    .kunci-section {
      margin-top: 32px;
      padding-top: 14px;
      border-top: 2px solid #111;
      page-break-before: always;
    }
    .kunci-title {
      font-size: 13pt;
      font-weight: 700;
      text-align: center;
      letter-spacing: 2px;
      text-transform: uppercase;
      margin-bottom: 14px;
    }
    .kunci-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 4px 16px;
    }
    .kunci-item { font-size: 11pt; display: flex; gap: 6px; align-items: baseline; }
    .kunci-num  { font-weight: 700; min-width: 24px; flex-shrink: 0; }
    .kunci-ans  { font-weight: 700; color: #15803d; }
    .kunci-expl { font-size: 10pt; color: #6b7280; font-style: italic; }
  ';

  /* --- ASSEMBLE ----------------------------------------------- */
  $html  = '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8">';
  $html .= '<style>' . $css . '</style></head><body>';
  $html .= $headerHtml . $infoHtml . $instructionsHtml . $biodataHtml . $questionsHtml . $answersSection;
  $html .= '</body></html>';

  return $html;
}

/* ─────────────────────────────────────────────
   DOCX EXPORT  (PhpWord)
   ───────────────────────────────────────────── */
/* ─────────────────────────────────────────────
   HTML-FOR-WORD EXPORT (khusus .doc)
   ───────────────────────────────────────────── */
function buildExportHtmlDoc(
  $questions, $title, $includeAnswer, $includeExplanation,
  $answerPosition, $showHeader, $headerText, $showInfo, $infoFields,
  $showBiodata, $biodataFields, $showInstructions, $instructionsText
) {
  $optionLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

  /* --- HEADER ------------------------------------------------- */
  $headerHtml = '';
  if ($showHeader) {
    $hText = !empty($headerText) ? $headerText : $title;
    $headerHtml = '
    <div class="doc-header">
      <p class="doc-header__title">' . strtoupper(escapeHtml($hText)) . '</p>
    </div>';
  }

  /* --- INFO SOAL ---------------------------------------------- */
  $infoHtml = '';
  if ($showInfo && !empty($infoFields)) {
    $rows = '';
    foreach ($infoFields as $field) {
      $label = $field['label'] ?? '';
      $value = $field['value'] ?? '';
      if (!$label) continue;
      $rows .= '<tr>
        <td class="info-label">' . escapeHtml($label) . '</td>
        <td class="info-sep">:</td>
        <td class="info-value">' . escapeHtml($value) . '</td>
      </tr>';
    }
    $infoHtml = '<table class="info-table">' . $rows . '</table>';
  }

  /* --- PETUNJUK ----------------------------------------------- */
  $instructionsHtml = '';
  if ($showInstructions && !empty($instructionsText)) {
    $instructionsHtml = '
    <p class="instructions-box">
      <b>Petunjuk:</b> ' . escapeHtml($instructionsText) . '
    </p>';
  }

  /* --- BIODATA ------------------------------------------------ */
  $biodataHtml = '';
  if ($showBiodata && !empty($biodataFields)) {
    $rows = '';
    foreach ($biodataFields as $field) {
      $rows .= '<tr>
        <td class="bio-label">' . escapeHtml($field) . '</td>
        <td class="bio-sep">:</td>
        <td class="bio-line">&nbsp;</td>
      </tr>';
    }
    $biodataHtml = '
    <div class="biodata-box">
      <p class="biodata-title">IDENTITAS PESERTA</p>
      <table class="bio-table">' . $rows . '</table>
    </div>';
  }

  /* --- SOAL --------------------------------------------------- */
  $questionsHtml = '';
  $answersList   = [];

  foreach ($questions as $i => $q) {
    $num          = $i + 1;
    $questionText = escapeHtml($q['question'] ?? '');
    $answer       = $q['answer'] ?? '';
    $explanation  = $q['explanation'] ?? '';

    /* opsi pilihan ganda */
    $optionsHtml = '';
    if (!empty($q['options']) && is_array($q['options'])) {
      $optRows = '';
      foreach ($q['options'] as $oi => $opt) {
        $letter   = $optionLetters[$oi] ?? chr(65 + $oi);
        $optRows .= '<tr>
          <td class="opt-key">' . $letter . '.</td>
          <td class="opt-val">' . escapeHtml($opt) . '</td>
        </tr>';
      }
      $optionsHtml = '<table class="options-table">' . $optRows . '</table>';
    }

    $imageHtml = '';

    if (!empty($q['image_url'])) {
        $imagePath = realpath(dirname(__DIR__) . '/' . ltrim($q['image_url'], '/'));

        if ($imagePath && file_exists($imagePath)) {
            $ext = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

            $mime = match ($ext) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png'         => 'image/png',
                'gif'         => 'image/gif',
                'webp'        => 'image/webp',
                'svg'         => 'image/svg+xml',
                default       => 'application/octet-stream'
            };

            $imageData = base64_encode(file_get_contents($imagePath));

            $imageHtml = sprintf(
                '<div class="soal-image"><img width="200" src="data:%s;base64,%s" alt="Gambar soal"></div>',
                $mime,
                $imageData
            );
        }
    }

    /* jawaban per soal */
    $answerHtml = '';
    if ($includeAnswer && $answerPosition === 'per_soal') {
      $answerHtml = '<p class="answer-box"><b>Jawaban:</b> <b>' . escapeHtml($answer) . '</b>';
      if ($includeExplanation && $explanation) {
        $answerHtml .= '<br><i>Pembahasan: ' . escapeHtml($explanation) . '</i>';
      }
      $answerHtml .= '</p>';
    }

    if ($includeAnswer && $answerPosition === 'akhir') {
      $answersList[] = ['num' => $num, 'answer' => $answer, 'explanation' => $explanation];
    }

    $questionsHtml .= '
    <table class="soal-table">
      <tr>
        <td class="soal-num">' . $num . '.</td>
        <td class="soal-body">
          <p class="soal-text">' . $questionText . '</p>
          ' . $imageHtml . '         <!-- tambahkan di sini -->
          ' . $optionsHtml . '
          ' . $answerHtml . '
        </td>
      </tr>
    </table>';
  }

  /* --- KUNCI JAWABAN (akhir) ---------------------------------- */
  $answersSection = '';
  if ($includeAnswer && $answerPosition === 'akhir' && !empty($answersList)) {
    // Bagi ke 3 kolom dengan tabel
    $perRow  = 3;
    $chunks  = array_chunk($answersList, $perRow);
    $kRows   = '';
    foreach ($chunks as $row) {
      while (count($row) < $perRow) $row[] = null;
      $kCells = '';
      foreach ($row as $a) {
        if ($a === null) {
          $kCells .= '<td class="kunci-cell"></td>';
        } else {
          $expl = ($includeExplanation && !empty($a['explanation']))
            ? ' <i style="color:#555;font-size:9pt;">— ' . escapeHtml($a['explanation']) . '</i>'
            : '';
          $kCells .= '<td class="kunci-cell"><b>' . $a['num'] . '.</b> <b style="color:#166534;">' . escapeHtml($a['answer']) . '</b>' . $expl . '</td>';
        }
      }
      $kRows .= '<tr>' . $kCells . '</tr>';
    }
    $answersSection = '
    <div style="page-break-before:always;">
      <p class="kunci-title">KUNCI JAWABAN</p>
      <table class="kunci-table">' . $kRows . '</table>
    </div>';
  }

  /* --- CSS (Word-compatible, tanpa flexbox/grid) -------------- */
  $css = '
    body {
      font-family: "Times New Roman", Times, serif;
      font-size: 12pt;
      line-height: 1.6;
      color: #111;
      margin: 0;
      padding: 0;
    }

    /* ── HEADER ── */
    .doc-header {
      text-align: center;
      border-top: 3pt double #111;
      border-bottom: 3pt double #111;
      padding: 8pt 0;
      margin-bottom: 14pt;
    }
    .doc-header__title {
      font-size: 15pt;
      font-weight: bold;
      letter-spacing: 2px;
      margin: 0;
    }

    .soal-image { margin: 4pt 0 6pt 0; }
    .soal-image img { width: 200px; height: auto; }

    /* ── INFO TABLE ── */
    .info-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10pt;
      font-size: 11pt;
    }
    .info-table td { padding: 2pt 3pt; vertical-align: top; }
    .info-label { width: 150pt; font-weight: bold; }
    .info-sep   { width: 10pt; text-align: center; }
    .info-value { }

    /* ── PETUNJUK ── */
    .instructions-box {
      border-left: 4pt solid #1d4ed8;
      padding: 6pt 10pt;
      margin-bottom: 10pt;
      font-size: 11pt;
      color: #1e3a5f;
      background: #eff6ff;
      mso-border-left-alt: solid #1d4ed8 3pt;
    }

    /* ── BIODATA ── */
    .biodata-box {
      border: 1pt solid #ccc;
      padding: 10pt 12pt;
      margin-bottom: 14pt;
    }
    .biodata-title {
      font-size: 11pt;
      font-weight: bold;
      text-transform: uppercase;
      border-bottom: 1pt solid #ddd;
      padding-bottom: 4pt;
      margin: 0 0 6pt 0;
    }
    .bio-table { width: 100%; border-collapse: collapse; }
    .bio-table td { padding: 4pt 3pt; font-size: 11pt; }
    .bio-label { width: 150pt; font-weight: 500; }
    .bio-sep   { width: 10pt; text-align: center; }
    .bio-line  { border-bottom: 1pt solid #555; min-width: 180pt; }

    /* ── SOAL TABLE ── */
    .soal-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 14pt;
      page-break-inside: avoid;
    }
    .soal-table td { vertical-align: top; padding: 0; }
    .soal-num {
      width: 22pt;
      font-weight: bold;
      font-size: 12pt;
      white-space: nowrap;
      padding-top: 1pt;
    }
    .soal-body { }
    .soal-text { margin: 0 0 4pt 0; text-align: justify; }

    /* ── OPSI ── */
    .options-table {
      border-collapse: collapse;
      margin: 2pt 0 4pt 4pt;
      width: 100%;
    }
    .options-table td { padding: 1pt 3pt; vertical-align: top; font-size: 11pt; }
    .opt-key { width: 18pt; font-weight: bold; white-space: nowrap; }

    /* ── JAWABAN PER SOAL ── */
    .answer-box {
      margin: 4pt 0 0 0;
      padding: 4pt 8pt;
      border-left: 4pt solid #16a34a;
      font-size: 11pt;
      background: #f0fdf4;
      mso-border-left-alt: solid #16a34a 3pt;
    }

    /* ── KUNCI JAWABAN ── */
    .kunci-title {
      font-size: 13pt;
      font-weight: bold;
      text-align: center;
      letter-spacing: 2px;
      text-transform: uppercase;
      border-top: 2pt solid #111;
      border-bottom: 1pt solid #111;
      padding: 6pt 0;
      margin: 0 0 10pt 0;
    }
    .kunci-table {
      width: 100%;
      border-collapse: collapse;
    }
    .kunci-cell {
      width: 33%;
      padding: 3pt 6pt;
      font-size: 11pt;
      vertical-align: top;
    }
  ';

  /* --- ASSEMBLE (dengan XML namespace Word agar dikenali Word) - */
  $html  = '<html xmlns:o="urn:schemas-microsoft-com:office:office"';
  $html .= ' xmlns:w="urn:schemas-microsoft-com:office:word"';
  $html .= ' xmlns="http://www.w3.org/TR/REC-html40">';
  $html .= '<head><meta charset="UTF-8">';
  $html .= '<!--[if gte mso 9]><xml><w:WordDocument>';
  $html .= '<w:View>Print</w:View>';
  $html .= '<w:Zoom>100</w:Zoom>';
  $html .= '<w:DoNotOptimizeForBrowser/>';
  $html .= '</w:WordDocument></xml><![endif]-->';
  $html .= '<style>' . $css . '</style></head><body>';
  $html .= $headerHtml . $infoHtml . $instructionsHtml . $biodataHtml . $questionsHtml . $answersSection;
  $html .= '</body></html>';

  return $html;
}

/* ─────────────────────────────────────────────
   DISPATCH
   ───────────────────────────────────────────── */
if ($action === 'pdf') {
  require_once __DIR__ . '/../vendor/autoload.php';

  $html = buildExportHtml(
    $questions, $title, $includeAnswer, $includeExplanation,
    $answerPosition, $showHeader, $headerText, $showInfo, $infoFields,
    $showBiodata, $biodataFields, $showInstructions, $instructionsText
  );

  $mpdf = new \Mpdf\Mpdf([
    'mode'          => 'utf-8',
    'format'        => 'A4',
    'orientation'   => 'P',
    'margin_top'    => 18,
    'margin_bottom' => 18,
    'margin_left'   => 20,
    'margin_right'  => 20,
    'tempDir'       => sys_get_temp_dir() . '/mpdf',
  ]);
  $mpdf->WriteHTML($html);

  $filename = preg_replace('/[^a-zA-Z0-9\-\_]/', '_', $title) . '.pdf';
  header('Content-Type: application/pdf');
  header('Content-Disposition: attachment; filename="' . $filename . '"');
  $mpdf->Output($filename, 'D');
  exit;

} elseif ($action === 'doc') {
  // Gunakan mPDF sama persis seperti PDF
  require_once __DIR__ . '/../vendor/autoload.php';

  $html = buildExportHtmlDoc(  // pakai fungsi PDF yang sudah jalan
    $questions, $title, $includeAnswer, $includeExplanation,
    $answerPosition, $showHeader, $headerText, $showInfo, $infoFields,
    $showBiodata, $biodataFields, $showInstructions, $instructionsText
  );

  $mpdf = new \Mpdf\Mpdf([
    'mode'          => 'utf-8',
    'format'        => 'A4',
    'margin_top'    => 18,
    'margin_bottom' => 18,
    'margin_left'   => 20,
    'margin_right'  => 20,
    'tempDir'       => sys_get_temp_dir() . '/mpdf',
  ]);
  $mpdf->WriteHTML($html);

  $filename = preg_replace('/[^a-zA-Z0-9\-\_]/', '_', $title) . '.pdf';
  header('Content-Type: application/pdf');
  header('Content-Disposition: attachment; filename="' . $filename . '"');
  $mpdf->Output($filename, 'D');
  exit;
}else {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Unknown action']);
  exit;
}