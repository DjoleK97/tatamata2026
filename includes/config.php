<?php

if ($_SERVER['SERVER_NAME'] == "localhost") {
  define("BASE_URL", "https://" . $_SERVER['SERVER_NAME'] . '/karagaca/');
} else {
  define("BASE_URL", "https://" . $_SERVER['SERVER_NAME'] . '/');
}
