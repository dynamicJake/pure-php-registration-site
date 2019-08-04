<?php


require ('includes/config.new.php');
include 'includes/functions.php';

// Set the page title and include the HTML header:
$page_title = 'Welcome to this Site!';
include('includes/header.php');

$hack = '<script>';
$hack .= 'alert("message successfully sent");';
$hack .= '</script>';
$hack .= 'hello there nothing to see here <script>except</script>';


// Testing Sanitation
$hack = removeTagsWithTheirContent(array('script'), $hack);
echo 'After using removeTagsWithTheirContent()<br>';
$hack = test_input($hack);
echo $hack;


include('includes/footer.php');

?>