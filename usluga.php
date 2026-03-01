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


<section id="usluga">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-md-10 offset-md-1 ps-0">

        <?php printFormatedFlashMessage("usluge_contact_fail"); ?>
        <?php printFormatedFlashMessage("usluge_contact_success"); ?>


        <h1 class="text-white text-center fw-bold mb-3 d-inline-block mx-auto"><?php echo $uslugaNAME ?>
          <hr class="hr">
        </h1>

        <video id="usluga-video" class="video" width="100%" height="auto" controls disablepictureinpicture oncontextmenu="return false;" controlsList="nodownload">
          <source src="<?php echo BASE_URL . "videos/" . $uslugaVIDEO; ?>" type="video/mp4">
          Your browser does not support the video tag.
        </video>

        <!-- CLIP DESCRIPTION -->
        <div class="card mt-4 clip-descc">
          <div class="card-body">
            <h4 class="card-title">Opis usluge:</h4>
            <hr class="hr">
            <p class="card-text clip-description"><?php echo $uslugaDESC; ?></p>
          </div>
        </div>

        <?php if ($usluga == 'priprema-za-prijemni') { ?>
          <div class="login-form-container w-75 wurf">

            <h1 class="mb-1">Prijava za BESPLATAN probni čas</h1>
            <p class="mb-4">Polja označena <strong class="text-danger">*</strong> su obavezna.</p>

            <form method="POST">

              <?php if (Database::getInstance()->isUserLoggedIn()) { ?>

                <div class="mb-4">
                  <label class="form-label"> <i class="far fa-user"></i>Ime i prezime</label>
                  <input id="contact-name" readonly name="firstname" type="text" class="form-control" value="<?php echo $_SESSION['user']->firstname . " " . $_SESSION['user']->lastname; ?>">
                </div>

                <div class="mb-4">
                  <label class="form-label"><i class="far fa-envelope"></i> Email</label>
                  <input id="contact-email" readonly name="email" type="email" class="form-control" placeholder="Unesite email" value="<?php echo $_SESSION['user']->email ?? ""; ?>">
                </div>

              <?php } else { ?>

                <div class="mb-4">
                  <label class="form-label">Ime i prezime <strong class="text-danger">*</strong></label>
                  <input name="firstname" type="text" class="form-control <?php if (isset($errors['firstname'])) echo 'is-invalid';
                                                                          else if (isset($firstname)) echo 'is-valid'; ?>" placeholder="Unesi ime i prezime" value="<?php echo $firstname ?? ""; ?>">
                  <?php echo $errors['firstname'] ?? ""; ?>
                </div>

                <div class="mb-4">
                  <label class="form-label">Email <strong class="text-danger">*</strong></label>
                  <input name="email" type="email" class="form-control <?php if (isset($errors['email']) || isset($errors['taken_email'])) echo 'is-invalid';
                                                                        else if (isset($email)) echo 'is-valid'; ?>" placeholder="Unesi email" value="<?php echo $email ?? ""; ?>">
                  <?php echo $errors['email'] ?? ""; ?>
                </div>

              <?php } ?>

              <div class="mb-4">
                <label class="form-label">Pripremu za nastavu ću pratiti preko <strong class="text-danger">*</strong></label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" value="Racunara / Laptopa" name="pratim-preko" checked>
                  <label class="form-check-label">
                    Računara / Laptopa (preporučeno)
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" value="Telefona / Tableta" name="pratim-preko">
                  <label class="form-check-label">
                    Telefona / Tableta
                  </label>
                </div>
              </div>

              <!-- <div class="mb-4">
                <label class="form-label">U koju osnovnu školu ideš <strong class="text-danger">*</strong></label>
                <input name="skola" type="text" class="form-control <?php //if (isset($errors['skola'])) echo 'is-invalid';
                                                                    //else if (isset($skola)) echo 'is-valid'; 
                                                                    ?>" placeholder="Unesi ime škole" value="<?php //echo $skola ?? ""; 
                                                                                                              ?>">
                <?php //echo $errors['skola'] ?? ""; 
                ?>
              </div> -->


              <?php if (Database::getInstance()->isUserLoggedIn()) { ?>

                <div class="mb-4">
                  <label class="form-label">Broj telefona</label>
                  <div class="form-text text-white">
                    Mozeš ostaviti tvoj broj ukoliko ti je lakše da komuniciramo preko Viber-a / WhatsApp-a nego preko E-maila. (Ukoliko ti odgovara komunikacija preko email-a ostavi ovo polje prazno).
                  </div>
                  <input readonly name="phone" type="text" class="form-control <?php if (isset($errors['phone'])) echo 'is-invalid';
                                                                                else if (isset($phone)) echo 'is-valid'; ?>" placeholder="Unesi broj telefona" value="<?php echo $_SESSION['user']->phone_number ?? ""; ?>">
                  <?php echo $errors['phone'] ?? ""; ?>
                </div>

              <?php } else { ?>

                <div class="mb-4">
                  <label class="form-label">Broj telefona</label>
                  <div class="form-text text-white">
                    Mozeš ostaviti tvoj broj ukoliko ti je lakše da komuniciramo preko Viber-a / WhatsApp-a nego preko E-maila. (Ukoliko ti odgovara komunikacija preko email-a ostavi ovo polje prazno).
                  </div>
                  <input name="phone" type="text" class="form-control <?php if (isset($errors['phone'])) echo 'is-invalid';
                                                                      else if (isset($phone)) echo 'is-valid'; ?>" placeholder="Unesi broj telefona" value="<?php echo $phone ?? ""; ?>">
                  <?php echo $errors['phone'] ?? ""; ?>
                </div>

              <?php } ?>


              <button name="usluga-contact" type="submit" class="register-btn btn btn-primary scale-btn-2">Pošalji <i class="fas fa-check"></i></button>
            </form>
          </div>
        <?php } ?>

        <p class="text-white mt-5 text-center">Za sva dodatna pitanja javite se putem <a href="<?php echo BASE_URL ?>pocetna#kontakt" class="text-decoration-none kontakt-forme-link" target="_blank">kontakt forme</a>.</p>

        <div class="mt-4 text-white text-center go-back">
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