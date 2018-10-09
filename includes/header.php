<?php # Script 18.1 - header.php
// This page begins the HTML header for the site.

// Start output buffering:
ob_start();

// Initialize a session:
session_start();

// Check for a $page_title value:
if (!isset($page_title)) {
	$page_title = 'User Registration';
}
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/x-icon" href="favicon.png" />
	<title><?php echo $page_title; ?></title>
	<link rel="stylesheet" type="text/css" href="includes/layout.css">
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'/>
</head>
<body>
<div id="header">
	<h1>Registration</h1>
	<div style="float:right; font-size:14px; padding-right:15px;" id="userbar">
		<?php
			if(isset($_SESSION['user_name'])) {
				echo '<p>Hello ' . $_SESSION['user_name'] . '. Not you? <a href="logout.php">Sign out</a></p>';
			}
			else
			{
				echo '<p><a href="login.php">Sign in</a> or <a href="register.php">Register</a></p>';
			}
		?>
	</div>
</div>
<div id="content">


<!-- End of Header -->