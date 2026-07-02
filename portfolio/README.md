# Ayodeji Oluwafemi Daniel — Portfolio

A production-ready PHP 8.2+ portfolio site. No frameworks — vanilla HTML5, CSS3, JS (ES6+), and PHP.

## Local setup with XAMPP

1. Copy the entire `portfolio` folder into your XAMPP `htdocs` directory, e.g.
   `C:\xampp\htdocs\portfolio`.
2. Open the XAMPP Control Panel and start **Apache** and **MySQL**.
3. Import the database: open `http://localhost/phpmyadmin`, click **Import**,
   choose `database/schema.sql`, click **Go**. This creates `portfolio_db`.
4. Visit `http://localhost/portfolio/` in your browser.

That's it — `includes/database.php` already defaults to `root` / no password /
`127.0.0.1`, which matches a stock XAMPP install. Only create a `.env` file
(copy `.env.example`) if your local MySQL differs (e.g. you set a password).

## Project structure

```
portfolio/
├── index.php              Entry point — assembles header, sections, footer
├── config.php              Constants, env loader, session + security headers, CSRF
├── contact.php              AJAX handler for the contact form
├── includes/
│   ├── functions.php        Helpers + content arrays (skills, projects, etc.)
│   ├── database.php         PDO connection + query helpers
│   ├── header.php           <head>, SEO tags, nav
│   └── footer.php           Footer, closes <main>, loads JS
├── components/               One file per page section
├── assets/
│   ├── css/style.css         Design tokens + all styling
│   └── js/main.js            Theme toggle, animations, form handling
├── database/schema.sql       MySQL schema (contact_submissions, analytics…)
└── storage/logs/             Rate-limit state + fallback submission logs
```

## Security features implemented

- CSRF tokens (session-bound, rotated hourly and after each submission)
- Honeypot field on the contact form
- File-locked, sliding-window rate limiting (5 submissions / 10 min / IP)
- Server-side validation on every field
- Prepared statements (PDO, `ATTR_EMULATE_PREPARES` off) — no raw SQL interpolation
- Security headers: CSP, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, HSTS (on HTTPS)
- Hardened session cookies (HttpOnly, SameSite=Strict, Secure on HTTPS)
- `.htaccess` blocks direct access to `includes/`, `components/`, `storage/`, `database/`, and dotfiles

## Things to personalize before going live

- Swap the portrait placeholder (`.portrait-placeholder` in `components/hero.php`) for a real photo.
- Replace `#` demo/GitHub links in `includes/functions.php::projects_list()`.
- Add real testimonials in `includes/functions.php::testimonials_list()`.
- Add `/assets/resume.pdf` (the "Download Resume" button links here).
- Wire up real SMTP in `contact.php` (currently uses PHP's built-in `mail()` as a placeholder — swap in PHPMailer or similar for reliable delivery).
- Update social links in `includes/footer.php`.
- Set `SITE_URL` in `config.php` to your real domain before deploying (affects canonical/OG tags and `sitemap.xml`/`robots.txt`).

## Deploying to a live Apache + PHP 8.2+ server

1. Upload everything except `.env` (create a fresh one on the server).
2. Import `database/schema.sql` on your production MySQL.
3. Set real DB credentials and `CONTACT_EMAIL_*` in `.env`.
4. Make sure `storage/` is writable by the web server user.
5. Uncomment the HTTPS-redirect block in `.htaccess`.
