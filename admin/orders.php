<?php
ob_start();
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '../vendor/autoload.php';

require_once './header_admin.php';



if (isset($_POST['confirm-purchase'])) {
  $confirmID = clean($_POST['id']);
  $email = clean($_POST['confirm-email']);
  $courseName = clean($_POST['confirm-course-name']);
  if (Database::getInstance()->confirmCoursePurchase($confirmID)) {
    $_SESSION['confirm_purchase_success_message'] = '
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Potvrda uspešna! <i class="fas fa-check"></i></strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
      //Server settings
      // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
      $mail->isSMTP();                                            // Send using SMTP
      $mail->Host       = 'mail.tatamata.rs';                    // Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
      $mail->Username   = 'admin@tatamata.rs';                     // SMTP username
      $mail->Password   = defined('SMTP_PASS') ? SMTP_PASS : '';  // SEC-FIX: iz env.php
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
      $mail->Port       = 25;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

      // From
      $mail->setFrom('admin@tatamata.rs', 'Admin Tatamata');
      // To
      $mail->addAddress('dragoslav.gagi8@gmail.com');               // Name is optional
      $mail->addAddress($email);               // Name is optional

      // Content
      $mail->isHTML(true);                                  // Set email format to HTML
      $mail->Subject = 'Uspesna kupovina!';
      $mail->Body    = "Zdravo, <br><br>Tvoja uplata je uspešno evidentirana i gledanje kursa \"$courseName\" je ti je omogućeno. <br><br>Mali podsetnik da sajtu možeš pristupiti sa najviše 2 različita uređaja (npr. kompjuter i telefon).<br><br>Kurs možeš gledati u okviru sekcije \"Moji Kursevi\" <br>https://www.tatamata.rs/mojikursevi <br><br>  Želim ti puno uspeha u učenju! Za sva pitanja slobodno mi piši u kontakt formi na sajtu. <br> <br> Hajde da pokidamo ovo 💪🔥 <br> ~TataMata ✌️";

      if ($_SERVER['SERVER_NAME'] != "localhost") {
        $mail->send();
      }
      // echo 'Message has been sent';
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  }
}

if (isset($_POST['delete-user-course'])) {
  $confirmID = clean($_POST['user_course_id']);
  if (Database::getInstance()->deleteCoursePurchase($confirmID)) {
    $_SESSION['delete_purchase_success_message'] = '
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Brisanje uspešno! <i class="fas fa-check"></i></strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
  }
}

$courses = Database::getInstance()->getAllUser_Courses();

?>

