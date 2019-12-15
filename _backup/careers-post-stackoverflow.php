<?php

    $field='attachment';
    $target_dir = "uploads/";
    $errors=array();
    $allowed=['pdf','txt','docx','jpg'];
    $attachments=[];

    $okMessage = 'Contact form successfully submitted. Thank you, I will get back to you soon!';
    $errorMessage = 'There was an error while submitting the form. Please try again later';


    foreach( $_FILES[ $field ]['name'] as $i => $name ) {
        try{
            if( !empty( $_FILES[ $field ]['tmp_name'][$i] ) ) {
                $name = $_FILES[ $field ]['name'][$i];
                $size = $_FILES[ $field ]['size'][$i];
                $type = $_FILES[ $field ]['type'][$i];
                $tmp  = $_FILES[ $field ]['tmp_name'][$i];
                $error= $_FILES[ $field ]['error'][$i];
                $ext  = strtolower( pathinfo( $name, PATHINFO_EXTENSION ) );

                $uploadOk = true;

                if( $error == UPLOAD_ERR_OK && is_uploaded_file( $tmp ) ){
                    $target_file = $target_dir . $name;

                    if( file_exists( $target_file ) ) $uploadOk=false;
                    if( $size > 10000000 ) $uploadOk=false;
                    if( !in_array( $ext, $allowed ) ) $uploadOk=false;

                    if( $uploadOk ){
                        $status = move_uploaded_file( $tmp, $target_file );
                        if( $status ){
                            $attachments[]=$target_file;
                        } else {
                            throw new Exception(sprintf('unable to save %s',$name));
                        }
                    } else {
                        throw new Exception( sprintf('problem with %s',$name) );
                    }
                }
            }
        }catch( Exception $e ){
            $errors[]=$e->getMessage();
        }
    }

    if( empty( $errors ) ){

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;
        require 'vendor/phpmailer/phpmailer/src/Exception.php';
        require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
        require 'vendor/phpmailer/phpmailer/src/SMTP.php';
        $mail = new PHPMailer( true );

        $from = 'Careers <email@email.se>';
        $sendTo = 'email@email.se';
        $subject = 'New message from Careers form';


        $mail->AddAddress( $sendTo );
        $mail->Subject( $subject );
        $mail->SetFrom( $from );
        $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';


        $fields = array(
            'title' => 'Salutation',
            'fname' => 'Firstname',
            'lname' => 'Lastname',
            'email' => 'Email',
            'city' => 'City',
            'residence' => 'Country',
            'engagement' => 'Interested',
            'interest' => 'Domain interest',
            'message' => 'Message'
        );

        $html=array();
        foreach( $fields as $field => $text ){
            if( !empty( $_POST[ $field ] ) ) $html[]=sprintf( '<div>%s: %s</div>', $text, filter_input( INPUT_POT, $field, FILTER_SANITIZE_STRING ) );
        }

        /* add message body */
        if( !empty( $html ) ){
            $mail->MsgHTML( implode( PHP_EOL, $html ) );
        }

        /* add attachments */
        foreach( $attachments as $file ){
            $mail->AddAttachment( $file );
        }

        $status = $mail->send();
        exit( $status ? $okMessage : $errorMessage );
    }


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
    $mail->setFrom('info@carsen.se', 'Carsen Careers');
    $mail->addAddress('careers@carsen.se', 'Carsen Careers');     // Add a recipient

    $mail->addReplyTo('careers@carsen.se', 'Carsen Careers');
    /*     $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com'); */

    // Attachments
    $mail->addAttachment($target_file);    // Optional name */
    $emailText = "You have a new message from your Careers form\n=============================\n";
    foreach ($_POST as $key => $value) {
        // If the field exists in the $fields array, include it in the email
        if (isset($fields[$key])) {
            $emailText .= "$fields[$key]: $value\n";
        }
    }
    $emailTextHtml = "<h2>You have a new message from your Careers form</h2><hr>";
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
    $mail->Subject = 'Careers Information ';
    $mail->Body    = $emailTextHtml;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    /*
    if($mail->send()){
        header('Location: careers.php?msg=success');
    }else{
        header('Location: careers.php?msg=fail');
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

    $newMail->setFrom('info@carsen.se', 'Carsen Careers');
    $newMail->addAddress($_POST['email'], $_POST['fname'] . ' ' . $_POST['lname']);     // Add a recipient

    //let get the content from emailbody.php file
    $emailBody = file_get_contents('careersbody.php');
    //replace the occurence of %firstname% by the actual name posted by user
    $emailBody = str_replace('%firstname%', $_POST['fname'], $emailBody);
    //replace the occurence of %user_email% by the actual email posted by user
    $emailBody = str_replace('%user_mail%', $_POST['email'], $emailBody);

    // Content
    $newMail->isHTML(true);                                  // Set email format to HTML
    $newMail->Subject = 'Application Confirmation';
    $newMail->Body    = $emailBody;
    $newMail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if ($newMail->send()) {
        header('Location: careers.php?msg=success&ex=y');
    } else {
        header('Location: careers.php?msg=fail');
    }
} catch (Exception $e) {
    /* echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"; */
    header('Location: careers.php?msg=fail');
}



die;
