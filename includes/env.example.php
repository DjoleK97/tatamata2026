<?php
/**
 * Tatamata - Environment Configuration PRIMER
 *
 * Kopirajte ovaj fajl u includes/env.php i unesite prave vrednosti.
 * NIKADA ne komitovati includes/env.php u git!
 *
 * Generisanje CSRF_SECRET:
 *   php -r "echo bin2hex(random_bytes(32));"
 */

// ===== DATABASE =====
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    define('DB_HOST',     'localhost');
    define('DB_NAME',     'karagaca');         // naziv lokalne baze
    define('DB_USER',     'root');
    define('DB_PASS',     '');
} else {
    define('DB_HOST',     'localhost');
    define('DB_NAME',     'NAZIV_BAZE');       // npr. tatamatars_main
    define('DB_USER',     'KORISNIK_BAZE');    // npr. tatamatars_admin
    define('DB_PASS',     'JAKA_LOZINKA');
}

// ===== EMAIL (SMTP) =====
define('SMTP_HOST',        'mail.tatamata.rs');
define('SMTP_USER',        'admin@tatamata.rs');
define('SMTP_PASS',        'SMTP_LOZINKA');
define('SMTP_PORT',        25);
define('SMTP_ADMIN_EMAIL', 'tvoj@email.com');

// ===== SESSION =====
define('SESSION_LIFETIME', 3600);   // 1 sat u sekundama

// ===== SECURITY =====
// Generisati sa: php -r "echo bin2hex(random_bytes(32));"
define('CSRF_SECRET', 'PROMENITI_NA_RANDOM_64_HEX_KARAKTERA');
