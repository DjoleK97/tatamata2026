<?php ob_start(); ?>

<?php require_once './header_admin.php'; ?>

<?php

if (isset($_POST['delete-course'])) {
  $id = clean($_POST['course_id']);

  if (Database::getInstance()->deleteCourse($id)) {
    $_SESSION['delete_course_success_message'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Brisanje kursa <strong>"' . clean($_POST['course_name']) . '"</strong> uspešno! <i class="fas fa-check"></i>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
  }
}

if (isset($_POST['update-course'])) {
  $name_edit = clean($_POST['name_edit']);
  $description_edit = clean($_POST['description_edit']);
  $trailer_edit = clean($_POST['trailer_edit']);
  $price_edit = (int)clean($_POST['price_edit']);
  $price2_edit = (int)clean($_POST['price2_edit']);
  $id_edit = clean($_POST['course_id_edit']);
  $schoolTypeIDEdit = clean($_POST['school_type_id_edit']);
  $gradeIDEdit = clean($_POST['grade_id_edit']);
  $img_edit = clean($_POST['img_edit']);

  if (isset($_POST['course_live']) && $_POST['course_live'] == 'on') {
    $live_edit = 1;
  } else {
    $live_edit = 0;
  }

  $errors = array();

  if (empty($name_edit)) {
    $errors['name_edit'] = '<div class="mb-0 invalid-feedback">Please enter name.</div>';
  }

  if (empty($description_edit)) {
    $errors['description_edit'] = '<div class="mb-0 invalid-feedback">Please enter description.</div>';
  }

  if (empty($trailer_edit)) {
    $errors['trailer_edit'] = '<div class="mb-0 invalid-feedback">Please enter trailer.</div>';
  }

  if ($price_edit !== 0 && empty($price_edit)) {
    $errors['price_edit'] = '<div class="mb-0 invalid-feedback">Please enter price.</div>';
  }

  if ($price2_edit !== 0 && empty($price2_edit)) {
    $errors['price2_edit'] = '<div class="mb-0 invalid-feedback">Please enter price eur.</div>';
  }

  if (empty($img_edit)) {
    $errors['img_edit'] = '<div class="mb-0 invalid-feedback">Please enter img.</div>';
  }

  if (count($errors) == 0) {
    $data = array(
      "name_edit" => $name_edit,
      "description_edit" => $description_edit,
      "price_edit" => $price_edit,
      "price2_edit" => $price2_edit,
      "schoolTypeIDEdit" => $schoolTypeIDEdit,
      "gradeIDEdit" => $gradeIDEdit,
      "img_edit" => $img_edit,
      "live_edit" => $live_edit,
      "trailer_edit" => $trailer_edit,
    );

    if (Database::getInstance()->updateCourse($data, $id_edit)) {
      $_SESSION['add_course_success_message'] = '<div class="alert alert-warning alert-dismissible show" role="alert">
                Update kursa <strong>"' . $name_edit . '"</strong> uspešno! <i class="fas fa-check"></i>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
      $name_edit = "";
      $description_edit = "";
      $price_edit = "";
      $img_edit = "";
      $trailer_edit = "";
    }
  } else {
    $_SESSION['update_course_fail_message'] = '<div class="alert alert-danger alert-dismissible show" role="alert">
                Update kursa <strong>"' . $name_edit . '"</strong> neuspešno!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
  }
}

if (isset($_POST['add-course'])) {
  $name = clean($_POST['name']);
  $description = clean($_POST['description']);
  $trailer = clean($_POST['trailer']);
  $price = (int)clean($_POST['price']);
  $price2 = (int)clean($_POST['price2']);
  $schoolTypeID = clean($_POST['school_type_id']);
  $gradeID = clean($_POST['grade_id']);
  // $img = clean($_POST['img']);

  $errors = array();

  if (empty($name)) {
    $errors['name'] = '<div class="mb-0 invalid-feedback">Please enter name.</div>';
  }

  if (empty($description)) {
    $errors['description'] = '<div class="mb-0 invalid-feedback">Please enter description.</div>';
  }

  if (empty($trailer)) {
    $errors['trailer'] = '<div class="mb-0 invalid-feedback">Please enter trailer.</div>';
  }

  // if (empty($img)) {
  //   $errors['img'] = '<div class="mb-0 invalid-feedback">Please enter img.</div>';
  // }

  if ($price !== 0 && empty($price)) {
    $errors['price'] = '<div class="mb-0 invalid-feedback">Please enter price.</div>';
  }

  if ($price2 !== 0 && empty($price2)) {
    $errors['price2'] = '<div class="mb-0 invalid-feedback">Please enter price eur.</div>';
  }

  if (count($errors) == 0) {
    $file = $_FILES['upload'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    $destination_path = getcwd() . DIRECTORY_SEPARATOR;

    $file_ext = explode('.', $file_name);
    $file_ext = strtolower(end($file_ext));

    $file_name_new = $file_name;
    $file_destination = dirname(__FILE__, 2) . '/public/images/courses/' . $file_name_new;

    $uploadOk = 1;

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
      // if everything is ok, try to upload file
    } else {
      if (move_uploaded_file($file_tmp, $file_destination)) {
        // echo "The file " . htmlspecialchars(basename($_FILES["upload"]["name"])) . " has been uploaded.";

        $data = array(
          "name" => $name,
          "description" => $description,
          "price" => $price,
          "price2" => $price2,
          "schoolTypeID" => $schoolTypeID,
          "gradeID" => $gradeID,
          "img" => $file_name,
          "trailer" => $trailer,
        );

        if (Database::getInstance()->addCourse($data)) {
          $_SESSION['add_course_success_message'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Dodavanje kursa <strong>"' . $name . '"</strong> uspešno! <i class="fas fa-check"></i>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';

          $name = "";
          $description = "";
          $price = "";
          $price2 = "";
          $img = "";
          $trailer = "";
        }
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    }
  }
}

$courses = Database::getInstance()->getAllCourses();
$schoolTypes = Database::getInstance()->getAllSchoolTypes();
$grades = Database::getInstance()->getAllGradesForSchoolType($schoolTypeID ?? $schoolTypes[0]["school_type_id"]);

?>

<div class="app-wrapper">

  <div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">
      <div class="row g-3 mb-4 align-items-center justify-content-between">
        <div class="col">
          <?php printFormatedFlashMessage("add_course_success_message"); ?>
          <?php printFormatedFlashMessage("update_course_fail_message"); ?>
          <?php printFormatedFlashMessage("delete_course_success_message"); ?>
          <h1 class="app-page-title mb-0">My Courses</h1>
        </div>
        <!--//col-auto-->
      </div>
      <!--//row-->


      <div class="tab-content" id="orders-table-tab-content">
        <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
          <div class="app-card app-card-orders-table shadow-sm mb-5">
            <div class="app-card-body">
              <div class="table-responsive">
                <table class="table app-table-hover mb-0 text-left">
                  <thead>
                    <tr>
                      <th class="cell">Couse ID</th>
                      <th class="cell">Name</th>
                      <th class="cell">School Type</th>
                      <th class="cell">Grade</th>
                      <th class="cell">Description</th>
                      <th class="cell">Trailer</th>
                      <th class="cell">Price</th>
                      <th class="cell">Price 2</th>
                      <th class="cell">Live</th>
                      <th class="cell"></th>
                      <th class="cell"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($courses as $index => $course) : ?>
                      <tr>
                        <td class="cell"><?php echo $course['id']; ?></td>
                        <td class="cell"><span class="truncate"><?php echo $course['name'] ?? "Error"; ?></span></td>
                        <td class="cell"><span class="truncate"><?php echo $course['school_type_name'] ?? "Error"; ?></span></td>
                        <td class="cell"><span class="truncate"><?php echo $course['grade_name'] ?? "Error"; ?></span></td>
                        <td class="cell"><?php echo $course['description'] ?? "Clip Name"; ?></td>
                        <td class="cell"><?php echo $course['trailer'] ?? "Clip Name"; ?></td>
                        <td class="cell"><?php echo $course['price'] ?? "Number"; ?></td>
                        <td class="cell"><?php echo $course['price2'] ?? "Number"; ?></td>
                        <td class="cell"><?php echo $course['live'] ?? "Number"; ?></td>
                        <td class="cell p-0">

                          <!-- Button trigger modal -->
                          <button type="button" data-bs-toggle="modal" data-bs-target="#updateCourseModal<?php echo $course['id']; ?>" style="border: none; background-color: transparent;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil text-warning" viewBox="0 0 16 16">
                              <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175l-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                            </svg>
                          </button>

                          <!-- UPDATE Modal -->
                          <div class="modal fade" id="updateCourseModal<?php echo $course['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Updating course "<?php echo $course['name']; ?>"</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <form method="POST" class="text-left">

                                    <div class="mb-3">
                                      <label class="form-label">Name</label>
                                      <input name="name_edit" type="text" class="form-control <?php if (isset($errors['name_edit'])) echo 'is-invalid';
                                                                                              else if (isset($name_edit) && !empty($name_edit)) echo 'is-valid'; ?>" placeholder="Enter name" value="<?php echo $course['name'] ?? ""; ?>">
                                      <?php echo $errors['name_edit'] ?? ""; ?>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label">School Type</label>
                                      <select data-course-id-sc="<?php echo $course['id']; ?>" name="school_type_id_edit" class="form-select" aria-label="Default select example">
                                        <?php foreach ($schoolTypes as $schoolType) : ?>
                                          <option value="<?php echo $schoolType['school_type_id'] ?? "1"; ?>" <?php if ($schoolType['school_type_id'] == $course['school_type_id']) echo 'selected'; ?>><?php echo $schoolType['school_type_name'] ?? "Error"; ?></option>
                                        <?php endforeach; ?>
                                      </select>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label">Grade</label>
                                      <select data-course-id-gd="<?php echo $course['id']; ?>" name="grade_id_edit" class="form-select" aria-label="Default select example">
                                        <?php $gradesG = Database::getInstance()->getAllGradesForSchoolType($course['school_type_id']); ?>
                                        <?php foreach ($gradesG as $grade) : ?>
                                          <?php if ($grade['grade_school_type_id' == 1] && $grade['grade_id'] <= 4) { ?>
                                            <?php continue; ?>
                                          <?php } ?>
                                          <option value="<?php echo $grade['grade_id'] ?? "1"; ?>" <?php if ($grade['grade_id'] == $course['grade_id']) echo 'selected'; ?>><?php echo $grade['grade_name'] ?? "Error"; ?></option>
                                        <?php endforeach; ?>
                                      </select>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label">Image</label>
                                      <input name="img_edit" type="text" class="form-control <?php if (isset($errors['img_edit'])) echo 'is-invalid';
                                                                                              else if (isset($img_edit) && !empty($img_edit)) echo 'is-valid'; ?>" placeholder="Enter img" value="<?php echo $course['img'] ?? ""; ?>">
                                      <?php echo $errors['img_edit'] ?? ""; ?>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label">Description</label>
                                      <textarea name="description_edit" id="" cols="30" class="form-control text-left <?php if (isset($errors['description_edit'])) echo 'is-invalid';
                                                                                                                      else if (isset($description_edit) && !empty($description_edit)) echo 'is-valid'; ?>" placeholder="Enter description" style="height: 150px;"><?php echo $course['description'] ?? ""; ?></textarea>
                                      <?php echo $errors['description_edit'] ?? ""; ?>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label">Trailer</label>
                                      <input name="trailer_edit" type="text" class="form-control <?php if (isset($errors['trailer_edit'])) echo 'is-invalid';
                                                                                                  else if (isset($trailer_edit) && !empty($trailer_edit)) echo 'is-valid'; ?>" placeholder="Enter trailer" value="<?php echo $course['trailer'] ?? ""; ?>">
                                      <?php echo $errors['trailer_edit'] ?? ""; ?>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label">Price</label>
                                      <input name="price_edit" type="number" class="form-control <?php if (isset($errors['price_edit'])) echo 'is-invalid';
                                                                                                  else if (isset($price_edit) && !empty($price_edit)) echo 'is-valid'; ?>" placeholder="Enter price" value="<?php echo $course['price'] ?? ""; ?>">
                                      <?php echo $errors['price_edit'] ?? ""; ?>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label">Price EUR</label>
                                      <input name="price2_edit" type="number" class="form-control <?php if (isset($errors['price2_edit'])) echo 'is-invalid';
                                                                                                  else if (isset($price2_edit) && !empty($price2_edit)) echo 'is-valid'; ?>" placeholder="Enter price eur" value="<?php echo $course['price2'] ?? ""; ?>">
                                      <?php echo $errors['price2_edit'] ?? ""; ?>
                                    </div>

                                    <div class="form-check form-switch">
                                      <input class="form-check-input" type="checkbox" name="course_live" id="flexSwitchCheckChecked" <?php if ($course['live']) echo "checked"; ?>>
                                      <label class="form-check-label" for="flexSwitchCheckChecked">Course live</label>
                                    </div>

                                    <input type="hidden" name="course_id_edit" value="<?php echo $course['id']; ?>">

                                    <button name="update-course" type="submit" class="btn btn-warning d-block w-100 text-white">Update<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                        <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                                      </svg></button>

                                  </form>
                                </div>
                              </div>
                            </div>
                          </div>

                        </td>

                        <td class="cell p-0">
                          <!-- Button trigger modal -->
                          <button type="button" class="btn-sm btn-danger text-white" data-bs-toggle="modal" data-bs-target="#deleteCourseModal<?php echo $course['id']; ?>" style="border: none; background-color: transparent;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash text-danger" viewBox="0 0 16 16">
                              <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                              <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                            </svg>
                          </button>

                          <!-- DELETE Modal -->
                          <div class="modal fade" id="deleteCourseModal<?php echo $course['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title text-danger" id="exampleModalLabel">Danger <i class="fas fa-exclamation-circle"></i></h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  Are you sure that you want to delete course <strong class="text-danger">"<?php echo $course['name'] ?? "Error"; ?>"</strong> ?
                                </div>
                                <div class="modal-footer">
                                  <form method="POST" class="d-inline">
                                    <input type="hidden" name="course_id" value="<?php echo $course['id'] ?? "-1"; ?>">
                                    <input type="hidden" name="course_name" value="<?php echo $course['name'] ?? "Error"; ?>">
                                    <button name="delete-course" type="submit" class="btn btn-danger text-white">Yes</button>
                                  </form>
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                              </div>
                            </div>
                          </div>

                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <!--//table-responsive-->
            </div>
            <!--//app-card-body-->
          </div>
        </div>
        <!--//tab-pane-->
      </div>
      <!--//tab-content-->

      <div class="row mt-5 text-center">
        <div class="col">
          <div class="collapse show" id="collapseExample">
            <div class="card card-body">

              <section id="prijave">
                <div class="container">
                  <div class="row justify-content-center">
                    <div class="col-md-6 col-sm-8 col-8">
                      <h1 class="mt-2">Add New Course</h1>

                      <?php echo $errors['taken_email'] ?? ""; ?>
                      <?php echo $errors['taken_username'] ?? ""; ?>

                      <form method="POST" class="mt-5 mb-5 text-left" enctype="multipart/form-data">

                        <div class="mb-3">
                          <label class="form-label">Name</label>
                          <input name="name" type="text" class="form-control <?php if (isset($errors['name'])) echo 'is-invalid';
                                                                              else if (isset($name) && !empty($name)) echo 'is-valid'; ?>" placeholder="Enter name" value="<?php echo $name ?? ""; ?>">
                          <?php echo $errors['name'] ?? ""; ?>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">School Type</label>
                          <select id="school-type" name="school_type_id" class="form-select" aria-label="Default select example">
                            <?php foreach ($schoolTypes as $schoolType) : ?>
                              <option value="<?php echo $schoolType['school_type_id'] ?? "1"; ?>" <?php if (isset($schoolTypeID) && $schoolType['school_type_id'] == $schoolTypeID) echo 'selected'; ?>><?php echo $schoolType['school_type_name'] ?? "Error"; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Grade</label>
                          <select id="grades-select" name="grade_id" class="form-select" aria-label="Default select example">
                            <?php foreach ($grades as $grade) : ?>
                              <?php if ($grade['grade_school_type_id' == 1] && $grade['grade_id'] <= 4) { ?>
                                <?php continue; ?>
                              <?php } ?>
                              <option value="<?php echo $grade['grade_id'] ?? "1"; ?>" <?php if (isset($grade_id) && $grade['grade_id'] == $grade_id) echo 'selected'; ?>><?php echo $grade['grade_name'] ?? "Error"; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Upload image</label> <br>
                          <input required name="upload" type="file">
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Description</label>
                          <textarea name="description" id="" cols="30" class="form-control text-left <?php if (isset($errors['description'])) echo 'is-invalid';
                                                                                                      else if (isset($description) && !empty($description)) echo 'is-valid'; ?>" placeholder="Enter description" style="height: 100px;"><?php echo $description ?? ""; ?></textarea>
                          <?php echo $errors['description'] ?? ""; ?>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Trailer</label>
                          <input name="trailer" type="text" class="form-control <?php if (isset($errors['trailer'])) echo 'is-invalid';
                                                                                else if (isset($trailer) && !empty($trailer)) echo 'is-valid'; ?>" placeholder="Enter trailer" value="<?php echo $trailer ?? ""; ?>">
                          <?php echo $errors['trailer'] ?? ""; ?>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Price</label>
                          <input name="price" type="number" class="form-control <?php if (isset($errors['price'])) echo 'is-invalid';
                                                                                else if (isset($price) && !empty($price)) echo 'is-valid'; ?>" placeholder="Enter price" value="<?php echo $price ?? ""; ?>">
                          <?php echo $errors['price'] ?? ""; ?>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Price EUR</label>
                          <input name="price2" type="number" class="form-control <?php if (isset($errors['price2'])) echo 'is-invalid';
                                                                                  else if (isset($price2) && !empty($price2)) echo 'is-valid'; ?>" placeholder="Enter price eur" value="<?php echo $price2 ?? ""; ?>">
                          <?php echo $errors['price2'] ?? ""; ?>
                        </div>

                        <button name="add-course" type="submit" class="btn btn-primary d-block w-100 text-white">Create</button>

                      </form>
                    </div>
                  </div>
                </div>
              </section>
              <!-- -------- REGISTRACIJA ---------- -->
            </div>
          </div>
        </div>
      </div>
      <!--//row-->
      <!-- <nav class="app-pagination mt-5">
          <ul class="pagination justify-content-center">
            <li class="page-item disabled">
              <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
              <a class="page-link" href="#">Next</a>
            </li>
          </ul>
        </nav> -->
      <!--//app-pagination-->
    </div>
    <!--//container-fluid-->
  </div>
  <!--//app-content-->


</div>
<!--//app-wrapper-->


<?php require_once './footer_admin.php'; ?>