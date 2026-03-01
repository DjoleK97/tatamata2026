<?php
ob_start();
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

require_once "includes/functions.php";
require_once "classes/Database.php";
require_once "includes/config.php";


if (!Database::getInstance()->isUserLoggedIn()) {
  $_SESSION['unauthorized_access'] = '<div class="alert alert-danger alert-dismissible show" role="alert">
        <strong>Greška!</strong> Zabranjen pristup! <i class="fas fa-exclamation-triangle"></i>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>';
  header("Location: " . BASE_URL . 'kursevi');

  exit;
}

$id = clean($_GET['id']);
$course = Database::getInstance()->getCourse($id);

if (isset($_POST['contact'])) {

  $data = array(
    "course_id" => $id,
    "user_id" => $_SESSION['user']->id,
  );

  if (!Database::getInstance()->userCourseExists($data)) {
    $file = $_FILES['file'];

    if (!empty($file['name'])) { // Da li je poslao neke fajlove

      $uploaded = array();
      $failed = array();
      $allowed = ['jpg', 'jpeg', 'png'];

      // Posalji mail djoletu

      // Instantiation and passing `true` enables exceptions
      $mail2 = new PHPMailer(true);

      try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail2->isSMTP();                                            // Send using SMTP
        $mail2->Host       = 'mail.tatamata.rs';                    // Set the SMTP server to send through
        $mail2->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail2->Username   = 'admin@tatamata.rs';                     // SMTP username
        $mail2->Password   = 'pidyejretard123';                               // SMTP password
        $mail2->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail2->Port       = 25;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail2->setFrom($_SESSION['user']->email, $_SESSION['user']->firstname . $_SESSION['user']->lastname);
        $mail2->addAddress('dragoslav.gagi8@gmail.com');               // Name is optional
        $mail2->addAddress('admin@tatamata.rs');               // Name is optional

        // Content
        $mail2->isHTML(true);                                  // Set email format to HTML
        $mail2->Subject = "[ADMIN] Kupovina iz inostranstva";
        $mail2->Body    = "Korisnik <strong>" . $_SESSION['user']->firstname . " " . $_SESSION['user']->lastname . "</strong> je kupio kurs: <strong>" . $course['name'] . "</strong> i potvrdio je uplatu.<br>";
        $mail2->Body    .= "Novac poslat preko: <strong>" . $_POST['sent_type'] . "</strong>";
      } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }

      $file_name = $file['name'];
      $file_tmp = $file['tmp_name'];
      $file_size = $file['size'];
      $file_error = $file['error'];

      $file_ext = explode('.', $file_name);
      $file_ext = strtolower(end($file_ext));

      if (in_array($file_ext, $allowed)) {
        if ($file_error === 0) {

          if ($file_size <= 5242880) {
            $file_name_new = $file_name;
            $file_destination = 'uploads/' . $file_name_new;

            if (move_uploaded_file($file_tmp, $file_destination)) {
              $uploaded[0] = $file_destination;
              try {
                $mail2->addAttachment($file_destination);         // Add attachments
              } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
              }
            } else {
              $failed[0] = "[{$file_name}] failed upload";
            }
          } else {
            $failed[0] = "[{$file_name}] is too large";
          }
        } else {
          $failed[0] = "[{$file_name}] failed to upload";
        }
      } else {
        $failed[0] = "[{$file_name}] file extension '{$file_ext}' is not allowed";
      }

      if (!empty($uploaded)) {
        try {
          if ($_SERVER['SERVER_NAME'] != "localhost") {
            $mail2->send();
          }
          array_map('unlink', glob('uploads/*'));
          $_SESSION['contact_form_success'] = '<div class="alert alert-warning alert-dismissible show mb-5" role="alert">
            Poruka uspešno poslata.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
          </div>';
          Database::getInstance()->buyCourse($data);
        } catch (Exception $e) {
          echo "Message could not be sent. Mailer Error: {$mail2->ErrorInfo}";
        }
      }

      if (!empty($failed)) {
        $_SESSION['contact_form_error'] = '<div class="alert alert-danger alert-dismissible show mb-5" role="alert">'
          . $failed[0] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
        </div>';
      }
    } else {
      $_SESSION['contact_form_error'] = '<div class="alert alert-danger alert-dismissible show mb-5" role="alert">
         Niste izabrali sliku.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
        </div>';
    }
  } else {
    $_SESSION['contact_form_error'] = '<div class="alert alert-danger alert-dismissible show mb-5" role="alert">
    Već ste potvrdili uplatu za ovaj kurs.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
   </div>';
  }
}

?>


<?php include_once 'includes/header.php'; ?>

