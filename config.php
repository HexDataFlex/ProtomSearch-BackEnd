<?php
ini_set("display_errors", true);
date_default_timezone_set("Europe/Bratislava"); // http://www.php.net/manual/en/timezones.php
define("DB_DSN", "mysql:host=localhost;dbname=protomsearch");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "x");
define("CLASS_PATH", "classes");

define("TOKEN_EXP", 1800);

// --------------------------------------------------------------------
// DANGER ZONE ! DANGER ZONE ! DANGER ZONE ! DANGER ZONE ! DANGER ZONE
// --------------------------------------------------------------------

// Turning on this setting will disable authentication for all.
define("DISABLE_AUTH", false);

// --------------------------------------------------------------------
// DANGER ZONE ! DANGER ZONE ! DANGER ZONE ! DANGER ZONE ! DANGER ZONE
// --------------------------------------------------------------------

include_once CLASS_PATH . '/DB.php';
include_once CLASS_PATH . '/Token.php';
include_once CLASS_PATH . '/Authorization.php';

function handleException($exception)
{
    echo json_encode(array("error" => "An unknown error occurred"));
    error_log($exception->getMessage() . "\n", 3, "/srv/http/protomsearch.log");
    http_response_code(500);
    exit();
}

set_exception_handler('handleException');
