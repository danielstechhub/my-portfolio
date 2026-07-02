<?php
/** components/services.php — Service offering cards. */
declare(strict_types=1);
$services = services_list();
?>
<section id="services" class="section services" aria-label="Services">
  <div class="container">
    <div class="section-heading reveal" data-reveal>
      <p class="eyebrow">Services</p>
      <h2>Where I can help.</h2>
    </div>

    <div class="services-grid">
      <?php foreach ($services as $i => $service): ?>
        <div class="service-card reveal" data-reveal style="--delay: <?= (int) ($i % 6) * 50 ?>ms">
          <span class="service-index"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
          <h3><?= e($service['title']) ?></h3>
          <p><?= e($service['desc']) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
