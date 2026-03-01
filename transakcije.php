<?php

require_once "includes/functions.php";
require_once "classes/Database.php";

if (!Database::getInstance()->isUserLoggedIn()) {
  $_SESSION['unauthorized_access'] = '<div class="container-fluid">
  <div class="row">
    <div class="col-md-10 col-sm-10 offset-sm-1 offset-md-1 p-0 mt-5">
      <div class="alert alert-danger alert-dismissible show" role="alert">
        <strong>Greška!</strong> Zabranjen pristup! <i class="fas fa-exclamation-triangle"></i>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  </div>
</div>';
  header("Location: pocetna");

  exit;
}

$orders = Database::getInstance()->getAllCoursesForUser($_SESSION['user']->id);
$total = 0;
$total2 = 0;

?>


<?php include_once 'includes/header.php'; ?>

<div class="container transakcije" id="profile-page">
  <div class="row">

    <div class="col-md-8 offset-md-2 mb-3">
      <h1 class="text-white text-center fw-bold">&euro; Transakcije</h1>
      <hr class="hr">
    </div>

    <div class="col-md-8 offset-md-2 profile-div">

      <?php printFormatedFlashMessage("change_password_failed_message"); ?>
      <?php printFormatedFlashMessage("change_password_success_message"); ?>

      <table class="table text-white table-hover">
        <thead>
          <tr class="text-center">
            <th scope="col">#</th>
            <th scope="col">Kurs</th>
            <th scope="col">Datum</th>
            <th scope="col">Status</th>
            <th scope="col">Iznos</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $index => $order) : ?>
            <tr class="text-center">
              <th scope="row"><?php echo ++$index; ?>.</th>
              <td><?php echo $order['name']; ?></td>
              <td><?php echo $order['date']; ?></td>
              <td>
                <?php if ($order['confirmed']) { ?>
                  <i class="far fa-check-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Uspešna kupovina"></i>
                <?php } else { ?>
                  <i data-bs-toggle="tooltip" data-bs-placement="top" title="Čeka se potvrda admina" class="far fa-hourglass"></i>
                <?php } ?>
              </td>
              <?php if ($_SESSION['user']->country == "srbija") { ?>
                <td><?php echo $order['price'];
                    $total += $order['price']; ?> RSD</td>
              <?php } else { ?>
                <td><?php echo $order['price2'];
                    $total2 += $order['price2']; ?> &euro;</td>
              <?php } ?>
            </tr>
          <?php endforeach; ?>
          <tr class="text-center">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Ukupno: <?php if (strtolower($_SESSION['user']->country) == 'srbija') echo $total . " RSD";
                        else echo $total2 . " &euro;"; ?></td>
          </tr>
        </tbody>
      </table>

    </div>
  </div>
</div>

<script>
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
</script>

<?php include_once 'includes/footer.php'; ?>