<?php
/**
 * =============================================================================
 * includes/database.php
 * -----------------------------------------------------------------------------
 * PDO connection helper. Defaults match a stock local XAMPP install
 * (host=localhost, user=root, no password, db=portfolio_db) so the site
 * connects out of the box after importing database/schema.sql. Override any
 * of these in a .env file for a real production host.
 * =============================================================================
 */

declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = env('DB_HOST', '127.0.0.1');
    $port = env('DB_PORT', '3306');
    $name = env('DB_NAME', 'portfolio_db');
    $user = env('DB_USER', 'root');
    $pass = env('DB_PASS', '');

    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        // Never leak connection details to the browser — log and rethrow a
        // generic exception that calling code can catch and handle gracefully.
        write_log(LOG_DIR . '/db-error.log', 'DB connection failed: ' . $e->getMessage());
        throw new RuntimeException('Database connection failed.');
    }

    return $pdo;
}

/**
 * Inserts one validated contact form submission and returns the new row id.
 *
 * @param array<string,string> $data
 */
function save_contact_submission(array $data): int
{
    $sql = 'INSERT INTO contact_submissions
                (name, email, phone, company, subject, message, project_type, budget, ip_address, user_agent)
            VALUES
                (:name, :email, :phone, :company, :subject, :message, :project_type, :budget, :ip_address, :user_agent)';

    $stmt = db()->prepare($sql);
    $stmt->execute([
        'name'         => trim($data['name'] ?? ''),
        'email'        => trim($data['email'] ?? ''),
        'phone'        => trim($data['phone'] ?? '') ?: null,
        'company'      => trim($data['company'] ?? '') ?: null,
        'subject'      => trim($data['subject'] ?? ''),
        'message'      => trim($data['message'] ?? ''),
        'project_type' => trim($data['project_type'] ?? '') ?: null,
        'budget'       => trim($data['budget'] ?? '') ?: null,
        'ip_address'   => client_ip(),
        'user_agent'   => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255) ?: null,
    ]);

    return (int) db()->lastInsertId();
}

/**
 * Records a lightweight, cookie-free page view. Failure here must never
 * break the page, so callers should wrap this in try/catch (see index.php).
 */
function log_page_view(string $page): void
{
    $sql = 'INSERT INTO visitor_analytics (page, referrer, ip_address, user_agent)
            VALUES (:page, :referrer, :ip_address, :user_agent)';

    $stmt = db()->prepare($sql);
    $stmt->execute([
        'page'       => $page,
        'referrer'   => substr($_SERVER['HTTP_REFERER'] ?? '', 0, 255) ?: null,
        'ip_address' => client_ip(),
        'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255) ?: null,
    ]);
}
