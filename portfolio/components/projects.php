<?php
/** components/projects.php — Featured project showcase with full detail per project. */
declare(strict_types=1);
$projects = projects_list();
?>
<section id="projects" class="section projects" aria-label="Featured Projects">
  <div class="container">
    <div class="section-heading reveal" data-reveal>
      <p class="eyebrow">Featured Projects</p>
      <h2>Platforms built for production, not for demos.</h2>
    </div>

    <div class="projects-list">
      <?php foreach ($projects as $index => $project): ?>
        <article class="project-card reveal" data-reveal>
          <div class="project-card-main">
            <div class="project-index"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></div>
            <div class="project-info">
              <span class="project-tag"><?= e($project['tag']) ?></span>
              <h3 class="project-name"><?= e($project['name']) ?></h3>
              <p class="project-summary"><?= e($project['summary']) ?></p>

              <ul class="project-stack" aria-label="Technology stack">
                <?php foreach ($project['stack'] as $tech): ?>
                  <li><?= e($tech) ?></li>
                <?php endforeach; ?>
              </ul>

              <div class="project-actions">
                <a href="<?= e($project['demo']) ?>" class="btn btn-primary btn-small">Live Demo</a>
                <a href="<?= e($project['code']) ?>" class="btn btn-outline btn-small">GitHub</a>
                <button type="button" class="project-toggle" aria-expanded="false" aria-controls="project-detail-<?= $index ?>">
                  Full case study
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                </button>
              </div>
            </div>
          </div>

          <div class="project-detail" id="project-detail-<?= $index ?>" hidden>
            <div class="project-detail-grid">
              <div>
                <h4>Problem</h4>
                <p><?= e($project['problem']) ?></p>
              </div>
              <div>
                <h4>Architecture</h4>
                <p><?= e($project['architecture']) ?></p>
              </div>
              <div>
                <h4>Key Features</h4>
                <ul>
                  <?php foreach ($project['features'] as $feature): ?>
                    <li><?= e($feature) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
              <div>
                <h4>Challenge</h4>
                <p><?= e($project['challenge']) ?></p>
              </div>
              <div>
                <h4>Solution</h4>
                <p><?= e($project['solution']) ?></p>
              </div>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
