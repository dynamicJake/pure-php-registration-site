<?php # Script 18.6 - register.php
// This is the registration page for the site.
require ('includes/config.new.php');
$page_title = 'Register';
include ('includes/header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form.

	echo var_dump($_POST);
	// Need the database connection:
	require (MYSQL);
	
	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);
	echo var_dump($trimmed);

	// Assume invalid values:
	$un = $e = $p = FALSE;
	
	// Check for a username:
	if ($trimmed['user_name']) {
		$un = mysqli_real_escape_string ($dbc, $trimmed['user_name']);
	} else {
		echo '<p class="error">Please enter your Username!</p>';
	}

	// // Check for a last name:
	// if (preg_match ('/^[A-Z \'.-]{2,40}$/i', $trimmed['last_name'])) {
	// 	$ln = mysqli_real_escape_string ($dbc, $trimmed['last_name']);
	// } else {
	// 	echo '<p class="error">Please enter your last name!</p>';
	// }
	
	// Check for an email address:
	if (filter_var($trimmed['email'], FILTER_VALIDATE_EMAIL)) {
		$e = mysqli_real_escape_string ($dbc, $trimmed['email']);
	} else {
		echo '<p class="error">Please enter a valid email address!</p>';
	}

	// Check for a password and match against the confirmed password:
	if (preg_match ('/^\w{4,20}$/', $trimmed['password1']) ) {
		if ($trimmed['password1'] == $trimmed['password2']) {
			$p = mysqli_real_escape_string ($dbc, $trimmed['password1']);
		} else {
			echo '<p class="error">Your password did not match the confirmed password!</p>';
		}
	} else {
		echo '<p class="error">Please enter a valid password!</p>';
	}
	
	if ($un && $e && $p) { // If everything's OK...

		// Make sure the email address is available:
		$q = "SELECT user_id FROM users_reg WHERE email='$e'";
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
		if (mysqli_num_rows($r) == 0) { // Available.

			// Create the activation code:
			$a = md5(uniqid(rand(), true));

			// Add the user to the database:
			$q = "INSERT INTO users_reg(user_name, pass, email, active, registration_date) VALUES ('$un', SHA1('$p'), '$e', '$a', NOW() )";
			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));

			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

				// Send the email:
				$body = "Thank you for registering at <whatever site>. To activate your account, please click on this link:\n\n";
				$body .= BASE_URL . 'activate.php?x=' . urlencode($e) . "&y=$a";
				mail($trimmed['email'], 'Registration Confirmation', $body, 'From: admin@sitename.com');
				
				// Finish the page:
				echo '<h3>Thank you for registering! A confirmation email has been sent to your address. Please click on the link in that email in order to activate your account.</h3>';
				include ('includes/footer.php'); // Include the HTML footer.
				exit(); // Stop the page.
				
			} else { // If it did not run OK.
				echo '<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';
			}
			
		} else { // The email address is not available.
			echo '<p class="error">That email address has already been registered. If you have forgotten your password, use the link at right to have your password sent to you.</p>';
		}
		
	} else { // If one of the data tests failed.
		echo '<p class="error">Please try again.</p>';
	}

	mysqli_close($dbc);

} // End of the main Submit conditional.
?>
	
<h2>Register</h2>
<form action="register.php" method="post">
	<p>
		<strong>Username:</strong> 
		<input type="text" name="user_name" size="20" maxlength="20" value="<?php if (isset($trimmed['user_name'])) echo $trimmed['user_name']; ?>" />
	</p>
	<p>
		<strong>Email Address:</strong> 
		<input type="text" name="email" size="30" maxlength="60" value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>" /> 
	</p>
	<p>
		<strong>Password:</strong>
		<input type="password" name="password1" size="20" maxlength="20" value="<?php if (isset($trimmed['password1'])) echo $trimmed['password1']; ?>" /> 
		<small class="warn">
			Use only letters, numbers, and the underscore. Must be between 4 and 20 characters long.
		</small>
	</p>
	<p>
		<strong>Confirm Password:</strong>
		<input type="password" name="password2" size="20" maxlength="20" value="<?php if (isset($trimmed['password2'])) echo $trimmed['password2']; ?>" />
	</p>
	<div align="center"><input type="submit" name="submit" value="Register" /></div>

</form>

<?php include ('includes/footer.php'); ?>