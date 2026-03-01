<?php

include_once 'includes/functions.php';
include_once 'includes/config.php';
require_once 'classes/Database.php';

if (Database::getInstance()->isUserLoggedIn()) {
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
  header("Location: pocetna");

  exit;
}

if (isset($_POST['reset-password'])) {
  $selectorP = $_POST['selector'];
  $validatorP = $_POST['validator'];
  $password = clean($_POST['password']);
  $password2 = clean($_POST['password2']);

  $errors = [];

  if (empty($password)) {
    $errors['password'] = '<div class="mb-0 invalid-feedback">Molimo Vas unesite šifru.</div>';
  } else {
    if ($password != $password2) {
      $errors['password_confirm'] = '<div class="mb-0 invalid-feedback">Šifre se ne poklapaju.</div>';
    } else {
      if (empty($password2)) {
        $errors['password2'] = '<div class="mb-0 invalid-feedback">Molimo potvrdite šifru.</div>';
      }
    }
  }

  if (count($errors) == 0) {
    $currentDate = date("U");
    $password_reset = Database::getInstance()->getPasswordReset($selectorP, $currentDate);

    $tokenBin = hex2bin($validatorP);
    $tokenCheck = password_verify($tokenBin, $password_reset['token']);

    if ($tokenCheck === false) {
      die("Greška prilikom restartovanja lozinke. 1");
    } else if ($tokenCheck === true) {
      $tokenEmail = $password_reset['email'];
      $user = Database::getInstance()->getUserByEmail($tokenEmail);
      if (Database::getInstance()->updateUserPassword($user['id'], $password)) {
        if (Database::getInstance()->deletePasswordReset($tokenEmail)) {
          $_SESSION['reset_password_success_message'] = '<div class="mt-5 alert alert-warning alert-dismissible show" role="alert">
                <strong>Promena lozinke uspešna!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
              </div>';

          header("Location: " . BASE_URL . "prijava");

          exit;
        }
      } else {
        die("Greška prilikom restartovanja lozinke. 2");
      }
    }
  }
}

if (!isset($_GET['selector']) || !isset($_GET['validator'])) {
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
  header("Location: pocetna");

  exit;
}

$selector = $_GET['selector'];
$validator = $_GET['validator'];

?>

<?php include 'includes/header.php'; ?>

<!-- -------- REGISTRACIJA ---------- -->
<section id="prijave" class="login-form">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 col-sm-10 col-10">


        <?php echo $errors['taken_email'] ?? "";

        if (empty($selector) || empty($validator)) {
        } else {
          if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) { ?>


            <div class="login-form-container">

              <h1 class="mb-0">Nova šifra</h1>
              <p class="kreiraj-p">Kreigrajte novu šifru za prijavu na Vaš nalog.</p>

              <form method="POST" class="mb-3">

                <div class="mb-3">
                  <label class="form-label">Šifra <strong class="text-danger">*</strong></label>
                  <input name="password" type="password" class="form-control <?php if (isset($errors['password']) || isset($errors['password_confirm'])) echo 'is-invalid'; ?>" placeholder="Unesite šifru">
                  <?php echo $errors['password'] ?? ""; ?>
                  <?php echo $errors['password_confirm'] ?? ""; ?>
                </div>

                <div class="mb-3">
                  <label class="form-label">Ponovite šifru <strong class="text-danger">*</strong></label>
                  <input name="password2" type="password" class="form-control <?php if (isset($errors['password2'])) echo 'is-invalid'; ?>" placeholder="Potvrdite šifru">
                  <?php echo $errors['password2'] ?? ""; ?>
                </div>

                <input type="hidden" name="selector" value="<?php echo $selector; ?>">
                <input type="hidden" name="validator" value="<?php echo $validator; ?>">

                <button name="reset-password" type="submit" class="confirm-btn btn btn-primary d-block w-100 mt-4 scale-btn-2">Potvrdi</button>

              </form>
            </div>

            <div class="mt-4 text-white text-center go-back">
              <a href="<?php echo BASE_URL; ?>pocetna">
                <i class="fas fa-arrow-left"></i> Odustani
              </a>
            </div>
        <?php
          }
        }
        ?>


      </div>
    </div>
  </div>
</section>
<!-- -------- REGISTRACIJA ---------- -->

<?php include 'includes/footer.php'; ?>