<?php
/** components/contact.php — Contact form. Submits via fetch() to contact.php. */
declare(strict_types=1);
$token = csrf_token();
?>
<section id="contact" class="section contact" aria-label="Contact">
  <div class="container contact-grid">
    <div class="contact-intro reveal" data-reveal>
      <p class="eyebrow">Contact</p>
      <h2>Have a project worth building properly?</h2>
      <p>Tell me what you're building and what's at stake. I reply within one business day.</p>

      <ul class="contact-meta">
        <li>
          <span class="contact-meta-label">Email</span>
          <a href="mailto:<?= e(CONTACT_EMAIL_TO) ?>"><?= e(CONTACT_EMAIL_TO) ?></a>
        </li>
        <li>
          <span class="contact-meta-label">Based in</span>
          <span>Ibadan, Nigeria — working with clients worldwide</span>
        </li>
        <li>
          <span class="contact-meta-label">Availability</span>
          <span>Open for select new projects</span>
        </li>
      </ul>
    </div>

    <form class="contact-form reveal" data-reveal id="contact-form" novalidate>
      <input type="hidden" name="csrf_token" value="<?= e($token) ?>">
      <!-- Honeypot field — hidden from real users via CSS, bots tend to fill every field. -->
      <div class="honeypot-field" aria-hidden="true">
        <label for="website">Leave this field empty</label>
        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
      </div>

      <div class="form-row">
        <div class="form-field">
          <label for="name">Full Name <span aria-hidden="true">*</span></label>
          <input type="text" id="name" name="name" required autocomplete="name" maxlength="100">
          <span class="field-error" data-error-for="name"></span>
        </div>
        <div class="form-field">
          <label for="email">Email <span aria-hidden="true">*</span></label>
          <input type="email" id="email" name="email" required autocomplete="email" maxlength="150">
          <span class="field-error" data-error-for="email"></span>
        </div>
      </div>

      <div class="form-row">
        <div class="form-field">
          <label for="phone">Phone</label>
          <input type="tel" id="phone" name="phone" autocomplete="tel" maxlength="20">
          <span class="field-error" data-error-for="phone"></span>
        </div>
        <div class="form-field">
          <label for="company">Company</label>
          <input type="text" id="company" name="company" autocomplete="organization" maxlength="150">
          <span class="field-error" data-error-for="company"></span>
        </div>
      </div>

      <div class="form-row">
        <div class="form-field">
          <label for="project_type">Project Type</label>
          <select id="project_type" name="project_type">
            <option value="">Select one</option>
            <option value="web_app">Web Application</option>
            <option value="ecommerce">Ecommerce</option>
            <option value="api">API / Integration</option>
            <option value="wordpress">WordPress</option>
            <option value="shopify">Shopify</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="form-field">
          <label for="budget">Budget Range</label>
          <select id="budget" name="budget">
            <option value="">Select one</option>
            <option value="under_1k">Under $1,000</option>
            <option value="1k_5k">$1,000 – $5,000</option>
            <option value="5k_15k">$5,000 – $15,000</option>
            <option value="15k_plus">$15,000+</option>
          </select>
        </div>
      </div>

      <div class="form-field">
        <label for="subject">Subject <span aria-hidden="true">*</span></label>
        <input type="text" id="subject" name="subject" required maxlength="150">
        <span class="field-error" data-error-for="subject"></span>
      </div>

      <div class="form-field">
        <label for="message">Message <span aria-hidden="true">*</span></label>
        <textarea id="message" name="message" rows="5" required maxlength="5000"></textarea>
        <span class="field-error" data-error-for="message"></span>
      </div>

      <button type="submit" class="btn btn-primary btn-submit" id="contact-submit">
        <span class="btn-label">Send Message</span>
        <span class="btn-spinner" aria-hidden="true"></span>
      </button>

      <div class="form-notice" id="form-notice" role="status" aria-live="polite"></div>
    </form>
  </div>
</section>
