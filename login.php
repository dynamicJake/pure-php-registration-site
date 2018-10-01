<?php # Script 18.8 - login.php
// This is the login page for the site.
require ('includes/config.new.php'); 
$page_title = 'Login';
include ('includes/header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require (MYSQL);
	
	// Validate the email address:
	if (!empty($_POST['username'])) {
		$e = mysqli_real_escape_string ($dbc, $_POST['username']);
	} else {
		$e = FALSE;
		echo '<p class="error">Please enter your username!</p>';
	}
	
	// Validate the password:
	if (!empty($_POST['pass'])) {
		$p = mysqli_real_escape_string ($dbc, $_POST['pass']);
	} else {
		$p = FALSE;
		echo '<p class="error">You forgot to enter your password!</p>';
	}
	
	if ($e && $p) { // If everything's OK.

		// Query the database:
		$q = "SELECT user_id, user_name, user_level, email FROM users_reg WHERE (user_name='$e' AND pass=SHA1('$p')) AND active IS NULL";		
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
		if (@mysqli_num_rows($r) == 1) { // A match was made.

			// Register the values:
			$_SESSION = mysqli_fetch_array ($r, MYSQLI_ASSOC); 
			mysqli_free_result($r);
			mysqli_close($dbc);
							
			// Redirect the user:
			$url = BASE_URL; // Define the URL.
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.
				
		} else { // No match was made.
			echo '<p class="error">Either the email address and password entered do not match those on file or you have not yet activated your account.</p>';
		}
		
	} else { // If everything wasn't OK.
		echo '<p class="error">Please try again.</p>';
	}
	
	mysqli_close($dbc);

} // End of SUBMIT conditional.
?>

<h1>Login</h1>
<p class="error">Your browser must allow cookies in order to log in.</p>
<form action="login.php" method="post">
	<fieldset>
	<input placeholder="Username" type="text" name="username" size="20" maxlength="60" /></p>
	<input placeholder="Password" type="password" name="pass" size="20" maxlength="20" /></p>
	<div align="center"><input type="submit" name="submit" value="Login" /></div>
	</fieldset>
</form>

<?php include ('includes/footer.php'); ?>