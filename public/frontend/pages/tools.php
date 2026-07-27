<?php include __DIR__ . '/../scripts/css/tools.php' ?>

<div id="page-tools" class="page">
  <div class="tools-layout">
    <?php include __DIR__ . '/tools/sidebar.php' ?>

    <div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleToolsSidebar()"></div>

    <main class="tools-content">
      <?php include __DIR__ . '/tools/generate.php' ?>
      <?php include __DIR__ . '/tools/describe.php' ?>
      <?php include __DIR__ . '/tools/questions.php' ?>
      <?php include __DIR__ . '/tools/materials.php' ?>
      <?php include __DIR__ . '/tools/edit-question-modal.php' ?>
      <?php include __DIR__ . '/tools/question-detail.php' ?>
      <?php include __DIR__ . '/tools/account.php' ?>
      <?php include __DIR__ . '/tools/tutorial.php' ?>
      <?php include __DIR__ . '/tools/subjects.php' ?>
      <?php include __DIR__ . '/tools/subject-edit-modal.php' ?>
      <?php include __DIR__ . '/tools/classes.php' ?>
      <?php include __DIR__ . '/tools/class-edit-modal.php' ?>
      <?php include __DIR__ . '/tools/report.php' ?>
    </main>
  </div>
</div>

<?php include __DIR__ . '/tools/modals.php' ?>

<?php include __DIR__ . '/../scripts/js/tools.php' ?>
