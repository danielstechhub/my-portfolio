<?php
/** components/testimonials.php — Testimonial slider (placeholder content). */
declare(strict_types=1);
$testimonials = testimonials_list();
?>
<section id="testimonials" class="section testimonials" aria-label="Testimonials">
  <div class="container">
    <div class="section-heading reveal" data-reveal>
      <p class="eyebrow">Testimonials</p>
      <h2>What people say — <span class="text-muted">coming soon</span></h2>
      <p class="section-note">Placeholders shown below until real client testimonials are collected.</p>
    </div>

    <div class="testimonial-slider reveal" data-reveal>
      <div class="testimonial-track" id="testimonial-track">
        <?php foreach ($testimonials as $t): ?>
          <div class="testimonial-card">
            <svg class="quote-mark" width="28" height="22" viewBox="0 0 28 22" fill="none" aria-hidden="true"><path fill="currentColor" d="M0 22V13.2C0 5.5 4.9.9 12.6 0l1 3.6C8.4 4.7 6 7.2 6 10.6h6V22H0Zm16 0V13.2c0-7.7 4.9-12.3 12.6-13.2l1 3.6c-5.2 1.1-7.6 3.6-7.6 7v.2h6V22H16Z"/></svg>
            <p class="testimonial-quote">&ldquo;<?= e($t['quote']) ?>&rdquo;</p>
            <div class="testimonial-author">
              <span class="testimonial-name"><?= e($t['name']) ?></span>
              <span class="testimonial-role"><?= e($t['role']) ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="testimonial-controls">
        <button type="button" class="testimonial-btn" id="testimonial-prev" aria-label="Previous testimonial">&larr;</button>
        <div class="testimonial-dots" id="testimonial-dots"></div>
        <button type="button" class="testimonial-btn" id="testimonial-next" aria-label="Next testimonial">&rarr;</button>
      </div>
    </div>
  </div>
</section>
