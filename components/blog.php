<?php
/** components/blog.php — Blog "coming soon" section. */
declare(strict_types=1);
?>
<section id="blog" class="section blog" aria-label="Blog">
  <div class="container blog-inner reveal" data-reveal>
    <p class="eyebrow">Blog</p>
    <h2>Writing on architecture, security, and shipping — coming soon.</h2>
    <p class="section-note">
      Notes on building fintech infrastructure, lessons from Lumynex, and practical PHP engineering.
      Subscribe below and it'll land in your inbox on release.
    </p>
    <form class="blog-notify-form" aria-label="Notify me when the blog launches">
      <label for="blog-email" class="sr-only">Email address</label>
      <input type="email" id="blog-email" name="blog_email" placeholder="you@company.com" autocomplete="email" required>
      <button type="submit" class="btn btn-primary">Notify Me</button>
    </form>
  </div>
</section>