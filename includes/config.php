<?php

if ($_SERVER['SERVER_NAME'] == "localhost") {
  define("BASE_URL", "https://" . $_SERVER['SERVER_NAME'] . '/karagaca/');
} else {
  define("BASE_URL", "https://" . $_SERVER['SERVER_NAME'] . '/');
}

// Cache busting - automatski se menja pri svakom deployu (na osnovu datuma izmene styles.css)
define("ASSET_VERSION", @filemtime($_SERVER['DOCUMENT_ROOT'] . '/public/css/styles.css') ?: '1');
