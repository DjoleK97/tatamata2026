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
<section id="prijave" class="registracija mt-4 mb-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 col-sm-10 col-10 g-0">

        <ul id="progressbar" class="text-center mt-5 p-0">
          <li class="active register-progress-item" id="account"><strong>Osnovno</strong></li>
          <li class="register-progress-item" id="personal"><strong>Nalog</strong></li>
          <li class="register-progress-item" id="confirm"><strong>Kraj</strong></li>
        </ul> <!-- fieldsets -->

        <div class="login-form-container">

          <h1 class="mb-1">Novi Korisnik</h1>
          <p class="mb-4">Polja označena <strong class="text-danger">*</strong> su obavezna.</p>

          <?php echo $errors['taken_email'] ?? ""; ?>

          <form id="register-form" method="POST">

            <div id="ime-prezime">

              <div class="mb-4">
                <label class="form-label">Ime <strong class="text-danger">*</strong></label>
                <input name="firstname" type="text" class="form-control <?php if (isset($errors['firstname'])) echo 'is-invalid';
                                                                        else if (isset($firstname)) echo 'is-valid'; ?>" placeholder="Unesite ime" value="<?php echo $firstname ?? ""; ?>">
                <?php echo $errors['firstname'] ?? ""; ?>
                <div class="mb-0 invalid-feedback ifime">Molimo unesite ime.</div>
                <div class="mb-0 invalid-feedback ifime2">Dozvoljeno je koristiti samo slova.</div>
              </div>

              <div class="mb-4">
                <label class="form-label">Prezime <strong class="text-danger">*</strong></label>
                <input name="lastname" type="text" class="form-control <?php if (isset($errors['lastname'])) echo 'is-invalid';
                                                                        else if (isset($lastname)) echo 'is-valid'; ?>" placeholder="Unesite prezime" value="<?php echo $lastname ?? ""; ?>">
                <?php echo $errors['lastname'] ?? ""; ?>
                <div class="mb-0 invalid-feedback ifprezime">Molimo unesite prezime.</div>
                <div class="mb-0 invalid-feedback ifprezime2">Dozvoljeno je koristiti samo slova.</div>
              </div>

              <div class="mb-4">
                <label class="form-label">Pohađam <strong class="text-danger">*</strong></label>
                <select class="form-select" name="school" aria-label="Default select example">
                  <option value="1" selected>Osnovnu školu</option>
                  <option value="2">Srednju školu</option>
                  <option value="3">Fakultet</option>
                </select>
              </div>

            </div>

            <div id="email-sifra">

              <div class="mb-4">
                <label class="form-label">Email <strong class="text-danger">*</strong></label>
                <input name="email" type="email" class="form-control <?php if (isset($errors['email']) || isset($errors['taken_email'])) echo 'is-invalid';
                                                                      else if (isset($email)) echo 'is-valid'; ?>" placeholder="Unesite email" value="<?php echo $email ?? ""; ?>">
                <?php echo $errors['email'] ?? ""; ?>
                <div class="mb-0 invalid-feedback ifemail">Pogrešan email format.</div>
                <div class="mb-0 invalid-feedback ifemail2">Email je zauzet.</div>
              </div>

              <div class="mb-4">
                <label class="form-label">Šifra <strong class="text-danger">*</strong></label>
                <input name="password" type="password" class="form-control <?php if (isset($errors['password']) || isset($errors['password_confirm'])) echo 'is-invalid'; ?>" placeholder="Unesite šifru">
                <?php echo $errors['password'] ?? ""; ?>
                <?php echo $errors['password_confirm'] ?? ""; ?>
                <div class="mb-0 invalid-feedback ifpassword">Molimo Vas unesite šifru.</div>
                <div class="mb-0 invalid-feedback ifpassword2">Šifre se ne poklapaju.</div>
              </div>

              <div class="mb-4">
                <label class="form-label">Potvrda šifre <strong class="text-danger">*</strong></label>
                <input name="password2" type="password" class="form-control <?php if (isset($errors['password2'])) echo 'is-invalid'; ?>" placeholder="Ponovo unesite šifru">
                <?php echo $errors['password2'] ?? ""; ?>
              </div>

            </div>

            <div id="zemlja-razred-telefon">

              <div class="mb-4">
                <label class="form-label">Zemlja <strong class="text-danger">*</strong></label>
                <select class="form-select" name="country" aria-label="Default select example">
                  <option value="srbija" selected>Srbija</option>
                  <option value="cg">Crna Gora</option>
                  <option value="other">Preostale zemlje</option>
                </select>
              </div>

              <!-- <div id="grade-div" class="mb-4">
                <label class="form-label">Koji si razred? <strong class="text-danger">*</strong></label>
                <select class="form-select" name="grade" aria-label="Default select example">
                  <option value="Osmi" selected>Osmi</option>
                  <option value="Sedmi">Sedmi</option>
                  <option value="Sesti">Šesti</option>
                  <option value="Peti">Peti</option>
                  <option value="Nizi">Niži razredi osnovne škole</option>
                  <option value="Srednja skola">Srednja škola</option>
                </select>
              </div> -->

              <div id="number-div" style="margin-bottom: 2rem;">
                <label class="form-label">Broj telefona (opciono)</label>
                <input name="phone_number" type="tel" , pattern="[0-9-+]*" class="form-control" placeholder="Unesite broj telefona" value="<?php echo $phone_number ?? ""; ?>">
                <?php echo $errors['phone_number'] ?? ""; ?>
              </div>

              <div class="mb-3 form-check">
                <input type="checkbox" name="slazem_se" class="form-check-input" id="exampleCheck1" <?php if (isset($slazemSe) && $slazemSe !== 0) echo 'checked'; ?>>
                <label class="form-check-label" for="exampleCheck1">Slažem se sa <a target="_blank" href="<?php echo BASE_URL; ?>uslovi-koriscenja">uslovima korisćenja</a> i <a target="_blank" href="<?php echo BASE_URL; ?>politika-privatnosti">politikom privatnosti</a></label>
                <?php echo $errors['slazem_se'] ?? ""; ?>
                <div class="mb-0 invalid-feedback ifslazemse">Nisi prihvatio uslove korišćenja.</div>
              </div>

              <div class="mb-3 form-check detemozecheck">
                <input type="checkbox" name="slazem_se2" class="form-check-input" id="exampleCheck2" <?php if (isset($slazemSe2) && $slazemSe2 !== 0) echo 'checked'; ?>>
                <label class="form-check-label" for="exampleCheck2">Saglasan sam da moje dete može da koristi platformu Tatamata za učenje</label>
                <?php echo $errors['slazem_se2'] ?? ""; ?>
                <div class="mb-0 invalid-feedback ifslazemse2">Nisi prihvatio uslove korišćenja.</div>
              </div>

            </div>

            <div class="text-center">
              <button id="prevB" type="button" class="register-btn btn btn-primary scale-btn-2 rr me-1"><i class="fas fa-arrow-left"></i> Nazad</button>
              <button id="nextB" type="button" name="register" class="register-btn btn btn-primary scale-btn-2 rr ms-1">Dalje <i class="fas fa-arrow-right"></i></button>
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
              <a href="<?php echo BASE_URL . 'prijava'; ?>"><button type="button" class="btn btn-primary scale-btn-2">Prijavi se</button></a>
            </div>

          </form>
        </div>

		<!-- NAPOMENA ZA MAX BROJ UREDJAJA -->
			<div class="mt-3">
			<p class="row justify-content-center" style="color:red; text-align:center;">Napomena:<br><em> Jedan nalog sme da koristi samo jedna osoba sa najviše 2 različita uređaja</em></p>
			</div>
		<!-- NAPOMENA ZA MAX BROJ UREDJAJA -->

        <div class="mt-4 text-center go-back">
          <a href="<?php echo BASE_URL . $redirectTo; ?>">
            <i class="fas fa-arrow-left"></i> Odustani
          </a>
        </div>

      </div>
    </div>
  </div>
</section>
<!-- -------- REGISTRACIJA ---------- -->

<?php include 'includes/footer.php'; ?>