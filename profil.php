<?php

require_once "includes/functions.php";
require_once "classes/Database.php";

if (!Database::getInstance()->isUserLoggedIn()) {
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

$displayCollapse = false;

if (isset($_POST['update-profile'])) {
  csrf_protect(); // SEC-FIX: CSRF zaštita
  $schoolTypeIDG  = clean($_POST['school_type_id']);

  if (Database::getInstance()->updateUserProfile($schoolTypeIDG, $_SESSION['user']->id)) {
    $_SESSION['change_profile_success_message'] = '<div class="mt-3 alert alert-warning alert-dismissible show" role="alert">
    <strong>Promena profila uspešna! <i class="fas fa-check"></i></strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
  </div>';
    $_SESSION['user']->school_type_id = $schoolTypeIDG;
  }
}

if (isset($_POST['change-password'])) {
  csrf_protect(); // SEC-FIX: CSRF zaštita
  $oldPassword = clean($_POST['old_password']);
  $password = clean($_POST['password']);
  $password2 = clean($_POST['password2']);

  $errors = [];

  if (empty($oldPassword)) {
    $errors['old_password'] = '<div class="mb-0 invalid-feedback">Molimo Vas unesite staru šifru.</div>';
  } else {
    // SEC-FIX: password_verify() za bcrypt + md5 fallback za stare naloge
    $storedHash = Database::getInstance()->getPasswordForUser($_SESSION['user']->id);
    if (!password_verify($oldPassword, $storedHash) && $storedHash !== md5(md5($oldPassword))) {
      $errors['old_password'] = '<div class="mb-0 invalid-feedback">Stara šifra nije tačna.</div>';
    }
  }

  if (empty($password)) {
    $errors['password'] = '<div class="mb-0 invalid-feedback">Molimo Vas unesite novu šifru.</div>';
  } else {
    if ($password != $password2) {
      $errors['password_confirm'] = '<div class="mb-0 invalid-feedback">Šifre se ne poklapaju.</div>';
    } else {
      if (empty($password2)) {
        $errors['password2'] = '<div class="mb-0 invalid-feedback">Molimo potvrdite novu šifru.</div>';
      }
    }
  }

  if (count($errors) == 0) {
    if (Database::getInstance()->updateUserPassword($_SESSION['user']->id, $password)) {
      $_SESSION['change_password_success_message'] = '<div class="mt-3 alert alert-warning alert-dismissible show" role="alert">
              <strong>Promena šifre uspešna! <i class="fas fa-check"></i></strong>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>';
    }
  } else {
    $_SESSION['change_password_failed_message'] = '<div class="mt-3 mb-0 alert alert-danger alert-dismissible show" role="alert">
            <strong>Greška! </strong> Promena šifre nije uspela.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
          </div>';
    $displayCollapse = true;
  }
}

$schoolTypes = Database::getInstance()->getAllSchoolTypes();
?>


<?php include_once 'includes/header.php'; ?>

<div class="container" id="profile-page">
  <div class="row g-4">

    <!-- Sidebar navigacija -->
    <div class="col-lg-3">
      <div class="profil-sidebar-nav">
        <a href="<?php echo BASE_URL; ?>profil" class="active"><i class="far fa-user"></i> Profil</a>
        <a href="<?php echo BASE_URL; ?>mojikursevi"><i class="fas fa-play-circle"></i> Moji kursevi</a>
        <a href="<?php echo BASE_URL; ?>transakcije"><i class="fas fa-euro-sign"></i> Transakcije</a>
      </div>
    </div>

    <!-- Glavni sadrzaj -->
    <div class="col-lg-9">

      <!-- Profil header kartica -->
      <div class="profil-header-karta">
        <div class="profil-header-avatar"><i class="far fa-user"></i></div>
        <h2><?php echo htmlspecialchars($_SESSION['user']->firstname . ' ' . $_SESSION['user']->lastname); ?></h2>
        <p class="text-muted"><?php echo htmlspecialchars($_SESSION['user']->email); ?></p>
        <span class="bedz-aktivno"><i class="fas fa-check-circle"></i> Aktivan nalog</span>
      </div>

      <?php printFormatedFlashMessage("change_password_failed_message"); ?>
      <?php printFormatedFlashMessage("change_password_success_message"); ?>
      <?php printFormatedFlashMessage("change_profile_success_message"); ?>

      <!-- Licni podaci -->
      <div class="profile-div mb-4">
        <h3 style="font-size:1.1rem; font-weight:700; margin-bottom:20px;"><i class="fas fa-user me-2" style="color:var(--plava);"></i> Licni podaci</h3>
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><span class="profile-span">Ime: </span><?php echo htmlspecialchars($_SESSION['user']->firstname); ?></li>
          <li class="list-group-item"><span class="profile-span">Prezime: </span><?php echo htmlspecialchars($_SESSION['user']->lastname); ?></li>
          <li class="list-group-item"><span class="profile-span">Broj telefona: </span><?php echo htmlspecialchars($_SESSION['user']->phone_number); ?></li>
          <li class="list-group-item"><span class="profile-span">Email: </span><?php echo htmlspecialchars($_SESSION['user']->email); ?></li>
        </ul>
      </div>

      <!-- Nivo obrazovanja -->
      <div class="profile-div mb-4">
        <h3 style="font-size:1.1rem; font-weight:700; margin-bottom:20px;"><i class="fas fa-graduation-cap me-2" style="color:var(--plava);"></i> Nivo obrazovanja</h3>
        <form method="POST">
          <?php echo csrf_field(); // SEC-FIX: CSRF zaštita ?>
          <select name="school_type_id" class="form-select mb-3" aria-label="Nivo obrazovanja">
            <?php foreach ($schoolTypes as $schoolType) : ?>
              <option value="<?php echo $schoolType['school_type_id'] ?? "1"; ?>" <?php if ($schoolType['school_type_id'] == $_SESSION['user']->school_type_id) echo 'selected'; ?>><?php echo $schoolType['school_type_name'] ?? "Error"; ?></option>
            <?php endforeach; ?>
            <option value="NULL" <?php if ($_SESSION['user']->school_type_id == "NULL") echo 'selected'; ?>>Ne zelim da se izjasnim</option>
          </select>
          <button name='update-profile' class="btn btn-primary" type="submit">
            Sacuvaj izmenu
          </button>
        </form>
      </div>

      <!-- Bezbednost -->
      <div class="profile-div">
        <h3 style="font-size:1.1rem; font-weight:700; margin-bottom:20px;"><i class="fas fa-shield-alt me-2" style="color:var(--plava);"></i> Bezbednost</h3>
        <p class="mb-3">
          <button class="btn btn-outline-plava" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            <i class="fas fa-key me-1"></i> Promeni sifru
          </button>
        </p>
        <div class="collapse <?php if ($displayCollapse) {
                                echo 'show';
                                $displayCollapse = false;
                              } ?>" id="collapseExample">
          <form method="POST" class="mb-2" style="max-width:480px;">
            <?php echo csrf_field(); // SEC-FIX: CSRF zaštita ?>

            <div class="mb-3">
              <label class="form-label">Stara sifra <strong class="text-danger">*</strong></label>
              <input name="old_password" type="password" class="form-control <?php if (isset($errors['old_password'])) echo 'is-invalid'; ?>" placeholder="Unesite staru sifru">
              <?php echo $errors['old_password'] ?? ""; ?>
            </div>

            <div class="mb-3">
              <label class="form-label">Nova sifra <strong class="text-danger">*</strong></label>
              <input name="password" type="password" class="form-control <?php if (isset($errors['password']) || isset($errors['password_confirm'])) echo 'is-invalid'; ?>" placeholder="Unesite novu sifru">
              <?php echo $errors['password'] ?? ""; ?>
              <?php echo $errors['password_confirm'] ?? ""; ?>
            </div>

            <div class="mb-3">
              <label class="form-label">Potvrda nove sifre <strong class="text-danger">*</strong></label>
              <input name="password2" type="password" class="form-control <?php if (isset($errors['password2'])) echo 'is-invalid'; ?>" placeholder="Potvrdite novu sifru">
              <?php echo $errors['password2'] ?? ""; ?>
            </div>

            <button name="change-password" type="submit" class="btn btn-primary d-block w-100 mt-4">Potvrdi promenu</button>

          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<?php include_once 'includes/footer.php'; ?>