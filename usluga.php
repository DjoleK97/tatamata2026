<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

require_once "includes/functions.php";
require_once "includes/config.php";
require_once "classes/Database.php";

if (!isset($_GET['name'])) {
  header("Location: " . BASE_URL . "pocetna");

  exit;
}

$usluga = clean($_GET['name']) ?? "priprema-za-prijemni";
$uslugaNAME = "";
$uslugaVIDEO = "";
$uslugaDESC = "";

switch ($usluga) {
  case 'priprema-za-prijemni':
    $uslugaNAME = "Priprema za prijemni";
    $uslugaVIDEO = "djole1.mp4";
    $uslugaDESC = "Opis uslugice";
    break;
  case 'konsultacije':
    $uslugaNAME = "Konsultacije";
    $uslugaVIDEO = "djole1.mp4";
    $uslugaDESC = "Opis uslugice 2";
    break;
  case 'priprema-za-pismene-i-kontrolne':
    $uslugaNAME = "Priprema za pismene i kontrolne";
    $uslugaVIDEO = "djole1.mp4";
    $uslugaDESC = "Opis uslugice 3";
    break;
  case 'individualni-casovi':
    $uslugaNAME = "Individualni časovi";
    $uslugaVIDEO = "djole1.mp4";
    $uslugaDESC = "Opis uslugice 4";
    break;
  case 'grupni-casovi':
    $uslugaNAME = "Grupni časovi";
    $uslugaVIDEO = "djole1.mp4";
    $uslugaDESC = "Opis uslugice 5";
    break;
}


if (isset($_POST['usluga-contact'])) {
  $email = clean($_POST['email']);
  $phone = clean($_POST['phone']);
  // $skola = clean($_POST['skola']);
  $firstname = clean($_POST['firstname']);
  // $pol = clean($_POST['pol']);
  $pratimPreko = clean($_POST['pratim-preko']);

  $errors = array();

  if (empty($email)) {
    $errors['email'] = '<div class="mb-0 invalid-feedback">Molimo Vas unesite email.</div>';
  } else {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = '<div class="mb-0 invalid-feedback">Pogrešan email format.</div>';
    }
  }

  if (empty($firstname)) {
    $errors['firstname'] = '<div class="mb-0 invalid-feedback">Molimo unesite ime.</div>';
  } else {
    if (!isLettersAndSpacesOnly($firstname)) {
      $errors['firstname'] = '<div class="mb-0 invalid-feedback">Dozvoljeno je koristiti samo slova.</div>';
    }
  }

  // if (empty($skola)) {
  //   $errors['skola'] = '<div class="mb-0 invalid-feedback">Molimo unesite ime škole.</div>';
  // }

  if (count($errors) == 0) {
    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
      //Server settings
      // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
      $mail->isSMTP();                                            // Send using SMTP
      $mail->Host       = 'mail.tatamata.rs';                    // Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
      $mail->Username   = 'admin@tatamata.rs';                     // SMTP username
      $mail->Password   = defined('SMTP_PASS') ? SMTP_PASS : '';  // SEC-FIX: iz env.php
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
      $mail->Port       = 25;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

      //Recipients
      $mail->setFrom(clean($_POST['email']), clean($_POST['firstname']));
      // $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
      $mail->addAddress('dragoslav.gagi8@gmail.com');               // Name is optional
      $mail->addAddress('info@tatamata.rs');               // Name is optional
      $mail->addReplyTo(clean($_POST['email']), 'Information');

      // Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = "[TataMata] Usluge Kontakt forma";
      $mail->Body    = "Porukica treba text smisliti"; // Smisliti text
      if ($_SERVER['SERVER_NAME'] != "localhost") {
        $mail->send();
      }
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    $_SESSION['usluge_contact_success'] = '
            <div class="alert alert-warning alert-dismissible show" role="alert" style="margin-bottom: 4rem;">
              <strong>Prijava uspešna! <i class="fas fa-check"></i></strong>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>';
  } else {
    $_SESSION['usluge_contact_fail'] = '
    <div class="alert alert-danger alert-dismissible show" role="alert" style="margin-bottom: 4rem;">
      <strong>Niste pravilno popunili formu!</strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>';
  }
}

?>


<?php include_once 'includes/header.php'; ?>


