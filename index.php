<?php
// index.php — Main entrypoint for the portfolio.

declare(strict_types=1);

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';

apply_security_headers();

include __DIR__ . '/header.php';
include __DIR__ . '/hero.php';
include __DIR__ . '/about.php';
include __DIR__ . '/skills.php';
include __DIR__ . '/projects.php';
include __DIR__ . '/experience.php';
include __DIR__ . '/services.php';
include __DIR__ . '/testimonials.php';
include __DIR__ . '/blog.php';
include __DIR__ . '/contact.php';
include __DIR__ . '/footer.php';
