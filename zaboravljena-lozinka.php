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
  csrf_protect(); // SEC-FIX: CSRF zaštita
  $email = clean($_POST['email']);

  $errors = array();

  if (empty($email)) {
    $errors['email'] = '<div class="mb-0 invalid-feedback">Molimo Vas unesite email.</div>';
  } else {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = '<div class="mb-0 invalid-feedback">Pogrešan email format.</div>';
    } else if (!Database::getInstance()->takenEmail($email)) {
      $errors['not_taken_email'] = '<div class="alert alert-danger alert-dismissible show" role="alert">
      <strong>Greška!</strong> Email ne postoji.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>';
    }
  }

  if (count($errors) == 0) {
    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);

    $url = BASE_URL . "kreiraj-novu-sifru?selector=$selector&validator=" . bin2hex($token);
    $expires = date("U") + 900;

    $data = array(
      "email" => $email,
      "selector" => $selector,
      "token" => $token,
      "expires" => $expires,
    );

    if (Database::getInstance()->deletePasswordReset($email)) {
      if (Database::getInstance()->insertPasswordReset($data)) {
        $to = $email;
        $subject = "Promena lozinke na tatamata.rs";
        $message = "<p>Poslali ste zahtev za promenu lozinke. Link za promenu lozinke se nalazi ispod. Ukoliko niste vi poslali zahtev ignorišite ovu poruku.</p>";
        $message .= "<p>Link za promenu lozinke: <br>";
        $message .= "<a href=\"$url\">$url</a></p>";

        $headers = "From: tatamata <tatamata@tatamata.rs>\r\n";
        $headers .= "Reply-To: tatamata@tatamata.rs\r\n";
        $headers .= "Content-type: text/html\r\n";

        mail($to, $subject, $message, $headers);

        $_SESSION['password_reset_success_message'] = '
            <div class="alert alert-warning alert-dismissible show" role="alert">
              <strong>Email uspešno poslat!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>';
      }
    }
  }
}

?>

<?php include 'includes/header.php'; ?>

<?php printFormatedFlashMessage("not_taken_email"); ?>

<!-- -------- ZABORAVLJENA LOZINKA ---------- -->
<section id="prijave" class="login-form">
  <div class="row g-0" style="min-height:100vh;">

    <!-- Levi panel (branding) -->
    <div class="col-lg-5 d-none d-lg-flex auth-panel-levo">
      <img src="<?php echo BASE_URL; ?>public/images/LOGO_VEKTOR.svg" alt="TataMata" class="auth-logo">
      <i class="fas fa-key mb-4" style="font-size:3rem; color:var(--zuta); opacity:.6; position:relative; z-index:1;"></i>
      <h2>Resetuj svoju<br><span class="highlight">lozinku</span></h2>
    </div>

    <!-- Desni panel (forma) -->
    <div class="col-lg-7 auth-panel-desno">
      <div style="max-width:460px; width:100%;">

        <?php printFormatedFlashMessage("password_reset_success_message"); ?>

        <div class="login-form-container">
          <h1 class="mb-2"><i class="fas fa-key me-2" style="color:var(--plava);"></i> Zaboravljena sifra?</h1>
          <p class="auth-subtitle">Poslacemo ti email sa uputstvom za promenu sifre.</p>

          <?php echo $errors['not_taken_email'] ?? ""; ?>

          <form method="POST" class="mb-3">
            <?php echo csrf_field(); // SEC-FIX: CSRF zaštita ?>

            <div class="mb-4">
              <label class="form-label">Email <strong class="text-danger">*</strong></label>
              <div class="input-ikona">
                <i class="far fa-envelope"></i>
                <input name="email" type="email" class="form-control <?php if (isset($errors['email']) || isset($errors['not_taken_email'])) echo 'is-invalid';
                                                                      else if (isset($email)) echo 'is-valid'; ?>" placeholder="Unesite email" value="<?php echo $email ?? ""; ?>">
              </div>
              <?php echo $errors['email'] ?? ""; ?>
            </div>

            <button name="reset-password" type="submit" class="confirm-btn btn btn-primary d-block w-100">
              <i class="fas fa-paper-plane me-2"></i> Posalji uputstvo
            </button>

          </form>
        </div>

        <div class="mt-4 text-center go-back">
          <a href="<?php echo BASE_URL; ?>prijava">
            <i class="fas fa-arrow-left"></i> Nazad na prijavu
          </a>
        </div>

      </div>
    </div>

  </div>
</section>

<?php include 'includes/footer.php'; ?>
