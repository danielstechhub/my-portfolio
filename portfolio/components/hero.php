<?php
/** components/hero.php — Full-viewport hero with typing effect and live metrics ledger. */
declare(strict_types=1);
?>
<section id="home" class="hero" aria-label="Introduction">
  <div class="container hero-grid">
    <div class="hero-copy">
      <p class="eyebrow reveal" data-reveal>Full Stack Developer &amp; Platform Architect</p>
      <h1 class="hero-title reveal" data-reveal>
        I build <span class="text-accent">fintech-grade</span> platforms that businesses can trust.
      </h1>
      <p class="hero-role reveal" data-reveal>
        <span class="hero-role-label">Currently working as a</span>
        <span class="typing-wrap"><span id="typing-text"></span><span class="typing-cursor" aria-hidden="true"></span></span>
      </p>
      <p class="hero-desc reveal" data-reveal><?= e(SITE_DESCRIPTION) ?></p>

      <div class="hero-actions reveal" data-reveal>
        <a href="#projects" class="btn btn-primary magnetic">View Projects</a>
        <a href="<?= asset('resume.pdf') ?>" class="btn btn-outline magnetic" download>Download Resume</a>
        <a href="#contact" class="btn btn-ghost magnetic">Hire Me</a>
      </div>
    </div>

    <div class="hero-visual reveal" data-reveal>
      <div class="portrait-frame">
        <div class="portrait-placeholder" role="img" aria-label="Portrait of Ayodeji Oluwafemi Daniel">
          <img src="<?= asset('images/WhatsApp Image 2026-07-02 at 12.32.46.jpeg') ?>" alt="Portrait of Ayodeji Oluwafemi Daniel" style="width:100%;height:100%;object-fit:cover;">
        </div>
        <div class="portrait-glow" aria-hidden="true"></div>
      </div>

      <div class="ledger-panel" aria-label="Platform metrics">
        <div class="ledger-header">
          <span class="ledger-dot" aria-hidden="true"></span>
          <span>lumynex/ledger — live</span>
        </div>
        <ul class="ledger-metrics">
          <?php foreach (hero_metrics() as $metric): ?>
            <li>
              <span class="ledger-value" data-count="<?= e($metric['value']) ?>"><?= e($metric['value']) ?></span>
              <span class="ledger-label"><?= e($metric['label']) ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>

  <a href="#about" class="scroll-indicator" aria-label="Scroll to About section">
    <span></span>
  </a>
</section>