<section id="kursevi" class="kurs">
  <div class="container-fluid px-4">

    <!-- Breadcrumb -->
    <div class="kurs-breadcrumb">
      <a href="<?php echo BASE_URL; ?>pocetna#usluge">Usluge</a>
      <span> / </span>
      <?php echo htmlspecialchars($uslugaNAME); ?>
    </div>

    <?php printFormatedFlashMessage("usluge_contact_fail"); ?>
    <?php printFormatedFlashMessage("usluge_contact_success"); ?>

    <div class="row mt-3">
      <!-- Leva kolona: Video + Opis -->
      <div class="col-lg-8 mb-4">

        <h1 class="mb-3 animiraj" style="font-size:1.6rem;"><?php echo $uslugaNAME ?></h1>

        <div class="animiraj">
          <video id="usluga-video" class="video" width="100%" height="auto" style="border-radius:var(--radius-lg);" controls disablepictureinpicture oncontextmenu="return false;" controlsList="nodownload">
            <source src="<?php echo BASE_URL . "videos/" . $uslugaVIDEO; ?>" type="video/mp4">
          </video>
        </div>

        <!-- Opis usluge -->
        <div class="profile-div mt-4 animiraj">
          <h4 style="font-size:1.05rem; font-weight:700; margin-bottom:12px;">
            <i class="fas fa-align-left me-2" style="color:var(--plava);"></i> Opis usluge
          </h4>
          <hr class="hr">
          <p style="color:var(--siva-700); line-height:1.85; font-size:.95rem;"><?php echo $uslugaDESC; ?></p>
        </div>

      </div>

      <!-- Desna kolona: Kontakt forma -->
      <div class="col-lg-4">

        <?php if ($usluga == 'priprema-za-prijemni') { ?>
          <div class="login-form-container animiraj">
            <h3 style="font-size:1.1rem; font-weight:800; margin-bottom:4px;">Prijava za probni cas</h3>
            <p class="auth-subtitle" style="margin-bottom:20px;">Besplatno. Polja sa <strong class="text-danger">*</strong> su obavezna.</p>

            <form method="POST">

              <?php if (Database::getInstance()->isUserLoggedIn()) { ?>
                <div class="mb-3">
                  <label class="form-label"><i class="far fa-user"></i> Ime i prezime</label>
                  <input id="contact-name" readonly name="firstname" type="text" class="form-control" value="<?php echo $_SESSION['user']->firstname . " " . $_SESSION['user']->lastname; ?>">
                </div>
                <div class="mb-3">
                  <label class="form-label"><i class="far fa-envelope"></i> Email</label>
                  <input id="contact-email" readonly name="email" type="email" class="form-control" value="<?php echo $_SESSION['user']->email ?? ""; ?>">
                </div>
              <?php } else { ?>
                <div class="mb-3">
                  <label class="form-label">Ime i prezime <strong class="text-danger">*</strong></label>
                  <input name="firstname" type="text" class="form-control <?php if (isset($errors['firstname'])) echo 'is-invalid';
                                                                          else if (isset($firstname)) echo 'is-valid'; ?>" placeholder="Unesi ime i prezime" value="<?php echo $firstname ?? ""; ?>">
                  <?php echo $errors['firstname'] ?? ""; ?>
                </div>
                <div class="mb-3">
                  <label class="form-label">Email <strong class="text-danger">*</strong></label>
                  <input name="email" type="email" class="form-control <?php if (isset($errors['email']) || isset($errors['taken_email'])) echo 'is-invalid';
                                                                        else if (isset($email)) echo 'is-valid'; ?>" placeholder="Unesi email" value="<?php echo $email ?? ""; ?>">
                  <?php echo $errors['email'] ?? ""; ?>
                </div>
              <?php } ?>

              <div class="mb-3">
                <label class="form-label">Pratim preko <strong class="text-danger">*</strong></label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" value="Racunara / Laptopa" name="pratim-preko" checked>
                  <label class="form-check-label" style="font-size:.9rem;">Racunar / Laptop</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" value="Telefona / Tableta" name="pratim-preko">
                  <label class="form-check-label" style="font-size:.9rem;">Telefon / Tablet</label>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Broj telefona</label>
                <p style="font-size:.78rem; color:var(--siva-700); margin-bottom:6px;">Opciono, za komunikaciju preko Viber-a / WhatsApp-a.</p>
                <?php if (Database::getInstance()->isUserLoggedIn()) { ?>
                  <input readonly name="phone" type="text" class="form-control" value="<?php echo $_SESSION['user']->phone_number ?? ""; ?>">
                <?php } else { ?>
                  <input name="phone" type="text" class="form-control <?php if (isset($errors['phone'])) echo 'is-invalid';
                                                                      else if (isset($phone)) echo 'is-valid'; ?>" placeholder="Unesi broj telefona" value="<?php echo $phone ?? ""; ?>">
                  <?php echo $errors['phone'] ?? ""; ?>
                <?php } ?>
              </div>

              <button name="usluga-contact" type="submit" class="btn btn-primary w-100">
                <i class="fas fa-paper-plane me-2"></i> Posalji prijavu
              </button>
            </form>
          </div>
        <?php } ?>

        <!-- Info kartica -->
        <div class="kontakt-info-karta mt-4 animiraj">
          <h3>Imas pitanja?</h3>
          <div class="kontakt-info-stavka">
            <i class="fas fa-envelope"></i>
            <span>info@tatamata.rs</span>
          </div>
          <div class="kontakt-info-stavka">
            <i class="fas fa-clock"></i>
            <span>Odgovaramo u roku od 24h</span>
          </div>
          <p style="font-size:.85rem; color:var(--siva-700); margin-top:16px;">
            Ili nam se javi putem <a href="<?php echo BASE_URL ?>pocetna#kontakt" class="kontakt-forme-link">kontakt forme</a>.
          </p>
        </div>

        <div class="mt-4 text-center go-back">
          <a href="<?php echo BASE_URL; ?>pocetna#usluge">
            <i class="fas fa-arrow-left"></i> Nazad na usluge
          </a>
        </div>

      </div>
    </div>

  </div>
</section>


<?php include_once 'includes/plyr_footer.php'; ?>
<script>
  new Plyr('#usluga-video', {
    controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'captions', 'settings', 'airplay', 'fullscreen'],
  });
</script>
<?php include_once 'includes/footer.php'; ?>