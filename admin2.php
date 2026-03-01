<?php

require_once "includes/functions.php";
require_once "classes/Database.php";

$loggedIn = Database::getInstance()->isUserLoggedIn();

if (!$loggedIn || ($loggedIn && !$_SESSION['user']->is_admin)) {
  header("Location: pocetna");

  exit;
}

$courses = Database::getInstance()->getAllCourses();

if (isset($_POST['buy-course'])) {
  $course_id = clean($_POST['course_id']);
  $user_id = $_SESSION['user']->id;

  $data = array(
    "course_id" => $course_id,
    "user_id" => $user_id,
  );

  if (Database::getInstance()->buyCourse($data)) {
    $_SESSION['buy_course_success_message'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Potvrda uspešna! <i class="fas fa-check"></i></strong> Admin će proveriti vašu uplatu i odobriće Vam klipove nakon provere.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
  }
}

?>

<?php include_once 'includes/header.php'; ?>

<section id="kursevi" class="mt-5">
  <div class="container">
    <div class="row">

      <?php printFormatedFlashMessage("buy_course_success_message"); ?>

      <h1>Kursevi</h1>

      <?php foreach ($courses as $course) : ?>

        <div class="col-md-4 mt-4">
          <div class="card">
            <div class="card-body">

              <h5 class="card-title"><?php echo $course['name'] ?? "Error"; ?></h5>
              <h6 class="card-subtitle mb-2 text-muted"><?php echo $course['price'] ?? "-1"; ?> RSD</h6>
              <p class="card-text"><?php echo $course['description'] ?? "Opis"; ?>.</p>
              <a href="kurs/<?php echo $course['id']; ?>" class="card-link">Detaljnije</a>

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
                  <!-- Znaci da nije vec narucio kurs -->
                  <a type="button" class="card-link" data-bs-toggle="modal" data-bs-target="#kupiModal<?php echo $course['id'] ?? "1"; ?>">
                    Kupi
                  </a>
                <?php } ?>

              <?php } else { ?>
                <!-- Znaci da nije ulogovan i neka ga vodi na modal -->
                <a type="button" class="card-link" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  Kupi
                </a>
              <?php } ?>

              <!-- KUPI MODAL -->
              <div class="modal fade" id="kupiModal<?php echo $course['id'] ?? "1"; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Kako popuniti uplatnicu <i class="fas fa-info-circle"></i></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      Uplatilac: <?php echo $_SESSION['user']->username; ?><br>
                      Svrha uplate: Kupovina kursa <?php echo $course['name'] ?? "Error"; ?><br>
                      Primalac: <?php echo "Djordje Karagaca" ?><br>
                      Broj racuna: <?php echo "840-002516125-856" ?><br>
                      Cena: <?php echo $course['price'] ?? "-1"; ?> RSD<br><br>
                      Nakon što popunite uplatnicu i izvršite uplatu u pošti ili banci
                      kliknite na dugme POTVRDI UPLATU, nakon toga sačekajte da
                      Vam admin odobri klipove.
                    </div>
                    <div class="modal-footer">
                      <form method="POST">
                        <input type="hidden" name="course_id" value="<?php echo $course['id'] ?? '-1'; ?>">
                        <input name="buy-course" type="submit" class="btn btn-primary" value="Potvrdi uplatu">
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <!-- KUPI MODAL -->

            </div>
          </div>
        </div>

      <?php endforeach; ?>

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

<?php include_once 'includes/footer.php'; ?>