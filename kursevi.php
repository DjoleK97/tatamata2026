<?php

require_once "includes/functions.php";
require_once "classes/Database.php";


if (isset($_POST['buy-course'])) {
  $course_id = clean($_POST['course_id']);
  $user_id = $_SESSION['user']->id;

  $data = array(
    "course_id" => $course_id,
    "user_id" => $user_id,
  );

  if (Database::getInstance()->buyCourse($data)) {
    $_SESSION['buy_course_success_message'] = '<div class="alert alert-warning alert-dismissible show" role="alert">
    <strong>Potvrda uspešna! <i class="fas fa-check"></i></strong> Admin će proveriti vašu uplatu i odobriće Vam klipove nakon provere.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
  </div>';
  }
}

$courses = Database::getInstance()->getAllCourses();
$grades = Database::getInstance()->getAllGrades();

?>

<?php include_once 'includes/header.php'; ?>

<section id="kursevi">
  <div class="container-fluid px-4">

    <?php printFormatedFlashMessage("buy_course_success_message"); ?>
    <?php printFormatedFlashMessage("register_success_message"); ?>
    <?php printFormatedFlashMessage("unauthorized_access"); ?>
    <?php printFormatedFlashMessage("login_success_message"); ?>

    <!-- Page Header -->
    <div class="stranica-header animiraj">
      <h1><i class="fas fa-graduation-cap"></i> Kursevi</h1>
      <p>Izaberi nivo obrazovanja i pronadji kurs koji ti treba.</p>
    </div>

    <!-- Filter Toolbar -->
    <div class="lewrapper animiraj">
      <div class="col-auto skola-container">
        <div id="osnovna-btn" class="skola col-auto d-inline-block <?php if (!Database::getInstance()->isUserLoggedIn()) echo 'active';
                                                                    else if ($_SESSION['user']->school_type_id != "2" && $_SESSION['user']->school_type_id != '3') echo "active"; ?>">
          Osnovna skola
        </div>
        <div id="srednja-btn" class="skola col-auto d-inline-block <?php if (Database::getInstance()->isUserLoggedIn() && $_SESSION['user']->school_type_id == "2") echo "active"; ?>">
          Srednja skola
        </div>
        <div id="fakultet-btn" class="skola col-auto d-inline-block <?php if (Database::getInstance()->isUserLoggedIn() && $_SESSION['user']->school_type_id == "3") echo "active"; ?>">
          Fakultet
        </div>
      </div>

      <div class="col-auto text-white ms-auto g-0 pretraga-col" style="width: 300px;">
        <div class="input-group flex-nowrap">
          <input type="text" class="form-control pretraga-input" placeholder="Pretrazi kurseve...">
          <button class="btn yellow-btn" style="pointer-events: none;" type="button" id="button-addon2"><i class="fas fa-search"></i></button>
        </div>
        <div class="search-results w-100">
        </div>
      </div>
    </div>

    <!-- Kursevi po razredima -->
    <?php foreach ($grades as $grade) : ?>
      <?php if ($grade['grade_school_type_id' == 1] && $grade['grade_id'] <= 4) { ?>
        <?php continue; ?>
      <?php } ?>
      <div class="row mt-4 animiraj <?php if ($grade['grade_school_type_id'] == 1) echo "osnovnaskoladiv";
                                    else if ($grade['grade_school_type_id'] == 2) echo 'srednjaskoladiv';
                                    else echo 'fakultetskoladiv'; ?>">
        <div class="col-12 g-0 d-flex mb-4">
          <div class="grade-name col-auto me-3 mt-1">
            <?php if (isset($_SESSION['user'])) { ?>
              <?php if (strtolower($_SESSION['user']->country) == 'cg' && strtolower($grade['grade_name']) != 'mala matura') { ?>
                <?php echo $grade['grade_name'] ?? "Error"; ?>
              <?php } else if (strtolower($_SESSION['user']->country) == 'srbija' && strtolower($grade['grade_name']) != 'crna gora') { ?>
                <?php echo $grade['grade_name'] ?? "Error"; ?>
              <?php } else { ?>
                <?php echo $grade['grade_name'] ?? "Error"; ?>
              <?php } ?>
            <?php } else { ?>
              <?php echo $grade['grade_name'] ?? "Error"; ?>
            <?php } ?>
          </div>
          <div class="col">
            <hr class="custom-hr">
          </div>
        </div>
        <!-- OWL CAROSEL -->
        <section id="kursevi-area">
          <div class="owl-carousel owl-theme">
            <?php foreach ($courses as $course) : ?>
              <?php if ($course['grade_id'] == $grade['grade_id']) { ?>
                <div class="item">
                  <div class="course-col-container">
                    <a href="<?php echo BASE_URL; ?>kurs/<?php echo $course['id']; ?>">
                      <div class="course-img">
                        <?php if ($course['live']) { ?>
                          <img class="scale-btn-2" style="width: 100%;" src="<?php echo BASE_URL; ?>public/images/courses/<?php echo $course['img']; ?>" alt="<?php echo htmlspecialchars($course['name']); ?>">
                        <?php } else { ?>
                          <img class="scale-btn-2" style="width: 100%;" src="<?php echo BASE_URL; ?>public/images/upripremi.png" alt="U pripremi">
                        <?php } ?>
                      </div>
                    </a>
                    <div class="course-name text-center mt-2">
                      <?php echo $course['name']; ?>
                    </div>
                  </div>
                </div>
              <?php } ?>
            <?php endforeach; ?>
          </div>
        </section>
        <!-- OWL CAROSEL -->
      </div>
    <?php endforeach; ?>

  </div>
</section>

<?php include_once 'includes/footer.php'; ?>
