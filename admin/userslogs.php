<?php ob_start(); ?>

<?php require_once './header_admin.php'; ?>

<?php

if (isset($_POST['delete-log'])) {
  $id = clean($_POST['ld_id']);

  if (Database::getInstance()->deleteLoginInfo($id)) {
    $_SESSION['delete_log_success_message'] = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                Brisanje loga uspešno! <i class="fas fa-check"></i>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
  }
}

$logs = Database::getInstance()->getAllLoginDetailsSortedByUsers();
$perPage = 30; // Change here, also change in app.js
$totalPages = ceil(sizeof($logs) / $perPage);
$currentPageNumber = $_GET['page'] ?? 1;

if (isset($_GET['sort']) && $_GET['sort'] == 'date') {
  $logs = Database::getInstance()->getAllLoginDetailsSortedByDate();
}

?>

<style>
  th#user a,
  th#date a {
    color: #5d6778;
  }

  th#user:hover,
  th#date:hover {
    cursor: pointer;
  }
</style>

<div class="app-wrapper">

  <div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">

      <div class="row g-3 mb-4 align-items-center justify-content-between">
        <div class="col">
          <?php printFormatedFlashMessage("confirm_purchase_success_message"); ?>
          <?php printFormatedFlashMessage("delete_log_success_message"); ?>
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
                      <th id="user" class="cell"><a href="<?php echo BASE_URL . 'admin/userslogs.php?sort=user&page=' . $currentPageNumber; ?>">User</a></th>
                      <th class="cell">CPU Cores</th>
                      <th class="cell">Ram</th>
                      <th class="cell">GPU</th>
                      <th class="cell">OS</th>
                      <th class="cell">Screen Res</th>
                      <th class="cell">Timezone</th>
                      <th id="date" class="cell"><a href="<?php echo BASE_URL . 'admin/userslogs.php?sort=date&page=' . $currentPageNumber; ?>">Date</a></th>
                      <th class="cell"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($logs as $index => $log) : ?>
                      <?php if ((($index + 1) <= ($currentPageNumber * $perPage)) && ($index + 1) > ($currentPageNumber * $perPage - $perPage)) : ?>
                        <tr>
                          <td class="cell">#<?php echo ++$index; ?></td>
                          <td class="cell"><a href="<?php echo BASE_URL . 'admin/user/' . $log['user_id'] ?? "unknown"; ?>"><?php echo $log['firstname'] . " " . $log['lastname']; ?></a></td>
                          <td class="cell"><?php echo $log['cpu_cores'] ?? "Cpu"; ?></td>
                          <td class="cell"><?php echo $log['ram'] ?? "Ram"; ?> GB</td>
                          <td class="cell"><?php echo $log['gpu'] ?? "Gpu"; ?></td>
                          <td class="cell"><?php echo $log['os'] ?? "Gpu"; ?></td>
                          <td class="cell"><?php echo $log['screen_resolution'] ?? "Gpu"; ?></td>
                          <td class="cell"><?php echo $log['timezone'] ?? "Gpu"; ?></td>
                          <td class="cell"><?php echo $log['date'] ?? "Gpu"; ?></td>
                          <td class="cell">

                            <!-- Button trigger modal -->
                            <button type="button" class="btn-sm btn-danger text-white" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $log['ld_id']; ?>" style="border: none; background-color: transparent;">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash text-danger" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                              </svg>
                            </button>

                            <!-- DELETE Modal -->
                            <div class="modal fade" id="deleteModal<?php echo $log['ld_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title text-danger" id="exampleModalLabel">Danger <i class="fas fa-exclamation-circle"></i></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    Are you sure that you want to delete log file <br>
                                    <strong class="text-danger">User: "<?php echo $log['firstname'] . " " . $log['lastname']; ?>"</strong><br>
                                    <strong class="text-danger"><?php echo "CPU CORES: " . $log['cpu_cores'] . "<br>RAM: " . $log['ram'] . " GB<br>GPU: " . $log['gpu'] . "<br>Date: " . $log['date']; ?></strong> ?
                                  </div>
                                  <div class="modal-footer">
                                    <form method="POST" class="d-inline">
                                      <input type="hidden" name="ld_id" value="<?php echo $log['ld_id'] ?? "-1"; ?>">
                                      <button name="delete-log" type="submit" class="btn btn-danger text-white">Yes</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                  </div>
                                </div>
                              </div>
                            </div>



                          </td>
                        </tr>
                      <?php endif; ?>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <!--//table-responsive-->

            </div>
            <!--//app-card-body-->
          </div>
          <!--//app-card-->
          <nav class="app-pagination">
            <ul class="pagination justify-content-center">
              <li class="page-item <?php if ($currentPageNumber == 1) echo 'disabled'; ?>">
                <?php if (isset($_GET['sort'])) { ?>
                  <a class="page-link" href="<?php echo BASE_URL . "admin/userslogs.php?sort=" . $_GET['sort'] . "&page=" . ($currentPageNumber - 1); ?>">Previous</a>
                <?php } else { ?>
                  <a class="page-link" href="<?php echo BASE_URL . "admin/userslogs/" . ($currentPageNumber - 1); ?>" tabindex="-1" aria-disabled="true">Previous</a>
                <?php } ?>
              </li>
              <?php for ($i = 0; $i < $totalPages; $i++) : ?>
                <!-- <li class="page-item active"><a class="page-link" href="#">1</a></li> -->
                <li class="page-item <?php if ($currentPageNumber == ($i + 1)) echo 'disabled'; ?> <?php if ($currentPageNumber == ($i + 1)) echo 'active'; ?>">
                  <?php if (isset($_GET['sort'])) { ?>
                    <a class="page-link <?php if ($currentPageNumber == ($i + 1)) echo 'text-white'; ?>" href="<?php echo BASE_URL . "admin/userslogs.php?sort=" . $_GET['sort'] . "&page=" . ($i + 1); ?>"><?php echo ($i + 1); ?></a>
                  <?php } else { ?>
                    <a class="page-link <?php if ($currentPageNumber == ($i + 1)) echo 'text-white'; ?>" href="<?php echo BASE_URL . "admin/userslogs/" . ($i + 1); ?>"><?php echo ($i + 1); ?></a>
                  <?php } ?>
                </li>
              <?php endfor; ?>
              <li class="page-item <?php if ($currentPageNumber == $totalPages) echo 'disabled'; ?>">
                <?php if (isset($_GET['sort'])) { ?>
                  <a class="page-link" href="<?php echo BASE_URL . "admin/userslogs.php?sort=" . $_GET['sort'] . "&page=" . ($currentPageNumber + 1); ?>">Next</a>
                <?php } else { ?>
                  <a class="page-link" href="<?php echo BASE_URL . "admin/userslogs/" . ($currentPageNumber + 1); ?>">Next</a>
                <?php } ?>
              </li>
            </ul>
          </nav>
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