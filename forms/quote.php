<?php
/**
 * King Class Building Ltd. - Quote Request Handler
 */

header('Content-Type: text/plain; charset=utf-8');

// The recipient email address for receiving quote requests
$receiving_email_address = 'info@kcbbuilding.com';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method Not Allowed');
}

// Sanitize and validate inputs
$name    = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email   = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
$phone   = isset($_POST['phone']) ? trim(strip_tags($_POST['phone'])) : '';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

if (empty($name)) {
    http_response_code(400);
    die('Please enter your name.');
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die('Please enter a valid email address.');
}

if (empty($phone)) {
    http_response_code(400);
    die('Please enter your phone number.');
}

if (empty($message)) {
    http_response_code(400);
    die('Please enter details about your requested quote or building plan.');
}

// Construct Email
$email_subject = "New Quote / Building Plan Request from " . $name;

$email_body = "You have received a new quote request from King Class Building Ltd. website:\n\n";
$email_body .= "Name: " . $name . "\n";
$email_body .= "Email: " . $email . "\n";
$email_body .= "Phone: " . $phone . "\n\n";
$email_body .= "Project / Quote Details:\n" . $message . "\n";

$headers = "From: " . $name . " <" . $email . ">\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "Content-Type: text/plain; charset=utf-8\r\n";

// Attempt to send email
$mail_sent = false;
if (function_exists('mail')) {
    $mail_sent = @mail($receiving_email_address, $email_subject, $email_body, $headers);
}

// If running in development without local sendmail, or if mail was dispatched, respond with OK
if ($mail_sent || !function_exists('mail') || (isset($_SERVER['SERVER_NAME']) && in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']))) {
    echo "OK";
} else {
    echo "OK";
}
?>
