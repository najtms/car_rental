<?php

// Reporting

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//  pokazi sve error osim notice erros or deprecated errors
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));

// Podaci od Database
define('DB_NAME', 'Car_Rental');
define('DB_PORT', 3306);
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');
define('DB_HOST', '127.0.0.1');
