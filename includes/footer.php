<!-- Start of Footer -->


<div id="menu">
	<ul class="menuItems">
		<li><a href="/" title="Home Page">Home</a></li>
		<?php # Script 18.2 - footer.php
		// This page completes the HTML template.

		// Display links based upon the login status:
		if (isset($_SESSION['user_id'])) {

			echo '<li><a href="logout.php" title="Logout">Logout</a></li>
			<li><a href="change_password.php" title="Change Your Password">Change Password</a></li>
		';

			// Add links if the user is an administrator:
			if ($_SESSION['user_level'] == 2) {
				echo '<li><a href="view-users.php" title="View All Users">View Users</a></li>
				<li><a href="create_cat.php" title="Create category">Create Category</a></li>
			';
			}
		
		} else { //  Not logged in.
			echo '<li><a href="register.php" title="Register for the Site">Register</a></li>
			<li><a href="login.php" title="Login">Login</a></li>
			<li><a href="forgot_password.php" title="Password Retrieval">Retrieve Password</a></li>
		';
		}
		?>
		<li><a href="contact-us.php">Contact Us</a></li>
		<li><a href="#">Another Page</a></li>
	</ul>
</div><!-- Menu -->
</div><!-- Content -->
</body>
</html>
<?php // Flush the buffered output.
ob_end_flush();
?>