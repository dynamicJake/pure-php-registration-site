<?php
//create_cat.php
require_once ('includes/config.new.php'); 
$page_title = 'Create Category';
include_once ('includes/functions.php');
include ('includes/header.php');
 
if($_SERVER['REQUEST_METHOD'] != 'POST') { ?>
    <!-- the form hasn't been posted yet, display it -->
    <h2>Create a new Category</h2>
    <form method='post' action=''>
        <p>
            <strong>Category Name:</strong>
            <input type='text' name='cat_name' />
        </p>
        <p>
            <strong>Give a Description:</strong>
            <textarea spellcheck="true" name='cat_description' /></textarea>
        </p>        
        <div style="text-align:center;margin-top:5px;"><input type='submit' value='Add category' /></div>
    </form>
    
<?php
    include ('includes/footer.php');
}
else
{
	// Need the database connection:
	require (MYSQL);
	
	// Trim all the incoming data:
    $trimmed = array_map('trim', $_POST);

    $cn = $cd = FALSE;

    if ($trimmed['cat_name']) {
        $cn = mysqli_real_escape_string ($dbc, $trimmed['cat_name']);
        $cn = test_input($cn);
	} else {
		echo '<p class="error">Please enter a proper category name!</p>';
    }
    
    if ($trimmed['cat_description']) {
        $cd = mysqli_real_escape_string ($dbc, $trimmed['cat_description']);
        $cd = test_input($cd);
	} else {
		echo '<p class="error">Please enter a proper category name!</p>';
	}
    
    if ($cn && $cd) {

        $q = "SELECT cat_name FROM categories WHERE cat_name='$cn'";
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
		if (mysqli_num_rows($r) == 0) { // Available.

            //the form has been posted, so save it
            $q = "INSERT INTO categories(cat_name, cat_description) VALUES ('$cn','$cd')";
            $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));

            if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

                // Send the email:
                $body = "Check out the new category: $cn\n";
                $body .= "Description: $cd";

                // TODO: update mail function to use PHPMailer as used in contact-us.php page
                mail(EMAIL, 'New Category Created!', $body, 'From: admin@sitename.com');
                
                // Finish the page:
                echo "<h2>Great!</h2>";
                echo "<p><strong>$cn</strong> has been added as a new category!<br></p>";
                echo '<a class="btn" href="create_cat.php" title="Create another category">Create another</a>';
                include ('includes/footer.php'); // Include the HTML footer.
                exit(); // Stop the page.
                
            } else { // If it did not run OK.
                echo '<p class="error">Category could not be added!</p>';
            }
        } else {
            echo '<h2>Oh no!</h2>';
            echo '<p class="error">Category already exists!</p>';
            echo '<a class="btn" href="create_cat.php" title="Create category">try again</a>';
            include ('includes/footer.php');
        }

        mysqli_close($dbc);


    }

}
?>