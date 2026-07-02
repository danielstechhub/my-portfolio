<?php
/**
 * =============================================================================
 * includes/functions.php
 * -----------------------------------------------------------------------------
 * Reusable helper functions: rate limiting, logging, validation and the
 * structured content arrays that drive the Skills, Projects, Experience,
 * Services and Testimonials sections. Keeping content as data (rather than
 * hard-coded markup) keeps index.php a thin rendering layer.
 * =============================================================================
 */

declare(strict_types=1);

/**
 * File-based sliding-window rate limiter, keyed by client IP.
 * Uses flock() so concurrent requests can't race past the limit.
 */
function rate_limit_check(string $identifier): bool
{
    $safeKey  = preg_replace('/[^a-f0-9.]/i', '_', $identifier) ?? 'unknown';
    $filePath = RATE_LIMIT_DIR . '/' . md5($safeKey) . '.json';

    $handle = fopen($filePath, 'c+');
    if ($handle === false) {
        return true; // fail-open rather than blocking legitimate visitors
    }

    flock($handle, LOCK_EX);

    $raw  = stream_get_contents($handle);
    $data = json_decode((string) $raw, true);
    $now  = time();

    if (!is_array($data)) {
        $data = ['requests' => []];
    }

    // Keep only requests inside the current window.
    $data['requests'] = array_values(array_filter(
        $data['requests'],
        static fn (int $t) => ($now - $t) < RATE_LIMIT_WINDOW_SECS
    ));

    $allowed = count($data['requests']) < RATE_LIMIT_MAX_REQUESTS;

    if ($allowed) {
        $data['requests'][] = $now;
        ftruncate($handle, 0);
        rewind($handle);
        fwrite($handle, json_encode($data));
        fflush($handle);
    }

    flock($handle, LOCK_UN);
    fclose($handle);

    return $allowed;
}

/**
 * Appends a line to a log file using an exclusive lock so writes never
 * interleave under concurrent traffic.
 */
function write_log(string $file, string $message): void
{
    $line = sprintf('[%s] [%s] %s%s', date('Y-m-d H:i:s'), client_ip(), $message, PHP_EOL);
    file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
}

/**
 * Very small validation helper — returns an array of field => error message.
 *
 * @param array<string,string> $data
 * @return array<string,string>
 */
function validate_contact_form(array $data): array
{
    $errors = [];

    $name = trim($data['name'] ?? '');
    if ($name === '' || mb_strlen($name) < 2) {
        $errors['name'] = 'Please enter your full name.';
    } elseif (mb_strlen($name) > 100) {
        $errors['name'] = 'Name is too long.';
    }

    $email = trim($data['email'] ?? '');
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    $phone = trim($data['phone'] ?? '');
    if ($phone !== '' && !preg_match('/^[0-9+\-\s()]{7,20}$/', $phone)) {
        $errors['phone'] = 'Please enter a valid phone number.';
    }

    $subject = trim($data['subject'] ?? '');
    if ($subject === '' || mb_strlen($subject) < 3) {
        $errors['subject'] = 'Please enter a subject.';
    } elseif (mb_strlen($subject) > 150) {
        $errors['subject'] = 'Subject is too long.';
    }

    $message = trim($data['message'] ?? '');
    if ($message === '' || mb_strlen($message) < 10) {
        $errors['message'] = 'Please write a message of at least 10 characters.';
    } elseif (mb_strlen($message) > 5000) {
        $errors['message'] = 'Message is too long.';
    }

    $company = trim($data['company'] ?? '');
    if ($company !== '' && mb_strlen($company) > 150) {
        $errors['company'] = 'Company name is too long.';
    }

    return $errors;
}

/**
 * Structured navigation used by the header and footer.
 */
function nav_links(): array
{
    return [
        ['label' => 'Home',       'href' => '#home'],
        ['label' => 'About',      'href' => '#about'],
        ['label' => 'Skills',     'href' => '#skills'],
        ['label' => 'Projects',   'href' => '#projects'],
        ['label' => 'Experience', 'href' => '#experience'],
        ['label' => 'Services',   'href' => '#services'],
        ['label' => 'Contact',    'href' => '#contact'],
    ];
}

/** Roles cycled by the hero typing animation. */
function hero_roles(): array
{
    return [
        'Full Stack Developer',
        'Platform Architect',
        'PHP Engineer',
        'Shopify Developer',
        'WordPress Expert',
        'Founder of Lumynex',
    ];
}

