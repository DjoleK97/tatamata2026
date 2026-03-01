<?php require_once "./classes/Database.php"; ?>
<?php require_once "./includes/config.php"; ?>
<?php require_once "./includes/functions.php"; ?>
<?php

if (isset($_POST['razumem'])) {
  Database::getInstance()->warnUser($_SESSION['user']->id);
  unset($_SESSION['warning_modal']);

  exit;
}

if (isset($_POST['email'])) {
  if (Database::getInstance()->takenEmail($_POST['email'])) {
    echo 1;

    exit;
  }

  echo 0;

  exit;
}

if (isset($_POST['pretragaKursevi'])) {
  echo json_encode(Database::getInstance()->searchCourses(clean($_POST['searchText'])));

  exit;
}


if (isset($_POST['pretragaKlipovi'])) {
  echo json_encode(Database::getInstance()->searchClips(clean($_POST['searchText']), clean($_POST['courseID'])));

  exit;
}

