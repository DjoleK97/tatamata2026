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

    <!-- Sidebar -->
    <div class="col-lg-3 mb-4">
      <nav class="profil-sidebar-nav animiraj">
        <a href="<?php echo BASE_URL; ?>profil"><i class="far fa-user"></i> Profil</a>
        <a href="<?php echo BASE_URL; ?>mojikursevi"><i class="fas fa-play-circle"></i> Moji kursevi</a>
        <a href="<?php echo BASE_URL; ?>transakcije" class="active"><i class="fas fa-receipt"></i> Transakcije</a>
      </nav>
    </div>

    <!-- Main content -->
    <div class="col-lg-9">

      <div class="mb-4 animiraj">
        <h1><i class="fas fa-receipt me-2" style="color:var(--plava);"></i> Transakcije</h1>
      </div>

      <div class="profile-div animiraj">

        <?php printFormatedFlashMessage("change_password_failed_message"); ?>
        <?php printFormatedFlashMessage("change_password_success_message"); ?>

        <?php if (count($orders) == 0) { ?>
          <div class="prazno-stanje text-center">
            <i class="fas fa-receipt prazno-ikona"></i>
            <h3>Nema transakcija</h3>
            <p>Kupljeni kursevi ce se pojaviti ovde.</p>
          </div>
        <?php } else { ?>

          <!-- Desktop tabela -->
          <div class="d-none d-md-block">
            <table class="table table-hover mb-0">
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
                    <td><?php echo htmlspecialchars($order['name']); ?></td>
                    <td><?php echo $order['date']; ?></td>
                    <td>
                      <?php if ($order['confirmed']) { ?>
                        <span class="bedz-dostupno"><i class="fas fa-check me-1"></i> Potvrdjeno</span>
                      <?php } else { ?>
                        <span class="bedz-uskoro"><i class="fas fa-hourglass-half me-1"></i> Ceka potvrdu</span>
                      <?php } ?>
                    </td>
                    <?php if ($_SESSION['user']->country == "srbija") { ?>
                      <td><strong><?php echo $order['price'];
                          $total += $order['price']; ?> RSD</strong></td>
                    <?php } else { ?>
                      <td><strong><?php echo $order['price2'];
                          $total2 += $order['price2']; ?> &euro;</strong></td>
                    <?php } ?>
                  </tr>
                <?php endforeach; ?>
                <tr class="text-center" style="background:var(--plava-ultra-svetla);">
                  <td colspan="4" class="text-end"><strong>Ukupno:</strong></td>
                  <td><strong><?php if (strtolower($_SESSION['user']->country) == 'srbija') echo $total . " RSD";
                              else echo $total2 . " &euro;"; ?></strong></td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Mobile kartice -->
          <div class="d-md-none">
            <?php
            $total = 0;
            $total2 = 0;
            foreach ($orders as $order) : ?>
              <div class="p-3 mb-3" style="background:var(--siva-svetla); border-radius:var(--radius); border:1px solid var(--siva-200);">
                <div class="d-flex justify-content-between align-items-start mb-2">
                  <strong style="font-size:.95rem;"><?php echo htmlspecialchars($order['name']); ?></strong>
                  <?php if ($order['confirmed']) { ?>
                    <span class="bedz-dostupno"><i class="fas fa-check"></i></span>
                  <?php } else { ?>
                    <span class="bedz-uskoro"><i class="fas fa-hourglass-half"></i></span>
                  <?php } ?>
                </div>
                <div class="d-flex justify-content-between" style="font-size:.85rem; color:var(--siva-700);">
                  <span><?php echo $order['date']; ?></span>
                  <strong style="color:var(--tamna);">
                    <?php if ($_SESSION['user']->country == "srbija") { ?>
                      <?php echo $order['price'];
                            $total += $order['price']; ?> RSD
                    <?php } else { ?>
                      <?php echo $order['price2'];
                            $total2 += $order['price2']; ?> &euro;
                    <?php } ?>
                  </strong>
                </div>
              </div>
            <?php endforeach; ?>
            <div class="text-end p-3" style="background:var(--plava-ultra-svetla); border-radius:var(--radius);">
              <strong>Ukupno: <?php if (strtolower($_SESSION['user']->country) == 'srbija') echo $total . " RSD";
                              else echo $total2 . " &euro;"; ?></strong>
            </div>
          </div>

        <?php } ?>

      </div>
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