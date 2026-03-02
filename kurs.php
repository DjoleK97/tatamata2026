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

$id = clean($_GET['id']);
$course = Database::getInstance()->getCourse($id);
$chapters = Database::getInstance()->getAllChaptersForCourse($id);
$cch = Database::getInstance()->getAllClipsForCourse($id);

if (isset($_SESSION['user'])) {
  $userCourses = Database::getInstance()->getAllCoursesForUser($_SESSION['user']->id);
  $userBought = false;

  foreach ($userCourses as $userCourse) {
    if ($userCourse['course_id'] == $id && $userCourse['confirmed']) {
      $userBought = true;
    }
  }
} else {
  $userBought = false;
}

if (isset($_POST['contact'])) {
  $firstnameAndLastname = clean($_POST['firstname']);
  $email = clean($_POST['email']);
  $courseC = clean($_POST['course']);
  $clip = clean($_POST['clip']);
  $clipName = Database::getInstance()->getClipNameFromID($clip);
  $message = clean($_POST['message']);

  $errors = array();

  if (empty($message)) {
    $errors['message'] = '<div style="display: block;" class="mb-0 invalid-feedback">Molimo unesite poruku.</div>';
  }

  if (count($errors) == 0) {
    // Instantiation and passing `true` enables exceptions
    $mail2 = new PHPMailer(true);

    try {
      //Server settings
      // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
      $mail2->isSMTP();                                            // Send using SMTP
      $mail2->Host       = 'mail.tatamata.rs';                    // Set the SMTP server to send through
      $mail2->SMTPAuth   = true;                                   // Enable SMTP authentication
      $mail2->Username   = 'admin@tatamata.rs';                     // SMTP username
      $mail2->Password  = defined('SMTP_PASS') ? SMTP_PASS : '';  // SEC-FIX: iz env.php
      $mail2->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
      $mail2->Port       = 25;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

      //Recipients
      $mail2->setFrom($_SESSION['user']->email, $_SESSION['user']->firstname . $_SESSION['user']->lastname);
      $mail2->addAddress('dragoslav.gagi8@gmail.com');               // Name is optional
      $mail2->addAddress('info@tatamata.rs');               // Name is optional

      // Content
      $mail2->isHTML(true);                                  // Set email format to HTML
      $mail2->Subject = "[PITANJE] Nejasnoca u klipu";
      $mail2->Body    = "Korisnik <strong>" . $_SESSION['user']->firstname . " " . $_SESSION['user']->lastname . "</strong> je postavio pitanje.<br><br>";
      $mail2->Body    .= "Kurs: " . $course['name'] . "<br>";
      $mail2->Body    .= "Video: $clipName <br><br>";
      $mail2->Body    .= "Poruka: $message<br><br>";
      $mail2->Body    .= "Email: " . $_SESSION['user']->email;
      if ($_SERVER['SERVER_NAME'] != "localhost") {
        $mail2->send();
      }

      $_SESSION['contact_form_success'] = '<div class="alert alert-warning alert-dismissible show mb-5" role="alert">
      Pitanje uspešno poslato.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
    </div>';
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  } else {
    $_SESSION['contact_form_error'] = '<div class="alert alert-danger alert-dismissible show mb-5" role="alert">
    Molimo unesite poruku.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
  </div>';
  }
}

?>


<?php include_once 'includes/header.php'; ?>


<section id="kursevi" class="kurs">
  <div class="container-fluid px-4">

    <?php if ($course == false) { ?>

      <div class="row justify-content-center mt-5">
        <div class="col-md-6 text-center py-5">
          <div class="prazno-stanje">
            <i class="fas fa-exclamation-circle prazno-ikona"></i>
            <h3>Kurs ne postoji.</h3>
            <p>Kurs koji trazite nije pronadjen ili je uklonjen.</p>
            <a href="<?php echo BASE_URL; ?>kursevi" class="btn btn-primary">
              <i class="fas fa-arrow-left me-2"></i> Nazad na kurseve
            </a>
          </div>
        </div>
      </div>

    <?php } else { ?>

      <!-- Breadcrumb -->
      <div class="kurs-breadcrumb">
        <a href="<?php echo BASE_URL; ?>kursevi">Kursevi</a>
        <span> / </span>
        <?php echo htmlspecialchars($course['name'] ?? 'Kurs'); ?>
      </div>

      <?php printFormatedFlashMessage("buy_course_success_message"); ?>
      <?php printFormatedFlashMessage("contact_form_success"); ?>
      <?php printFormatedFlashMessage("contact_form_error"); ?>
      <?php printFormatedFlashMessage("login_success_message"); ?>

      <!-- Zaglavlje kursa -->
      <div class="kurs-header">
        <a href="<?php echo BASE_URL . 'kursevi' ?>" class="kurs-nazad">
          <i class="fas fa-arrow-left"></i> Nazad
        </a>
        <div class="kurs-info">
          <h2><?php echo $course['name'] ?? "Error"; ?></h2>
          <p>Razred: <?php echo $course['grade_name'] ?? "Error"; ?></p>
        </div>

        <?php if (isset($userBought) && !$userBought) { ?>
          <div class="kurs-cena-badge">
            <?php if ($course['price'] == "0" || $course['price2'] == "0") { ?>
              <i class="fas fa-gift me-1"></i> Besplatno
            <?php } else { ?>
              <i class="fas fa-tag me-1"></i>
              <?php if (Database::getInstance()->isUserLoggedIn()) { ?>
                <?php if ($_SESSION['user']->country == "srbija") { ?>
                  <?php echo $course['price']; ?> RSD
                <?php } else { ?>
                  <?php echo $course['price2']; ?> &euro;
                <?php } ?>
              <?php } else { ?>
                <?php echo $course['price']; ?> RSD
              <?php } ?>
            <?php } ?>
          </div>

          <div>
            <?php if (Database::getInstance()->isUserLoggedIn()) { ?>
              <?php $crs = Database::getInstance()->getUser_Course($_SESSION['user']->id, $course['id']); ?>
              <?php if ($crs) { ?>
                <?php if ($crs['confirmed']) { ?>
                  <button class="card-link" disabled><i class="fas fa-check me-1"></i> Kupljen</button>
                <?php } else { ?>
                  <button class="card-link" disabled><i class="fas fa-hourglass me-1"></i> Čeka potvrdu admina</button>
                <?php } ?>
              <?php } else { ?>
                <?php if ($course['live']) { ?>
                  <?php if ($course['price'] == "0" || $course['price2'] == "0") { ?>
                    <form action="<?php echo BASE_URL; ?>mojikursevi.php" method="POST">
                      <input type="hidden" name="course_id" value="<?php echo $course['id'] ?? '-1'; ?>">
                      <input type="hidden" name="course_name" value="<?php echo $course['name'] ?? '-1'; ?>">
                      <input name="buy-course" type="submit" class="kupi" value="Počni kurs">
                    </form>
                  <?php } else { ?>
                    <?php if (strtolower($_SESSION['user']->country) == 'srbija') { ?>
                      <a class="kupi" href="<?php echo BASE_URL . 'kupovina-kursa/' . $course['id']; ?>">
                        <i class="fas fa-shopping-cart me-1"></i> Kupi kurs
                      </a>
                    <?php } else { ?>
                      <a class="kupi" href="<?php echo BASE_URL . 'kupovina-kursa-eng/' . $course['id']; ?>">
                        <i class="fas fa-shopping-cart me-1"></i> Kupi kurs
                      </a>
                    <?php } ?>
                  <?php } ?>
                <?php } else { ?>
                  <button class="card-link" disabled><i class="fas fa-clock me-1"></i> U pripremi</button>
                <?php } ?>
              <?php } ?>
            <?php } else { ?>
              <a class="kupi" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fas fa-play me-1"></i> Počni kurs
              </a>
            <?php } ?>
          </div>
        <?php } ?>
      </div>

      <!-- Glavni sadržaj -->
      <div class="row mt-0">

        <!-- Video kolona -->
        <div class="col-lg-9 col-md-12 ps-0 pe-0 pe-lg-3 mb-4">

          <?php if (isset($userBought) && !$userBought) { ?>
            <!-- Trailer video -->
            <video id="nekupljen-video" class="video w-100" style="border-radius:var(--radius-lg);" height="auto" controls disablepictureinpicture oncontextmenu="return false;" controlsList="nodownload">
              <source src="<?php echo BASE_URL . "videos/" . $course['trailer']; ?>" type="video/mp4">
            </video>

          <?php } else { ?>

            <?php if (count($cch) == 0) { ?>
              <div class="text-center py-5">
                <i class="fas fa-video" style="font-size:3rem; color:var(--plava); opacity:.35;"></i>
                <h4 class="mt-4" style="color:var(--siva);">Trenutno nema klipova za ovaj kurs.</h4>
              </div>
            <?php } else { ?>

              <div id="container">
                <div class="video-container text-center">
                  <?php $index2 = 0; $clipHappened = false;
                  foreach ($chapters as $index => $chapter) : ?>
                    <?php $clips = Database::getInstance()->getAllClipsForChapterAndCourse($chapter['chapter_id'], $id); ?>
                    <?php foreach ($clips as $clip) : ?>
                      <div data-chapter-id="<?php echo $chapter['chapter_id']; ?>" data-clip-id="<?php echo $clip['c_id']; ?>" style="<?php if ($clipHappened) echo 'display: none;'; ?>" class="video-container-small">
                        <?php $clipHappened = true; ?>
                        <!-- OVO SU CHILDERN KOJA AKO SE DODAJU ILI MENJAJU MORA I JS DA SE MENJA !!! -->
                        <video data-chapter-id="<?php echo $chapter['chapter_id']; ?>" data-clip-id="<?php echo $clip['c_id']; ?>" class="video" data-video-index="<?php echo $index2++; ?>" width="100%" height="auto" controls oncontextmenu="return false;" controlsList="nodownload nofullscreen" style="border-radius:var(--radius);">
                          <source src="<?php echo BASE_URL; ?>videos/<?php echo $clip['link']; ?>" type="video/mp4">
                        </video>
                      </div>
                    <?php endforeach; ?>
                  <?php endforeach; ?>
                  <div class="mt-3 d-flex justify-content-between">
                    <button disabled class="btn btn-primary prev-btn">
                      <i class="fas fa-chevron-left"></i> Prethodni klip
                    </button>
                    <button class="btn btn-primary next-btn">
                      Sledeći klip <i class="fas fa-chevron-right"></i>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Klipovi na malom ekranu (d-lg-none) -->
              <div class="card crd mt-4 d-lg-none">
                <div class="card-body">
                  <h5 class="card-title fw-bold"><?php echo $course['name']; ?></h5>
                  <div class="pretraga-col mb-3">
                    <div class="input-group flex-nowrap">
                      <input type="text" class="form-control pretraga-input-kurs" placeholder="Pretraži...">
                      <button class="btn yellow-btn" style="pointer-events: none;" type="button"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="search-results-small"></div>
                  </div>
                  <div class="accordion" id="accordionExample69">
                    <?php $collapseHappenedS = false;
                    foreach ($chapters as $chapter) : ?>
                      <?php $clips = Database::getInstance()->getAllClipsForChapterAndCourse($chapter['chapter_id'], $id);
                      $hasClipsS = !empty($clips); ?>
                      <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne<?php echo $chapter['chapter_id']; ?>69">
                          <button class="accordion-button <?php if (!$hasClipsS || ($hasClipsS && $collapseHappenedS)) echo 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne<?php echo $chapter['chapter_id']; ?>69" aria-expanded="false">
                            <?php echo ($chapter['chapter_name'] == '_global') ? 'Uvod' : ($chapter['chapter_name'] ?? "error"); ?>
                          </button>
                        </h2>
                        <div id="collapseOne<?php echo $chapter['chapter_id']; ?>69" class="accordion-collapse ac collapse <?php if ($hasClipsS && !$collapseHappenedS) echo 'show'; ?>" data-bs-parent="#accordionExample69">
                          <?php foreach ($clips as $clip) : ?>
                            <div class="accordion-body ab">
                              <p data-chapter-id="<?php echo $chapter['chapter_id']; ?>" data-clip-id="<?php echo $clip['c_id']; ?>" class="m-0 card-text clip-link <?php if ($hasClipsS && !$collapseHappenedS) { echo 'active-clip'; $collapseHappenedS = true; } ?>">
                                - <?php echo $clip['c_name'] ?? "Error"; ?>
                              </p>
                            </div>
                          <?php endforeach; ?>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>

              <!-- Opis klipa -->
              <div class="card mt-4 clip-descc">
                <div class="card-body">
                  <h5 class="card-title fw-bold"><i class="fas fa-align-left me-2" style="color:var(--plava);"></i> Opis klipa</h5>
                  <hr class="hr">
                  <?php $index = 0; $selected = false;
                  foreach ($chapters as $chapter) :
                    $clips = Database::getInstance()->getAllClipsForChapterAndCourse($chapter['chapter_id'], $id);
                    if (empty($clips)) { continue; }
                    if (!$selected) { $firstClipName = $clips[0]['c_id']; $selected = true; }
                    foreach ($clips as $clip) : ?>
                      <p data-chapter-id="<?php echo $chapter['chapter_id']; ?>" data-clip-id="<?php echo $clip['c_id']; ?>" style="<?php if ($index++ > 0) echo 'display: none;'; ?>" class="card-text clip-description">
                        <?php echo $clip['clip_description']; ?>
                      </p>
                  <?php endforeach; endforeach; ?>
                </div>
              </div>

              <!-- Postavi pitanje -->
              <div class="card mt-4">
                <div class="card-body">
                  <h5 class="card-title fw-bold">
                    <i class="fas fa-question-circle me-2" style="color:var(--plava);"></i> Nešto ti nije jasno?
                  </h5>
                  <hr class="hr">
                  <form id="contact-form" method="POST">
                    <input id="contact-name" name="firstname" type="hidden" value="<?php echo $_SESSION['user']->firstname . " " . $_SESSION['user']->lastname; ?>">
                    <input id="contact-email" name="email" type="hidden" value="<?php echo $_SESSION['user']->email ?? ""; ?>">
                    <div class="mb-4">
                      <label class="form-label"><i class="far fa-comment-dots"></i> Tvoje pitanje</label>
                      <textarea id="contact-message" name="message" class="form-control" placeholder="npr. Nisam razumeo ovaj deo videa..." style="height: 100px"></textarea>
                      <?php echo $errors['message'] ?? ""; ?>
                    </div>
                    <input type="hidden" id="clip-name-question" name="clip" value="<?php echo $firstClipName ?? ''; ?>">
                    <input type="hidden" name="course" value="<?php echo $course['name']; ?>">
                    <button id="contact-send-btn" name="contact" type="submit" class="btn btn-primary">
                      <i class="fas fa-paper-plane me-2"></i> Pošalji pitanje
                    </button>
                  </form>
                </div>
              </div>

            <?php } ?>
          <?php } ?>
        </div>

        <!-- Desna kolona (sidebar) -->
        <?php if (isset($userBought) && !$userBought) { ?>
          <div class="col-lg-3 col-md-12 mt-0 pe-0">
            <div class="course-info-container">
              <h3><?php echo $course['name'] ?? "Error"; ?></h3>
              <hr class="hr">
              <div class="course-description">
                <?php echo $course['description'] ?? ""; ?>
              </div>
            </div>
          </div>
        <?php } else { ?>
          <div class="col-lg-3 col-md-12 mt-0 d-none d-lg-block pe-0">
            <div class="card crd">
              <div class="card-body">
                <h5 class="card-title fw-bold"><?php echo $course['name']; ?></h5>
                <div class="pretraga-col mb-3">
                  <div class="input-group flex-nowrap">
                    <input type="text" class="form-control pretraga-input-kurs" placeholder="Pretraži...">
                    <button class="btn yellow-btn" style="pointer-events: none;" type="button"><i class="fas fa-search"></i></button>
                  </div>
                  <div class="search-results"></div>
                </div>
                <div class="accordion" id="accordionExample">
                  <?php $collapseHappened = false;
                  foreach ($chapters as $chapter) : ?>
                    <?php $clips = Database::getInstance()->getAllClipsForChapterAndCourse($chapter['chapter_id'], $id);
                    $hasClips = !empty($clips); ?>
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="headingOne<?php echo $chapter['chapter_id']; ?>">
                        <button class="accordion-button text-start <?php if (!$hasClips || ($hasClips && $collapseHappened)) echo 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne<?php echo $chapter['chapter_id']; ?>" aria-expanded="false">
                          <?php echo ($chapter['chapter_name'] == '_global') ? 'Uvod' : ($chapter['chapter_name'] ?? "error"); ?>
                        </button>
                      </h2>
                      <div id="collapseOne<?php echo $chapter['chapter_id']; ?>" class="accordion-collapse ac collapse <?php if ($hasClips && !$collapseHappened) echo 'show'; ?>" data-bs-parent="#accordionExample">
                        <?php foreach ($clips as $brojaccc => $clip) : ?>
                          <div class="accordion-body ab">
                            <p data-chapter-id="<?php echo $chapter['chapter_id']; ?>" data-clip-id="<?php echo $clip['c_id']; ?>" class="m-0 card-text clip-link <?php if ($hasClips && !$collapseHappened) { echo 'active-clip'; $collapseHappened = true; } ?>">
                              <?php echo ++$brojaccc . ". " . ($clip['c_name'] ?? "Error"); ?>
                            </p>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>

      </div>

    <?php } ?>

  </div>
</section>





<!-- Modal -->
<div class="modal fade morate-biti-prijavljeni-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-lock me-2" style="color:var(--plava);"></i> Potrebna prijava</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <i class="fas fa-user-lock mb-3" style="font-size:2.5rem; color:var(--plava); opacity:.3;"></i>
        <p>Da bi pristupio ovom kursu, potrebno je da imas nalog na platformi.</p>
      </div>
      <div class="modal-footer justify-content-center" style="gap:12px;">
        <a href="<?php echo BASE_URL . "prijava" ?>" class="btn btn-primary">
          <i class="fas fa-sign-in-alt me-2"></i> Prijavi se
        </a>
        <a href="<?php echo BASE_URL . "registracija" ?>" class="btn btn-outline-plava">
          <i class="fas fa-user-plus me-2"></i> Kreiraj nalog
        </a>
      </div>
    </div>
  </div>
</div>

<?php include_once 'includes/plyr_footer.php'; ?>
<script>
  new Plyr('#nekupljen-video', {
    controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'captions', 'settings', 'airplay', 'fullscreen'],
  });
</script>
<?php include_once 'includes/footer.php'; ?>