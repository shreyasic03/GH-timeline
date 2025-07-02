<?php
require_once 'functions.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['unsubscribe_email'])) {
        $_SESSION['unsubscribe_email'] = $_POST['unsubscribe_email'];
        $_SESSION['unsubscribe_code'] = generateVerificationCode();
        $code = $_SESSION['unsubscribe_code'];
        $email = $_SESSION['unsubscribe_email'];
        $subject = "Confirm Unsubscription";
        $message = "<p>To confirm unsubscription, use this code: <strong>$code</strong></p>";
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: no-reply@example.com\r\n";
        mail($email, $subject, $message, $headers);
        echo "Unsubscribe code sent!";
    } elseif (isset($_POST['unsubscribe_verification_code'])) {
        if ($_POST['unsubscribe_verification_code'] === $_SESSION['unsubscribe_code']) {
            unsubscribeEmail($_SESSION['unsubscribe_email']);
            echo "Unsubscribed successfully!";
        } else {
            echo "Invalid code.";
        }
    }
}
?>

<form method="POST">
    <input type="email" name="unsubscribe_email" required>
    <button id="submit-unsubscribe">Unsubscribe</button>
</form>

<form method="POST">
    <input type="text" name="unsubscribe_verification_code" required>
    <button id="verify-unsubscribe">Verify</button>
</form>
