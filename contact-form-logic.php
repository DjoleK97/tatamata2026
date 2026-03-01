<?php
ob_start();
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
require 'includes/functions.php';
require 'includes/config.php';

if (!isset($_POST['contact'])) {
  exit;
}

if (!empty($_FILES['files']['name'][0])) { // Da li je poslao neke fajlove
  $files = $_FILES['files'];

  $uploaded = array();
  $failed = array();

  $allowed = ['pdf', 'jpg', 'jpeg', 'png', 'jfif'];

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
    $mail2->setFrom(clean($_POST['email']), clean($_POST['firstname']));
    // $mail2->addAddress('joe@example.net', 'Joe User');     // Add a recipient
    $mail2->addAddress('dragoslav.gagi8@gmail.com');               // Name is optional
    $mail2->addAddress('info@tatamata.rs');               // Name is optional
    $mail2->addReplyTo(clean($_POST['email']), 'Information');

    // Attachments
    // $mail2->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail2->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail2->isHTML(true);                                  // Set email format to HTML
    $mail2->Subject = "[TataMata] Kontakt forma";
    $mail2->Body    = clean($_POST['message']);
  } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }

  foreach ($files['name'] as $position => $file_name) {

    $file_tmp = $files['tmp_name'][$position];
    $file_size = $files['size'][$position];
    $file_error = $files['error'][$position];

    $file_ext = explode('.', $file_name);
    $file_ext = strtolower(end($file_ext));

    if (in_array($file_ext, $allowed)) {
      if ($file_error === 0) {

        if ($file_size <= 5242880) {
          $file_name_new = $file_name;
          $file_destination = 'uploads/' . $file_name_new;

          if (move_uploaded_file($file_tmp, $file_destination)) {
            $uploaded[$position] = $file_destination;
            try {
              $mail2->addAttachment($file_destination);         // Add attachments
            } catch (Exception $e) {
              echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
          } else {
            $failed[$position] = "[{$file_name}] failed upload";
          }
        } else {
          $failed[$position] = "[{$file_name}] is too large";
        }
      } else {
        $failed[$position] = "[{$file_name}] failed to upload";
      }
    } else {
      $failed[$position] = "[{$file_name}] file extension '{$file_ext}' is not allowed";
    }
  }

  if (!empty($uploaded)) {
    try {
      if ($_SERVER['SERVER_NAME'] != "localhost") {
        $mail2->send();
      }
      array_map('unlink', glob('uploads/*'));
      $_SESSION['contact_form_success'] = '<div class="alert alert-warning alert-dismissible show mt-5" role="alert">
       Poruka uspešno poslata.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
      </div>';
      header("Location: " . BASE_URL . "pocetna");

      exit;
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  }

  if (!empty($failed)) {
    print_r($failed);
  }
} else { // Nije poslao fajlove

  // Posalji mail djoletu

  // Instantiation and passing `true` enables exceptions
  $mail = new PHPMailer(true);

  try {
    //Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'mail.tatamata.rs';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'admin@tatamata.rs';                     // SMTP username
    $mail->Password   = 'pidyejretard123';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 25;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom(clean($_POST['email']), clean($_POST['firstname']));
    // $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
    $mail->addAddress('dragoslav.gagi8@gmail.com');               // Name is optional
    $mail->addAddress('info@tatamata.rs');               // Name is optional
    $mail->addReplyTo(clean($_POST['email']), 'Information');

    // Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = "[TataMata] Kontakt forma";
    $mail->Body    = clean($_POST['message']);

    if ($_SERVER['SERVER_NAME'] != "localhost") {
      $mail->send();
    }
    // echo 'Message has been sent';
    $_SESSION['contact_form_success'] = '<div class="alert alert-warning alert-dismissible show mt-5" role="alert">
     Poruka uspešno poslata.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>';
    header("Location: " . BASE_URL . "pocetna");

    exit;
  } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}
