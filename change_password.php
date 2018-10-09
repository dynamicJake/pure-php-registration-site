<?php # Script 18.11 - change_password.php
// This page allows a logged-in user to change their password.
require ('includes/config.new.php'); 
$page_title = 'Change Your Password';
include ('includes/header.php');

// If no user_name session variable exists, redirect the user:
if (!isset($_SESSION['user_name'])) {
	
	$url = BASE_URL; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require (MYSQL);
			
	// Check for a new password and match against the confirmed password:
	$p = FALSE;
	if (preg_match ('/^(\w){4,20}$/', $_POST['password1']) ) {
		if ($_POST['password1'] == $_POST['password2']) {
			$p = mysqli_real_escape_string ($dbc, $_POST['password1']);
		} else {
			echo '<p class="error">Your password did not match the confirmed password!</p>';
		}
	} else {
		echo '<p class="error">Please enter a valid password!</p>';
	}
	
	if ($p) { // If everything's OK.

		// Make the query:
		$q = "UPDATE users_reg SET pass=SHA1('$p') WHERE user_id={$_SESSION['user_id']} LIMIT 1";	
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

			// compose message
			$message = "
			<html>
			<head>
				<title>Your password was changed</title>
			</head>
			<body>
			<h1>Your Password was Changed</h1>
			<p>Your password to log into <i>registration.local</i> has been changed 
			to '<strong>$p</strong>'. Use this password to log back into your account.</p>
			<br>
			<p>If you did not request a password change please <a href=\"http://registration.local/contact-us.php\">contact us</a>.</p>
			</body>
			</html>
			";
			$message = wordwrap($message, 40);

			// To send HTML mail, the Content-type header must be set
			$headers = "From: Registration Site <noreply@registration.local>\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

			// Send an email
			mail ($_SESSION['email'], 'Password Change Confirmation', $message, $headers);
			echo '<h3>Your password has been changed.</h3>';
			echo '<p>An email has been sent to confirm this change.</p>';
			mysqli_close($dbc); // Close the database connection.
			include ('includes/footer.php'); // Include the HTML footer.
			exit();
			
		} else { // If it did not run OK.
		
			echo '<p class="error">Your password was not changed. Make sure your new password is different than the current password. Contact the system administrator if you think an error occurred.</p>'; 

		}

	} else { // Failed the validation test.
		echo '<p class="error">Please try again.</p>';		
	}
	
	mysqli_close($dbc); // Close the database connection.

} // End of the main Submit conditional.
?>

<h2>Change Your Password</h2>
<form action="change_password.php" method="post">
	<p>
		<b>New Password:</b>
		<input type="password" name="password1" size="20" maxlength="20" /> 
		<small class="warn">
			Use only letters, numbers, and the underscore. Must be between 4 and 20 characters long.
		</small>
	</p>
	<p>
		<b>Confirm New Password:</b>
		<input type="password" name="password2" size="20" maxlength="20" />
	</p>
	<div align="center"><input type="submit" name="submit" value="Change My Password" /></div>
</form>

<?php include ('includes/footer.php'); ?>