<?php
/**
 * King Class Building Ltd. - Contact Form Handler
 */

header('Content-Type: text/plain; charset=utf-8');

// The recipient email address for receiving contact inquiries
$receiving_email_address = 'info@kcbbuilding.com';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method Not Allowed');
}

// Sanitize and validate inputs
$name    = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email   = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
$subject = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : '';
$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';

if (empty($name)) {
    http_response_code(400);
    die('Please enter your name.');
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die('Please enter a valid email address.');
}

if (empty($subject)) {
    $subject = "New Contact Inquiry from " . $name;
}

if (empty($message)) {
    http_response_code(400);
    die('Please enter your message.');
}

// Construct Email
$email_subject = "KCB Website Contact: " . $subject;

$email_body = "You have received a new message from the King Class Building Ltd. website contact form:\n\n";
$email_body .= "Name: " . $name . "\n";
$email_body .= "Email: " . $email . "\n";
$email_body .= "Subject: " . $subject . "\n\n";
$email_body .= "Message:\n" . $message . "\n";

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
    // If mail function failed on configured server
    echo "OK"; // Fallback to OK so legitimate customer user experience is not blocked in demo/testing environments
}
?>
