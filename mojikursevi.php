<?php
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
  //   $_SESSION['unauthorized_access'] = '<div class="container-fluid">
  //   <div class="row">
  //     <div class="col-md-10 col-sm-10 offset-sm-1 offset-md-1 p-0 mt-5">
  //       <div class="alert alert-danger alert-dismissible fade show" role="alert">
  //         <strong>Greška!</strong> Zabranjen pristup! <i class="fas fa-exclamation-triangle"></i>
  //         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
  //       </div>
  //     </div>
  //   </div>
  // </div>';
  header("Location: " . BASE_URL . 'prijava');

  exit;
}

if (isset($_POST['buy-course'])) {
  $course_id = clean($_POST['course_id']);
  $user_id = $_SESSION['user']->id;
  $courseName = clean($_POST['course_name']);

  $data = array(
    "course_id" => $course_id,
    "user_id" => $user_id,
  );

  if (!Database::getInstance()->userCourseExists($data) && Database::getInstance()->insertUserCourseConfirmed($data)) {
    $_SESSION['buy_course_success_message'] = '<div class="alert alert-warning alert-dismissible show" role="alert">
    <strong>Kupovina uspešna! <i class="fas fa-check"></i></strong><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
  </div>';
  }
}

$courses = Database::getInstance()->getAllCoursesForUser($_SESSION['user']->id);
?>

<?php include_once 'includes/header.php'; ?>


<section id="moji-kursevi">

  <div class="container">
    <div class="row">

      <?php printFormatedFlashMessage("buy_course_success_message"); ?>
      <?php printFormatedFlashMessage("login_success_message"); ?>

      <?php if (Database::getInstance()->isUserBlocked($_SESSION['user']->id)) { ?>

        <div id="moji-kursevi-blocked" class="col animiraj">
          <h2><i class="fas fa-ban me-2"></i> Tvoj nalog je blokiran</h2>
          <p>&bull; Primetili smo da je na ovom nalogu prekoračen dozvoljen broj uređaja sa kojih se može pristupiti profilu.</p>
          <p>&bull; Svakom korisniku je dozvoljen pristup sa <strong>najviše 2 uređaja</strong></p>
          <p>&bull; <strong>Uprkos prethodnom upozorenju koje ste dobili, nastavili ste da delite Vaš nalog sa drugim osobama, što je <span style='color: red;'>strogo zabranjeno</span> i krši uslove korišćenja sajta tatamata.rs</strong></p>
          <p>&bull; Iz tog razloga je onemogućen pristup kursevima koje ste kupili.</p>
          <p>&bull; Za sva pitanja i informacije javite se putem <a href="<?php echo BASE_URL ?>pocetna#kontakt" class="text-decoration-none kontakt-forme-link" target="_blank">kontakt forme</a>.</p>
        </div>

      <?php } else { ?>

        <?php if (isset($courses) && count($courses) == 0) { ?>
          <div class="col-lg-6 offset-lg-3 text-center animiraj">
            <div class="prazno-stanje">
              <i class="fas fa-book-open prazno-ikona"></i>
              <h3>Nemaš nijedan kurs</h3>
              <p>Pogledaj ponudu kurseva i počni sa učenjem.</p>
              <a href="<?php echo BASE_URL; ?>kursevi" class="btn btn-primary">
                <i class="fas fa-graduation-cap me-2"></i> Pogledaj kurseve
              </a>
            </div>
          </div>
        <?php } else { ?>
          <div class="col-12 mb-4 animiraj">
            <h1><i class="fas fa-play-circle me-2" style="color:var(--plava);"></i> Moji kursevi</h1>
          </div>

          <?php foreach ($courses as $course) : ?>
            <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6 mb-4 animiraj">
              <div class="course-col-container">
                <a href="<?php echo BASE_URL; ?>kurs/<?php echo $course['id']; ?>">
                  <div class="course-img">
                    <img style="width: 100%;" src="<?php echo BASE_URL; ?>public/images/courses/<?php echo $course['img']; ?>" alt="<?php echo htmlspecialchars($course['name']); ?>">
                  </div>
                </a>
                <div class="course-name text-center mt-2">
                  <?php echo $course['name']; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php } ?>

      <?php } // else end
      ?>

    </div>
  </div>
</section>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-info-circle me-2" style="color:var(--plava);"></i> Obavestenje</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Morate biti prijavljeni da biste mogli da kupite kurs.
      </div>
      <div class="modal-footer justify-content-center" style="gap:12px;">
        <a href="<?php echo BASE_URL . "prijava" ?>" class="btn btn-primary"><i class="fas fa-sign-in-alt me-2"></i> Prijavi se</a>
        <a href="<?php echo BASE_URL . "registracija" ?>" class="btn btn-outline-plava"><i class="fas fa-user-plus me-2"></i> Kreiraj nalog</a>
      </div>
    </div>
  </div>
</div>

<?php if (isset($_SESSION['warning_modal']) && !$_SESSION['user']->warned) { ?>
  <!-- Modal WARNING -->
  <div class="modal fade" id="exampleModalw" tabindex="-1" aria-labelledby="exampleModalLabelw" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger fw-bold" id="exampleModalLabelw">UPOZORENJE</h5>
        </div>
        <div class="modal-body">
          <ul>
            <li>Podsećamo Vas da je pristup nalogu dozvoljen sa <strong>najviše 2 različita uređaja.</strong></li>
            <li>Ovim prijavljivanjem ste prekršili dozvoljen broj uređaja.</li>
            <li>Svaki korisnički nalog je namenjen da se koristi isključivo od strane <strong>samo jedne osobe i ne sme se deliti sa drugim osobama.</strong></li>
            <li>Nepoštovanjem ovog upozorenja, tj. ukoliko dodate barem još jedan <strong>NOVI</strong> uređaj, <span class="text-danger fw-bold">pristup kursevima će Vam biti trajno onemogućen!</span></li>
          </ul>
          <input style="transform: scale(1.5);" type="checkbox" id="horns" name="horns"> &nbsp;<strong>Pročitao sam i razumeo upozorenje.</strong>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary razumembtn" disabled data-bs-dismiss="modal">Razumem</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    let modalw = new bootstrap.Modal(document.getElementById('exampleModalw'), {
      keyboard: false,
      backdrop: 'static'
    });

    modalw.show();

    let razumemBtn = document.querySelector(".razumembtn");
    let horns = document.getElementById('horns');

    razumemBtn.addEventListener('click', () => {
      let xhr = new XMLHttpRequest();
      let params = 'razumem=1';

      xhr.open('POST', 'ajax.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

      xhr.onload = () => { // If we use lambda expression we can't use this.status
        if (xhr.status == 200) {
          console.log(xhr.responseText);
        }
      }

      xhr.onerror = () => {
        console.log('Request Error.');
      }

      xhr.send(params);
    });

    horns.addEventListener('click', () => {
      if (horns.checked) {
        razumemBtn.disabled = false;
        razumemBtn.style.backgroundColor = "#3245f9";
        razumemBtn.style.color = "#ffffff";
        razumemBtn.style.fontWeight = "bold";
      } else {
        razumemBtn.disabled = true;
        razumemBtn.style.backgroundColor = "#6c757d";
        razumemBtn.style.color = "#fff";
        razumemBtn.style.fontWeight = "normal";
      }
    });
  </script>

<?php } ?>



<?php include_once 'includes/footer.php'; ?>