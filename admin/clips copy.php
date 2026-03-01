<?php ob_start(); ?>

<?php require_once './header_admin.php'; ?>

<?php

if (isset($_POST['delete-clip'])) {
  $id = clean($_POST['clip_id']);

  if (Database::getInstance()->deleteClip($id)) {
    $_SESSION['delete_clip_success_message'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Brisanje klipa <strong>"' . clean($_POST['clip_name']) . '"</strong> uspešno! <i class="fas fa-check"></i>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
  }
}

if (isset($_POST['update-clip'])) {
  $course_id_edit = clean($_POST['course_id_edit']);
  $chapter_id_edit = clean($_POST['chapter_id_edit']);
  $name_edit = clean($_POST['name_edit']);
  $number_edit = clean($_POST['number_edit']);
  $link_edit = clean($_POST['link_edit']);
  $description_edit = clean($_POST['description_edit']);

  $errors = array();

  if (empty($name_edit)) {
    $errors['name_edit'] = '<div class="mb-0 invalid-feedback">Please enter name.</div>';
  }

  if (empty($number_edit)) {
    $errors['number_edit'] = '<div class="mb-0 invalid-feedback">Please enter number.</div>';
  }

  if (empty($link_edit)) {
    $errors['link_edit'] = '<div class="mb-0 invalid-feedback">Please enter link.</div>';
  }

  if (empty($description_edit)) {
    $errors['description_edit'] = '<div class="mb-0 invalid-feedback">Please enter description.</div>';
  }


  if (count($errors) == 0) {
    $data = array(
      "course_id_edit" => $course_id_edit,
      "chapter_id_edit" => $chapter_id_edit,
      "name_edit" => $name_edit,
      "number_edit" => $number_edit,
      "link_edit" => $link_edit,
      "description_edit" => $description_edit,
    );

    if (Database::getInstance()->updateClip($data, clean($_POST['clip_id_edit']))) {
      $_SESSION['update_clip_success_message'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Update klipa <strong>"' . $name_edit . '"</strong> uspešan! <i class="fas fa-check"></i>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
      $name_edit = "";
      $number_edit = "";
      $link_edit = "";
      $description_edit = "";
    } else {
      die();
    }
  }
}

if (isset($_POST['add-clip'])) {
  $course_id = clean($_POST['course_id']);
  $chapter_id = clean($_POST['chapter_id']);
  $name = clean($_POST['name']);
  $number = clean($_POST['number']);
  $link = clean($_POST['link']);
  $description = clean($_POST['description']);

  $errors = array();

  if (empty($name)) {
    $errors['name'] = '<div class="mb-0 invalid-feedback">Please enter name.</div>';
  }

  if (empty($number)) {
    $errors['number'] = '<div class="mb-0 invalid-feedback">Please enter number.</div>';
  }

  // if (empty($link)) {
  //   $errors['link'] = '<div class="mb-0 invalid-feedback">Please enter link.</div>';
  // }

  if (empty($description)) {
    $errors['description'] = '<div class="mb-0 invalid-feedback">Please enter description.</div>';
  }


  if (count($errors) == 0) {
    if (!empty($_FILES['upload']['name'])) { // Da li je poslao neke fajlove
      $file = $_FILES['upload'];
      $file_name = $file['name'];
      $file_tmp = $file['tmp_name'];
      $file_size = $file['size'];
      $file_error = $file['error'];

      $destination_path = getcwd() . DIRECTORY_SEPARATOR;

      $file_ext = explode('.', $file_name);
      $file_ext = strtolower(end($file_ext));

      $file_name_new = $file_name;
      $file_destination = dirname(__FILE__, 2) . '/videos/' . $file_name_new;

      if (move_uploaded_file($file_tmp, $file_destination)) {
        // echo "The file " . htmlspecialchars(basename($_FILES["upload"]["name"])) . " has been uploaded.";

        $data = array(
          "course_id" => $course_id,
          "chapter_id" => $chapter_id,
          "name" => $name,
          "number" => $number,
          "link" => $file_name,
          "description" => $description,
        );

        if (Database::getInstance()->addClip($data)) {
          $_SESSION['add_clip_success_message'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Dodavanje klipa <strong>"' . $name . '"</strong> uspešno! <i class="fas fa-check"></i>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
          // $course_id = 1;
          $name = "";
          $number = "";
          // $link = "";
          $description = "";
        }
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    } else {

      // NIJE POSLAO FAJL

      $data = array(
        "course_id" => $course_id,
        "chapter_id" => $chapter_id,
        "name" => $name,
        "number" => $number,
        "link" => $link,
        "description" => $description,
      );

      if (Database::getInstance()->addClip($data)) {
        $_SESSION['add_clip_success_message'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                  Dodavanje klipa <strong>"' . $name . '"</strong> uspešno! <i class="fas fa-check"></i>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        // $course_id = 1;
        $name = "";
        $number = "";
        $link = "";
        $description = "";
      }
    }
  }
}

$clips = Database::getInstance()->getAllClips();
$courses = Database::getInstance()->getAllCourses();
if (!empty($courses)) {
  $chapters = Database::getInstance()->getAllChaptersForCourse($courses[0]['id']);
}

?>

<div class="app-wrapper">

  <div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">

      <div class="row g-3 mb-4 align-items-center justify-content-between">
        <div class="col">
          <?php printFormatedFlashMessage("add_clip_success_message"); ?>
          <?php printFormatedFlashMessage("delete_clip_success_message"); ?>
          <?php printFormatedFlashMessage("update_clip_success_message"); ?>
          <h1 class="app-page-title mb-0">Clips</h1>
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
                      <th class="cell">Course</th>
                      <th class="cell">Chapter</th>
                      <th class="cell">Name</th>
                      <th class="cell">Number</th>
                      <th class="cell">Link</th>
                      <th class="cell">Description</th>
                      <th class="cell"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($clips as $index => $clip) : ?>
                      <tr>
                        <td class="cell">#<?php echo ++$index; ?></td>
                        <td class="cell"><span class="truncate"><?php echo $clip['course_name'] ?? "Error"; ?></span></td>
                        <td class="cell"><?php echo $clip['chapter_name'] ?? "Chapter Name"; ?></td>
                        <td class="cell"><?php echo $clip['c_name'] ?? "Clip Name"; ?></td>
                        <td class="cell"><?php echo $clip['clip_number'] ?? "Number"; ?></td>
                        <td class="cell"><?php echo $clip['link'] ?? "Link"; ?></td>
                        <td class="cell"><?php echo $clip['c_description'] ?? "Date"; ?></td>
                        <td class="cell">



                          <!-- Button trigger modal -->
                          <button type="button" data-bs-toggle="modal" data-bs-target="#editClipModal<?php echo $clip['c_id']; ?>" style="border: none; background-color: transparent;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil text-warning" viewBox="0 0 16 16">
                              <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5L13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175l-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                            </svg>
                          </button>

                          <!-- EDIT MODAL -->
                          <div class="modal fade" id="editClipModal<?php echo $clip['c_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Editing clip "<?php echo $clip['c_name']; ?>"</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <form method="POST" class="text-left">

                                    <div class="mb-3">
                                      <label class="form-label">Course</label>
                                      <select id="course-select-edit" name="course_id_edit" class="form-select" aria-label="Default select example">
                                        <?php foreach ($courses as $course) : ?>
                                          <option value="<?php echo $course['id'] ?? "1"; ?>" <?php if ($clip['course_id'] == $course['id']) {
                                                                                                $selectedID = $course['id'];
                                                                                                echo 'selected';
                                                                                              } ?>><?php echo $course['name'] ?? "Error"; ?></option>
                                        <?php endforeach; ?>
                                      </select>
                                    </div>

                                    <?php $chapters_edit = Database::getInstance()->getAllChaptersForCourse($selectedID); ?>

                                    <div class="mb-3">
                                      <label class="form-label">Chapter</label>
                                      <select id="chapter-select-edit" name="chapter_id_edit" class="form-select" aria-label="Default select example">
                                        <?php foreach ($chapters_edit as $chapter) : ?>
                                          <option value="<?php echo $chapter['chapter_id'] ?? "1"; ?>" <?php if ($clip['current_chapter'] == $chapter['chapter_id']) echo "selected"; ?>><?php echo $chapter['chapter_name'] ?? "Error"; ?></option>
                                        <?php endforeach; ?>
                                      </select>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label">Name</label>
                                      <input name="name_edit" type="text" class="form-control <?php if (isset($errors['name_edit'])) echo 'is-invalid';
                                                                                              else if (isset($name_edit) && !empty($name_edit)) echo 'is-valid'; ?>" placeholder="Enter name" value="<?php echo $clip['c_name'] ?? ""; ?>">
                                      <?php echo $errors['name_edit'] ?? ""; ?>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label">Number</label>
                                      <input name="number_edit" type="number_edit" class="form-control <?php if (isset($errors['number_edit'])) echo 'is-invalid';
                                                                                                        else if (isset($number_edit) && !empty($number_edit)) echo 'is-valid'; ?>" placeholder="Enter number" value="<?php echo $clip['clip_number'] ?? ""; ?>">
                                      <?php echo $errors['number_edit'] ?? ""; ?>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label">Link</label>
                                      <input name="link_edit" type="text" class="form-control <?php if (isset($errors['link_edit'])) echo 'is-invalid';
                                                                                              else if (isset($link_edit) && !empty($link_edit)) echo 'is-valid'; ?>" placeholder="Enter link" value="<?php echo $clip['link'] ?? ""; ?>">
                                      <?php echo $errors['link_edit'] ?? ""; ?>
                                    </div>

                                    <div class="mb-3">
                                      <label class="form-label">Description</label>
                                      <textarea name="description_edit" id="" cols="30" class="form-control text-left <?php if (isset($errors['description_edit'])) echo 'is-invalid';
                                                                                                                      else if (isset($description_edit)  && !empty($description_edit)) echo 'is-valid'; ?>" placeholder="Enter description_edit" style="height: 100px;"><?php echo $clip['c_description'] ?? ""; ?></textarea>
                                      <?php echo $errors['description_edit'] ?? ""; ?>
                                    </div>

                                    <input type="hidden" name="clip_id_edit" value="<?php echo $clip['c_id']; ?>">

                                    <button name="update-clip" type="submit" class="btn btn-warning d-block w-100 text-white">
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
                          <button type="button" data-bs-toggle="modal" data-bs-target="#deleteClipModal<?php echo $clip['c_id']; ?>" style="border: none; background-color: transparent;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash text-danger" viewBox="0 0 16 16">
                              <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                              <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                            </svg>
                          </button>

                          <!-- DELETE MODAL -->
                          <div class="modal fade" id="deleteClipModal<?php echo $clip['c_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title text-danger" id="exampleModalLabel">Danger <i class="fas fa-exclamation-circle"></i></h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  Are you sure that you want to delete course <strong class="text-danger">"<?php echo $clip['c_name'] ?? "Error"; ?>"</strong> ?
                                </div>
                                <div class="modal-footer">
                                  <form method="POST" class="d-inline">
                                    <input type="hidden" name="clip_id" value="<?php echo $clip['c_id'] ?? "-1"; ?>">
                                    <input type="hidden" name="clip_name" value="<?php echo $clip['c_name'] ?? "Error"; ?>">
                                    <button name="delete-clip" type="submit" class="btn btn-danger text-white">Yes</button>
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
                      <h1 class="mt-2">Add New Clip</h1>

                      <form method="POST" class="mt-5 mb-5 text-left" enctype="multipart/form-data">

                        <div class="mb-3">
                          <label class="form-label">Course</label>
                          <select id="course-select" name="course_id" class="form-select" aria-label="Default select example">
                            <?php foreach ($courses as $course) : ?>
                              <option <?php if (isset($course_id) && $course_id == $course['id']) echo "selected"; ?> value="<?php echo $course['id'] ?? "1"; ?>" <?php if (isset($course_id) && $course['id'] == $course_id) echo 'selected'; ?>><?php echo $course['name'] ?? "Error"; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Chapter</label>
                          <select id="chapter-select" name="chapter_id" class="form-select" aria-label="Default select example">
                            <?php foreach ($chapters as $chapter) : ?>
                            <?php echo $chapter_id . " ------" . $chapter['chapter_id'] . "\n"; ?>
                              <option <?php if (isset($chapter_id) && $chapter_id == $chapter['chapter_id']) echo "selected"; ?> value="<?php echo $chapter['chapter_id'] ?? "1"; ?>"><?php echo $chapter['chapter_name'] ?? "Error"; ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Number</label>
                          <input name="number" type="number" class="form-control <?php if (isset($errors['number'])) echo 'is-invalid';
                                                                                  else if (isset($number) && !empty($number)) echo 'is-valid'; ?>" placeholder="Enter number" value="<?php echo $number ?? ""; ?>">
                          <?php echo $errors['number'] ?? ""; ?>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Name</label>
                          <input name="name" type="text" class="form-control <?php if (isset($errors['name'])) echo 'is-invalid';
                                                                              else if (isset($name) && !empty($name)) echo 'is-valid'; ?>" placeholder="Enter name" value="<?php echo $name ?? ""; ?>">
                          <?php echo $errors['name'] ?? ""; ?>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Upload video</label> <br>
                          <input name="upload" type="file">
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Link</label>
                          <input name="link" type="text" class="form-control <?php if (isset($errors['link'])) echo 'is-invalid';
                                                                              else if (isset($link) && !empty($link)) echo 'is-valid';
                                                                              ?>" placeholder="Enter link" value="<?php echo $link ?? "";
                                                                                                                  ?>">
                          <?php echo $errors['link'] ?? "";
                          ?>
                        </div>

                        <div class="mb-3">
                          <label class="form-label">Description</label>
                          <textarea name="description" id="" cols="30" class="form-control text-left <?php if (isset($errors['description'])) echo 'is-invalid';
                                                                                                      else if (isset($description)  && !empty($description)) echo 'is-valid'; ?>" placeholder="Enter description" style="height: 100px;"><?php echo $description ?? ""; ?></textarea>
                          <?php echo $errors['description'] ?? ""; ?>
                        </div>



                        <button name="add-clip" type="submit" class="btn btn-primary d-block w-100 text-white">
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