<div class="app-wrapper">

  <div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">

      <div class="row g-3 mb-4 align-items-center justify-content-between">
        <div class="col">
          <?php printFormatedFlashMessage("confirm_purchase_success_message"); ?>
          <?php printFormatedFlashMessage("delete_purchase_success_message"); ?>
          <h1 class="app-page-title mb-0">Orders</h1>
        </div>
      </div>
      <!--//row-->


      <nav id="orders-table-tab" class="orders-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
        <a class="flex-sm-fill text-sm-center nav-link active" id="orders-all-tab" data-toggle="tab" href="#orders-all" role="tab" aria-controls="orders-all" aria-selected="true">All</a>
        <a class="flex-sm-fill text-sm-center nav-link" id="orders-paid-tab" data-toggle="tab" href="#orders-paid" role="tab" aria-controls="orders-paid" aria-selected="false">Paid</a>
        <a class="flex-sm-fill text-sm-center nav-link" id="orders-pending-tab" data-toggle="tab" href="#orders-pending" role="tab" aria-controls="orders-pending" aria-selected="false">Pending</a>
        <!-- <a class="flex-sm-fill text-sm-center nav-link" id="orders-cancelled-tab" data-toggle="tab" href="#orders-cancelled" role="tab" aria-controls="orders-cancelled" aria-selected="false">Cancelled</a> -->
      </nav>


      <div class="tab-content" id="orders-table-tab-content">
        <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
          <div class="app-card app-card-orders-table shadow-sm mb-5">
            <div class="app-card-body">
              <div class="table-responsive">
                <table class="table app-table-hover mb-0 text-left">
                  <thead>
                    <tr>
                      <th class="cell">Rb.</th>
                      <th class="cell">Course ID</th>
                      <th class="cell">Product</th>
                      <th class="cell">Customer</th>
                      <th class="cell">Date</th>
                      <th class="cell">Status</th>
                      <th class="cell">Total</th>
                      <th class="cell"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($courses as $index => $course) : ?>
                      <tr>
                        <td class="cell"><?php echo ++$index; ?>)</td>
                        <td class="cell"><?php echo $course['id']; ?></td>
                        <td class="cell"><span class="truncate"><?php echo $course['name'] ?? "Error"; ?></span></td>
                        <td class="cell"><a href="<?php echo BASE_URL . 'admin/user/' . $course['user_id'] ?? "unknown"; ?>"><?php echo $course['firstname'] . " " . $course['lastname']; ?></a></td>
                        <td class="cell"><?php echo $course['uc_created_at'] ?? "Date"; ?></td>
                        <td class="cell">
                          <span class="badge<?php if ($course['confirmed']) echo ' bg-success';
                                            else echo ' bg-warning'; ?>">
                            <?php if ($course['confirmed']) { ?>
                              Paid
                            <?php } else { ?>
                              Pending
                            <?php } ?>
                          </span>
                        </td>
                        <td class="cell"><?php echo $course['price'] ?? "-1"; ?> RSD</td>
                        <?php if (!$course['confirmed']) { ?>
                          <td class="cell">
                            <form method="POST" class="d-inline">
                              <input type="hidden" name="id" value="<?php echo $course['uc_id']; ?>">
                              <input type="hidden" name="confirm-email" value="<?php echo $course['email']; ?>">
                              <input type="hidden" name="confirm-course-name" value="<?php echo $course['name']; ?>">
                              <button name="confirm-purchase" type="submit" class="btn-sm app-btn-secondary" href="#"><i class="fas fa-check"></i></button>
                            </form>

                            <!-- Button trigger modal -->
                            <button type="button" data-bs-toggle="modal" class="app-btn-secondary" data-bs-target="#deleteUserCourse<?php echo $course['uc_id']; ?>" style="background-color: transparent;">
                              <i class="far fa-trash-alt text-danger"></i>
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="deleteUserCourse<?php echo $course['uc_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Are you sure that you want to <strong class="text-danger">DELETE</strong> course <strong class="text-danger"><?php echo $course['course_name']; ?></strong> for user <strong class="text-danger"><?php echo $course['firstname'] . " " . $course['lastname']; ?></strong> ?</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-footer">
                                    <form method="POST" class="d-inline">
                                      <input type="hidden" name="user_course_id" value="<?php echo $course['uc_id'] ?? "-1"; ?>">
                                      <button name="delete-user-course" type="submit" class="btn btn-danger text-white">Yes</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                  </div>
                                </div>
                              </div>
                            </div>

                          </td>
                        <?php } else { ?>
                          <td class="cell">
                            <!-- Button trigger modal -->
                            <button type="button" data-bs-toggle="modal" class="app-btn-secondary" data-bs-target="#deleteUserCourse<?php echo $course['uc_id']; ?>" style="background-color: transparent;">
                              <i class="far fa-trash-alt text-danger"></i>
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="deleteUserCourse<?php echo $course['uc_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Are you sure that you want to <strong class="text-danger">DELETE</strong> course <strong class="text-danger"><?php echo $course['course_name']; ?></strong> for user <strong class="text-danger"><?php echo $course['firstname'] . " " . $course['lastname']; ?></strong> ?</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-footer">
                                    <form method="POST" class="d-inline">
                                      <input type="hidden" name="user_course_id" value="<?php echo $course['uc_id'] ?? "-1"; ?>">
                                      <button name="delete-user-course" type="submit" class="btn btn-danger text-white">Yes</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </td>
                        <?php } ?>
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

        <div class="tab-pane fade" id="orders-paid" role="tabpanel" aria-labelledby="orders-paid-tab">
          <div class="app-card app-card-orders-table mb-5">
            <div class="app-card-body">
              <div class="table-responsive">

                <table class="table mb-0 text-left">
                  <thead>
                    <tr>
                      <th class="cell">Rb.</th>
                      <th class="cell">Course ID</th>
                      <th class="cell">Product</th>
                      <th class="cell">Customer</th>
                      <th class="cell">Date</th>
                      <th class="cell">Status</th>
                      <th class="cell">Total</th>
                      <th class="cell"></th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php $counter = 1;
                    foreach ($courses as $index => $course) : ?>
                      <?php if ($course['confirmed']) { ?>
                        <tr>
                          <td class="cell"><?php echo $counter++; ?>)</td>
                          <td class="cell"><?php echo $course['id']; ?></td>
                          <td class="cell"><span class="truncate"><?php echo $course['name'] ?? "Error"; ?></span></td>
                          <td class="cell"><a href="<?php echo BASE_URL . 'admin/user/' . $course['user_id'] ?? "unknown"; ?>"><?php echo $course['firstname'] . " " . $course['lastname']; ?></a></td>
                          <td class="cell"><?php echo $course['uc_created_at'] ?? "Date"; ?></td>
                          <td class="cell"><span class="badge bg-success">Paid</span></td>
                          <td class="cell"><?php echo $course['price'] ?? "-1"; ?> RSD</td>
                          <td class="cell"></td>
                        </tr>
                      <?php } ?>
                    <?php endforeach; ?>

                  </tbody>
                </table>
              </div>
              <!--//table-responsive-->
            </div>
            <!--//app-card-body-->
          </div>
          <!--//app-card-->
        </div>
        <!--//tab-pane-->

        <div class="tab-pane fade" id="orders-pending" role="tabpanel" aria-labelledby="orders-pending-tab">
          <div class="app-card app-card-orders-table mb-5">
            <div class="app-card-body">
              <div class="table-responsive">
                <table class="table mb-0 text-left">
                  <thead>
                    <tr>
                      <th class="cell">Rb.</th>
                      <th class="cell">Course ID</th>
                      <th class="cell">Product</th>
                      <th class="cell">Customer</th>
                      <th class="cell">Date</th>
                      <th class="cell">Status</th>
                      <th class="cell">Total</th>
                      <th class="cell"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $counter = 1;
                    foreach ($courses as $course) : ?>
                      <?php if (!$course['confirmed']) { ?>
                        <tr>
                          <td class="cell"><?php echo $counter++; ?>)</td>
                          <td class="cell"><?php echo $course['id']; ?></td>
                          <td class="cell"><span class="truncate"><?php echo $course['name'] ?? "Error"; ?></span></td>
                          <td class="cell"><a href="<?php echo BASE_URL . 'admin/user/' . $course['user_id'] ?? "unknown"; ?>"><?php echo $course['firstname'] . " " . $course['lastname']; ?></a></td>
                          <td class="cell"><?php echo $course['uc_created_at'] ?? "Date"; ?></td>
                          <td class="cell"><span class="badge bg-warning">Pending</span></td>
                          <td class="cell"><?php echo $course['price'] ?? "-1"; ?> RSD</td>
                          <td class="cell">
                            <form method="POST">
                              <input type="hidden" name="id" value="<?php echo $course['uc_id']; ?>">
                              <button name="confirm-purchase" type="submit" class="btn-sm app-btn-secondary" href="#"><i class="fas fa-check"></i></button>
                            </form>
                          </td>
                        </tr>
                      <?php } ?>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <!--//table-responsive-->
            </div>
            <!--//app-card-body-->
          </div>
          <!--//app-card-->
        </div>
        <!--//tab-pane-->
        <div class="tab-pane fade" id="orders-cancelled" role="tabpanel" aria-labelledby="orders-cancelled-tab">
          <div class="app-card app-card-orders-table mb-5">
            <div class="app-card-body">
              <div class="table-responsive">
                <table class="table mb-0 text-left">
                  <thead>
                    <tr>
                      <th class="cell">Order</th>
                      <th class="cell">Product</th>
                      <th class="cell">Customer</th>
                      <th class="cell">Date</th>
                      <th class="cell">Status</th>
                      <th class="cell">Total</th>
                      <th class="cell"></th>
                    </tr>
                  </thead>
                  <tbody>

                    <tr>
                      <td class="cell">#15342</td>
                      <td class="cell"><span class="truncate">Justo feugiat neque</span></td>
                      <td class="cell">Reina Brooks</td>
                      <td class="cell"><span class="cell-data">12 Oct</span><span class="note">04:23 PM</span></td>
                      <td class="cell"><span class="badge bg-danger">Cancelled</span></td>
                      <td class="cell">$59.00</td>
                      <td class="cell"><a class="btn-sm app-btn-secondary" href="#">View</a></td>
                    </tr>

                  </tbody>
                </table>
              </div>
              <!--//table-responsive-->
            </div>
            <!--//app-card-body-->
          </div>
          <!--//app-card-->
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