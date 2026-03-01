<?php require_once "../classes/Database.php"; ?>
<?php require_once "../includes/config.php"; ?>
<?php

$loggedIn = Database::getInstance()->isUserLoggedIn();

if (!$loggedIn || ($loggedIn && !$_SESSION['user']->is_admin)) {
  $_SESSION['unauthorized_access'] = '<div class="container-fluid">
  <div class="row">
    <div class="col-md-10 col-sm-10 offset-sm-1 offset-md-1 p-0 mt-5">
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Greška!</strong> Zabranjen pristup! <i class="fas fa-exclamation-triangle"></i>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  </div>
</div>';
  header("Location: " . BASE_URL . "pocetna");

  exit;
}

if (isset($_GET['course_id'])) {
  $chapters = Database::getInstance()->getAllChaptersForCourse($_GET['course_id']);

  echo json_encode($chapters);
}

if (isset($_GET['school_type_id'])) {
  $grades = Database::getInstance()->getAllGradesForSchoolType($_GET['school_type_id']);

  echo json_encode($grades);
}
