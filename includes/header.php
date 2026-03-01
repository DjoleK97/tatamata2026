<?php require_once "classes/Database.php"; ?>
<?php require_once "includes/config.php"; ?>
<?php
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
  $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: ' . $location);
  exit;
}

$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], '/')); //  /prijava /registracija /index.php
// echo $currentPage;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="site_name" property="og:site_name" content="TataMata">
  <meta name="image" property="og:image" content="<?php echo BASE_URL; ?>public/images/udjinawa.png">
  <meta name="HandheldFriendly" property="True">
  <meta name="MobileOptimized" content="320">
  <meta name="type" property="og:type" content="website">
  <meta name="title" property="og:title" content="TataMata | Matiš na lakši način">
  <meta name="author" content="TataMata">
  <meta property="og:description" content="Skapiraj da možeš da skapiraš.">
  <meta property="og:url" content="https://tatamata.rs">

  <?php if ($currentPage != '/kupovina-kursa.php') { ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php } ?>

  <title>TataMata</title>
  <!-- BOOTSTRAP -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
  <!-- CUSTOM CSS -->
  <link rel="stylesheet" href="<?php echo BASE_URL . "public/css/styles.css" ?>"> <!-- Ovde treba putanja kao da smo u index.php fajlu a ne u header.php -->
  <!-- FONT AWESOME -->
  <script src="https://kit.fontawesome.com/5c5689b7a2.js"></script>
  <!-- PLAYER -->
  <link rel="stylesheet" href="https://cdn.plyr.io/3.6.4/plyr.css">
  <!-- FAVICON -->
  <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>public/images/favicon.ico">
  <!-- OWL CAROUSEL -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css" integrity="sha512-X/RSQYxFb/tvuz6aNRTfKXDnQzmnzoawgEQ4X8nZNftzs8KFFH23p/BA6D2k0QCM4R0sY1DEy9MIY9b3fwi+bg==" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css" integrity="sha512-f28cvdA4Bq3dC9X9wNmSx21rjWI+5piIW/uoc2LuQ67asKxfQjUow2MkcCNcfJiaLrHcGbed1wzYe3dlY4w9gA==" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.transitions.min.css" integrity="sha512-1rUauSOKExLvRq1W54SZexX5zRJqdYr8imrbspYyy1jLSWYcHqQRej2sBVJU/fhkC8Gu5wT4rEZ/nrSn8IeuhQ==" crossorigin="anonymous" />
  <script defer src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js" integrity="sha512-9CWGXFSJ+/X0LWzSRCZFsOPhSfm6jbnL+Mpqo0o8Ke2SYr8rCTqb4/wGm+9n13HtDE1NQpAEOrMecDZw4FXQGg==" crossorigin="anonymous"></script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
  <?php include_once "includes/googleAnal.php"; ?>

  <!-- REGISTER -->
  <?php if ($currentPage == '/registracija.php') { ?>
    <script defer src="<?php echo BASE_URL; ?>public/js/register.js"></script>
  <?php } ?>
</head>

