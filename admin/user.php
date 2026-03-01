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

if (isset($_POST['delete-log'])) {
  $id = clean($_POST['ld_id']);

  if (Database::getInstance()->deleteLoginInfo($id)) {
    $_SESSION['delete_log_success_message'] = '<div class="alert alert-warning alert-dismissible show" role="alert">
                Brisanje loga uspešno! <i class="fas fa-check"></i>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
  }
}

if (isset($_POST['deleteMultipleLoginDetails'])) {
  $data = $_POST;
  $data['user_id'] = clean($_GET['id']);
  Database::getInstance()->deleteMultipleLoginInfo($data);
}

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
      $mail->Password   = 'pidyejretard123';                               // SMTP password
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

if (isset($_POST['remove-confirmation'])) {
  $confirmID = clean($_POST['user_course_id']);
  if (Database::getInstance()->unconfirmCourse($confirmID)) {
    $_SESSION['remove_confirmation_success_message'] = '
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>Unconfirm uspešan! <i class="fas fa-check"></i></strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
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

$id = clean($_GET['id']);

// Count unique devices for user that logged in
$loginDetails = Database::getInstance()->getLoginInfoForUser($id);
$numberOfDifferentDevices = countUniqueLoginsForUser($loginDetails);

if ($numberOfDifferentDevices < 3) {
  Database::getInstance()->unWarnUser($id);
  // $_SESSION['user']->warned = 0;
}

if ($numberOfDifferentDevices < 4) {
  Database::getInstance()->unBlockUser($id);
  // $_SESSION['user']->blocked = 0;
}

$user = Database::getInstance()->getUserByID($id);
$userCourses = Database::getInstance()->getAllCoursesForUser($user['id']);
$userLogs = Database::getInstance()->getLoginInfoForUser($id);

?>

<div class="app-wrapper">

  <div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">

      <?php if ($user === false) { ?>
        <h1 class="app-page-title">User not found.</h1>
      <?php } else { ?>
        <?php printFormatedFlashMessage("confirm_purchase_success_message"); ?>
        <?php printFormatedFlashMessage("delete_purchase_success_message"); ?>
        <?php printFormatedFlashMessage("remove_confirmation_success_message"); ?>
        <?php printFormatedFlashMessage("delete_log_success_message"); ?>
        <h1 class="app-page-title">User "<?php echo $user['firstname'] . " " . $user['lastname']; ?>"</h1>
        <div class="row gy-4">

          <div class="col-12 col-lg-6">
            <div class="app-card app-card-account shadow-sm d-flex flex-column align-items-start">
              <div class="app-card-header p-3 border-bottom-0">
                <div class="row align-items-center gx-3">
                  <div class="col-auto">
                    <div class="app-icon-holder">
                      <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm6 5c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                      </svg>
                    </div>
                    <!--//icon-holder-->

                  </div>
                  <!--//col-->
                  <div class="col-auto">
                    <h4 class="app-card-title">Profile</h4>
                  </div>
                  <!--//col-->
                </div>
                <!--//row-->
              </div>
              <!--//app-card-header-->
              <div class="app-card-body px-4 w-100">
                <div class="item border-bottom py-3">
                  <div class="row justify-content-between align-items-center">
                    <div class="col-auto">
                      <div class="item-label"><strong>Name</strong></div>
                      <div class="item-data"><?php echo $user['firstname'] . " " . $user['lastname']; ?></div>
                    </div>
                    <!--//col-->
                  </div>
                  <!--//row-->
                </div>
                <!--//item-->
                <div class="item border-bottom py-3">
                  <div class="row justify-content-between align-items-center">
                    <div class="col-auto">
                      <div class="item-label"><strong>Email</strong></div>
                      <div class="item-data"><?php echo $user['email'] ?? "Email Error"; ?></div>
                    </div>
                    <!--//col-->
                  </div>
                  <!--//row-->
                </div>
                <!--//item-->
                <div class="item border-bottom py-3">
                  <div class="row justify-content-between align-items-center">
                    <div class="col-auto">
                      <div class="item-label"><strong>Phone</strong></div>
                      <div class="item-data">
                        <?php echo $user['phone_number'] ?? "Phone"; ?>
                      </div>
                    </div>
                    <!--//col-->
                  </div>
                  <!--//row-->
                </div>
                <!--//item-->
                <div class="item border-bottom py-3">
                  <div class="row justify-content-between align-items-center">
                    <div class="col-auto">
                      <div class="item-label"><strong>Grade</strong></div>
                      <div class="item-data">
                        <?php echo $user['grade'] ?? "Phone"; ?>
                      </div>
                    </div>
                    <!--//col-->
                  </div>
                  <!--//row-->
                </div>
                <!--//item-->
                <div class="item border-bottom py-3">
                  <div class="row justify-content-between align-items-center">
                    <div class="col-auto">
                      <div class="item-label"><strong>Number of devices</strong></div>
                      <div class="item-data">
                        <?php
                        echo $numberOfDifferentDevices;
                        ?>
                      </div>
                    </div>
                    <!--//col-->
                  </div>
                  <!--//row-->
                </div>
                <!--//item-->
                <div class="item border-bottom py-3">
                  <div class="row justify-content-between align-items-center">
                    <div class="col-auto">
                      <div class="item-label"><strong>Warned</strong></div>
                      <div class="item-data">
                        <?php echo $user['warned']; ?>
                      </div>
                    </div>
                    <!--//col-->
                  </div>
                  <!--//row-->
                </div>
                <!--//item-->
                <div class="item border-bottom py-3">
                  <div class="row justify-content-between align-items-center">
                    <div class="col-auto">
                      <div class="item-label"><strong>Blocked</strong></div>
                      <div class="item-data">
                        <?php echo $user['blocked']; ?>
                      </div>
                    </div>
                    <!--//col-->
                  </div>
                  <!--//row-->
                </div>
                <!--//item-->
                <div class="item py-3">
                  <div class="row justify-content-between align-items-center">
                    <div class="col-auto">
                      <div class="item-label"><strong>Courses Bought</strong></div>
                      <div class="item-data">

                        <ul class="list-group mt-2">
                          <?php foreach ($userCourses as $userCourse) : ?>
                            <li class="list-group-item">
                              <strong><?php echo $userCourse['name'] ?? "error"; ?></strong>
                              <?php if ($userCourse['confirmed']) { ?>
                                (paid)

                                <!-- Button trigger modal -->
                                <button type="button" data-bs-toggle="modal" data-bs-target="#unconfirmCourse<?php echo $userCourse['uc_id']; ?>" style="background-color: transparent; border: none;">
                                  <i class="fas fa-undo text-warning"></i>
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="unconfirmCourse<?php echo $userCourse['uc_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Are you sure that you want to <strong class="text-warning">un-confirm</strong> course <strong class="text-danger"><?php echo $userCourse['course_name']; ?></strong> for user <strong class="text-danger"><?php echo $userCourse['firstname'] . " " . $userCourse['lastname']; ?></strong> ?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-footer">
                                        <form method="POST" class="d-inline">
                                          <input type="hidden" name="user_course_id" value="<?php echo $userCourse['uc_id'] ?? "-1"; ?>">
                                          <button name="remove-confirmation" type="submit" class="btn btn-danger text-white">Yes</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                                <!-- Button trigger modal -->
                                <button type="button" data-bs-toggle="modal" class="app-btn-secondary" data-bs-target="#deleteUserCourse<?php echo $userCourse['uc_id']; ?>" style="background-color: transparent;">
                                  <i class="far fa-trash-alt text-danger"></i>
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="deleteUserCourse<?php echo $userCourse['uc_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Are you sure that you want to <strong class="text-danger">DELETE</strong> course <strong class="text-danger"><?php echo $userCourse['course_name']; ?></strong> for user <strong class="text-danger"><?php echo $userCourse['firstname'] . " " . $userCourse['lastname']; ?></strong> ?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-footer">
                                        <form method="POST" class="d-inline">
                                          <input type="hidden" name="user_course_id" value="<?php echo $userCourse['uc_id'] ?? "-1"; ?>">
                                          <button name="delete-user-course" type="submit" class="btn btn-danger text-white">Yes</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                              <?php } else { ?>
                                (pending confirmation)
                                <form method="POST" class="d-inline">
                                  <input type="hidden" name="id" value="<?php echo $userCourse['uc_id']; ?>">
                                  <input type="hidden" name="confirm-email" value="<?php echo $userCourse['email']; ?>">
                                  <input type="hidden" name="confirm-course-name" value="<?php echo $userCourse['name']; ?>">
                                  <button name="confirm-purchase" type="submit" class="btn-sm app-btn-secondary" href="#"><i class="fas fa-check"></i></button>
                                </form>

                                <!-- Button trigger modal -->
                                <button type="button" data-bs-toggle="modal" class="app-btn-secondary" data-bs-target="#deleteUserCourse<?php echo $userCourse['uc_id']; ?>" style="background-color: transparent;">
                                  <i class="far fa-trash-alt text-danger"></i>
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="deleteUserCourse<?php echo $userCourse['uc_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Are you sure that you want to <strong class="text-danger">DELETE</strong> course <strong class="text-danger"><?php echo $userCourse['course_name']; ?></strong> for user <strong class="text-danger"><?php echo $userCourse['firstname'] . " " . $userCourse['lastname']; ?></strong> ?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-footer">
                                        <form method="POST" class="d-inline">
                                          <input type="hidden" name="user_course_id" value="<?php echo $userCourse['uc_id'] ?? "-1"; ?>">
                                          <button name="delete-user-course" type="submit" class="btn btn-danger text-white">Yes</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>


                              <?php } ?>

                              <br>

                              <?php echo $userCourse['date']; ?>
                            </li>
                          <?php endforeach; ?>
                        </ul>


                      </div>
                    </div>
                    <!--//col-->
                  </div>
                  <!--//row-->
                </div>
                <!--//item-->
              </div>
              <!--//app-card-body-->

            </div>
            <!--//app-card-->
          </div>
          <!--//col-->

          <div class="col-12">

            <form class="row row-cols-lg-auto g-3 align-items-center mb-4" method="POST">

              <div class="col-12">
                <label class="visually-hidden" for="inlineFormSelectPref">Preference</label>
                <select class="form-select" name="column_name">
                  <option value="cpu_cores">CPU</option>
                  <option value="ram">RAM</option>
                  <option value="gpu">GPU</option>
                  <option value="screen_resolution">Screen Resolution</option>
                </select>
              </div>

              <div class="col-12">
                <div class="input-group">
                  <input type="text" name="column_value" class="form-control" id="inlineFormInputGroupUsername" placeholder="Column value">
                </div>
              </div>

              <div class="col-12">
                <button type="submit" class="btn app-btn-secondary" name="deleteMultipleLoginDetails" href="#">DELETE</button>
              </div>
            </form>


            <div class="tab-content" id="orders-table-tab-content">
              <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
                <div class="app-card app-card-orders-table shadow-sm mb-5">
                  <div class="app-card-body">
                    <div class="table-responsive" style="height: 500px;">
                      <table class="table app-table-hover mb-0 text-left">
                        <thead>
                          <tr>
                            <th class="cell">RB.</th>
                            <th class="cell">CPU Cores</th>
                            <th class="cell">Ram</th>
                            <th class="cell">GPU</th>
                            <th class="cell">OS</th>
                            <th class="cell">Screen Res</th>
                            <th class="cell">Timezone</th>
                            <th id="date" class="cell">Date</th>
                            <th class="cell"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($userLogs as $index => $log) : ?>
                            <tr>
                              <td class="cell">#<?php echo ++$index; ?></td>
                              <td class="cell"><?php echo $log['cpu_cores'] ?? "Cpu"; ?></td>
                              <td class="cell"><?php echo $log['ram'] ?? "Ram"; ?> GB</td>
                              <td class="cell"><?php echo $log['gpu'] ?? "Gpu"; ?></td>
                              <td class="cell"><?php echo $log['os'] ?? "Gpu"; ?></td>
                              <td class="cell"><?php echo $log['screen_resolution'] ?? "Gpu"; ?></td>
                              <td class="cell"><?php echo $log['timezone'] ?? "Gpu"; ?></td>
                              <td class="cell"><?php echo $log['date'] ?? "Gpu"; ?></td>
                              <td class="cell">

                                <!-- Button trigger modal -->
                                <button type="button" class="btn-sm btn-danger text-white" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $log['id']; ?>" style="border: none; background-color: transparent;">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash text-danger" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                  </svg>
                                </button>

                                <!-- DELETE Modal -->
                                <div class="modal fade" id="deleteModal<?php echo $log['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title text-danger" id="exampleModalLabel">Danger <i class="fas fa-exclamation-circle"></i></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                        Are you sure that you want to delete log file <br>
                                        <strong class="text-danger">User: "<?php echo $user['firstname'] . " " . $user['lastname']; ?>"</strong><br>
                                        <strong class="text-danger"><?php echo "CPU CORES: " . $log['cpu_cores'] . "<br>RAM: " . $log['ram'] . " GB<br>GPU: " . $log['gpu'] . "<br>Date: " . $log['date']; ?></strong> ?
                                      </div>
                                      <div class="modal-footer">
                                        <form method="POST" class="d-inline">
                                          <input type="hidden" name="ld_id" value="<?php echo $log['id'] ?? "-1"; ?>">
                                          <button name="delete-log" type="submit" class="btn btn-danger text-white">Yes</button>
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

              </div>
              <!--//tab-pane-->

            </div>
            <!--//tab-content-->
          </div>
          <!--//col-->

        </div>
      <?php } ?>

    </div>
    <!--//container-fluid-->
  </div>
  <!--//app-content-->

</div>
<!--//app-wrapper-->

<?php require_once './footer_admin.php'; ?>