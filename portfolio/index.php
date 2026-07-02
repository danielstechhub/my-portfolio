<?php
/**
 * =============================================================================
 * index.php — Site entry point
 * -----------------------------------------------------------------------------
 * Thin orchestration layer: loads config/helpers, applies security headers,
 * optionally logs a page view, then renders header -> sections -> footer.
 * All real content lives in includes/functions.php and components/*.php.
 * =============================================================================
 */

declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/functions.php';

apply_security_headers();

// Best-effort analytics — a DB hiccup here must never break the page.
try {
    require_once __DIR__ . '/includes/database.php';
    log_page_view($_SERVER['REQUEST_URI'] ?? '/');
} catch (Throwable $e) {
    // Silently skip analytics if the database isn't set up yet.
}

require __DIR__ . '/includes/header.php';
require __DIR__ . '/components/hero.php';
require __DIR__ . '/components/about.php';
require __DIR__ . '/components/skills.php';
require __DIR__ . '/components/projects.php';
require __DIR__ . '/components/experience.php';
require __DIR__ . '/components/services.php';
require __DIR__ . '/components/testimonials.php';
require __DIR__ . '/components/blog.php';
require __DIR__ . '/components/contact.php';
require __DIR__ . '/includes/footer.php';
