<?php
/**
 * includes/header.php
 * Document head (SEO, Open Graph, schema.org) and primary navigation.
 * Expects config.php + functions.php to already be loaded by index.php.
 */
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= e(SITE_TITLE) ?></title>

<meta name="description" content="<?= e(SITE_DESCRIPTION) ?>">
<meta name="keywords" content="<?= e(SITE_KEYWORDS) ?>">
<meta name="author" content="<?= e(SITE_AUTHOR) ?>">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= e(SITE_URL) ?>/">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:title" content="<?= e(SITE_TITLE) ?>">
<meta property="og:description" content="<?= e(SITE_DESCRIPTION) ?>">
<meta property="og:url" content="<?= e(SITE_URL) ?>/">
<meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
<meta property="og:image" content="<?= e(SITE_URL) ?>/assets/images/og-cover.jpg">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e(SITE_TITLE) ?>">
<meta name="twitter:description" content="<?= e(SITE_DESCRIPTION) ?>">
<meta name="twitter:image" content="<?= e(SITE_URL) ?>/assets/images/og-cover.jpg">

<!-- Favicon -->
<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22 fill=%22%23D4A24C%22>A</text></svg>">

<!-- Preconnect + fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,450;0,9..144,600;1,9..144,450&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

<link rel="stylesheet" href="<?= asset('css/style.css') ?>">

<!-- Schema.org structured data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Ayodeji Oluwafemi Daniel",
  "url": "<?= e(SITE_URL) ?>/",
  "jobTitle": "Full Stack Developer & Platform Architect",
  "worksFor": {
    "@type": "Organization",
    "name": "Lumynex"
  },
  "description": "<?= e(SITE_DESCRIPTION) ?>",
  "sameAs": []
}
</script>
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>

<div class="grid-backdrop" aria-hidden="true"></div>
<div class="cursor-follower" aria-hidden="true"></div>

<header class="site-header" id="site-header">
  <div class="container header-inner">
    <a href="#home" class="brand" aria-label="<?= e(SITE_NAME) ?> — home">
      <span class="brand-mark">AD</span>
      <span class="brand-name">Ayodeji<span class="brand-dot">.</span>Daniel</span>
    </a>

    <nav class="primary-nav" aria-label="Primary">
      <ul>
        <?php foreach (nav_links() as $link): ?>
          <li><a href="<?= e($link['href']) ?>" class="nav-link"><?= e($link['label']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="header-actions">
      <button type="button" class="theme-toggle" id="theme-toggle" aria-label="Switch to light theme" aria-pressed="false">
        <svg class="icon-sun" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.6"/><path stroke="currentColor" stroke-width="1.6" stroke-linecap="round" d="M12 2v2M12 20v2M4 12H2M22 12h-2M5 5l1.4 1.4M17.6 17.6 19 19M5 19l1.4-1.4M17.6 6.4 19 5"/></svg>
        <svg class="icon-moon" width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" d="M20 14.5A8.5 8.5 0 1 1 9.5 4a7 7 0 0 0 10.5 10.5Z"/></svg>
      </button>
      <a href="#contact" class="btn btn-primary btn-small">Hire Me</a>
      <button type="button" class="nav-toggle" id="nav-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-nav">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>

  <nav class="mobile-nav" id="mobile-nav" aria-label="Mobile">
    <ul>
      <?php foreach (nav_links() as $link): ?>
        <li><a href="<?= e($link['href']) ?>" class="nav-link"><?= e($link['label']) ?></a></li>
      <?php endforeach; ?>
      <li><a href="#contact" class="btn btn-primary">Hire Me</a></li>
    </ul>
  </nav>
</header>

<div class="scroll-progress" id="scroll-progress" aria-hidden="true"></div>

<main id="main">