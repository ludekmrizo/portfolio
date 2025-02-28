<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require __DIR__ . '/PHPMailer/Exception.php';
require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$result = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // Validace vstupů
    if (!$name) {
        $errName = 'Please enter your name';
    } elseif (preg_match("/[\r\n]/", $name)) {
        $errName = 'Invalid name input';
    }
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errEmail = 'Please enter a valid email address';
    }
    if (!$message) {
        $errMessage = 'Please enter your message';
    }


    if (empty($errName) && empty($errEmail) && empty($errMessage)) {
        $mailSubject = 'Message from Portfolio LM: ' . $subject;
        $body = "From: $name\nE-Mail: $email\nSubject: $subject\nMessage:\n$message";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'x@gmail.com';
            $mail->Password   = 'x';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;                            // 587 pro TLS, 465 pro SSL

            $mail->setFrom('x@gmail.com', 'Your Name');
            $mail->addAddress('x@gmail.com', 'Recipient Name');
            $mail->addReplyTo($email, $name);

            $mail->isHTML(false);
            $mail->Subject = $mailSubject;
            $mail->Body    = $body;

            $mail->send();
            $result = '<div class="alert alert-success">Thank You! I will be in touch</div>';
            $vysledek = '<div class="alert alert-success">Děkuju! Zpráva se odeslala</div>';
        } catch (Exception $e) {
            $result = '<div class="alert alert-danger">Mailer Error: ' . $mail->ErrorInfo . '</div>';
            $vysledek = '<div class="alert alert-danger">Chybové hlášení: ' . $mail->ErrorInfo . '</div>';
        }
    } else {
        // Sestavení chybového výpisu
        $errors = '';
        if (!empty($errName))    $errors .= $errName . '<br>';
        if (!empty($errEmail))   $errors .= $errEmail . '<br>';
        if (!empty($errMessage)) $errors .= $errMessage . '<br>';
        $result = '<div class="alert alert-danger">' . $errors . '</div>';
        $vysledek = '<div class="alert alert-danger">' . $errors . '</div>';
    }
}

echo $result;
echo $vysledek;
exit;
