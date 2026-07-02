<?php
/**
 * =============================================================================
 * contact.php
 * -----------------------------------------------------------------------------
 * Handles the AJAX POST from components/contact.php. Always responds with
 * JSON. Order of checks: method -> CSRF -> honeypot -> rate limit -> validate
 * -> persist (DB, falling back to a log file if the DB is unreachable).
 * =============================================================================
 */

declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/database.php';

apply_security_headers();
header('Content-Type: application/json; charset=utf-8');

function respond(bool $success, string $message, array $errors = [], int $status = 200): never
{
    http_response_code($status);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'errors'  => $errors,
    ], JSON_UNESCAPED_SLASHES);
    exit;
}

// -- Method guard -------------------------------------------------------------
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    respond(false, 'Invalid request method.', [], 405);
}

// -- CSRF ----------------------------------------------------------------------
if (!csrf_verify($_POST['csrf_token'] ?? null)) {
    write_log(SUBMISSIONS_LOG, 'REJECTED (csrf) — token mismatch');
    respond(false, 'Your session expired. Please refresh the page and try again.', [], 419);
}

// -- Honeypot -------------------------------------------------------------------
// Real visitors never see or fill the "website" field (hidden via CSS).
if (!empty($_POST['website'])) {
    write_log(SUBMISSIONS_LOG, 'REJECTED (honeypot) — bot signature');
    // Respond success so bots don't learn the honeypot was tripped.
    respond(true, 'Thank you — your message has been sent.');
}

// -- Rate limiting ----------------------------------------------------------
if (!rate_limit_check(client_ip())) {
    write_log(SUBMISSIONS_LOG, 'REJECTED (rate-limit)');
    respond(false, 'Too many submissions. Please try again in a few minutes.', [], 429);
}

// -- Validation ---------------------------------------------------------------
$errors = validate_contact_form($_POST);
if (!empty($errors)) {
    respond(false, 'Please fix the highlighted fields.', $errors, 422);
}

// -- Persist --------------------------------------------------------------------
$saved = false;
try {
    save_contact_submission($_POST);
    $saved = true;
} catch (Throwable $e) {
    // Database unreachable (e.g. schema not imported yet) — degrade gracefully
    // by still capturing the lead in a plain-text log so nothing is lost.
    write_log(LOG_DIR . '/db-error.log', 'Insert failed: ' . $e->getMessage());
}

if (!$saved) {
    write_log(
        SUBMISSIONS_LOG,
        sprintf(
            'FALLBACK-LOG name="%s" email="%s" subject="%s"',
            trim($_POST['name'] ?? ''),
            trim($_POST['email'] ?? ''),
            trim($_POST['subject'] ?? '')
        )
    );
}

// -- Notification email (SMTP placeholder — wire up PHPMailer/Symfony Mailer) --
$emailBody = sprintf(
    "New contact form submission\n\nName: %s\nEmail: %s\nPhone: %s\nCompany: %s\nSubject: %s\n\nMessage:\n%s\n",
    trim($_POST['name'] ?? ''),
    trim($_POST['email'] ?? ''),
    trim($_POST['phone'] ?? ''),
    trim($_POST['company'] ?? ''),
    trim($_POST['subject'] ?? ''),
    trim($_POST['message'] ?? '')
);
$headers = 'From: ' . CONTACT_EMAIL_FROM . "\r\nReply-To: " . trim($_POST['email'] ?? '');
@mail(CONTACT_EMAIL_TO, 'Portfolio contact: ' . trim($_POST['subject'] ?? ''), $emailBody, $headers);

// Rotate the CSRF token after a successful submission.
unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);

respond(true, 'Thank you — your message has been sent. I\'ll get back to you within one business day.');
