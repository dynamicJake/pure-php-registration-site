<?php # Script 18.3 - config.inc.php
/* This script:
 * - define constants and settings
 * - dictates how errors are handled
 * - defines useful functions
 */
 
// Document who created this site, when, why, etc.


// ********************************** //
// ************ SETTINGS ************ //

// Flag variable for site status:
define('LIVE', FALSE);

// Admin contact address:
define('EMAIL', 'jacob@signamarketing.com');

define('SITE_EMAIL', 'From: site@registration.local');

// Site URL (base for all redirections):
define ('BASE_URL', 'http://registration.local/');

// Location of the MySQL connection script:
define ('MYSQL', 'includes/mysqli_connect.php');

// Adjust the time zone for PHP 5.1 and greater:
date_default_timezone_set ('US/Eastern');

// ************ SETTINGS ************ //
// ********************************** //


// ****************************************** //
// ************ ERROR MANAGEMENT ************ //

// TODO: replace mail() function with phpmailer for reliability. 

// error handler function
function myErrorHandler($e_number, $e_message, $e_file, $e_line, $e_vars) {

	// Build the error message:
	$message = "An error occurred in script '$e_file' on line $e_line: $e_message\n";
	
	// Add the date and time:
    $message .= "Date/Time: " . date('n-j-Y H:i:s') . "\n";
    

    if (!(error_reporting() & $e_number)) {
        // This error code is not included in error_reporting, so let it fall
        // through to the standard PHP error handler
        return false;
    }

    switch ($e_number) {
        case E_USER_ERROR:
            if (!LIVE) {
                echo "<b>User ERROR</b> [$e_number] $e_message<br />\n";
                echo "  Fatal error on line $e_line in file $e_file";
                echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
                echo "Aborting...<br />\n";
            } else {
                // Send an email to the admin: 
                $body = $message . "\n" . print_r ($e_vars, 1);
                mail(EMAIL, 'User Error', $body, 'From: error@example.com');
            }
            exit(1);
            break;

        case E_USER_WARNING:
            if (!LIVE) {
                echo "<b>User WARNING</b> [$e_number] $e_message<br />\n";
                break;
            } else {
                // Send an email to the admin:
                $body = $message . "\n" . print_r ($e_vars, 1);
                mail(EMAIL, 'User Warning', $body, 'From: error@example.com');
            }

        case E_USER_NOTICE:
            if (!LIVE) {
                echo "<b>My NOTICE</b> [$e_number] $e_message<br />\n";
                break;
            } else {
                // Send an email to the admin:
                $body = $message . "\n" . print_r ($e_vars, 1);
                mail(EMAIL, 'Error Notice', $body, 'From: error@example.com');
            }

        default:
        if (!LIVE) {
            echo "Unknown error type: [$e_number] $e_message<br />\n";
            break;
        } else {
            // Send an email to the admin:
            $body = $message . "\n" . print_r ($e_vars, 1);
            mail(EMAIL, 'Unknown Error', $body, 'From: error@example.com');
        }
    } // End switch statement

    /* Don't execute PHP internal error handler */
    return true;
}


// Use my error handler:
set_error_handler ('myErrorHandler');

// ************ ERROR MANAGEMENT ************ //
// ****************************************** //


// Testing error handling!!!!
// function to test the error handling
function scale_by_log($vect, $scale)
{
    if (!is_numeric($scale) || $scale <= 0) {
        trigger_error("log(x) for x <= 0 is undefined, you used: scale = $scale", E_USER_ERROR);
    }

    if (!is_array($vect)) {
        trigger_error("Incorrect input vector, array of values expected", E_USER_WARNING);
        return null;
    }

    $temp = array();
    foreach($vect as $pos => $value) {
        if (!is_numeric($value)) {
            trigger_error("Value at position $pos is not a number, using 0 (zero)", E_USER_NOTICE);
            $value = 0;
        }
        $temp[$pos] = log($scale) * $value;
    }

    return $temp;
}

// set to the user defined error handler
$old_error_handler = set_error_handler("myErrorHandler");

// trigger some errors, first define a mixed array with a non-numeric item
// echo "vector a\n";
// $a = array(2, 3, "foo", 5.5, 43.3, 21.11);
// print_r($a);

// now generate second array
// echo "----\nvector b - a notice (b = log(PI) * a)\n";
/* Value at position $pos is not a number, using 0 (zero) */
// $b = scale_by_log($a, M_PI);
// print_r($b);

// this is trouble, we pass a string instead of an array
// echo "----\nvector c - a warning\n";
/* Incorrect input vector, array of values expected */
// $c = scale_by_log("not array", 2.3);
// var_dump($c); // NULL

// this is a critical error, log of zero or negative number is undefined
// echo "----\nvector d - fatal error\n";
/* log(x) for x <= 0 is undefined, you used: scale = $scale" */
// $d = scale_by_log($a, -2.5);
// var_dump($d); // Never reached
?>