<?php

require_once 'classes/Database.php';

if (!Database::getInstance()->isUserLoggedIn()) {
  $_SESSION['unauthorized_access'] = '<div class="container-fluid">
  <div class="row">
    <div class="col-md-10 col-sm-10 offset-sm-1 offset-md-1 p-0 mt-5">
      <div class="alert alert-danger alert-dismissible show" role="alert">
        <strong>Greška!</strong> Zabranjen pristup! <i class="fas fa-exclamation-triangle"></i>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>
    </div>
  </div>
</div>';
  header("Location: pocetna");

  exit;
}

$leavingUN = $_SESSION['user']->firstname . " " . $_SESSION['user']->lastname;

Database::getInstance()->logoutUser();
$_SESSION['logout_success_message'] = '<div class="alert alert-warning alert-dismissible show" role="alert">
        <strong>Odjava uspešna! <i class="fas fa-check"></i></strong> Vidimo se opet uskoro ' . $leavingUN .
      '. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>';
header("Location: prijava");

exit;