/** Skills grid content, grouped for visual rhythm. */
function skills_list(): array
{
    return [
        ['name' => 'HTML5',                  'group' => 'Core',        'level' => 96],
        ['name' => 'CSS3',                   'group' => 'Core',        'level' => 95],
        ['name' => 'JavaScript (ES6+)',      'group' => 'Core',        'level' => 92],
        ['name' => 'PHP',                    'group' => 'Backend',     'level' => 97],
        ['name' => 'MySQL',                  'group' => 'Backend',     'level' => 93],
        ['name' => 'REST APIs',              'group' => 'Backend',     'level' => 94],
        ['name' => 'Paystack API',           'group' => 'Backend',     'level' => 90],
        ['name' => 'Git & GitHub',           'group' => 'Tooling',     'level' => 95],
        ['name' => 'Linux',                  'group' => 'Tooling',     'level' => 88],
        ['name' => 'Docker',                 'group' => 'Tooling',     'level' => 82],
        ['name' => 'WordPress',              'group' => 'Platforms',   'level' => 91],
        ['name' => 'Shopify',                'group' => 'Platforms',   'level' => 89],
        ['name' => 'Responsive Design',      'group' => 'Craft',       'level' => 96],
        ['name' => 'Performance Optimization','group' => 'Craft',      'level' => 90],
        ['name' => 'Security',               'group' => 'Craft',       'level' => 93],
        ['name' => 'Accessibility',          'group' => 'Craft',       'level' => 88],
    ];
}

/** Featured project data. */
function projects_list(): array
{
    return [
        [
            'name'         => 'Vendo',
            'tag'          => 'Enterprise Marketplace',
            'summary'      => 'A multi-vendor marketplace platform with escrow-secured payments and per-vendor storefronts.',
            'problem'      => 'Independent sellers needed a trustworthy shared marketplace where payments are protected until delivery is confirmed, without building their own infrastructure.',
            'architecture' => 'PHP 8 service layer over MySQL, queued jobs for OTP + payout processing, and a vendor-scoped storefront generator that provisions subdomBased pages on signup.',
            'stack'        => ['PHP', 'MySQL', 'Paystack', 'REST API', 'Redis Queue', 'Vanilla JS'],
            'features'     => [
                'Automatic vendor storefront generation',
                'Paystack split payments across vendors',
                'Escrow wallet with delayed vendor payout',
                'OTP delivery verification for buyers',
                'Two-factor authentication for vendor accounts',
                'Role-based admin dashboard with analytics',
            ],
            'challenge'    => 'Splitting a single payment across multiple vendors while keeping a portion in escrow until delivery, without doubling transaction fees.',
            'solution'     => 'Built a custom ledger layer on top of the Paystack split API that tracks per-vendor balances internally and only releases funds after an OTP-confirmed delivery event.',
            'demo'         => '#',
            'code'         => '#',
        ],
        [
            'name'         => 'Lumynex',
            'tag'          => 'SaaS Ecosystem',
            'summary'      => 'A technology ecosystem and startup infrastructure platform built to host and scale multiple products under one architecture.',
            'problem'      => 'Early-stage products needed shared authentication, billing, and infrastructure instead of rebuilding the same foundation for every new idea.',
            'architecture' => 'A modular monolith with clearly separated domains (auth, billing, tenancy) designed to be extracted into services as individual products scale.',
            'stack'        => ['PHP', 'MySQL', 'REST API', 'Docker', 'Vanilla JS'],
            'features'     => [
                'Shared multi-tenant authentication',
                'Centralized billing and subscription engine',
                'Modular product scaffolding',
                'Internal admin and analytics console',
            ],
            'challenge'    => 'Designing shared infrastructure flexible enough for products that don\u2019t exist yet, without over-engineering for hypothetical needs.',
            'solution'     => 'Adopted a modular-monolith-first approach: strict internal module boundaries today, with clear service-extraction seams for tomorrow.',
            'demo'         => '#',
            'code'         => '#',
        ],
        [
            'name'         => 'M&K Jewelry Store',
            'tag'          => 'Luxury Ecommerce',
            'summary'      => 'A luxury ecommerce experience for a fine jewelry brand, built around an optimized, mobile-first checkout.',
            'problem'      => 'A luxury retail brand needed an online storefront that matched the elegance of its physical showroom while converting mobile visitors reliably.',
            'architecture' => 'Lightweight PHP storefront with a custom cart/checkout flow, image-optimized product catalog, and Paystack payment integration.',
            'stack'        => ['PHP', 'MySQL', 'Paystack', 'Vanilla JS', 'CSS3'],
            'features'     => [
                'Modern, gallery-led product presentation',
                'Optimized single-page checkout',
                'Mobile-first responsive layout',
                'Wishlist and product filtering',
            ],
            'challenge'    => 'Reducing checkout abandonment on mobile without stripping away the premium feel of the brand.',
            'solution'     => 'Collapsed checkout into a single scroll-through flow with inline validation, cutting form friction while preserving a refined visual layer.',
            'demo'         => '#',
            'code'         => '#',
        ],
        [
            'name'         => 'MAC Power',
            'tag'          => 'Lead Generation',
            'summary'      => 'A conversion-optimized lead generation platform for a solar energy company.',
            'problem'      => 'The business needed a fast, SEO-friendly site that turned visitor interest into qualified leads for solar installations.',
            'architecture' => 'Static-first PHP templating with server-rendered lead forms, aggressive image optimization, and schema markup for local SEO.',
            'stack'        => ['PHP', 'MySQL', 'JavaScript', 'SEO/Schema.org'],
            'features'     => [
                'Conversion-focused landing pages',
                'Sub-second page loads',
                'Local SEO schema markup',
                'Lead capture with validation and spam filtering',
            ],
            'challenge'    => 'Balancing a content-rich, SEO-heavy site with the fast load times conversion campaigns depend on.',
            'solution'     => 'Server-rendered critical content with deferred, lazy-loaded assets so the page is interactive well before every image finishes loading.',
            'demo'         => '#',
            'code'         => '#',
        ],
    ];
}

