/**
 * =============================================================================
 * assets/js/main.js
 * -----------------------------------------------------------------------------
 * Vanilla ES6+ only. Organized into small, self-contained init functions so
 * each interaction is easy to find, test, and (if needed) disable.
 * =============================================================================
 */
(() => {
  'use strict';

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------------------------- Theme toggle ---------------------------- */
  function initTheme() {
    const toggle = document.getElementById('theme-toggle');
    const root = document.documentElement;
    const stored = null; // no localStorage per artifact/browser-storage constraints; session-only default
    const prefersLight = window.matchMedia('(prefers-color-scheme: light)').matches;

    if (stored === 'light' || (!stored && prefersLight)) {
      root.setAttribute('data-theme', 'light');
    }

    const isLight = () => root.getAttribute('data-theme') === 'light';
    toggle?.setAttribute('aria-pressed', String(isLight()));

    toggle?.addEventListener('click', () => {
      const next = isLight() ? 'dark' : 'light';
      root.setAttribute('data-theme', next);
      toggle.setAttribute('aria-pressed', String(next === 'light'));
      toggle.setAttribute('aria-label', next === 'light' ? 'Switch to dark theme' : 'Switch to light theme');
    });
  }

  /* ------------------------------ Header state ---------------------------- */
  function initHeaderScroll() {
    const header = document.getElementById('site-header');
    const progress = document.getElementById('scroll-progress');
    if (!header) return;

    const onScroll = () => {
      header.classList.toggle('is-scrolled', window.scrollY > 12);
      if (progress) {
        const max = document.documentElement.scrollHeight - window.innerHeight;
        progress.style.width = max > 0 ? `${(window.scrollY / max) * 100}%` : '0%';
      }
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* -------------------------------- Mobile nav ------------------------------ */
  function initMobileNav() {
    const toggle = document.getElementById('nav-toggle');
    const menu = document.getElementById('mobile-nav');
    if (!toggle || !menu) return;

    const close = () => {
      toggle.setAttribute('aria-expanded', 'false');
      toggle.setAttribute('aria-label', 'Open menu');
      menu.classList.remove('is-open');
    };

    toggle.addEventListener('click', () => {
      const isOpen = menu.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', String(isOpen));
      toggle.setAttribute('aria-label', isOpen ? 'Close menu' : 'Open menu');
    });

    menu.querySelectorAll('a').forEach((link) => link.addEventListener('click', close));
  }

  /* ------------------------------ Active nav link --------------------------- */
  function initActiveNav() {
    const sections = document.querySelectorAll('main > section[id]');
    const links = document.querySelectorAll('.nav-link');
    if (!sections.length || !links.length) return;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          links.forEach((link) => {
            link.classList.toggle('is-active', link.getAttribute('href') === `#${entry.target.id}`);
          });
        });
      },
      { rootMargin: '-45% 0px -50% 0px', threshold: 0 }
    );
    sections.forEach((s) => observer.observe(s));
  }

  /* ------------------------------- Scroll reveal ----------------------------- */
  function initScrollReveal() {
    const items = document.querySelectorAll('[data-reveal]');
    if (!items.length) return;

    if (prefersReducedMotion) {
      items.forEach((el) => el.classList.add('is-visible'));
      return;
    }

    const observer = new IntersectionObserver(
      (entries, obs) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            obs.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.15 }
    );
    items.forEach((el) => observer.observe(el));
  }

  /* --------------------------------- Typing effect --------------------------- */
  function initTypingEffect() {
    const el = document.getElementById('typing-text');
    if (!el) return;

    let roles = [];
    try {
      roles = JSON.parse(el.dataset.roles || '[]');
    } catch (e) {
      roles = [];
    }
    if (!roles.length) roles = ['Full Stack Developer'];

    if (prefersReducedMotion) {
      el.textContent = roles[0];
      return;
    }

    let roleIndex = 0;
    let charIndex = 0;
    let deleting = false;

    const tick = () => {
      const current = roles[roleIndex];
      if (!deleting) {
        charIndex++;
        el.textContent = current.slice(0, charIndex);
        if (charIndex === current.length) {
          deleting = true;
          setTimeout(tick, 1400);
          return;
        }
      } else {
        charIndex--;
        el.textContent = current.slice(0, charIndex);
        if (charIndex === 0) {
          deleting = false;
          roleIndex = (roleIndex + 1) % roles.length;
        }
      }
      setTimeout(tick, deleting ? 40 : 75);
    };
    tick();
  }

  /* ------------------------------ Animated skill bars ------------------------- */
  function initSkillBars() {
    const bars = document.querySelectorAll('.skill-bar-fill');
    if (!bars.length) return;

    const observer = new IntersectionObserver(
      (entries, obs) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const level = entry.target.dataset.level || '0';
            entry.target.style.width = `${level}%`;
            obs.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.4 }
    );
    bars.forEach((bar) => observer.observe(bar));
  }

  /* -------------------------------- Magnetic buttons --------------------------- */
  function initMagneticButtons() {
    if (prefersReducedMotion || !window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

    document.querySelectorAll('.magnetic').forEach((btn) => {
      btn.addEventListener('mousemove', (e) => {
        const rect = btn.getBoundingClientRect();
        const x = e.clientX - rect.left - rect.width / 2;
        const y = e.clientY - rect.top - rect.height / 2;
        btn.style.transform = `translate(${x * 0.18}px, ${y * 0.35}px)`;
      });
      btn.addEventListener('mouseleave', () => {
        btn.style.transform = '';
      });
    });
  }

  /* ---------------------------------- Ripple ----------------------------------- */
  function initRipple() {
    document.querySelectorAll('.btn').forEach((btn) => {
      btn.addEventListener('click', function (e) {
        const rect = this.getBoundingClientRect();
        const ripple = document.createElement('span');
        ripple.className = 'btn-ripple';
        ripple.style.left = `${e.clientX - rect.left}px`;
        ripple.style.top = `${e.clientY - rect.top}px`;
        this.appendChild(ripple);
        setTimeout(() => ripple.remove(), 650);
      });
    });
  }

  /* ------------------------------ Cursor follower ------------------------------ */
  function initCursorFollower() {
    const el = document.querySelector('.cursor-follower');
    if (!el || prefersReducedMotion) return;

    window.addEventListener('mousemove', (e) => {
      el.style.left = `${e.clientX}px`;
      el.style.top = `${e.clientY}px`;
    }, { passive: true });
  }

  /* -------------------------------- Project accordions -------------------------- */
  function initProjectToggles() {
    document.querySelectorAll('.project-toggle').forEach((btn) => {
      btn.addEventListener('click', () => {
        const targetId = btn.getAttribute('aria-controls');
        const panel = document.getElementById(targetId);
        if (!panel) return;
        const isOpen = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', String(!isOpen));
        panel.hidden = isOpen;
        btn.firstChild.textContent = isOpen ? 'Full case study ' : 'Hide case study ';
      });
    });
  }

  /* --------------------------------- Testimonials -------------------------------- */
  function initTestimonialSlider() {
    const track = document.getElementById('testimonial-track');
    const dotsWrap = document.getElementById('testimonial-dots');
    const prevBtn = document.getElementById('testimonial-prev');
    const nextBtn = document.getElementById('testimonial-next');
    if (!track || !dotsWrap) return;

    const slides = track.children.length;
    let index = 0;

    for (let i = 0; i < slides; i++) {
      const dot = document.createElement('button');
      dot.type = 'button';
      dot.setAttribute('aria-label', `Go to testimonial ${i + 1}`);
      if (i === 0) dot.classList.add('is-active');
      dot.addEventListener('click', () => goTo(i));
      dotsWrap.appendChild(dot);
    }

    function goTo(i) {
      index = (i + slides) % slides;
      track.style.transform = `translateX(-${index * 100}%)`;
      [...dotsWrap.children].forEach((d, di) => d.classList.toggle('is-active', di === index));
    }

    prevBtn?.addEventListener('click', () => goTo(index - 1));
    nextBtn?.addEventListener('click', () => goTo(index + 1));

    if (!prefersReducedMotion && slides > 1) {
      setInterval(() => goTo(index + 1), 7000);
    }
  }

  /* ----------------------------------- Back to top -------------------------------- */
  function initBackToTop() {
    const btn = document.getElementById('back-to-top');
    btn?.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: prefersReducedMotion ? 'auto' : 'smooth' });
    });
  }

  /* ----------------------------------- Contact form -------------------------------- */
  function initContactForm() {
    const form = document.getElementById('contact-form');
    if (!form) return;

    const submitBtn = document.getElementById('contact-submit');
    const notice = document.getElementById('form-notice');

    const clearErrors = () => {
      form.querySelectorAll('.field-error').forEach((el) => (el.textContent = ''));
      form.querySelectorAll('.form-field').forEach((el) => el.classList.remove('has-error'));
    };

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      clearErrors();
      notice.textContent = '';
      notice.className = 'form-notice';
      submitBtn.classList.add('is-loading');
      submitBtn.disabled = true;

      try {
        const response = await fetch('contact.php', {
          method: 'POST',
          body: new FormData(form),
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        const data = await response.json();

        if (data.success) {
          notice.textContent = data.message;
          notice.classList.add('is-success');
          form.reset();
        } else {
          notice.textContent = data.message || 'Something went wrong. Please try again.';
          notice.classList.add('is-error');

          Object.entries(data.errors || {}).forEach(([field, msg]) => {
            const errEl = form.querySelector(`[data-error-for="${field}"]`);
            const wrapper = errEl?.closest('.form-field');
            if (errEl) errEl.textContent = msg;
            wrapper?.classList.add('has-error');
          });
        }
      } catch (err) {
        notice.textContent = 'Network error — please check your connection and try again.';
        notice.classList.add('is-error');
      } finally {
        submitBtn.classList.remove('is-loading');
        submitBtn.disabled = false;
      }
    });
  }

  /* ------------------------------------- Boot -------------------------------------- */
  document.addEventListener('DOMContentLoaded', () => {
    // Hero roles are injected as a data attribute to keep content in PHP, not JS.
    const typingEl = document.getElementById('typing-text');
    if (typingEl && !typingEl.dataset.roles) {
      typingEl.dataset.roles = JSON.stringify([
        'Full Stack Developer',
        'Platform Architect',
        'PHP Engineer',
        'Shopify Developer',
        'WordPress Expert',
        'Founder of Lumynex',
      ]);
    }

    initTheme();
    initHeaderScroll();
    initMobileNav();
    initActiveNav();
    initScrollReveal();
    initTypingEffect();
    initSkillBars();
    initMagneticButtons();
    initRipple();
    initCursorFollower();
    initProjectToggles();
    initTestimonialSlider();
    initBackToTop();
    initContactForm();
  });
})();
