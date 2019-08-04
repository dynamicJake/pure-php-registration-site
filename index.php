<?php # Script 18.5 - index.php
// This is the main page for the site.

include ('includes/config.new.php'); 

$page_title = 'Welcome to this Site!';
include ('includes/header.php');

// Welcome the user (by name if they are logged in):
echo '<h2>Welcome';
if (isset($_SESSION['user_name'])) {
	echo ", {$_SESSION['user_name']}";
	echo '!</h2>';
	echo '<p>Select a category you are interested in!</p>';
	require(MYSQL);

	$q = "SELECT * FROM categories ORDER BY cat_id DESC";

	$r = mysqli_query($dbc, $q);

	if (mysqli_num_rows($r) > 0) {
		echo '<div class="category-list" style="list-style:none;padding:0;">';
					// output data of each row
					while($row = mysqli_fetch_assoc($r)) {
						echo "<div class=\"item\"><h3>" . $row["cat_name"]. "</h3><p> " . $row["cat_description"]. "</p>";
						if ($_SESSION['user_level'] == 2) {
							echo '<div class="admin-item-options">';
							echo '<div style="text-align:right;"><a class="admin-icon" href="#edit"><span class="fa fa-edit"></span></a></div>';
							echo '<div style="text-align:right;"><a class="admin-icon" href="#delete"><span class="fa fa-trash-o"></span></a></div>';
							echo '</div>';
						}
						echo '</div>';
					}
		echo '</div>';
	} else {
		echo "<p>0 results</p>";
	}

	mysqli_close($dbc);


} else { 	// User is not logged in 
	echo '!</h2>'; 
	echo $b;
	?>
	<p>Try <a href="login.php" title="Login">logging in</a> or <a href="register" title="Sign up">signing up</a> to start viewing the forums!</p>
<?php
}
?>



<?php include ('includes/footer.php'); ?>