<?php
/**
 * Main Application Bootstrap file
 */

define( 'ABSPATH', dirname( __FILE__ ) );

session_start();

require_once "Log.class.php";
require_once "inc/db/Db.class.php";
require_once "inc/random_compat/lib/random.php"; // Needed for support of PHP<7
require_once "functions.php";