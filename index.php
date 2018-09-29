<?php # Script 18.5 - index.php
// This is the main page for the site.

// Include the configuration file:
require ('includes/config.new.php'); 

// Set the page title and include the HTML header:
$page_title = 'Welcome to this Site!';
include ('includes/header.html');

// Welcome the user (by name if they are logged in):
echo '<h1>Welcome';
if (isset($_SESSION['first_name'])) {
	echo ", {$_SESSION['first_name']}";
}
echo '!</h1>';

$input = array(
	0 => "apricot", 
	1 => "banana", 
	2 => "cherry", 
	3 => "dewberry", 
	4 => "eggplant", 
	5 => "fig"
);
echo "<br>";
$output_trimmed = array_map("trim", $input);
// echo "input = $input";
echo var_dump($output_trimmed);
if (count($output_trimmed) > 1) {
	echo '<select>';
	foreach ($output_trimmed as $key => $value) {
		echo "<option style=\"text-transform:capitalize;\" value=". $value . "> $value </option>";
	}
	echo '</select>';
}
foreach ($output_trimmed as $key => $value) {
	echo "<br>$value<br>";
}
?>
<p>Spam spam spam spam spam spam
spam spam spam spam spam spam 
spam spam spam spam spam spam 
spam spam spam spam spam spam.</p>
<p>Spam spam spam spam spam spam
spam spam spam spam spam spam 
spam spam spam spam spam spam 
spam spam spam spam spam spam.</p>

<?php include ('includes/footer.html'); ?>