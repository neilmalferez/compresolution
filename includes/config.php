<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'compresolution_db');

define('BASE_URL', 'http://localhost/projects/compresolution/');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');


ini_set('display_errors', 1);
error_reporting(E_ALL);
