<?php
/** components/skills.php — Interactive skill cards with animated proficiency bars. */
declare(strict_types=1);
$skills = skills_list();
?>
<section id="skills" class="section skills" aria-label="Skills">
  <div class="container">
    <div class="section-heading reveal" data-reveal>
      <p class="eyebrow">Skills</p>
      <h2>The stack behind the systems.</h2>
    </div>

    <div class="skills-grid">
      <?php foreach ($skills as $i => $skill): ?>
        <div class="skill-card reveal" data-reveal style="--delay: <?= (int) ($i % 8) * 40 ?>ms">
          <div class="skill-card-top">
            <span class="skill-name"><?= e($skill['name']) ?></span>
            <span class="skill-group"><?= e($skill['group']) ?></span>
          </div>
          <div class="skill-bar-track" role="progressbar" aria-valuenow="<?= (int) $skill['level'] ?>" aria-valuemin="0" aria-valuemax="100" aria-label="<?= e($skill['name']) ?> proficiency">
            <div class="skill-bar-fill" data-level="<?= (int) $skill['level'] ?>"></div>
          </div>
          <span class="skill-level"><?= (int) $skill['level'] ?>%</span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
