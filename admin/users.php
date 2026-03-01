<?php ob_start(); ?>

<?php require_once './header_admin.php'; ?>

<?php

$users = Database::getInstance()->getAllUsers();

?>

<div class="app-wrapper">

  <div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">

      <div class="row g-3 mb-4 align-items-center justify-content-between">
        <div class="col">
          <?php printFormatedFlashMessage("confirm_purchase_success_message"); ?>
          <h1 class="app-page-title mb-0">Users</h1>
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
                      <th class="cell">Email</th>
                      <th class="cell">Phone</th>
                      <th class="cell">Date Joined</th>
                      <th class="cell">Courses Bought</th>
                      <th class="cell">Warned</th>
                      <th class="cell">Blocked</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($users as $index => $user) : ?>
                      <tr>
                        <td class="cell">#<?php echo ++$index; ?></td>
                        <td class="cell"><a href="<?php echo BASE_URL . 'admin/user/' . $user['id'] ?? "unknown"; ?>"><?php echo $user['firstname'] . " " . $user['lastname']; ?></a></td>
                        <td class="cell"><?php echo $user['email'] ?? "Email"; ?></td>
                        <td class="cell"><?php echo $user['phone_number'] ?? "Phone"; ?></td>
                        <td class="cell"><?php echo $user['created_at'] ?? "-1"; ?></td>
                        <td class="cell"><a class="btn-sm app-btn-secondary" href="#" data-bs-toggle="modal" data-bs-target="#boughtCoursesModal<?php echo $user['id'] ?? "1"; ?>">View</a></td>
                        <td class="cell"><?php echo $user['warned']; ?></td>
                        <td class="cell"><?php echo $user['blocked']; ?></td>

                        <?php $userCourses = Database::getInstance()->getAllCoursesForUser($user['id']); ?>

                        <!-- Modal -->
                        <div class="modal fade" id="boughtCoursesModal<?php echo $user['id'] ?? "1"; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"><?php echo $user['firstname'] ?? "Error"; ?>'s courses.</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <ul class="list-group">
                                  <?php foreach($userCourses as $userCourse) : ?>
                                    <li class="list-group-item">
                                      <strong><?php echo $userCourse['name'] ?? "error"; ?></strong>
                                      <?php if ($userCourse['confirmed']) { ?>
                                        (paid)
                                      <?php } else { ?>
                                        (pending confirmation)
                                      <?php } ?>
                                    </li>
                                  <?php endforeach; ?>
                                </ul>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              </div>
                            </div>
                          </div>
                        </div>

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