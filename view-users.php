<?php # Script 18.5 - index.php
// This is the main page for the site.

// Include the configuration file:
include ('includes/config.new.php'); 

// Set the page title and include the HTML header:
$page_title = 'Welcome to this Site!';
include ('includes/header.php');

// Welcome the user (by name if they are logged in):
echo '<h2>Welcome';
if (isset($_SESSION['user_name']) and ($_SESSION['user_level'] == 2)) {
	echo '!</h2>';
    echo '<p>Here are the current users</p>';
	require(MYSQL);

	$q = "SELECT * FROM users_reg ORDER BY user_id DESC";

	$r = mysqli_query($dbc, $q);

	if (mysqli_num_rows($r) > 0) {
		echo '<div class="category-list" style="list-style:none;padding:0;">';
					// output data of each row
					while($row = mysqli_fetch_assoc($r)) {
                        echo "
                        <div class=\"itemUser\">
                           <div class=\"user-info\"> 
                                <i class=\"fa fa-user\"></i>
                                <h3>" . $row["user_name"]. "</h3>
                            </div>
						<p> " . $row["email"]. "</p>" . "
						<div style=\"display:flex; align-items:center;\">
                        <p style=\"margin:0;padding-right: 15px;\"><strong>Level:</strong> ". $row["user_level"]. "</p>";
                        if ($_SESSION['user_level'] == 2) {
							echo '<div class="admin-item-options">';
							echo '<div style="text-align:right;"><a class="admin-icon" href="#edit"><span class="fa fa-edit"></span></a></div>';
							echo '<div style="text-align:right;"><a class="admin-icon" href="#delete"><span class="fa fa-trash-o"></span></a></div>';
							echo '</div>';
						}
						echo '</div>';
						echo '</div>';
					}
		echo '</div>';
	} else {
		echo "<p>0 results</p>";
	}

	mysqli_close($dbc);


} else { 	// User is not logged in 
	echo '!</h2>'; ?>
	<p>Try <a href="login.php" title="Login">logging in</a> or <a href="register" title="Sign up">signing up</a> to start view users!</p>
<?php
}
?>



<?php include ('includes/footer.php'); ?>