<?php
/**
 * =============================================================================
 * CONFIG.PHP
 * -----------------------------------------------------------------------------
 * Central configuration for the Ayodeji Oluwafemi Daniel portfolio site.
 * Loads environment values, defines constants, hardens the session, and
 * exposes small typed helper functions used across the site.
 *
 * PHP 8.2+
 * =============================================================================
 */

declare(strict_types=1);

// -----------------------------------------------------------------------------
// Error handling — never show raw errors to visitors, always log them.
// -----------------------------------------------------------------------------
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/storage/logs/php-error.log');

// -----------------------------------------------------------------------------
// Timezone
// -----------------------------------------------------------------------------
date_default_timezone_set('Africa/Lagos');

// -----------------------------------------------------------------------------
// Environment loader (.env is optional — falls back to safe defaults so the
// site runs out of the box; override in production via a real .env file).
// -----------------------------------------------------------------------------
function env(string $key, mixed $default = null): mixed
{
    static $vars = null;

    if ($vars === null) {
        $vars = [];
        $envPath = __DIR__ . '/.env';
        if (is_file($envPath) && is_readable($envPath)) {
            foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                    continue;
                }
                [$k, $v] = array_map('trim', explode('=', $line, 2));
                $vars[$k] = trim($v, "\"'");
            }
        }
    }

    return $vars[$key] ?? ($_ENV[$key] ?? $default);
}

// -----------------------------------------------------------------------------
// Site-wide constants
// -----------------------------------------------------------------------------
const SITE_NAME        = 'Ayodeji Oluwafemi Daniel';
const SITE_TITLE       = 'Ayodeji Oluwafemi Daniel — Full Stack Developer & Platform Architect';
const SITE_TAGLINE     = 'Founder of Lumynex';
const SITE_DESCRIPTION = 'I engineer scalable digital products, secure fintech platforms, social commerce ecosystems, and high-performance web infrastructure that solve real-world business problems.';
const SITE_URL         = 'https://ayodejidaniel.dev';
const SITE_AUTHOR      = 'Ayodeji Oluwafemi Daniel';
const SITE_KEYWORDS    = 'Full Stack Developer, PHP Engineer, Platform Architect, Fintech Developer, Shopify Developer, WordPress Expert, Lumynex, Paystack Integration, Nigerian Software Engineer';

// Email configuration - using define() for dynamic values
define('CONTACT_EMAIL_TO', env('CONTACT_EMAIL_TO', 'hello@ayodejidaniel.dev'));
define('CONTACT_EMAIL_FROM', env('CONTACT_EMAIL_FROM', 'noreply@ayodejidaniel.dev'));

// SMTP configuration - using define() for dynamic values
define('SMTP_HOST', env('SMTP_HOST', 'smtp.mailtrap.io'));
define('SMTP_PORT', (int) env('SMTP_PORT', 587));
define('SMTP_USER', env('SMTP_USER', ''));
define('SMTP_PASS', env('SMTP_PASS', ''));
define('SMTP_SECURE', env('SMTP_SECURE', 'tls'));

// Security tunables
const RATE_LIMIT_MAX_REQUESTS = 5;      // max submissions
const RATE_LIMIT_WINDOW_SECS  = 600;    // per 10 minutes, per IP
const CSRF_TOKEN_LIFETIME     = 3600;   // 1 hour

// Storage paths
const LOG_DIR        = __DIR__ . '/storage/logs';
const RATE_LIMIT_DIR = __DIR__ . '/storage/logs/rate-limits';
const SUBMISSIONS_LOG = LOG_DIR . '/submissions.log';

foreach ([LOG_DIR, RATE_LIMIT_DIR] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0750, true);
    }
}

// -----------------------------------------------------------------------------
// Session hardening
// -----------------------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isHttps,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    ini_set('session.use_strict_mode', '1');
    session_name('ayd_session');
    session_start();
}

// -----------------------------------------------------------------------------
// Secure response / security headers — call once per request from index.php.
// -----------------------------------------------------------------------------
function apply_security_headers(): void
{
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    header("Content-Security-Policy: default-src 'self'; " .
        "script-src 'self' 'unsafe-inline'; " .
        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
        "font-src 'self' https://fonts.gstatic.com; " .
        "img-src 'self' data: https:; " .
        "connect-src 'self'; " .
        "base-uri 'self'; form-action 'self'; frame-ancestors 'none'");

    if (!empty($_SERVER['HTTPS'])) {
        header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
    }
}

// -----------------------------------------------------------------------------
// CSRF helpers
// -----------------------------------------------------------------------------
function csrf_token(): string
{
    $now = time();
    if (
        empty($_SESSION['csrf_token']) ||
        empty($_SESSION['csrf_token_time']) ||
        ($now - (int) $_SESSION['csrf_token_time']) > CSRF_TOKEN_LIFETIME
    ) {
        $_SESSION['csrf_token']      = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = $now;
    }

    return $_SESSION['csrf_token'];
}

function csrf_verify(?string $token): bool
{
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

// -----------------------------------------------------------------------------
// Small typed helpers shared by templates
// -----------------------------------------------------------------------------
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function asset(string $path): string
{
    $path = ltrim($path, '/');
    $full = __DIR__ . '/assets/' . $path;
    $version = is_file($full) ? filemtime($full) : time();

    return '/assets/' . $path . '?v=' . $version;
}

function client_ip(): string
{
    foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $key) {
        if (!empty($_SERVER[$key])) {
            $ip = trim(explode(',', $_SERVER[$key])[0]);
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }

    return '0.0.0.0';
}