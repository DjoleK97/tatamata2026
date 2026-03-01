<?php

// ============================================================
// SEC-FIX: CSRF zaštita
// ============================================================

/**
 * Generiše CSRF token i čuva ga u sesiji.
 * Pozivati jednom po sesiji (ili po formi ako je potrebno).
 */
function csrf_generate(): string {
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}

/**
 * Vraća HTML hidden input sa CSRF tokenom.
 * Dodati unutar svake <form> koja menja podatke.
 */
function csrf_field(): string {
  return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_generate()) . '">';
}

/**
 * Verifikuje CSRF token iz POST zahteva.
 * Vraća true ako je validan, false ako nije.
 */
function csrf_verify(): bool {
  if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token'])) {
    return false;
  }
  return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

/**
 * Proverava CSRF i gasi skriptu ako nije validan.
 * Koristiti na vrhu svakog POST handlera.
 */
function csrf_protect(): void {
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_verify()) {
    http_response_code(403);
    error_log('[TataMata] CSRF token nevalidan - ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
    die('<div class="alert alert-danger container mt-4">Nevažeći zahtev. Osvežite stranu i pokušajte ponovo.</div>');
  }
}

// ============================================================
// SEC-FIX: Validacija redirect URL-a (sprecava open redirect)
// ============================================================

/**
 * Proverava da li je redirect URL bezbedan (samo interni).
 * Vraća sigurnu fallback putanju ako nije.
 */
function safe_redirect(string $url, string $fallback = 'pocetna'): string {
  // Dozvoljeni prefiks - mora biti isti host
  $baseUrl = (defined('BASE_URL') ? BASE_URL : '/');
  if (strpos($url, $baseUrl) === 0 || strpos($url, '/') === 0) {
    // Proveri da ne sadrzi protokol (http:// ili //)
    if (!preg_match('/^(https?:)?\/\//i', ltrim($url, $baseUrl))) {
      return $url;
    }
  }
  return $baseUrl . $fallback;
}

// ============================================================

function clean($input) {
  $input = trim($input);
  $input = str_replace('"', "", $input);
  $input = str_replace("'", "", $input);
  $input = htmlspecialchars($input); // Mora ispod str_replace jer htmlspecialchars pretvara " u &nesto; i onda ga str_replace ne nadje

  return $input;
}

function isLettersOnly($text) {
  if (preg_match('/^[a-zA-ZšđčćžŠĐČĆŽ]+$/', $text)) {
    return true;
  }

  return false;
}

function isLettersAndSpacesOnly($text) {
  if (preg_match('/^[a-zA-ZšđčćžŠĐČĆŽ ]+$/', $text)) {
    return true;
  }

  return false;
}

function printFormatedFlashMessage($sessionName) {
  if (isset($_SESSION[$sessionName])) {
    echo $_SESSION[$sessionName];
    unset($_SESSION[$sessionName]);
  }
}

function printFlashMessage($sessionName) {
  if (isset($_SESSION[$sessionName])) {
    echo "<div class='container-fluid text-uppercase success-message-custom'>
            <div class='row'>
                <div class='col-md-10 col-sm-10 offset-sm-1 offset-md-1 p-0 mt-5'>
                    <div class='alert alert-success alert-dismissible fade show' role='alert'>
                      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                      <strong>" . $_SESSION[$sessionName] . " <i class='fas fa-check'></i></strong>
                    </div>
                </div>
            </div>
          </div>";
    unset($_SESSION[$sessionName]);
  }
}

function countUniqueLoginsForUser($loginDetails) {
  $array = [];
  $used = [];

  foreach ($loginDetails as $loginDetail) {
    $lgd = new LoginDetailHelp($loginDetail);
    array_push($array, $lgd);
  }

  $counter = 1;

  for ($i = 0; $i < sizeof($array); $i++) {
    array_push($used, $array[$i]);
    for ($j = $i + 1; $j < sizeof($array); $j++) {
      if (!$array[$i]->equals($array[$j]) && !in_array($array[$j], $used)) {
        array_push($used, $array[$j]);
        $counter++;
      }
    }
  }

  return $counter;
}

class LoginDetailHelp {
  public $gpu;
  public $cpu;
  public $ram;
  public $os;

  function __construct($loginDetail) {
    $this->ram = $loginDetail['ram'];
    $this->gpu = $loginDetail['gpu'];
    $this->cpu = $loginDetail['cpu_cores'];
    $this->os = $loginDetail['os'];
  }

  function equals(LoginDetailHelp $loginDetail) {
    if ($this->ram == $loginDetail->ram && $this->cpu == $loginDetail->cpu && $this->gpu == $loginDetail->gpu && $this->os == $loginDetail->os) {
      return true;
    }

    return false;
  }
}

function getAge($then) {
  $then = date('Ymd', strtotime($then));
  $diff = date('Ymd') - $then;

  return substr($diff, 0, -4);
}
