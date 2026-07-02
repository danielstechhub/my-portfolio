<?php
/**
 * includes/footer.php
 * Closes <main>, renders the site footer, and loads JS.
 */
declare(strict_types=1);
?>
</main>

<footer class="site-footer">
  <div class="container footer-inner">
    <div class="footer-brand">
      <a href="#home" class="brand">
        <span class="brand-mark">AD</span>
        <span class="brand-name">Ayodeji<span class="brand-dot">.</span>Daniel</span>
      </a>
      <p class="footer-tagline"><?= e(SITE_TAGLINE) ?> — building software that transforms businesses through elegant engineering.</p>
      <ul class="social-links" aria-label="Social links">
        <li><a href="https://github.com/" target="_blank" rel="noopener noreferrer" aria-label="GitHub">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 .5C5.73.5.98 5.24.98 11.52c0 4.94 3.2 9.13 7.65 10.6.56.1.76-.24.76-.54v-1.9c-3.11.68-3.77-1.5-3.77-1.5-.51-1.3-1.24-1.64-1.24-1.64-1.02-.7.08-.69.08-.69 1.12.08 1.72 1.15 1.72 1.15 1 1.72 2.62 1.22 3.26.93.1-.73.39-1.22.71-1.5-2.48-.28-5.1-1.24-5.1-5.53 0-1.22.44-2.22 1.15-3-.11-.28-.5-1.42.11-2.96 0 0 .94-.3 3.08 1.15a10.7 10.7 0 0 1 5.6 0c2.14-1.45 3.08-1.15 3.08-1.15.61 1.54.22 2.68.11 2.96.72.78 1.15 1.78 1.15 3 0 4.3-2.63 5.24-5.13 5.52.4.35.76 1.03.76 2.08v3.08c0 .3.2.65.77.54A11.03 11.03 0 0 0 23.02 11.5C23.02 5.24 18.27.5 12 .5Z"/></svg>
        </a></li>
        <li><a href="https://linkedin.com/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5ZM.5 8.98h4.96V23.5H.5V8.98ZM8.98 8.98h4.75v1.99h.07c.66-1.25 2.28-2.57 4.7-2.57 5.02 0 5.95 3.3 5.95 7.6v7.5h-4.96v-6.65c0-1.59-.03-3.63-2.21-3.63-2.22 0-2.56 1.73-2.56 3.51v6.77H8.98V8.98Z"/></svg>
        </a></li>
        <li><a href="https://x.com/" target="_blank" rel="noopener noreferrer" aria-label="X (Twitter)">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.9 1.5h3.3l-7.2 8.2 8.5 11.3h-6.7l-5.2-6.9-6 6.9H1.3l7.7-8.8L.9 1.5h6.9l4.7 6.3 6.4-6.3Zm-1.2 17.6h1.8L6.4 3.4H4.5l13.2 15.7Z"/></svg>
        </a></li>
      </ul>
    </div>

    <div class="footer-links">
      <h3 class="footer-heading">Quick Links</h3>
      <ul>
        <?php foreach (nav_links() as $link): ?>
          <li><a href="<?= e($link['href']) ?>"><?= e($link['label']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="footer-links">
      <h3 class="footer-heading">Services</h3>
      <ul>
        <li><a href="#services">Full Stack Development</a></li>
        <li><a href="#services">PHP Development</a></li>
        <li><a href="#services">Payment Integration</a></li>
        <li><a href="#services">Security Consulting</a></li>
      </ul>
    </div>

    <div class="footer-cta">
      <h3 class="footer-heading">Start a project</h3>
      <p>Have something worth building well?</p>
      <a href="#contact" class="btn btn-outline btn-small">Get in touch</a>
    </div>
  </div>

  <div class="container footer-bottom">
    <p>&copy; <?= date('Y') ?> Ayodeji Oluwafemi Daniel. All rights reserved.</p>
    <button type="button" class="back-to-top" id="back-to-top" aria-label="Back to top">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 19V5M5 12l7-7 7 7"/></svg>
    </button>
  </div>
</footer>

<script src="<?= asset('js/main.js') ?>" defer></script>
</body>
</html>