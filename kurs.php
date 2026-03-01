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


<section id="kursevi" class="mt-5 kurs">
  <div class="container-fluid">
    <div class="row">

      <?php if ($course == false) { ?>

        <h1>Kurs ne postoji</h1>

      <?php } else { ?>

        <?php printFormatedFlashMessage("buy_course_success_message"); ?>
        <?php printFormatedFlashMessage("contact_form_success"); ?>
        <?php printFormatedFlashMessage("contact_form_error"); ?>
        <?php printFormatedFlashMessage("login_success_message"); ?>

        <?php if (isset($userBought) && !$userBought) { ?>


          <div class="col-lg-9 col-md-12 d-flex mt-5 bigzy ps-0">


            <div class="bigzy1">
              <div class="strelica-container">
                <a href="<?php echo BASE_URL . 'kursevi' ?>">
                  <img class="strelica-img-kurs" src="<?php echo BASE_URL . 'public/images/zuta_strelica.png' ?>" alt="">
                </a>
                <br> <span class="text-white">Nazad</span>
              </div>

              <div class="kurs-ime-razred text-white d-inline-block">
                <p class="m-0">Kurs: <?php echo $course['name'] ?? "Error"; ?></p>
                <p class="m-0">Razred: <?php echo $course['grade_name'] ?? "Error"; ?></p>
              </div>
            </div>

            <div class="bigzy2">
              <div class="kurs-kupi">
                <!-- Ako je korisnik ulogovan -->
                <?php if (Database::getInstance()->isUserLoggedIn()) { ?>
                  <!-- Moramo proveriti da li je vec kupio kurs, tj narucio pa onda da vidimo da li je confirmed -->
                  <?php $crs = Database::getInstance()->getUser_Course($_SESSION['user']->id, $course['id']);
                  if ($crs) { ?>
                    <!-- Proveravamo da li je confirmed -->
                    <?php if ($crs['confirmed']) { ?>
                      <a type="button" class="card-link" disabled>
                        Kupljen
                      </a>
                    <?php } else { ?>
                      <a type="button" class="card-link" disabled>
                        Čeka se potvrda admina.
                      </a>
                    <?php } ?>

                  <?php } else { ?>
                    <!-- Ulogovan je ali nije kupio -->
                    <!-- Proveravamo da li je kurs live -->
                    <?php if ($course['live']) { ?>

                      <!-- Proveravamo da li je besplatan ili ne -->
                      <?php if ($course['price'] == "0" || $course['price2'] == "0") { ?>
                        <form action="<?php echo BASE_URL; ?>mojikursevi.php" method="POST">
                          <input type="hidden" name="course_id" value="<?php echo $course['id'] ?? '-1'; ?>">
                          <input type="hidden" name="course_name" value="<?php echo $course['name'] ?? '-1'; ?>">
                          <input name="buy-course" type="submit" class="btn btn-primary scale-btn-105" value="Počni kurs">
                        </form>
                      <?php } else { // Znaci da nije besplatan i vodi ga na novu stranu 
                      ?>

                        <?php if (strtolower($_SESSION['user']->country) == 'srbija') { ?>
                          <a type="button" class="card-link scale-btn-105 kupi" href="<?php echo BASE_URL . 'kupovina-kursa/' . $course['id']; ?>">
                            Kupi kurs
                          </a>
                        <?php } else { ?>
                          <a type="button" class="card-link scale-btn-105 kupi" href="<?php echo BASE_URL . 'kupovina-kursa-eng/' . $course['id']; ?>">
                            Kupi kurs
                          </a>
                        <?php } ?>

                      <?php } ?>

                    <?php } else { ?>
                      <!-- KURS NIJE LIVE -->
                      <a type="button" class="card-link" disabled style="background-color: grey; color:white; cursor: not-allowed;">
                        U pripremi
                      </a>
                    <?php } ?>

                  <?php } ?>
                <?php } else { ?>
                  <!-- Znaci da nije ulogovan i neka ga vodi na modal -->
                  <a type="button" class="card-link scale-btn-105" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Počni kurs
                  </a>
                <?php } ?>
              </div>
            </div>

            <div class="bigzy3">

              <div class="kurs-cena text-white d-inline-block">
                <p class="m-0">Cena:
                  <?php if ($course['price'] == "0" || $course['price2'] == "0") { ?>
                    Besplatno
                  <?php } else { ?>
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
                </p>
              </div>


            </div>

          </div>

        <?php } ?>

        <div class="row mt-md-3 centerrow">
          <p class="g-0 text-white clip-name-p">
            <?php
            // $chapterNAME =  $chapters[0]['chapter_name'];
            // if ($chapterNAME == "_global") {
            //   $chapterNAME = "Uvod";
            // }
            ?>
            <!-- <span class="chapternamezz"><?php //echo $chapterNAME; 
                                              ?></span> - -->
            <!-- <span class="clipnamezz"><?php //echo Database::getInstance()->getAllClipsForChapterAndCourse($chapters[0]['chapter_id'], $id)[0]["c_name"]; 
                                          ?></span> -->
          </p>

          <div class="col-md-12 col-lg-9 ps-0 centerrow1">
            <?php if (isset($userBought) && !$userBought) { ?>
              <video id="nekupljen-video" class="video" width="100%" height="auto" controls disablepictureinpicture oncontextmenu="return false;" controlsList="nodownload">
                <source src="<?php echo BASE_URL . "videos/" . $course['trailer']; ?>" type="video/mp4">
                Your browser does not support the video tag.
              </video>
            <?php } else { ?>

              <?php if (count($cch) == 0) { ?>
                <h2 class="text-white text-center mt-5">Trenutno nema klipova za ovaj kurs.</h2>
              <?php } else { ?>

                <div id="container">
                  <div class="video-container text-center">
                    <!-- <div style="-moz-user-select: none; -webkit-user-select: none; -ms-user-select:none; user-select:none;-o-user-select:none;" unselectable="on" onselectstart="return false;" onmousedown="return false;" class="watermark" oncontextmenu="return false;"><? //php echo $_SESSION['user']->firstname . " " . $_SESSION['user']->lastname; 
                                                                                                                                                                                                                                                                                  ?></div> -->
                    <?php $index2 = 0;
                    $clipHappened = false;
                    foreach ($chapters as $index => $chapter) : ?>
                      <?php $clips = Database::getInstance()->getAllClipsForChapterAndCourse($chapter['chapter_id'], $id); ?>
                      <?php foreach ($clips as $clip) : ?>
                        <div data-chapter-id="<?php echo $chapter['chapter_id']; ?>" data-clip-id="<?php echo $clip['c_id']; ?>" style="<?php if ($clipHappened) echo 'display: none;'; ?>" class="video-container-small">
                          <?php $clipHappened = true; ?>
                          <!-- OVO SU CHILDERN KOJA AKO SE DODAJU ILI MENJAJU MORA I JS DA SE MENJA !!! -->
                          <video data-chapter-id="<?php echo $chapter['chapter_id']; ?>" data-clip-id="<?php echo $clip['c_id']; ?>" class="video" data-video-index="<?php echo $index2++; ?>" width="100%" height="auto" controls oncontextmenu="return false;" controlsList="nodownload nofullscreen">
                            <source src="<?php echo BASE_URL; ?>videos/<?php echo $clip['link']; ?>" type="video/mp4">
                            Your browser does not support the video tag.
                          </video>
                        </div>
                      <?php endforeach; ?>
                    <?php endforeach; ?>
                    <!-- <p id="toggle_fullscreen" class="text-white text-center fullscreen mb-0">Gledaj preko celog ekrana.</p> -->
                    <div class="mt-3 d-flex justify-content-between">
                      <button disabled class="btn btn-primary text-white prev-btn">
                        <i class="fas fa-chevron-left"></i> Prethodni klip
                      </button>
                      <button class="btn btn-primary text-white next-btn">
                        Sledeći klip <i class="fas fa-chevron-right"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- CLIPS ON SMALL SCREEN -->
                <div class="card crd crd2 mt-4">
                  <div class="card-body">
                    <h5 class="card-title fw-bold"><?php echo $course['name']; ?></h5>

                    <div class="col-auto text-white g-0 pretraga-col mb-3">

                      <div class="input-group flex-nowrap">
                        <input type="text" class="form-control pretraga-input-kurs" placeholder="Pretraži...">
                        <button class="btn yellow-btn" style="pointer-events: none;" type="button" id="button-addon2"><i class="fas fa-search"></i></button>
                      </div>

                      <div class="search-results-small">

                      </div>

                    </div>

                    <div class="accordion" id="accordionExample69">

                      <?php
                      $collapseHappenedS = false;
                      $hasClipsS = false;
                      foreach ($chapters as $chapter) : ?>

                        <?php $clips = Database::getInstance()->getAllClipsForChapterAndCourse($chapter['chapter_id'], $id); ?>

                        <?php
                        if (empty($clips)) {
                          $hasClipsS = false;
                        } else {
                          $hasClipsS = true;
                        }
                        ?>

                        <div class="accordion-item">
                          <h2 class="accordion-header" id="headingOne<?php echo $chapter['chapter_id']; ?>69">
                            <button class="accordion-button <?php if (!$hasClipsS || ($hasClipsS && $collapseHappenedS)) echo 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne<?php echo $chapter['chapter_id']; ?>69" aria-expanded="false" aria-controls="collapseOne<?php echo $chapter['chapter_id']; ?>69">
                              <?php if ($chapter['chapter_name'] == '_global') { ?>
                                Uvod
                              <?php } else { ?>
                                <?php echo $chapter['chapter_name'] ?? "error"; ?>
                              <?php } ?>
                            </button>
                          </h2>
                          <div id="collapseOne<?php echo $chapter['chapter_id']; ?>69" class="accordion-collapse ac collapse <?php if ($hasClipsS && !$collapseHappenedS) echo 'show';
                                                                                                                              ?>" aria-labelledby="headingOne<?php echo $chapter['chapter_id']; ?>69" data-bs-parent="#accordionExample69">

                            <?php foreach ($clips as $clip) : ?>
                              <div class="accordion-body ab">
                                <p data-chapter-id="<?php echo $chapter['chapter_id']; ?>" data-clip-id="<?php echo $clip['c_id']; ?>" class="m-0 card-text clip-link <?php if ($hasClipsS && !$collapseHappenedS) {
                                                                                                                                                                        echo 'active-clip';
                                                                                                                                                                        $collapseHappenedS = true;
                                                                                                                                                                      }  ?>">- <?php echo $clip['c_name'] ?? "Error"; ?></p>
                              </div>
                            <?php endforeach; ?>

                            <!-- DOLE DIV ZATVOREN OD COLLAPSE -->
                          </div>
                        </div>

                      <?php endforeach; ?>

                      <!-- ACCORDION END DOLE -->
                    </div>
                  </div>
                </div>

                <!-- CLIP DESCRIPTION -->
                <div class="card mt-4 clip-descc">
                  <div class="card-body">
                    <h5 class="card-title fw-bold">Opis klipa:</h5>
                    <hr class="hr">
                    <?php
                    $index = 0;
                    $selected = false;
                    foreach ($chapters as $chapter) :
                      $clips = Database::getInstance()->getAllClipsForChapterAndCourse($chapter['chapter_id'], $id);
                      if (empty($clips)) {
                        continue;
                      }
                      if (!$selected) {
                        $firstClipName = $clips[0]['c_id'];
                        $selected = true;
                      }
                    ?>
                      <?php foreach ($clips as $clip) : ?>
                        <p data-chapter-id="<?php echo $chapter['chapter_id']; ?>" data-clip-id="<?php echo $clip['c_id']; ?>" style="<?php if ($index++ > 0) echo 'display: none;'; ?>" class="card-text clip-description"><?php echo $clip['clip_description']; ?></p>
                      <?php endforeach; ?>
                    <?php endforeach; ?>
                  </div>
                </div>

                <!-- POSTAVI PITANJE -->
                <div class="card mt-4">
                  <div class="card-body">
                    <h5 class="card-title fw-bold">Nešto ti nije jasno? Slobodno pitaj:
                      <hr class="hr">
                    </h5>
                    <form id="contact-form" method="POST">

                      <input id="contact-name" name="firstname" type="hidden" class="form-control" value="<?php echo $_SESSION['user']->firstname . " " . $_SESSION['user']->lastname; ?>">
                      <input id="contact-email" name="email" type="hidden" class="form-control" value="<?php echo $_SESSION['user']->email ?? ""; ?>">

                      <div class="mb-4">
                        <label class="form-label"><i class="far fa-comment-dots"></i> Pitanje</label>
                        <textarea id="contact-message" name="message" class="form-control" placeholder="npr. Nisam najbolje razumeo sta si rekao u ovom delu videa..." style="height: 100px"></textarea>
                        <?php echo $errors['message'] ?? ""; ?>
                      </div>

                      <input type="hidden" id="clip-name-question" name="clip" value="<?php echo $firstClipName; ?>">
                      <input type="hidden" name="course" value="<?php echo $course['name']; ?>">

                      <button id="contact-send-btn" name="contact" type="submit" class="register-btn btn btn-primary scale-btn-2">POŠALJI <i class="fas fa-check"></i></button>
                    </form>
                  </div>
                </div>

              <?php } ?>

            <?php } ?>
          </div>

          <?php if (isset($userBought) && !$userBought) { ?>
            <div class="col-md-12 col-lg-3 mt-5 mt-lg-0 gx-sm-0 gx-md-3 pe-0 centerrow2">
              <div class="course-info-container pb-1 px-2">
                <h3 class="text-white text-center" style="word-break: break-all;"><?php echo $course['name'] ?? "Error"; ?></h3>
                <hr>
                <div class="course-description text-white text-justify px-2 mt-2 mb-4">
                  <?php echo $course['description'] ?? "Error"; ?>
                </div>
              </div>
            </div>
          <?php } else { ?>
            <div class="col-md-12 col-lg-3 ps-0 mt-md-3 mt-lg-0">
              <div class="card crd">
                <div class="card-body">
                  <h5 class="card-title fw-bold"><?php echo $course['name']; ?></h5>

                  <div class="col-auto text-white g-0 pretraga-col mb-3">

                    <div class="input-group flex-nowrap">
                      <input type="text" class="form-control pretraga-input-kurs" placeholder="Pretraži...">
                      <button class="btn yellow-btn" style="pointer-events: none;" type="button" id="button-addon2"><i class="fas fa-search"></i></button>
                    </div>

                    <div class="search-results">

                    </div>

                  </div>

                  <div class="accordion" id="accordionExample">

                    <?php
                    $collapseHappened = false;
                    $hasClips = false;
                    foreach ($chapters as $chapter) : ?>

                      <?php
                      $clips = Database::getInstance()->getAllClipsForChapterAndCourse($chapter['chapter_id'], $id);
                      if (empty($clips)) {
                        $hasClips = false;
                      } else {
                        $hasClips = true;
                      }
                      ?>

                      <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne<?php echo $chapter['chapter_id']; ?>">
                          <button class="accordion-button text-start <?php if (!$hasClips || ($hasClips && $collapseHappened)) echo 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne<?php echo $chapter['chapter_id']; ?>" aria-expanded="false" aria-controls="collapseOne<?php echo $chapter['chapter_id']; ?>">
                            <?php if ($chapter['chapter_name'] == '_global') { ?>
                              Uvod
                            <?php } else { ?>
                              <?php echo $chapter['chapter_name'] ?? "error"; ?>
                            <?php } ?>
                          </button>
                        </h2>
                        <div id="collapseOne<?php echo $chapter['chapter_id']; ?>" class="accordion-collapse ac collapse <?php if ($hasClips && !$collapseHappened) echo 'show'; ?>" aria-labelledby="headingOne<?php echo $chapter['chapter_id']; ?>" data-bs-parent="#accordionExample">


                          <?php foreach ($clips as $brojaccc => $clip) : ?>
                            <div class="accordion-body ab">
                              <p data-chapter-id="<?php echo $chapter['chapter_id']; ?>" data-clip-id="<?php echo $clip['c_id']; ?>" class="m-0 card-text clip-link <?php if ($hasClips && !$collapseHappened) echo 'active-clip';
                                                                                                                                                                    $collapseHappened = true; ?>"> <?php echo ++$brojaccc . ". " . $clip['c_name'] ?? "Error"; ?></p>
                            </div>
                          <?php endforeach; ?>

                          <!-- DOLE DIV ZATVOREN OD COLLAPSE -->
                        </div>
                      </div>

                    <?php endforeach; ?>

                    <!-- ACCORDION END DOLE -->
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
    </div>

  <?php } ?>

  </div>
  </div>
</section>





<!-- Modal -->
<div class="modal fade morate-biti-prijavljeni-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Obaveštenje <i class="fas fa-info-circle"></i></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Moraš da <strong>imaš nalog</strong> da bi mogao da pristupiš kursu.
      </div>
      <div class="modal-footer d'flex justify-content-between text-center">
        <div class="col">
          <strong><em>Već imam nalog</em></strong> <br>
          <a href="<?php echo BASE_URL . "prijava" ?>" type="button" class="btn btn-primary zutob fw-bold">Postojeći korisnik</a>
        </div>
        <div class="col robz">
          <strong><em>Nemam nalog</em></strong> <br>
          <a href="<?php echo BASE_URL . "registracija" ?>" type="button" class="btn btn-outline-secondary regiregi zutob fw-bold">Novi korisnik</a>
        </div>
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