<section id="kupovina-kursa-eng">
  <div class="container-fluid">
    <div class="row">
      <?php printFormatedFlashMessage("contact_form_error"); ?>
      <?php printFormatedFlashMessage("contact_form_success"); ?>

      <h1 class="text-white text-center fw-bold mb-5">ODABRANI KURS: <?php echo $course['name']; ?>
        <hr class="hr">
      </h1>

      <div class="col text-white">
        <p>
          Osobe koje ne žive na teritoriji Republike Srbije mogu da izvrše
          plaćanje <br> u pošti putem usluge <strong>PostKeša</strong> ili putem <strong>WesternUniona</strong>.
        </p>
        <div class="card">
          <div class="card-body">
            <h5 class="card-title text-center fw-bold">PODACI ZA SLANJE NOVCA
              <hr class="hr">
            </h5>
            <p class="m-0"><strong>Ime i prezime:</strong> Đorđe Karagača</p>
            <p class="m-0"><strong>Adresa:</strong> Darinke Radović 6</p>
            <p class="m-0"><strong>Poštanski broj:</strong> 11250</p>
            <p class="m-0"><strong>Mesto:</strong> Beograd, Srbija</p>
            <p class="m-0 mt-3"><strong>Iznos za uplatu:</strong> <?php echo $course['price2']; ?> &euro;</p>
          </div>
        </div>
        <div class="napomena text-white text-left mt-4">
          <div class="row">
            <div class="col-auto d-inline-block pe-0">
              <em><strong>Napomena:</strong></em>
            </div>
            <div class="col">
              <em>Prilikom slanja novca mogu se javiti dodatni troškovi. <br>
                Troškovi usluga pošte nisu uračunati u cenu kursa. </em> <br>
            </div>
          </div>
        </div>
        <div class="card mt-4">
          <div class="card-body">
            <h5 class="card-title text-center fw-bold">FORMA ZA SLANJE NOVCA
              <hr class="hr">
            </h5>
            <form enctype="multipart/form-data" method="POST">

              <p class="m-0 mb-1" style="font-weight: 600;">Poslato putem:</p>

              <div class="form-check">
                <input class="form-check-input" type="radio" name="sent_type" value="PostKes" id="flexRadioDefault1" checked>
                <label class="form-check-label" for="flexRadioDefault1">
                  PostKeš
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="sent_type" value="WesternUnion" id="flexRadioDefault2">
                <label class="form-check-label" for="flexRadioDefault2">
                  WesternUnion
                </label>
              </div>

              <div class="mb-4 mt-3">
                <label class="form-label" style="font-weight: 600;"><i class="fas fa-paperclip"></i> Slika potvrde: </label>
                <input id="contact-files" accept="image/*" class="form-control" type="file" name="file">
              </div>

              <button id="contact-send-btn" name="contact" type="submit" class="register-btn btn btn-primary scale-btn-2">POTVRDA SLANJA</button>
            </form>
          </div>
        </div>
      </div>
      <div class="col text-white">
        <h3 class="fw-bold d-inline-block">KAKO DA DOBIJEM PRISTUP KURSU?
          <hr>
        </h3>

        <div class="uputstvo text-white d-flex">
          <div class="uputstvo-number">1</div>
          <div class="uputstvo-text">
            Pošalji novac u pošti putem <br>
            PostKeša ili WesternUniona
          </div>
        </div>

        <div class="uputstvo text-white d-flex">
          <div class="uputstvo-number">2</div>
          <div class="uputstvo-text">
            Slikaj potvrdu o slanju novca. <br>
            Važno je da se lepo vidi ime i prezime uplatioca kao i <br>
            kontrolni broj koji mi omogućuje da podignem novac.
          </div>
        </div>

        <div class="uputstvo text-white d-flex">
          <div class="uputstvo-number">3</div>
          <div class="uputstvo-text">
            Sliku potvrde okači na "Forma za slanje potvrde", <br>
            označi način slanja novca i potvrdi slanje.
          </div>
        </div>

        <div class="uputstvo text-white d-flex">
          <div class="uputstvo-number">4</div>
          <div class="uputstvo-text">
            Nakon evidentirane uplate, <br>
            dobićeš mejl da ti je gledanje kursa omogućeno.
          </div>
        </div>
        <p class="text-white mt-5">Za sva dodatna pitanja, slobodno mi se javi putem <a href="<?php echo BASE_URL ?>pocetna#kontakt" class="text-decoration-none kontakt-forme-link" target="_blank">kontakt forme</a>.</p>

      </div>
    </div>

    <div class="row mt-5">
      <div class="col text-white text-center">
        <p class="viseinfo">Više informacija o slanju novca iz tvog regiona možeš pronaći ovde:</p>
        <div class="zastave d-flex justify-content-center">
          <div>
            <a target="_blank" href="http://www.postacg.me/Medjunarodni-elektronski-prenos-novca-"><img src="<?php echo BASE_URL; ?>public/images/flags/Flag_of_Montenegro.png" alt="Country flag"></a>
            <br> <strong>Crna Gora</strong>
          </div>
          <div>
            <a target="_blank" href="https://www.postesrpske.com/finansijske-usluge/prenos-novca-medjunarodni-saobracaj/post-cash/"><img src="<?php echo BASE_URL; ?>public/images/flags/Flag_of_the_Republika_Srpska.png" alt="Country flag"></a>
            <br> <strong>Republika Srpska</strong>
          </div>
          <div>
            <a target="_blank" href="https://www.posta.ba/transfer-novca/"><img src="<?php echo BASE_URL; ?>public/images/flags/Flag_of_Bosnia_and_Herzegovina.png" alt="Country flag"></a>
            <br> <strong>BiH</strong>
          </div>
          <div>
            <a target="_blank" href="https://www.posta.hr/medunarodna-postanska-uputnica-166/166"><img src="<?php echo BASE_URL; ?>public/images/flags/Flag_of_Croatia.png" alt="Country flag"></a>
            <br> <strong>Hrvatska</strong>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include_once 'includes/footer.php'; ?>