/** Vertical career timeline. */
function experience_list(): array
{
    return [
        [
            'period' => '2018',
            'title'  => 'National Diploma',
            'org'    => 'Polytechnic Studies',
            'desc'   => 'Built a formal foundation in computing while shipping small freelance projects on the side.',
        ],
        [
            'period' => '2019 — 2021',
            'title'  => 'Freelance Developer',
            'org'    => 'Independent',
            'desc'   => 'Delivered websites and small business tools for local clients, learning to own a project end-to-end — from client conversation to deployment.',
        ],
        [
            'period' => '2021 — 2023',
            'title'  => 'Full Stack Engineer',
            'org'    => 'Contract & Product Work',
            'desc'   => 'Moved into larger PHP/MySQL platforms — ecommerce, payments, and admin systems — with a growing focus on security and performance.',
        ],
        [
            'period' => '2023 — Present',
            'title'  => 'Founder',
            'org'    => 'Lumynex',
            'desc'   => 'Founded Lumynex to build shared infrastructure for fintech and commerce products, architecting systems meant to scale past a single idea.',
        ],
        [
            'period' => 'Ongoing',
            'title'  => 'Current Projects',
            'org'    => 'Vendo · M&K Jewelry · MAC Power',
            'desc'   => 'Actively building and maintaining a portfolio of production platforms across marketplace, luxury retail, and lead generation.',
        ],
    ];
}

/** Service offering cards. */
function services_list(): array
{
    return [
        ['title' => 'Full Stack Development',  'desc' => 'End-to-end web applications, from database schema to polished interface.'],
        ['title' => 'PHP Development',          'desc' => 'Secure, maintainable PHP 8+ backends built on clean architecture.'],
        ['title' => 'WordPress Development',    'desc' => 'Custom themes, plugins, and performance tuning for WordPress sites.'],
        ['title' => 'Shopify Development',      'desc' => 'Custom storefronts, theme development, and checkout customization.'],
        ['title' => 'Ecommerce Development',    'desc' => 'Conversion-focused storefronts with reliable, secure checkout flows.'],
        ['title' => 'API Development',          'desc' => 'Well-documented REST APIs designed for real-world integration.'],
        ['title' => 'Payment Integration',      'desc' => 'Paystack and other payment gateway integration, including split payments and escrow.'],
        ['title' => 'Website Optimization',     'desc' => 'Performance audits and fixes targeting real Core Web Vitals gains.'],
        ['title' => 'Security Consulting',      'desc' => 'Hardening sessions, inputs, and infrastructure against common attack vectors.'],
    ];
}

/** Testimonial placeholders — clearly marked as such in the UI. */
function testimonials_list(): array
{
    return [
        [
            'name'  => 'Client Testimonial',
            'role'  => 'Founder, Early-Stage Startup',
            'quote' => 'Placeholder — swap in a real client quote once available. Keep it specific: what problem was solved and what changed as a result.',
        ],
        [
            'name'  => 'Client Testimonial',
            'role'  => 'Product Lead, Ecommerce Brand',
            'quote' => 'Placeholder — swap in a real client quote once available. Mention the outcome in measurable terms where possible.',
        ],
        [
            'name'  => 'Client Testimonial',
            'role'  => 'Operations Manager, Marketplace',
            'quote' => 'Placeholder — swap in a real client quote once available. Keep the voice authentic to the actual client.',
        ],
    ];
}

/** Live-looking platform metrics shown in the hero "ledger" panel. */
function hero_metrics(): array
{
    return [
        ['label' => 'Platforms architected', 'value' => '4'],
        ['label' => 'Payment integrations',  'value' => '10+'],
        ['label' => 'Years engineering',     'value' => '5+'],
        ['label' => 'Uptime target',         'value' => '99.9%'],
    ];
}