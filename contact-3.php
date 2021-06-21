<?php



/*
THIS FILE USES PHPMAILER INSTEAD OF THE PHP MAIL() FUNCTION
AND ALSO SMTP TO SEND THE EMAILS
*/

//require 'PHPMailer-master/PHPMailerAutoload.php';
require 'PHPMailer-5.2-stable/PHPMailerAutoload.php';

/*
*  CONFIGURE EVERYTHING HERE
*/

// an email address that will be in the From field of the email.

$fromEmail = 'info@ticketsupplies.com.ar';
$fromName = 'Ticket Supplies';


// an email address that will receive the email with the output of the form
$sendToEmail = $_POST['email'];
$sendToName = $_POST['name'];

// subject of the email
$subject = 'Ticket Supplies ha recibido tu consulta!';

// smtp credentials and server


$smtpHost = 'dtcwin014.ferozo.com';
$smtpUsername = 'info@ticketsupplies.com.ar';
$smtpPassword = 'Uruguay2021';



// form field names and their translations.
// array variable name => Text to appear in the email
$fields = array('name' => 'Name', 'surname' => 'Surname', 'phone' => 'Phone', 'email' => 'Email', 'message' => 'Message');

// message that will be displayed when everything is OK :)
$okMessage = 'Ya nos llegó tu consulta. Nos pondremos en contacto con vos a la brevedad!';

// If something goes wrong, we will display this message.
$errorMessage = 'There was an error while submitting the form. Please try again later';

/*
*  LET'S DO THE SENDING
*/

// if you are not debugging and don't need error reporting, turn this off by error_reporting(0);
error_reporting(E_ALL & ~E_NOTICE);


try {
    if (count($_POST) == 0) {
        throw new \Exception('Form is empty');
    }
    
    $emailTextHtml = "<h3>Hemos recibido tu consulta! Gracias por contactarnos! </h3><hr>";
    $emailTextHtml .= "<table>";
    
    foreach ($_POST as $key => $value) {
        // If the field exists in the $fields array, include it in the email
        if (isset($fields[$key])) {
            $emailTextHtml .= "<tr><th>$fields[$key]</th><td>$value</td></tr>";
        }
    }
   
    $emailTextHtml = "";
    $emailTextHtml = "Hola <b>$_POST[name]</b>, gracias por contactarnos! Hemos recibo la siguiente consulta: ";
    $emailTextHtml .= "<br><i>$_POST[message]</i>";
    $emailTextHtml .= "<br><br>Tus datos de contacto son:";
    $emailTextHtml .= "<br>Email: $_POST[email]";
    $emailTextHtml .= "<br>Télefono: $_POST[phone]";
    $emailTextHtml .= "<br><br><p>Pronto nos pondremos en contacto con vos.<br>Que tengas un buen día,<br><b>El equipo de Ticket Supplies.</b></p>";

    
   //1 $mail = new PHPMailer;
    $mail = new PHPMailer(true);
    
    $mail->setFrom($fromEmail, $fromName);
    $mail->addAddress($sendToEmail, $sendToName); // you can add more addresses by simply adding another line with $mail->addAddress();
    $mail->addReplyTo($fromEmail);
    $mail->addBCC($fromEmail);
    
    $mail->isHTML(true);
    
    $mail->Subject = $subject;
    $mail->Body    = $emailTextHtml;
    $mail->msgHTML($emailTextHtml); // this will also create a plain-text version of the HTML email, very handy
    
    
    $mail->isSMTP();
    
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';
    
    
    //Set the hostname of the mail server
    // use
    // $mail->Host = gethostbyname('smtp.gmail.com');
    // if your network does not support SMTP over IPv6
    //1$mail->Host = gethostbyname($smtpHost);
    $mail->Host = 'dtcwin014.ferozo.com';

    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    
    $mail->Port = '465';
    

    //Set the encryption system to use - ssl (deprecated) or tls
    
    $mail->SMTPSecure = 'ssl';
    

    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    
    
    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = $smtpUsername;
    
    //Password to use for SMTP authentication
    $mail->Password = $smtpPassword;
    
    if (!$mail->send()) {
        throw new \Exception('I could not send the email.' . $mail->ErrorInfo);
  
    }
    
    $responseArray = array('type' => 'success', 'message' => $okMessage);
} catch (\Exception $e) {
    //$responseArray = array('type' => 'danger', 'message' => $errorMessage);
    $responseArray = array('type' => 'danger', 'message' => $e->getMessage());
    
}


$encoded = json_encode($responseArray);
   
header('Content-Type: application/json');

echo $encoded;

/*

// if requested by AJAX request return JSON response
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);
   
    header('Content-Type: application/json');
    
    echo $encoded;
}
// else just display the message
else {
    echo $responseArray['message'];
}
*/