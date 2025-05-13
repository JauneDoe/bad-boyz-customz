<?php
// form-handler.php

// 1. Only handle POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// 2. Collect + sanitize
$name     = strip_tags(trim($_POST['name'] ?? ''));
$contact  = strip_tags(trim($_POST['contact'] ?? ''));
$vehicle  = strip_tags(trim($_POST['vehicle'] ?? ''));
$service  = strip_tags(trim($_POST['service'] ?? ''));
$datetime = strip_tags(trim($_POST['datetime'] ?? ''));
$notes    = strip_tags(trim($_POST['notes'] ?? ''));

// 3. Basic validation
if (empty($name) || empty($contact) || empty($service)) {
    http_response_code(400);
    exit('Please fill in all required fields.');
}

// 4. Build the email
$to      = 'you@yourdomain.com';    // <- Change to your email
$subject = "New Booking Request from $name";
$body    = "You have a new service request:\n\n"
         . "Name: $name\n"
         . "Contact: $contact\n"
         . "Vehicle: $vehicle\n"
         . "Service: $service\n"
         . "Preferred Date/Time: $datetime\n"
         . "Notes: $notes\n\n"
         . "---\nThis was sent from your booking form.";
$headers = "From: no-reply@yourdomain.com\r\n"
         . "Reply-To: $contact\r\n"
         . "X-Mailer: PHP/" . phpversion();

// 5. Send it
if (mail($to, $subject, $body, $headers)) {
    // 6. Redirect on success
    header('Location: thank-you.html');
    exit;
} else {
    http_response_code(500);
    exit('Sorry — there was a problem sending your request. Please try again later.');
}
?>