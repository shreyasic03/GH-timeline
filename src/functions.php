<?php

function generateVerificationCode(): string {
    return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

function sendVerificationEmail(string $email, string $code): bool {
    $subject = "Your Verification Code";
    $message = "<p>Your verification code is: <strong>$code</strong></p>";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: no-reply@example.com\r\n";
    return mail($email, $subject, $message, $headers);
}

function registerEmail(string $email): bool {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
        return true;
    }
    return false;
}

function unsubscribeEmail(string $email): bool {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    if (in_array($email, $emails)) {
        $newEmails = array_filter($emails, fn($e) => $e !== $email);
        file_put_contents($file, implode(PHP_EOL, $newEmails) . PHP_EOL);
        return true;
    }
    return false;
}

function fetchGitHubTimeline() {
    // Fake JSON since GitHub API for timeline doesn’t exist anymore
    $data = [
        ["event" => "Push", "user" => "testuser"],
        ["event" => "Fork", "user" => "anotheruser"],
    ];
    return $data;
}

function formatGitHubData(array $data): string {
    $html = "<h2>GitHub Timeline Updates</h2><table border='1'><tr><th>Event</th><th>User</th></tr>";
    foreach ($data as $row) {
        $html .= "<tr><td>{$row['event']}</td><td>{$row['user']}</td></tr>";
    }
    $html .= "</table>";
    $html .= "<p><a href='unsubscribe.php' id='unsubscribe-button'>Unsubscribe</a></p>";
    return $html;
}

function sendGitHubUpdatesToSubscribers(): void {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    $data = fetchGitHubTimeline();
    $formatted = formatGitHubData($data);
    $subject = "Latest GitHub Updates";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: no-reply@example.com\r\n";

    foreach ($emails as $email) {
        mail($email, $subject, $formatted, $headers);
    }
}
