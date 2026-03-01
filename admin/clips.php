<?php ob_start(); ?>

<?php require_once './header_admin.php'; ?>

<?php

$courses = Database::getInstance()->getAllCourses();

?>

<div class="app-wrapper">

  <div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">

      <div class="row g-3 mb-4 align-items-center justify-content-between">
        <div class="col">
          <?php printFormatedFlashMessage("add_clip_success_message"); ?>
          <?php printFormatedFlashMessage("delete_clip_success_message"); ?>
          <?php printFormatedFlashMessage("update_clip_success_message"); ?>
          <h1 class="app-page-title mb-0">Select Course</h1>
        </div>
      </div>
      <!--//row-->

      <div class="tab-content" id="orders-table-tab-content">
        <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
          <div class="app-card app-card-orders-table shadow-sm mb-5">
            <div class="app-card-body">
              <div class="table-responsive">
                <table class="table app-table-hover mb-0 text-left">
                  <thead class='text-center'>
                    <tr>
                      <th class="cell">Order</th>
                      <th class="cell">Course</th>
                      <th class="cell">Chapters</th>
                      <th class="cell">Clips</th>
                    </tr>
                  </thead>
                  <tbody class='text-center'>
                    <?php foreach ($courses as $index => $course) : ?>
                      <tr>
                        <td class="cell"> <a href="<?php echo BASE_URL . 'admin/courses/' . $course['id'] . '/chapters'; ?>">
                            <div>#<?php echo ++$index; ?></div>
                          </a></td>
                        <td class="cell"> <a href="<?php echo BASE_URL . 'admin/courses/' . $course['id'] . '/chapters'; ?>">
                            <div><span class="truncate"><?php echo $course['name'] ?? "Error"; ?></span></div>
                          </a></td>
                        <td class="cell"> <a href="<?php echo BASE_URL . 'admin/courses/' . $course['id'] . '/chapters'; ?>">
                            <div><?php echo sizeof(Database::getInstance()->getAllChaptersForCourse($course['id'])); ?></div>
                          </a></td>
                        <td class="cell"> <a href="<?php echo BASE_URL . 'admin/courses/' . $course['id'] . '/chapters'; ?>">
                            <div><?php echo sizeof(Database::getInstance()->getAllClipsForCourse($course['id'])); ?></div>
                          </a></td>
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

    </div>
    <!--//container-fluid-->
  </div>
  <!--//app-content-->

</div>
<!--//app-wrapper-->

<?php require_once './footer_admin.php'; ?>