<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require_once 'vendor/autoload.php';

include_once 'includes/functions.php';
require_once 'classes/Database.php';

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

if (Database::getInstance()->isUserLoggedIn()) {
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

if (isset($_POST['login'])) {
  // SEC-FIX: Verifikacija CSRF tokena
  csrf_protect();

  $email = clean($_POST['email']);
  $password = clean($_POST['password']);

  $errors = array();

  if (empty($email)) {
    $errors['email'] = '<div class="mb-0 invalid-feedback">Molimo Vas unesite email.</div>';
  } else {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = '<div class="mb-0 invalid-feedback">Pogrešan email format.</div>';
    }
  }

  if (empty($password)) {
    $errors['password'] = '<div class="invalid-feedback">Molimo Vas unesite šifru.</div>';
  }

  if (count($errors) == 0) {

    if (!Database::getInstance()->takenEmail($email)) {
      $errors['wrong_combination'] = '<div class="alert alert-danger alert-dismissible show mb-5" role="alert">
              <strong>Nemate nalog</strong>. Kliknite na "Novi korisnik"<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>';
    } else {
      $data = array(
        "email" => $email,
        "password" => $password,
        "color_depth" => clean($_POST['colorDepth']),
        "ram" => clean($_POST['deviceMemory']),
        "screen_resolution" => clean($_POST['screenResolution']),
        "cpu_cores" => clean($_POST['hardwareConcurrency']),
        "timezone" => clean($_POST['timezone']),
        "os" => clean($_POST['platform']),
        "fonts" => clean($_POST['fonts']),
        "cookies_enabled" => clean($_POST['cookiesEnabled']),
        "gpu" => clean($_POST['gpu']),
      );

      if (Database::getInstance()->loginUser($data)) {
        // SEC-FIX: Regenerisi session ID nakon uspesnog logina (sprecava session fixation)
        session_regenerate_id(true);

        $_SESSION['login_success_message'] = '<div class="alert alert-warning alert-dismissible show mb-5" role="alert">
                <strong>Prijava uspešna! <i class="fas fa-check"></i></strong> Dobrodošli ' . htmlspecialchars($_SESSION['user']->firstname) . " " . htmlspecialchars($_SESSION['user']->lastname) .
          '. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
              </div>';

        // Count unique devices for user that logged in

        $loginDetails = Database::getInstance()->getLoginInfoForUser($_SESSION['user']->id);
        $numberOfDifferentDevices = countUniqueLoginsForUser($loginDetails);

        if ($numberOfDifferentDevices == 3 && !$_SESSION['user']->warned) {
          // Salje warning i mail i djoletu mail
          $_SESSION['warning_modal'] = true;

          // SLANJE MAILA USERU =============================================================================================================================================

          // Instantiation and passing `true` enables exceptions
          $mail = new PHPMailer(true);

          try {
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'mail.tatamata.rs';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'admin@tatamata.rs';                     // SMTP username
            $mail->Password   = defined('SMTP_PASS') ? SMTP_PASS : '';  // SEC-FIX: iz env.php
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 25;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('admin@tatamata.rs', 'Admin Tatamata');
            // $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
            $mail->addAddress('dragoslav.gagi8@gmail.com');               // Name is optional
            $mail->addAddress($_SESSION['user']->email);               // Name is optional
            $mail->addReplyTo('admin@tatamata.rs', 'Upozorenje');

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = '[Upozorenje] ulogovali ste se sa vise od 2 uredjaja';
            $mail->Body    = "Zdravo,<br>";
            $mail->Body    .= "Primetili smo da ste prekršili dozvoljen broj uređaja sa kojim možete da pristupite Vašem profilu.<br><br>";
            $mail->Body    .= "Svakom korisniku je dozvoljen pristup sa <strong>najviše 2 uređaja</strong><br>";
            $mail->Body    .= "<br><em>Korisnik: " . $_SESSION['user']->firstname . " " . $_SESSION['user']->lastname . " je prekršio dozvoljen broj uređaja dana: " . $loginDetails[0]['date'] . "</em><br><br>";
            $mail->Body    .= "Ulogovali ste se sa više od 2 uređaja na Vašem nalogu.<br>";
            $mail->Body    .= "Ovim mailom želimo da Vas obavestimo da ukoliko dodate još jedan novi uređaj <strong>PRISTUP KUPLJENIM KURSEVIMA BIĆE VAM ONEMOGUĆEN</strong>.<br>";
            $mail->Body    .= "Svaki korisnički nalog je namenjen isključivo za jednu osobu i ne sme se deliti sa drugim ljudima, shodno uslovima korišćenja.<br><br>";
            $mail->Body    .= "<strong>Ukoliko ispoštujete ovo upozorenje i više ne dodajete nove uređaje, ne treba da brinete, imaćete pristup Vašim kursevima.</strong><br><br>";
            $mail->Body    .= "Hvala na razumevanju<br>Tatamata";

            if ($_SERVER['SERVER_NAME'] != "localhost") {
              $mail->send();
            }
            // echo 'Message has been sent';
          } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
          }
          // SLANJE MAILA USERU END =============================================================================================================================================

          // Instantiation and passing `true` enables exceptions
          $mail = new PHPMailer(true);

          try {
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'mail.tatamata.rs';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'admin@tatamata.rs';                     // SMTP username
            $mail->Password   = defined('SMTP_PASS') ? SMTP_PASS : '';  // SEC-FIX: iz env.php
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 25;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('admin@tatamata.rs', 'Admin Tatamata');
            // $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
            $mail->addAddress('info@tatamata.rs');               // Name is optional
            $mail->addAddress('dragoslav.gagi8@gmail.com');               // Name is optional

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = '[ADMIN] Korisnik je dobio WARNING';
            $mail->Body    = "Ovaj korisnik se prijavio sa više od 2 uređaja.<br>";
            $mail->Body    .= "Podaci o korisniku:<br><br>";
            $mail->Body    .= "Ime i prezime: " . $_SESSION['user']->firstname . " " . $_SESSION['user']->lastname . "<br>";
            $mail->Body    .= "Email: " . $_SESSION['user']->email . "<br>";
            $mail->Body    .= "Broj telefona: " . $_SESSION['user']->phone_number . "<br><br>";
            $mail->Body    .= "Poslednji login: " . $loginDetails[0]['date'];

            if ($_SERVER['SERVER_NAME'] != "localhost") {
              $mail->send();
            }
            // echo 'Message has been sent';
          } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
          }
        } else if ($numberOfDifferentDevices > 3 && !$_SESSION['user']->blocked) {
          Database::getInstance()->blockUser($_SESSION['user']->id);
          $_SESSION['user']->blocked = 1;

          // SLANJE MAILA USERU =============================================================================================================================================

          // Instantiation and passing `true` enables exceptions
          $mail = new PHPMailer(true);

          try {
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'mail.tatamata.rs';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'admin@tatamata.rs';                     // SMTP username
            $mail->Password   = defined('SMTP_PASS') ? SMTP_PASS : '';  // SEC-FIX: iz env.php
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 25;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('admin@tatamata.rs', 'Admin Tatamata');
            // $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
            $mail->addAddress('dragoslav.gagi8@gmail.com');               // Name is optional
            $mail->addAddress($_SESSION['user']->email);               // Name is optional
            $mail->addReplyTo('admin@tatamata.rs', 'Upozorenje');

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = '[ZABRANA] vas nalog je blokiran';
            $mail->Body    = "Zdravo,<br>";
            $mail->Body    .= "Primetili smo da ste prekršili dozvoljen broj uređaja sa kojim možete da pristupite Vašem profilu.<br><br>";
            $mail->Body    .= "Svakom korisniku je dozvoljen pristup sa <strong>najviše 2 uređaja</strong><br>";
            $mail->Body    .= "<br><em>Korisnik: " . $_SESSION['user']->firstname . " " . $_SESSION['user']->lastname . " je prekršio dozvoljen broj uređaja dana: " . $loginDetails[0]['date'] . "</em><br><br>";
            $mail->Body    .= "Ulogovali ste se sa više od 2 uređaja na Vašem nalogu.<br><br>";
            $mail->Body    .= "<strong>Uprkos prethodnom upozorenju koje ste dobili, nastavili ste da delite Vaš nalog sa drugim osobama, što je <span style='color: red;'>strogo zabranjeno</span> i krši uslove korišćenja sajta tatamata.rs</strong><br>";
            $mail->Body    .= "Iz tog razloga biće Vam onemogućen pristup kursevima koje ste kupili.<br>";
            $mail->Body    .= "Za sva pitanja i informacije javite se putem <a href='https://tatamata.rs/pocetna#kontakt'>kontakt forme</a>.<br>";
            $mail->Body    .= "Hvala na razumevanju<br>Tatamata";

            if ($_SERVER['SERVER_NAME'] != "localhost") {
              $mail->send();
            }
            // echo 'Message has been sent';
          } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
          }

          // SLANJE MAILA USERU END =============================================================================================================================================

          // Instantiation and passing `true` enables exceptions
          $mail = new PHPMailer(true);

          try {
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'mail.tatamata.rs';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'admin@tatamata.rs';                     // SMTP username
            $mail->Password   = defined('SMTP_PASS') ? SMTP_PASS : '';  // SEC-FIX: iz env.php
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 25;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('admin@tatamata.rs', 'Admin Tatamata');
            // $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
            $mail->addAddress('tatamata.casovi@gmail.com');               // Name is optional
            $mail->addAddress('dragoslav.gagi8@gmail.com');               // Name is optional

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = '[ADMIN] Nalog se deli';
            $mail->Body    = "Ovaj korisnik je blokiran.<br>";
            $mail->Body    .= "Podaci o korisniku:<br><br>";
            $mail->Body    .= "Ime i prezime: " . $_SESSION['user']->firstname . " " . $_SESSION['user']->lastname . "<br>";
            $mail->Body    .= "Email: " . $_SESSION['user']->email . "<br>";
            $mail->Body    .= "Broj telefona: " . $_SESSION['user']->phone_number . "<br><br>";
            $mail->Body    .= "Poslednji login: " . $loginDetails[0]['date'];

            if ($_SERVER['SERVER_NAME'] != "localhost") {
              $mail->send();
            }
            // echo 'Message has been sent';
          } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
          }
        }

        // SEC-FIX: Validacija redirect URL-a - sprecava open redirect
        header("Location: " . safe_redirect(clean($_POST['redirect'])));

        exit;
      } else {
        $errors['wrong_combination'] = '<div class="alert alert-danger alert-dismissible show" role="alert">
        <strong>Greška!</strong> Pogrešna šifra.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>';
      }
    }
  }
}
?>