<body id="style-7">

  <button id="scroll-to-top" style="display: none" class="btn btn-primary"><i class="fas fa-angle-double-up"></i></button>

  <div class="scrollbar" id="style-8">
    <div class="force-overflow"></div>
  </div>


  <div id="body-container" class="<?php if ($currentPage == '/index.php') echo "home"; ?>">

    <?php if ($currentPage == '/index.php') { ?>
      <div class="max-width-90">
      <?php } ?>

      <?php if ($currentPage != '/prijava.php' && $currentPage != '/registracija.php' && $currentPage != '/zaboravljena-lozinka.php' && $currentPage != '/kreiraj-novu-sifru.php') { ?>
        <!-- NAVBAR -->
        <div class="navbar-container-custom fixed-navbar-bg">
          <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid navbar-container">
              <a class="navbar-brand scale-btn-2" href="<?php echo BASE_URL . "pocetna"; ?>"><img src="<?php echo BASE_URL . 'public/images/LOGO_VEKTOR.svg' ?>" alt=""></a>
              <div class="mobile-nav-container d-flex justify-content-between">
                <a class="brand-mobile" href="<?php echo BASE_URL . "pocetna"; ?>"><img src="<?php echo BASE_URL . 'public/images/LOGO_VEKTOR.svg' ?>" alt=""></a>
                <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
              </div>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav left-nav ms-auto mb-2 mb-lg-0">
                  <?php if (Database::getInstance()->isUserLoggedIn()) { ?>
                    <li class="nav-item">
                      <a class="nav-link me-4 moji-kursevi scale-btn-2" href="<?php echo BASE_URL . "mojikursevi"; ?>">MOJI KURSEVI</a>
                    </li>
                  <?php } ?>
                  <li class="nav-item">
                    <a class="nav-link active scale-btn-2 skrr" href="<?php echo BASE_URL . "kursevi"; ?>">KURSEVI</a>
                  </li>
                </ul>

                <ul class="navbar-nav left-nav mx-auto mb-2 mb-lg-0">
                  <!-- <li class="nav-item">
                    <a class="nav-link middle-nav ms-0 close-nav-link" href="<?php echo BASE_URL . "pocetna#o-brendu"; ?>">BREND</a>
                  </li> -->
                  <li class="nav-item ms-0">
                    <a class="nav-link middle-nav close-nav-link" href="<?php echo BASE_URL . "pocetna#usluge"; ?>">USLUGE</a>
                  </li>
                  <li class="nav-item ms-0">
                    <a class="nav-link middle-nav close-nav-link" href="<?php echo BASE_URL . "pocetna#testimonials"; ?>">PREPORUKE</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link middle-nav close-nav-link" href="<?php echo BASE_URL . "pocetna#faq"; ?>">NAJČEŠĆA PITANJA</a>
                  </li>
                  <li class="nav-item contact-nav">
                    <a class="nav-link middle-nav close-nav-link" href="<?php echo BASE_URL . "pocetna#kontakt"; ?>">KONTAKT</a>
                  </li>
                </ul>

                <ul class="navbar-nav mb-2 mb-lg-0">
                  <?php if (!Database::getInstance()->isUserLoggedIn()) { ?>
                    <li class="nav-item">
                      <a class="nav-link prijava-nav" href="<?php echo BASE_URL . "prijava"; ?>">PRIJAVI SE</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link registracija-nav scale-btn-2" href="<?php echo BASE_URL . "registracija"; ?>">NOVI KORISNIK</a>
                    </li>
                  <?php } else { ?>
                    <li class="nav-item dropdown">
                      <a class="nav-link logged-in-as<?php if (isset($currentPage) && $currentPage == '/profil.php') echo ' active'; ?> dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="far fa-user"></i>&nbsp; <?php echo $_SESSION['user']->firstname . " " . $_SESSION['user']->lastname; ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill rotate" viewBox="0 0 16 16">
                          <path d="M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                        </svg>
                      </a>
                      <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                          <a class="dropdown-item" href="<?php echo BASE_URL . "profil"; ?>">
                            <div class="row">
                              <div class="col">Profil</div>
                              <div class="col text-right" style="text-align: right;"><i class="far fa-user"></i></div>
                            </div>
                          </a>
                        </li>
                        <li>
                          <hr class="dropdown-divider">
                        </li>
                        <li>
                          <a class="dropdown-item" href="<?php echo BASE_URL . "transakcije"; ?>">
                            <div class="row">
                              <div class="col">Transakcije</div>
                              <div class="col text-right" style="text-align: right;"><i class="fas fa-euro-sign"></i></div>
                            </div>
                          </a>
                        </li>
                        <li>
                          <hr class="dropdown-divider">
                        </li>
                        <?php if ($_SESSION['user']->is_admin) { ?>
                          <li>
                            <a class="dropdown-item" href="<?php echo BASE_URL . "admin/overview"; ?>">
                              <div class="row">
                                <div class="col">Admin</div>
                                <div class="col" style="text-align: right;">
                                  <i class="fas fa-crown"></i>
                                </div>
                              </div>
                            </a>
                          </li>
                          <li>
                            <hr class="dropdown-divider">
                          </li>
                        <?php } ?>
                        <li>
                          <a class="dropdown-item" href="<?php echo BASE_URL . "odjava"; ?>">
                            <div class="row">
                              <div class="col">Odjavi se</div>
                              <div class="col text-right" style="text-align: right;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                                  <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0v2z" />
                                  <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z" />
                                </svg>
                              </div>
                            </div>
                          </a>
                        </li>
                      </ul>
                    </li>
                  <?php } ?>
                </ul>

              </div>
            </div>
          </nav>
          <!-- NAVBAR -->
        </div>
      <?php } ?>