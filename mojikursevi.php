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

        <div id="moji-kursevi-blocked" class="col">
          <h2 class="text-uppercase text-danger fw-bold"><i class="fas fa-ban me-2"></i> Tvoj nalog je blokiran</h2>
          &bull; Primetili smo da je na ovom nalogu prekršen dozvoljen broj uređaja sa kojim se može pristupiti profilu.<br>
          &bull; Svakom korisniku je dozvoljen pristup sa <strong>najviše 2 uređaja</strong><br>
          &bull; <strong>Uprkos prethodnom upozorenju koje ste dobili, nastavili ste da delite Vaš nalog sa drugim osobama, što je <span style='color: red;'>strogo zabranjeno</span> i krši uslove korišćenja sajta tatamata.rs</strong><br>
          &bull; Iz tog razloga je onemogućen pristup kursevima koje ste kupili.<br>
          &bull; Za sva pitanja i informacije javite se putem <a href="<?php echo BASE_URL ?>pocetna#kontakt" class="text-decoration-none kontakt-forme-link" target="_blank">kontakt forme</a>.<br>
        </div>

      <?php } else { ?>

        <?php if (isset($courses) && count($courses) == 0) { ?>
          <div class="col-lg-6 offset-lg-3 text-center">
            <div class="py-5">
            <i class="fas fa-book-open" style="font-size:3.5rem; color:var(--plava); opacity:.35;"></i>
            <h3 class="mt-4" style="color:var(--siva);">Još uvek nemate ni jedan kurs.</h3>
            <a class="text-decoration-none" href="<?php echo BASE_URL; ?>kursevi">
              <button class="mt-4 register-btn btn btn-primary">
                <i class="fas fa-graduation-cap me-2"></i> Pogledaj dostupne kurseve
              </button>
            </a>
          </div>
          </div>
        <?php } else { ?>
          <h1 class="mb-5"><i class="fas fa-play-circle me-2" style="color:var(--plava);"></i> Moji Kursevi</h1>

          <?php foreach ($courses as $course) : ?>
            <div class="course-col-container col-xl-3 col-lg-4 col-md-4 col-sm-4 col-6">
              <a href="<?php echo BASE_URL; ?>kurs/<?php echo $course['id']; ?>">
                <div class="course-img">
                  <img class="scale-btn-2" style="width: 100%;" src="<?php echo BASE_URL; ?>public/images/courses/<?php echo $course['img']; ?>" alt="Image error">
                </div>
              </a>

              <div class="course-name text-center mt-2">
                <?php echo $course['name']; ?>
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
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Obaveštenje <i class="fas fa-info-circle"></i></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Morate biti prijavljeni da biste mogli da kupite kurs.
      </div>
      <div class="modal-footer">
        <a href="<?php echo BASE_URL . "prijava" ?>" type="button" class="btn btn-primary">Prijavi se</a>
        <a href="<?php echo BASE_URL . "registracija" ?>" type="button" class="btn btn-outline-secondary">Registruj se</a>
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