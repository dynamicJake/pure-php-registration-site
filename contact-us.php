<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require 'vendor/autoload.php';
include 'includes/functions.php';
require_once 'includes/config.new.php';
include_once 'vendor/phpmailer/phpmailer/src/SMTP.php';

/*
TESTING SPAM SCRIPTS
<script >alert('please confirm');</script>
*/


$msg = '';
if (isset($_POST['submit'])) {

	$subject = test_input($_POST['subject']);
	$email = test_input($_POST['email']);
	$message = '<p>'. nl2br($_POST['message']) . '</p>';

	
	$tagsToRemove = array('script', 'b');
	// echo htmlspecialchars($message);
	// $message= strip_tags($message, '<p><br><strong><l><ul>');
	$message = removeTagsWithTheirContent($tagsToRemove, $message, $subject);
	echo $message;

	$mail = new PHPMailer(true);                             // Passing `true` enables exceptions
	try {
	
		//Recipients
		$mail->setFrom(EMAIL, 'PHPMailer Jake');
		$mail->addAddress(SITE_EMAIL, 'Registration Site');     // Add a recipient
	
		//Content
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = $subject;
		$mail->Body    = $message;
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	
		$mail->send();
		$msg = '<p>Thank you, your message has been sent!</p>';
	} catch (Exception $e) {
		$msg = '<p>Message could not be sent.</p>';
	}
}

// Set the page title and include the HTML header:
$page_title = 'Contact Us';
include ('includes/header.html');

// Welcome the user (by name if they are logged in):
echo '<h1>Let us hear from you';
if (isset($_SESSION['first_name'])) {
	echo " {$_SESSION['first_name']}";
}
echo '!</h1>';
?>
	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor 
		incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud 
		exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
	</p>

	<?php if ($msg != '') {
		echo $msg;
	} ?>
	<form action="<?php echo htmlspecialchars('contact-us.php'); ?>" method="post">
		<input placeholder="Subject..." type="text" name="subject" size="20" maxlength="60" /><br>
		<input placeholder="Email..." type="email" name="email" /><br>
		<textarea name="message"></textarea><br>
		<!-- <input type="file" name="attachment"><br> -->
		<div align="center"><input type="submit" name="submit" value="Send Message" /></div>
	</form>

</div> <!-- End of Content -->

<?php include ('includes/footer.php'); ?>

</body>
</html>

