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
$mail2 = new PHPMailer(true);

$dirPath = getcwd();

$target_dir = $dirPath."/uploads/";

$fields = array(
    'title' => 'Salutation',
    'fname' => 'Firstname',
    'lname' => 'Lastname',
    'email' => 'Email',
    'city' => 'City',
    'residence' => 'Country',
    'engagement' => 'Interested',
    'interest' => 'Domain',
    'message' => 'Message'
);


$emailTextHtml = "<h2>You have a new message from your Careers form</h2><hr>";
$emailTextHtml .= "<table>";

foreach ($_POST as $key => $value) {
	// If the field exists in the $fields array, include it in the email
	if (isset($fields[$key])) {
		$emailTextHtml .= "<tr><th>$fields[$key]</th><td>$value</td></tr>";
	}
}
$emailTextHtml .= "</table><hr>";
$emailTextHtml .= "<p>Have a nice day.";




$mail->isSMTP();                                            // Set mailer to use SMTP
$mail->Host       = 'mail.carsen.se';  // Specify main and backup SMTP servers
$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
$mail->Username   = 'info@carsen.se';                     // SMTP username
$mail->Password   = '5rySCfGKoI7cmGu!';                               // SMTP password
$mail->SMTPSecure = '';                                  // Enable TLS encryption, `ssl` also accepted
$mail->Port       = 587;




$mail2->isSMTP();                                            // Set mailer to use SMTP
$mail2->Host       = 'mail.carsen.se';  // Specify main and backup SMTP servers
$mail2->SMTPAuth   = true;                                   // Enable SMTP authentication
$mail2->Username   = 'info@carsen.se';                     // SMTP username
$mail2->Password   = '5rySCfGKoI7cmGu!';                               // SMTP password
$mail2->SMTPSecure = '';                                  // Enable TLS encryption, `ssl` also accepted
$mail2->Port       = 587;


$mail->From = 'info@carsen.se';
$mail->FromName = 'Carsen';



$mail2->From = 'careers@carsen.se';
$mail2->FromName = 'Carsen: Careers';


/* $mail->addAddress('gbabarogic@gmail.com');  // Add a recipient */
$mail->addAddress('careers@carsen.se');  // Add a recipient

$attachments=array();


$total = count($_FILES['attachment']['name']);

$allowed =  array('pdf','doc' ,'ppt', 'docx', 'pptx', 'txt');

$errors=array();
// Loop through each file
for( $i=0 ; $i < $total ; $i++ ) {

  //Get the temp file path
  $tmpFilePath = $_FILES['attachment']['tmp_name'][$i];
  $path_info = pathinfo($_FILES['attachment']['name'][$i]);

   $ext = $path_info['extension'];
   $filename_trim= str_replace(' ','',$path_info['filename']);
   $newFileName= $filename_trim.time().'.'.$ext;
   $filename= $path_info['basename'];

   /*Validation */
	 if(!in_array($ext,$allowed) ) {
		$errors[]= 'Invalid file type '.$filename;
	}

   /*validation*/



  //Make sure we have a file path
  if ($tmpFilePath != "" & empty($errors)){
    //Setup our new file path
	$newFilePath = $target_dir.$newFileName;
	//Upload the file into the temp dir
	if(move_uploaded_file($tmpFilePath, $newFilePath)) {
		$mail->addAttachment($newFilePath,$newFileName);
		$attachments[]= $newFilePath;
	}
  }
}


/* $mail->addAttachment($target_dir.'Benefits.pdf','Benefits.pdf');         // Add attachments
$mail->addAttachment($target_dir.'CSS selectors cheatsheet.pdf','CSS selectors cheatsheet.pdf');  // Add attachments */

/* $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name     */


$mail->Subject = 'Application for ' .$_POST['interest'];
$mail->Body    = $emailTextHtml;
$mail->AltBody = $emailTextHtml;

 $mail->isHTML(true);
if(!empty($errors)){
	   header('Location: careers.php?msg=fail&filetype=fail');
	   exit;
}



$mail2->isHTML(true);
$mail2->Subject = 'Application Confirmation';
$mail2->AddAddress($_POST['email']);
$message2 ='';
 ob_start();
 include('careersbody.php');
 $message2 = ob_get_contents();
 ob_get_clean();

$emailBody = $message2;
//replace the occurence of %firstname% by the actual name posted by user
$emailBody = str_replace('%firstname%', $_POST['fname'], $emailBody);
//replace the occurence of %user_email% by the actual email posted by user
$emailBody = str_replace('%user_mail%', $_POST['email'], $emailBody);

$mail2->Body = $emailBody;

if($attachments){
	if($mail->send() && $mail2->send()) {
		foreach($attachments as $fileName){
			unlink($fileName);
		}
	    header('Location: careers.php?msg=success&ex=y');
	   exit;
	}else{
	    header('Location: careers.php?msg=fail');
	   exit;
	}

}
