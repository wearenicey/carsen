<?php
/*
 *  CONFIGURE EVERYTHING HERE
 */

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

function pr($obj)
{
    echo '<pre>';
    print_r($obj);
    echo '</pre>';
}


// an email address that will be in the From field of the email.
$from = 'Demo contact form <info@carsen.se>';

// an email address that will receive the email with the output of the form
$sendTo = 'office@carsen.se';

// subject of the email
$subject = 'New message from Carsen Contact form';

// form field names and their translations.
// array variable name => Text to appear in the email
$fields = array(
    'fname' => 'Name',
    'lname' => 'Surname',
    'email' => 'Email',
//    'phone' => 'Phone',
    'fullphone' => 'Phone',
    'message' => 'Message',
    'company' => 'Company',
    'industry' => 'Industry'
);

// message that will be displayed when everything is OK :)
$okMessage = 'Contact form successfully submitted. Thank you, I will get back to you soon!';

// If something goes wrong, we will display this message.
$errorMessage = 'There was an error while submitting the form. Please try again later';

/*
 *  LET'S DO THE SENDING
 */

// if you are not debugging and don't need error reporting, turn this off by error_reporting(0);
error_reporting(E_ALL & ~E_NOTICE);


try {
    //Server settings
    $mail->SMTPDebug = 0;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = 'mail.carsen.se';  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'info@carsen.se';                     // SMTP username
    $mail->Password   = '5rySCfGKoI7cmGu!';                               // SMTP password
    $mail->SMTPSecure = '';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port       = 587;                                    // TCP port to connect to */

    //Recipients
    $mail->setFrom('info@carsen.se', 'Carsen contact-form');
    $mail->addAddress('office@carsen.se', 'Carsen Contact');     // Add a recipient

    $mail->addReplyTo('office@carsen.se', 'Carsen Office');
    /*     $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com'); */

    // Attachments
    /*     $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name */
    /*        $emailText = "You have a new message from your contact form\n=============================\n";
	  foreach ($_POST as $key => $value) {
        // If the field exists in the $fields array, include it in the email
        if (isset($fields[$key])) {
            $emailText .= "$fields[$key]: $value\n";
        }
    } */
    $emailTextHtml = "<h2>You have a new message from your contact form</h2><hr>";
    $emailTextHtml .= "<table>";

    foreach ($_POST as $key => $value) {
        // If the field exists in the $fields array, include it in the email
        if (isset($fields[$key])) {
            $emailTextHtml .= "<tr><th>$fields[$key]</th><td>$value</td></tr>";
        }
    }
    $emailTextHtml .= "</table><hr>";
    $emailTextHtml .= "<p>Have a nice day";

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Contact Information ';
    $mail->Body    = $emailTextHtml;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    /*
    if($mail->send()){
		   header('Location: contact.php?msg=success');
	}else{
		    header('Location: contact.php?msg=fail');
    } */
    /*  echo 'Message has been sent'; */


    // Instantiation and passing `true` enables exceptions
    $newMail = new PHPMailer(true);
    $newMail->SMTPDebug = 0;                                       // Enable verbose debug output
    $newMail->isSMTP();                                            // Set mailer to use SMTP
    $newMail->Host       = 'mail.carsen.se';  // Specify main and backup SMTP servers
    $newMail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $newMail->Username   = 'info@carsen.se';                     // SMTP username
    $newMail->Password   = '5rySCfGKoI7cmGu!';                               // SMTP password
    $newMail->SMTPSecure = '';                                  // Enable TLS encryption, `ssl` also accepted
    $newMail->Port       = 587;                                    // TCP port to connect to */

    $newMail->setFrom('info@carsen.se', 'Carsen Contact');
    $newMail->addAddress($_POST['email'], $_POST['fname'] . ' ' . $_POST['lname']);     // Add a recipient

    //let get the content from emailbody.php file
    $emailBody = file_get_contents('contactbody.php');
    //replace the occurence of %firstname% by the actual name posted by user
    $emailBody = str_replace('%firstname%', $_POST['fname'], $emailBody);
    //replace the occurence of %user_email% by the actual email posted by user
    $emailBody = str_replace('%user_mail%', $_POST['email'], $emailBody);

    // Content
    $newMail->isHTML(true);                                  // Set email format to HTML
    $newMail->Subject = 'Thank you for contacting us';
    $newMail->Body    = $emailBody;
    $newMail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if ($newMail->send()) {
        header('Location: contact.php?msg=success&ex=y');
    } else {
        header('Location: contact.php?msg=fail');
    }
} catch (Exception $e) {
    /* echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"; */
    header('Location: contact.php?msg=fail');
}



die;
