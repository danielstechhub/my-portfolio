<?php
/** components/experience.php — Vertical career timeline. */
declare(strict_types=1);
$experience = experience_list();
?>
<section id="experience" class="section experience" aria-label="Experience">
  <div class="container">
    <div class="section-heading reveal" data-reveal>
      <p class="eyebrow">Experience</p>
      <h2>From first freelance gig to founder.</h2>
    </div>

    <ol class="timeline">
      <?php foreach ($experience as $item): ?>
        <li class="timeline-item reveal" data-reveal>
          <div class="timeline-marker" aria-hidden="true"></div>
          <div class="timeline-content">
            <span class="timeline-period"><?= e($item['period']) ?></span>
            <h3 class="timeline-title"><?= e($item['title']) ?></h3>
            <span class="timeline-org"><?= e($item['org']) ?></span>
            <p class="timeline-desc"><?= e($item['desc']) ?></p>
          </div>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>