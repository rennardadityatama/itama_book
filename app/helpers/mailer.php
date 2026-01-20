<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer manual
require_once BASE_PATH . '/app/libraries/phpmailer/src/Exception.php';
require_once BASE_PATH . '/app/libraries/phpmailer/src/PHPMailer.php';
require_once BASE_PATH . '/app/libraries/phpmailer/src/SMTP.php';

function sendMail($to, $subject, $body)
{
  $mail = new PHPMailer(true);

  try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'rennardadit@gmail.com';
    $mail->Password   = 'ptls jerl oite vyty';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('rennardadit@gmail.com', 'iTama Book');
    $mail->addAddress($to);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $body;

    return $mail->send();

  } catch (Exception $e) {
    return false;
  }
}
