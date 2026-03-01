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
  $oldPassword = clean($_POST['old_password']);
  $password = clean($_POST['password']);
  $password2 = clean($_POST['password2']);

  $errors = [];

  if (empty($oldPassword)) {
    $errors['old_password'] = '<div class="mb-0 invalid-feedback">Molimo Vas unesite staru šifru.</div>';
  } else if (Database::getInstance()->getPasswordForUser($_SESSION['user']->id) != md5(md5($oldPassword))) {
    $errors['old_password'] = '<div class="mb-0 invalid-feedback">Stara šifra nije tačna.</div>';
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
  <div class="row">
    <div class="col-md-8 offset-md-2 profile-div">

      <?php printFormatedFlashMessage("change_password_failed_message"); ?>
      <?php printFormatedFlashMessage("change_password_success_message"); ?>
      <?php printFormatedFlashMessage("change_profile_success_message"); ?>

      <ul class="list-group list-group-flush">
        <li class="mt-2 list-group-item"><span class="profile-span">Ime: </span><?php echo $_SESSION['user']->firstname; ?></li>
        <li class="list-group-item"><span class="profile-span">Prezime: </span><?php echo $_SESSION['user']->lastname; ?></li>
        <li class="list-group-item"><span class="profile-span">Broj telefona: </span><?php echo $_SESSION['user']->phone_number; ?></li>
        <li class="list-group-item"><span class="profile-span">Email: </span><?php echo $_SESSION['user']->email; ?></li>
        <li class="list-group-item"><span class="profile-span">Nivo obrazovanja: </span>
          <form method="POST">
            <select name="school_type_id" class="form-select" aria-label="Default select example">
              <?php foreach ($schoolTypes as $schoolType) : ?>
                <option value="<?php echo $schoolType['school_type_id'] ?? "1"; ?>" <?php if ($schoolType['school_type_id'] == $_SESSION['user']->school_type_id) echo 'selected'; ?>><?php echo $schoolType['school_type_name'] ?? "Error"; ?></option>
              <?php endforeach; ?>
              <option value="NULL" <?php if ($_SESSION['user']->school_type_id == "NULL") echo 'selected'; ?>>Ne želim da se izjasnim</option>
            </select>
            <button name='update-profile' class="btn btn-primary zutob2" style="margin-top: .5rem; margin-bottom: .5rem;" type="submit">
              Promeni nivo obrazovanja
            </button>
          </form>
        </li>
        <li class="list-group-item">
          <p class="mb-0">
            <button class="btn btn-primary zutob2" style="margin-top: .5rem; margin-bottom: .5rem;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
              Promeni šifru
            </button>
          </p>
          <div class="mt-2 collapse <?php if ($displayCollapse) {
                                      echo 'show';
                                      $displayCollapse = false;
                                    } ?>" id="collapseExample">
            <div class="card card-body">
              <form method="POST" class="mb-2">

                <div class="mb-3">
                  <label class="form-label">Stara šifra <strong class="text-danger">*</strong></label>
                  <input name="old_password" type="password" class="form-control <?php if (isset($errors['old_password'])) echo 'is-invalid'; ?>" placeholder="Unesite staru šifru">
                  <?php echo $errors['old_password'] ?? ""; ?>
                </div>

                <div class="mb-3">
                  <label class="form-label">Nova šifra <strong class="text-danger">*</strong></label>
                  <input name="password" type="password" class="form-control <?php if (isset($errors['password']) || isset($errors['password_confirm'])) echo 'is-invalid'; ?>" placeholder="Unesite novu šifru">
                  <?php echo $errors['password'] ?? ""; ?>
                  <?php echo $errors['password_confirm'] ?? ""; ?>
                </div>

                <div class="mb-3">
                  <label class="form-label">Potvrda nove šifre <strong class="text-danger">*</strong></label>
                  <input name="password2" type="password" class="form-control <?php if (isset($errors['password2'])) echo 'is-invalid'; ?>" placeholder="Potvrdite novu šifru">
                  <?php echo $errors['password2'] ?? ""; ?>
                </div>

                <button name="change-password" type="submit" class="zutob2 btn btn-primary d-block w-100 mt-4">Potvrdi</button>

              </form>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</div>

<?php include_once 'includes/footer.php'; ?>