<?php include 'includes/header.php'; ?>




<!-- -------- PRIJAVA ---------- -->
<section id="prijave" class="login-form">

  <div class="auth-panel-desno">
    <div style="max-width:460px; width:100%;">

      <?php printFormatedFlashMessage("reset_password_success_message"); ?>
      <?php printFormatedFlashMessage("logout_success_message"); ?>
      <?php echo $errors['wrong_combination'] ?? ""; ?>

      <div class="login-form-container">
        <h1><i class="fas fa-lock me-2" style="color:var(--plava);"></i> Prijava</h1>
        <p class="auth-subtitle">Dobrodošao nazad! Prijavi se i nastavi sa učenjem.</p>
        <form id="login-form" method="POST">
          <div class="mb-4">
            <label class="form-label">Email <strong class="text-danger">*</strong></label>
            <div class="input-ikona">
              <i class="far fa-envelope"></i>
              <input id="email" name="email" type="email" class="form-control <?php if (isset($errors['email'])) echo 'is-invalid';
                                                                              else if (isset($email)) echo 'is-valid'; ?>" placeholder="Unesite email" value="<?php echo $email ?? ""; ?>">
            </div>
            <?php echo $errors['email'] ?? ""; ?>
          </div>
          <div class="mb-3">
            <label class="form-label">Šifra <strong class="text-danger">*</strong></label>
            <div class="input-ikona">
              <i class="fas fa-lock"></i>
              <input id="password" name="password" type="password" class="form-control <?php if (isset($errors['password'])) echo 'is-invalid'; ?>" placeholder="Unesite šifru">
            </div>
            <?php echo $errors['password'] ?? ""; ?>
            <div class="text-end mt-2">
              <a href="<?php echo BASE_URL . "zaboravljena-lozinka"; ?>" class="zab-sifru text-decoration-none">Zaboravili ste šifru?</a>
            </div>
          </div>

          <div class="d-grid mt-4">
            <button name="login" id="login-btn" type="submit" class="btn btn-primary btn-lg">
              Prijavi se <i class="fas fa-arrow-right ms-2"></i>
            </button>
          </div>

          <div class="line-container d-flex justify-content-between">
            <div class="line"></div>
            <div class="ili mx-1">ili</div>
            <div class="line"></div>
          </div>

          <div class="not-registered-container d-flex justify-content-between align-items-center">
            <p class="mb-0 d-inline-block niste-reg">Nemaš nalog?</p>
            <a href="<?php echo BASE_URL . 'registracija'; ?>">
              <button type="button" role="button" class="btn btn-outline-secondary">
                <i class="fas fa-user-plus me-1"></i> Kreiraj nalog
              </button>
            </a>
          </div>

          <input type="hidden" name="redirect" value="<?php echo BASE_URL . $redirectTo; ?>">
          <?php echo csrf_field(); // SEC-FIX: CSRF zaštita ?>

        </form>
      </div>

      <div class="mt-3 text-center" style="font-size:.82rem; color:var(--siva-500);">
        <i class="fas fa-info-circle me-1" style="color:#f59e0b;"></i>
        <em>Jedan nalog koristi samo jedna osoba sa najviše 2 uređaja.</em>
      </div>

      <div class="mt-4 text-center go-back">
        <a href="<?php echo BASE_URL . $redirectTo; ?>">
          <i class="fas fa-arrow-left"></i> Nazad na sajt
        </a>
      </div>

    </div>
  </div>
</section>
<!-- -------- PRIJAVA ---------- -->

<?php include 'includes/footer.php'; ?>