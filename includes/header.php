<?php require_once "classes/Database.php"; ?>
<?php require_once "includes/config.php"; ?>
<?php
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
  $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: ' . $location);
  exit;
}

// SEC-FIX: HTTP Security Headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: camera=(), microphone=(), geolocation=()");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
// Content Security Policy - dozvoljava CDN resurse koje projekat koristi
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://ajax.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://kit.fontawesome.com https://cdn.plyr.io https://www.googletagmanager.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://cdn.plyr.io https://fonts.googleapis.com; img-src 'self' data: https:; font-src 'self' https://kit.fontawesome.com https://ka-f.fontawesome.com https://cdnjs.cloudflare.com https://fonts.gstatic.com; connect-src 'self' https://ka-f.fontawesome.com https://www.google-analytics.com; media-src 'self'; frame-ancestors 'self';");

$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], '/')); //  /prijava /registracija /index.php
?>

<!DOCTYPE html>
<html lang="sr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="site_name" property="og:site_name" content="TataMata">
  <meta name="image" property="og:image" content="<?php echo BASE_URL; ?>public/images/udjinawa.png">
  <meta name="HandheldFriendly" property="True">
  <meta name="MobileOptimized" content="320">
  <meta name="type" property="og:type" content="website">
  <meta name="title" property="og:title" content="TataMata | Matematika na laksi nacin">
  <meta name="author" content="TataMata">
  <meta property="og:description" content="Video kursevi matematike za osnovnu skolu, srednju skolu i fakultet.">
  <meta property="og:url" content="https://tatamata.rs">

  <?php if ($currentPage != '/kupovina-kursa.php') { ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php } ?>

  <title>TataMata</title>
  <!-- GOOGLE FONTS: POPPINS -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <!-- BOOTSTRAP -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
  <!-- CUSTOM CSS -->
  <link rel="stylesheet" href="<?php echo BASE_URL . "public/css/styles.css?v=" . ASSET_VERSION ?>">
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

</head>

<body id="style-7">

  <button id="scroll-to-top" style="display: none" class="btn btn-primary"><i class="fas fa-angle-double-up"></i></button>

  <div class="scrollbar" id="style-8">
    <div class="force-overflow"></div>
  </div>


  <div id="body-container" class="<?php if ($currentPage == '/index.php') echo "home"; ?>">


      <?php if ($currentPage != '/prijava.php' && $currentPage != '/registracija.php' && $currentPage != '/zaboravljena-lozinka.php' && $currentPage != '/kreiraj-novu-sifru.php') { ?>
        <!-- NAVBAR -->
        <div class="navbar-container-custom fixed-navbar-bg">
          <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid navbar-container">
              <a class="navbar-brand" href="<?php echo BASE_URL . "pocetna"; ?>"><img src="<?php echo BASE_URL . 'public/images/LOGO_VEKTOR.svg?v=' . ASSET_VERSION ?>" alt="TataMata"></a>
              <div class="mobile-nav-container justify-content-between">
                <a class="brand-mobile" href="<?php echo BASE_URL . "pocetna"; ?>"><img src="<?php echo BASE_URL . 'public/images/LOGO_VEKTOR.svg?v=' . ASSET_VERSION ?>" alt="TataMata"></a>
                <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
                </button>
              </div>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                  <?php if (Database::getInstance()->isUserLoggedIn()) { ?>
                    <li class="nav-item">
                      <a class="nav-link moji-kursevi" href="<?php echo BASE_URL . "mojikursevi"; ?>">Moji kursevi</a>
                    </li>
                  <?php } ?>
                  <li class="nav-item">
                    <a class="nav-link active-link" href="<?php echo BASE_URL . "kursevi"; ?>">Kursevi</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link close-nav-link" href="<?php echo BASE_URL . "pocetna#usluge"; ?>">Usluge</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link close-nav-link" href="<?php echo BASE_URL . "pocetna#testimonials"; ?>">Preporuke</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link close-nav-link" href="<?php echo BASE_URL . "pocetna#faq"; ?>">FAQ</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link close-nav-link" href="<?php echo BASE_URL . "pocetna#kontakt"; ?>">Kontakt</a>
                  </li>
                </ul>

                <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
                  <?php if (!Database::getInstance()->isUserLoggedIn()) { ?>
                    <li class="nav-item">
                      <a class="nav-link prijava-nav" href="<?php echo BASE_URL . "prijava"; ?>">Prijavi se</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link registracija-nav" href="<?php echo BASE_URL . "registracija"; ?>">Kreiraj nalog <i class="fas fa-arrow-right ms-1" style="font-size:.75rem;"></i></a>
                    </li>
                  <?php } else { ?>
                    <li class="nav-item dropdown">
                      <a class="nav-link logged-in-as<?php if (isset($currentPage) && $currentPage == '/profil.php') echo ' active'; ?> dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="far fa-user"></i>&nbsp; <?php echo $_SESSION['user']->firstname . " " . $_SESSION['user']->lastname; ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-caret-down-fill rotate ms-1" viewBox="0 0 16 16">
                          <path d="M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                        </svg>
                      </a>
                      <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                          <a class="dropdown-item" href="<?php echo BASE_URL . "profil"; ?>">
                            <i class="far fa-user"></i> Profil
                          </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                          <a class="dropdown-item" href="<?php echo BASE_URL . "transakcije"; ?>">
                            <i class="fas fa-euro-sign"></i> Transakcije
                          </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <?php if ($_SESSION['user']->is_admin) { ?>
                          <li>
                            <a class="dropdown-item" href="<?php echo BASE_URL . "admin/overview"; ?>">
                              <i class="fas fa-crown"></i> Admin
                            </a>
                          </li>
                          <li><hr class="dropdown-divider"></li>
                        <?php } ?>
                        <li>
                          <a class="dropdown-item" href="<?php echo BASE_URL . "odjava"; ?>">
                            <i class="fas fa-sign-out-alt"></i> Odjavi se
                          </a>
                        </li>
                      </ul>
                    </li>
                  <?php } ?>
                </ul>

              </div>
            </div>
          </nav>
        </div>
      <?php } ?>
