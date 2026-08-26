<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed.'
    ]);

    exit;
}


$name =
    trim($_POST['name'] ?? '');

$email =
    trim($_POST['email'] ?? '');

$subject =
    trim($_POST['subject'] ?? '');

$message =
    trim($_POST['message'] ?? '');

$website =
    trim($_POST['website'] ?? '');


/*
 * Honeypot anti-spam.
 * Real users never see or fill this field.
 */
if ($website !== '')
{
    http_response_code(200);

    echo json_encode([
        'success' => true,
        'message' => 'Your message has been sent.'
    ]);

    exit;
}


if (
    $name === '' ||
    $email === '' ||
    $subject === '' ||
    $message === ''
)
{
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Please complete all fields.'
    ]);

    exit;
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL))
{
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Please enter a valid email address.'
    ]);

    exit;
}


if (
    strlen($name) > 100 ||
    strlen($email) > 254 ||
    strlen($subject) > 100 ||
    strlen($message) > 5000
)
{
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'One or more fields are too long.'
    ]);

    exit;
}


$allowedSubjects = [
    'Technical Support',
    'Bug Report',
    'Purchase',
    'Feedback',
    'General Question'
];


if (!in_array($subject, $allowedSubjects, true))
{
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid subject.'
    ]);

    exit;
}


/*
 * Prevent header injection.
 */
if (
    preg_match('/[\r\n]/', $name) ||
    preg_match('/[\r\n]/', $email)
)
{
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid input.'
    ]);

    exit;
}


$destination =
    'contact@mrcx-studio.com';

$mailSubject =
    '[MRCX Studio Support] ' . $subject;


$mailBody =
    "New message from the MRCX Studio website\n\n" .
    "Name: " . $name . "\n" .
    "Email: " . $email . "\n" .
    "Subject: " . $subject . "\n\n" .
    "Message:\n" .
    $message . "\n";


$headers = [
    'From: MRCX Studio Website <donotreply@mrcx-studio.com>',
    'Reply-To: ' . $email,
    'Content-Type: text/plain; charset=UTF-8',
    'X-Mailer: PHP/' . PHP_VERSION
];


$sent =
    mail(
        $destination,
        $mailSubject,
        $mailBody,
        implode("\r\n", $headers)
    );


if (!$sent)
{
    error_log(
        'MRCX Studio contact form: mail() failed.'
    );

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'The message could not be sent. Please contact us directly at contact@mrcx-studio.com.'
    ]);

    exit;
}


echo json_encode([
    'success' => true,
    'message' => 'Your message has been sent. Thank you!'
]);