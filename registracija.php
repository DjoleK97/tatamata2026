<?php
ob_start();
include_once 'includes/functions.php';
include_once 'includes/config.php';
require_once 'classes/Database.php';

if (Database::getInstance()->isUserLoggedIn()) {
  $_SESSION['unauthorized_access'] = '<div class="container-fluid">
  <div class="row">
    <div class="col-md-10 col-sm-10 offset-sm-1 offset-md-1 p-0 mt-5">
      <div class="alert alert-danger alert-dismissible show" role="alert">
        <strong>Greška!</strong> Zabranjen pristup! <i class="fas fa-exclamation-triangle"></i>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  </div>
</div>';
  header("Location: pocetna");

  exit;
}

$previous = $_SERVER['HTTP_REFERER'] ?? "-1";
$arrayP = explode("/", $previous);
$penultimate = $arrayP[sizeof($arrayP) - 2] ?? "-1";
$last = end($arrayP);
$redirectTo = 'pocetna';

if ($penultimate == "kurs") {
  $redirectTo = '/kurs/' . $last;
} else {
  switch ($last) {
    case "kursevi":
      $redirectTo = 'kursevi';
      break;
  }
}

if (isset($_POST['password'])) {
  // SEC-FIX: CSRF zaštita
  csrf_protect();

  $email = clean($_POST['email']);
  $password = clean($_POST['password']);
  $password2 = clean($_POST['password2']);
  $firstname = clean($_POST['firstname']);
  $lastname = clean($_POST['lastname']);
  $phone_number = clean($_POST['phone_number']);
  $country = clean($_POST['country']);
  $school = clean($_POST['school']);
  // $slazemSe = $_POST['slazem_se'] ?? 0;
  // $slazemSe2 = $_POST['slazem_se2'] ?? 0;


  $errors = array();

  // if (empty($email)) {
  //   $errors['email'] = '<div class="mb-0 invalid-feedback">Molimo Vas unesite email.</div>';
  // } else {
  //   if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  //     $errors['email'] = '<div class="mb-0 invalid-feedback">Pogrešan email format.</div>';
  //   }
  // }

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

  if (empty($firstname)) {
    $errors['firstname'] = '<div class="mb-0 invalid-feedback">Molimo unesite ime.</div>';
  } else {
    if (!isLettersOnly($firstname)) {
      $errors['firstname'] = '<div class="mb-0 invalid-feedback">Dozvoljeno je koristiti samo slova.</div>';
    }
  }

  if (empty($lastname)) {
    $errors['lastname'] = '<div class="mb-0 invalid-feedback">Molimo unesite prezime.</div>';
  } else {
    if (!isLettersOnly($lastname)) {
      $errors['lastname'] = '<div class="mb-0 invalid-feedback">Dozvoljeno je koristiti samo slova.</div>';
    }
  }

  if (strtolower($country) == "other" || strtolower($country) == 'cg') {
    $phone_number = null;
    // $grade = null;
  }

  // if ($slazemSe === 0) {
  //   $errors['slazem_se'] = '<div style="display: block;" class="mb-0 invalid-feedback">Niste prihvatili uslove korišćenja.</div>';
  // }

  // if ($slazemSe2 === 0) {
  //   $errors['slazem_se2'] = '<div style="display: block;" class="mb-0 invalid-feedback">Niste prihvatili uslove korišćenja.</div>';
  // }

  if (count($errors) == 0) {
    $data = array(
      "email" => $email,
      "password" => $password,
      "firstname" => $firstname,
      "lastname" => $lastname,
      "phone_number" => $phone_number,
      "country" => $country,
      "color_depth" => clean($_POST['colorDepth']),
      "ram" => clean($_POST['deviceMemory']),
      "screen_resolution" => clean($_POST['screenResolution']),
      "cpu_cores" => clean($_POST['hardwareConcurrency']),
      "timezone" => clean($_POST['timezone']),
      "os" => clean($_POST['platform']),
      "fonts" => clean($_POST['fonts']),
      "cookies_enabled" => clean($_POST['cookiesEnabled']),
      "gpu" => clean($_POST['gpu']),
      "school" => $school,
    );

    if (Database::getInstance()->takenEmail($email)) {
      $errors['taken_email'] = '<div class="alert alert-danger alert-dismissible show" role="alert">
      <strong>Greška!</strong> Email je zauzet.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>';
    } else if (Database::getInstance()->registerUser($data)) {
      $_SESSION['register_success_message'] = '
            <div class="alert alert-warning alert-dismissible show" role="alert" style="margin-bottom: 4rem;">
              <strong>Registracija uspešna! <i class="fas fa-check"></i></strong> Dobrodošli ' . $_SESSION['user']->firstname . " " . $_SESSION['user']->lastname .
        '. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>';

      header("Location: " . clean($_POST['redirect']));

      exit;
    }
  }
}
?>

<?php include 'includes/header.php'; ?>

