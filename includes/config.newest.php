<?php 

// ********************************** //
// ************ SETTINGS ************ //

// Flag variable for site status:
    define('LIVE', true);

    // Admin contact address:
    define('EMAIL', 'jaureguijacob57@gmail.com');
    
    define('SITE_EMAIL', 'From: site@registration.local');
    
    // Site URL (base for all redirections):
    define ('BASE_URL', 'http://registration.local/');
    
    // Location of the MySQL connection script:
    define ('MYSQL', 'includes/mysqli_connect.php');
    
    // Adjust the time zone for PHP 5.1 and greater:
    date_default_timezone_set ('US/Eastern');
    
// ************ SETTINGS ************ //
// ********************************** //



// ***************************************** //
// ************ Error Handling ************ //
 
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");

function errorHandler($error_level, $error_message, $error_file, $error_line, $error_context)
{
    $error = "<br><br>" . $error_message . "<br><br>Location: " . $error_file . " <strong>on line: " . $error_line . "</strong>" ."<br>Error Level: " . $error_level;
    switch ($error_level) {
        case E_ERROR:
            if (!LIVE) {
                echo 'Error: ';
            }
        case E_CORE_ERROR:
            if (!LIVE) {
                echo 'Error: ';
            }
        case E_COMPILE_ERROR:
            if (!LIVE) {
                echo 'Compile Error: ';
            }
        case E_PARSE:
            mylog($error, "Parse");
            break;
        case E_USER_ERROR:
        case E_RECOVERABLE_ERROR:
            mylog($error, "Recoverable error");
            break;
        case E_WARNING:
        case E_CORE_WARNING:
        case E_COMPILE_WARNING:
        case E_USER_WARNING:
            mylog($error, "User Warning");
            break;
        case E_NOTICE:
        case E_USER_NOTICE:
            mylog($error, "Warning");
            break;
        case E_STRICT:
            mylog($error, "Strict Debug");
            break;
        default:
            mylog($error, "Bug found on site");
    }
}

function shutdownHandler() //will be called when php script ends.
{
$lasterror = error_get_last();
switch ($lasterror['type'])
{
    case E_ERROR:
    case E_CORE_ERROR:
    case E_COMPILE_ERROR:
    case E_USER_ERROR:
    case E_RECOVERABLE_ERROR:
    case E_CORE_WARNING:
    case E_COMPILE_WARNING:
    case E_PARSE:
        $error = "[SHUTDOWN] | msg:" . $lasterror['message'] . "<br><br>in file:" . $lasterror['file'] . " on line: " . $lasterror['line'];
        mylog($error, "Fatal error");
}
}

function mylog($error, $errlvl)
{

    if (!LIVE) {
        echo "$errlvl: $error";
    } else {
        mail(EMAIL, 'MyLog Error', $error, 'From: error@example.com');
    }
}

// ************ Error Handling ************ //
// **************************************** //



?>