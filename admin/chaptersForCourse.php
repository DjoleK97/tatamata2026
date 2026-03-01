<?php ob_start(); ?>

<?php require_once './header_admin.php'; ?>

<?php

if (isset($_POST['delete-chapter'])) {
  $id = clean($_POST['chapter_id']);

  if (Database::getInstance()->deleteChapter($id)) {
    $_SESSION['delete_chapter_success_message'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Brisanje poglavlja <strong>"' . clean($_POST['chapter_name']) . '"</strong> uspešno! <i class="fas fa-check"></i>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
  }
}

if (isset($_POST['update-chapter'])) {
  $course_id_edit = clean($_POST['course_id_edit']);
  $name_edit = clean($_POST['name_edit']);
  $number_edit = clean($_POST['number_edit']);

  $errors = array();

  if (empty($name_edit)) {
    $errors['name_edit'] = '<div class="mb-0 invalid-feedback">Please enter name.</div>';
  }

  if (empty($number_edit)) {
    $errors['number_edit'] = '<div class="mb-0 invalid-feedback">Please enter number.</div>';
  }

  if (count($errors) == 0) {
    $data = array(
      "course_id_edit" => $course_id_edit,
      "name_edit" => $name_edit,
      "number_edit" => $number_edit,
    );

    if (Database::getInstance()->updateChapter($data, clean($_POST['chapter_id_edit']))) {
      $_SESSION['update_chapter_success_message'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Update poglavlja <strong>"' . $name_edit . '"</strong> uspešan! <i class="fas fa-check"></i>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
      $name_edit = "";
      $number_edit = "";
    } else {
      die();
    }
  }
}

if (isset($_POST['add-chapter'])) {
  $course_id = clean($_POST['course_id']);
  $name = clean($_POST['name']);
  $number = clean($_POST['number']);

  $errors = array();

  if (empty($name)) {
    $errors['name'] = '<div class="mb-0 invalid-feedback">Please enter name.</div>';
  }

  if (empty($number)) {
    $errors['number'] = '<div class="mb-0 invalid-feedback">Please enter number.</div>';
  }

  if (count($errors) == 0) {
    $data = array(
      "course_id" => $course_id,
      "name" => $name,
      "number" => $number,
    );

    if (Database::getInstance()->addChapter($data)) {
      $_SESSION['add_chapter_success_message'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Dodavanje poglavlja <strong>"' . $name . '"</strong> uspešno! <i class="fas fa-check"></i>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
      // $course_id = 1;
      $name = "";
      $number = "";
    }
  }
}

$id = clean($_GET['course_id']);

$chapters = Database::getInstance()->getAllChaptersForCourse($id);
$courses = Database::getInstance()->getAllCourses();
$course = Database::getInstance()->getCourse($id);

?>

<div class="app-wrapper">

  <div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">

      <div class="row g-3 mb-4 align-items-center justify-content-between">
        <div class="col">
          <?php printFormatedFlashMessage("add_chapter_success_message"); ?>
          <?php printFormatedFlashMessage("delete_chapter_success_message"); ?>
          <?php printFormatedFlashMessage("update_chapter_success_message"); ?>
          <h1 class="app-page-title mb-0">Chapters for "<?php echo $course['name']; ?>"</h1>
        </div>
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
                      <th class="cell">Order</th>
                      <th class="cell">Name</th>
                      <th class="cell">Number</th>
                      <th class="cell">Num Of Clips</th>
                      <th class="cell"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($chapters as $index => $chapter) : ?>
                      <tr>
                        <td class="cell"><a href="<?php echo BASE_URL . 'admin/courses/' . $course['id'] . '/chapters/' . $chapter['chapter_id'] . "/clips"; ?>">
                            <div>#<?php echo ++$index; ?></div>
                          </a></td>
                        <td class="cell"><a href="<?php echo BASE_URL . 'admin/courses/' . $course['id'] . '/chapters/' . $chapter['chapter_id'] . "/clips"; ?>">
                            <div><?php echo $chapter['chapter_name'] ?? "Clip Name"; ?></div>
                          </a></td>
                        <td class="cell"><a href="<?php echo BASE_URL . 'admin/courses/' . $course['id'] . '/chapters/' . $chapter['chapter_id'] . "/clips"; ?>">
                            <div><?php echo $chapter['chapter_number'] ?? "Number"; ?></div>
                          </a></td>
                        <td class="cell"><a href="<?php echo BASE_URL . 'admin/courses/' . $course['id'] . '/chapters/' . $chapter['chapter_id'] . "/clips"; ?>">
                            <div><?php echo sizeof(Database::getInstance()->getAllClipsForChapterAndCourse($chapter['chapter_id'], $course['id'])); ?></div>
                          </a></td>
                        <td class="cell">

                          <?php if ($chapter['chapter_name'] != '_global') { ?>
                            <!-- Button trigger modal -->
                            <button type="button" data-bs-toggle="modal" data-bs-target="#editChapterModal<?php echo $chapter['chapter_id']; ?>" style="border: none; background-color: transparent;">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil text-warning" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175l-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                              </svg>
                            </button>

                            <!-- EDIT MODAL -->
                            <div class="modal fade" id="editChapterModal<?php echo $chapter['chapter_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Editing chapter "<?php echo $chapter['chapter_name']; ?>"</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <form method="POST" class="text-left">

                                      <div class="mb-3">
                                        <label class="form-label">Course</label>
                                        <select name="course_id_edit" class="form-select" aria-label="Default select example">
                                          <option value="<?php echo $course['id'] ?? "1"; ?>"><?php echo $course['name'] ?? "Error"; ?></option>
                                        </select>
                                      </div>

                                      <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input name="name_edit" type="text" class="form-control <?php if (isset($errors['name_edit'])) echo 'is-invalid';
                                                                                                else if (isset($name_edit) && !empty($name_edit)) echo 'is-valid'; ?>" placeholder="Enter name" value="<?php echo $chapter['chapter_name'] ?? ""; ?>">
                                        <?php echo $errors['name_edit'] ?? ""; ?>
                                      </div>

                                      <div class="mb-3">
                                        <label class="form-label">Number</label>
                                        <input name="number_edit" type="number_edit" class="form-control <?php if (isset($errors['number_edit'])) echo 'is-invalid';
                                                                                                          else if (isset($number_edit) && !empty($number_edit)) echo 'is-valid'; ?>" placeholder="Enter number" value="<?php echo $chapter['chapter_number'] ?? ""; ?>">
                                        <?php echo $errors['number_edit'] ?? ""; ?>
                                      </div>


                                      <input type="hidden" name="chapter_id_edit" value="<?php echo $chapter['chapter_id']; ?>">

                                      <button name="update-chapter" type="submit" class="btn btn-warning d-block w-100 text-white">
                                        Update<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                          <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                                        </svg>
                                      </button>

                                    </form>
                                  </div>
                                </div>
                              </div>
                            </div>

                            &nbsp;

                            <!-- Button trigger modal -->
                            <button type="button" data-bs-toggle="modal" data-bs-target="#deleteChapterModal<?php echo $chapter['chapter_id']; ?>" style="border: none; background-color: transparent;">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash text-danger" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                              </svg>
                            </button>

                            <!-- DELETE MODAL -->
                            <div class="modal fade" id="deleteChapterModal<?php echo $chapter['chapter_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title text-danger" id="exampleModalLabel">Danger <i class="fas fa-exclamation-circle"></i></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    Are you sure that you want to delete chapter <strong class="text-danger">"<?php echo $chapter['chapter_name'] ?? "Error"; ?>"</strong> ?
                                  </div>
                                  <div class="modal-footer">
                                    <form method="POST" class="d-inline">
                                      <input type="hidden" name="chapter_id" value="<?php echo $chapter['chapter_id'] ?? "-1"; ?>">
                                      <input type="hidden" name="chapter_name" value="<?php echo $chapter['chapter_name'] ?? "Error"; ?>">
                                      <button name="delete-chapter" type="submit" class="btn btn-danger text-white">Yes</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          <?php } ?>




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
          <!--//app-card-->
          <!-- <nav class="app-pagination">
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
                      <h1 class="mt-2">Add New Chapter</h1>

                      <form method="POST" class="mt-5 mb-5 text-left">

                        <div class="mb-3">
                          <label class="form-label">Course</label>
                          <select name="course_id" class="form-select" aria-label="Default select example">
                            <option value="<?php echo $course['id'] ?? "1"; ?>"><?php echo $course['name'] ?? "Error"; ?></option>
                          </select>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Name</label>
                          <input name="name" type="text" class="form-control <?php if (isset($errors['name'])) echo 'is-invalid';
                                                                              else if (isset($name) && !empty($name)) echo 'is-valid'; ?>" placeholder="Enter name" value="<?php echo $name ?? ""; ?>">
                          <?php echo $errors['name'] ?? ""; ?>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Number</label>
                          <input name="number" type="number" class="form-control <?php if (isset($errors['number'])) echo 'is-invalid';
                                                                                  else if (isset($number) && !empty($number)) echo 'is-valid'; ?>" placeholder="Enter number" value="<?php echo $number ?? ""; ?>">
                          <?php echo $errors['number'] ?? ""; ?>
                        </div>

                        <button name="add-chapter" type="submit" class="btn btn-primary d-block w-100 text-white">
                          Add<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                            <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                          </svg>
                        </button>

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


    </div>
    <!--//container-fluid-->
  </div>
  <!--//app-content-->

</div>
<!--//app-wrapper-->

<?php require_once './footer_admin.php'; ?>