<!-- -------- REGISTRACIJA ---------- -->
<section id="prijave" class="login-form">

  <div class="auth-panel-desno">
    <div style="max-width:520px; width:100%;">

      <div class="login-form-container">

        <h1 class="mb-1"><i class="fas fa-user-plus me-2" style="color:var(--plava);"></i> Kreiraj nalog</h1>
        <p class="auth-subtitle">Napravi nalog i pocni da ucis matematiku. Polja sa <strong class="text-danger">*</strong> su obavezna.</p>

        <?php echo $errors['taken_email'] ?? ""; ?>

        <form id="register-form" method="POST">

          <div class="mb-4">
            <label class="form-label">Ime <strong class="text-danger">*</strong></label>
            <input name="firstname" type="text" class="form-control <?php if (isset($errors['firstname'])) echo 'is-invalid';
                                                                    else if (isset($firstname)) echo 'is-valid'; ?>" placeholder="Unesite ime" value="<?php echo $firstname ?? ""; ?>">
            <?php echo $errors['firstname'] ?? ""; ?>
          </div>

          <div class="mb-4">
            <label class="form-label">Prezime <strong class="text-danger">*</strong></label>
            <input name="lastname" type="text" class="form-control <?php if (isset($errors['lastname'])) echo 'is-invalid';
                                                                    else if (isset($lastname)) echo 'is-valid'; ?>" placeholder="Unesite prezime" value="<?php echo $lastname ?? ""; ?>">
            <?php echo $errors['lastname'] ?? ""; ?>
          </div>

          <div class="mb-4">
            <label class="form-label">Email <strong class="text-danger">*</strong></label>
            <input name="email" type="email" class="form-control <?php if (isset($errors['email']) || isset($errors['taken_email'])) echo 'is-invalid';
                                                                  else if (isset($email)) echo 'is-valid'; ?>" placeholder="Unesite email" value="<?php echo $email ?? ""; ?>">
            <?php echo $errors['email'] ?? ""; ?>
          </div>

          <div class="mb-4">
            <label class="form-label">Šifra <strong class="text-danger">*</strong></label>
            <div class="input-ikona">
              <i class="fas fa-lock"></i>
              <input name="password" type="password" class="form-control <?php if (isset($errors['password']) || isset($errors['password_confirm'])) echo 'is-invalid'; ?>" placeholder="Unesite šifru">
            </div>
            <?php echo $errors['password'] ?? ""; ?>
            <?php echo $errors['password_confirm'] ?? ""; ?>
          </div>

          <div class="mb-4">
            <label class="form-label">Potvrda šifre <strong class="text-danger">*</strong></label>
            <div class="input-ikona">
              <i class="fas fa-lock"></i>
              <input name="password2" type="password" class="form-control <?php if (isset($errors['password2'])) echo 'is-invalid'; ?>" placeholder="Ponovo unesite šifru">
            </div>
            <?php echo $errors['password2'] ?? ""; ?>
          </div>

          <div class="mb-4">
            <label class="form-label">Pohađam <strong class="text-danger">*</strong></label>
            <select class="form-select" name="school">
              <option value="1" selected>Osnovnu školu</option>
              <option value="2">Srednju školu</option>
              <option value="3">Fakultet</option>
            </select>
          </div>

          <div class="mb-4">
            <label class="form-label">Zemlja <strong class="text-danger">*</strong></label>
            <select class="form-select" name="country">
              <option value="srbija" selected>Srbija</option>
              <option value="cg">Crna Gora</option>
              <option value="other">Preostale zemlje</option>
            </select>
          </div>

          <div class="mb-4">
            <label class="form-label">Broj telefona (opciono)</label>
            <input name="phone_number" type="tel" pattern="[0-9-+]*" class="form-control" placeholder="Unesite broj telefona" value="<?php echo $phone_number ?? ""; ?>">
          </div>

          <div class="mb-3 form-check">
            <input type="checkbox" name="slazem_se" class="form-check-input" id="exampleCheck1" <?php if (isset($slazemSe) && $slazemSe !== 0) echo 'checked'; ?>>
            <label class="form-check-label" for="exampleCheck1">Slažem se sa <a target="_blank" href="<?php echo BASE_URL; ?>uslovi-koriscenja">uslovima korišćenja</a> i <a target="_blank" href="<?php echo BASE_URL; ?>politika-privatnosti">politikom privatnosti</a></label>
            <?php echo $errors['slazem_se'] ?? ""; ?>
          </div>

          <div class="mb-4 form-check detemozecheck">
            <input type="checkbox" name="slazem_se2" class="form-check-input" id="exampleCheck2" <?php if (isset($slazemSe2) && $slazemSe2 !== 0) echo 'checked'; ?>>
            <label class="form-check-label" for="exampleCheck2">Saglasan sam da moje dete može da koristi platformu Tatamata za učenje</label>
            <?php echo $errors['slazem_se2'] ?? ""; ?>
          </div>

          <div class="d-grid mt-2">
            <button type="submit" class="btn btn-primary btn-lg">
              <i class="fas fa-user-plus me-2"></i> Kreiraj nalog
            </button>
          </div>

          <input type="hidden" name="redirect" value="<?php echo BASE_URL . $redirectTo; ?>">
          <?php echo csrf_field(); // SEC-FIX: CSRF zaštita ?>

          <div class="line-container d-flex justify-content-between">
            <div class="line"></div>
            <div class="ili mx-1">ili</div>
            <div class="line"></div>
          </div>

          <div class="not-registered-container d-flex justify-content-between align-items-center">
            <p class="mb-0 d-inline-block niste-reg">Već imaš nalog?</p>
            <a href="<?php echo BASE_URL . 'prijava'; ?>">
              <button type="button" class="btn btn-outline-secondary">
                <i class="fas fa-sign-in-alt me-1"></i> Prijavi se
              </button>
            </a>
          </div>

        </form>
      </div>

      <div class="mt-3 text-center" style="font-size:.82rem; color:var(--siva-700);">
        <i class="fas fa-info-circle me-1" style="color:#f59e0b;"></i>
        <em>Jedan nalog koristi samo jedna osoba sa najviše 2 uređaja.</em>
      </div>

      <div class="mt-4 text-center go-back">
        <a href="<?php echo BASE_URL . $redirectTo; ?>">
          <i class="fas fa-arrow-left"></i> Odustani
        </a>
      </div>

    </div>
  </div>

</section>
<!-- -------- REGISTRACIJA ---------- -->

<?php include 'includes/footer.php'; ?>