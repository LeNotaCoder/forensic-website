<?php
// includes/security.php
// Single-file security helper. Include at the very top of each page BEFORE output:
// require_once __DIR__ . '/security.php'; Security::init();

class Security {
    // CONFIG — tweak to your environment
    private static $cookie_name = 'PHPSESSID';
    private static $csrf_token_name = 'csrf_token';
    private static $session_lifetime = 1800; // 30 minutes
    private static $rate_limit_requests = 60; // requests per window
    private static $rate_limit_window = 60; // seconds
    private static $rate_limit_storage = __DIR__ . '/_rate_limit.json';
    private static $allowed_file_mime = [
        'application/pdf','image/jpeg','image/png','image/gif'
    ];
    private static $max_upload_size = 5 * 1024 * 1024; // 5 MB

    public static function init() {
        // Start secure session
        self::start_secure_session();

        // Enforce HTTPS (if behind proxy you may need checks)
        self::enforce_https();

        // Set safe headers
        self::set_security_headers();

        // Regenerate session id periodically
        self::refresh_session();

        // Prevent clickjacking / XSS via CSP
        self::send_csp();

        // Disable PHP error display for production; log errors instead
        ini_set('display_errors', '0');
        ini_set('log_errors', '1');

        // Basic rate limiting per IP
        self::rate_limit();

        // Protect against common global variable issues
        self::harden_globals();
    }

    // ------------------------
    // Session & cookie helpers
    private static function start_secure_session() {
        if (session_status() === PHP_SESSION_NONE) {
            // Use secure cookie params
            $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
            $httponly = true;
            $samesite = 'Lax'; // or 'Strict' if appropriate
            $params = session_get_cookie_params();
            session_set_cookie_params([
                'lifetime' => self::$session_lifetime,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $secure,
                'httponly' => $httponly,
                'samesite' => $samesite
            ]);
            session_name(self::$cookie_name);
            session_start();

            // Prevent session fixation
            if (!isset($_SESSION['created'])) {
                session_regenerate_id(true);
                $_SESSION['created'] = time();
            }
        }
    }

    private static function refresh_session() {
        if (isset($_SESSION['created']) && (time() - $_SESSION['created'] > 600)) { // regen every 10 min
            session_regenerate_id(true);
            $_SESSION['created'] = time();
        }
    }

    // ------------------------
    // Headers
    private static function set_security_headers() {
        // Prevent MIME sniffing
        header('X-Content-Type-Options: nosniff');
        // Prevent clickjacking
        header('X-Frame-Options: SAMEORIGIN');
        // Basic XSS protection (legacy)
        header('X-XSS-Protection: 1; mode=block');
        // HSTS - only when HTTPS is used and site is served over HTTPS
        if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        }
        // Referrer policy
        header('Referrer-Policy: no-referrer-when-downgrade');
        // Content Security Policy header is applied in send_csp()
    }

    private static function send_csp() {
        // Minimal CSP — tailor to your site: add hashes or nonces for inline scripts if needed.
        // NOTE: if you use inline scripts/styles you must adapt or use nonces.
        $csp = "default-src 'self'; script-src 'self'; object-src 'none'; frame-ancestors 'self'; base-uri 'self';";
        header("Content-Security-Policy: $csp");
    }

    // ------------------------
    // HTTPS enforcement
    private static function enforce_https() {
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] == 443);
        // If behind load balancer/proxy, check X-Forwarded-Proto
        if (!$isSecure && !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            $isSecure = true;
        }
        if (!$isSecure) {
            // Redirect to HTTPS (301)
            $host = $_SERVER['HTTP_HOST'];
            $uri = $_SERVER['REQUEST_URI'];
            header("Location: https://{$host}{$uri}", true, 301);
            exit();
        }
    }

    // ------------------------
    // CSRF
    public static function csrf_token() {
        if (empty($_SESSION[self::$csrf_token_name])) {
            $_SESSION[self::$csrf_token_name] = bin2hex(random_bytes(32));
            $_SESSION['csrf_created'] = time();
        }
        return $_SESSION[self::$csrf_token_name];
    }

    public static function csrf_input() {
        $token = self::csrf_token();
        return '<input type="hidden" name="'.htmlspecialchars(self::$csrf_token_name, ENT_QUOTES, 'UTF-8').'" value="'.htmlspecialchars($token, ENT_QUOTES, 'UTF-8').'">';
    }

    public static function validate_csrf($tokenFromRequest) {
        if (empty($_SESSION[self::$csrf_token_name]) || empty($tokenFromRequest)) {
            return false;
        }
        // token expiry (optional)
        if (isset($_SESSION['csrf_created']) && time() - $_SESSION['csrf_created'] > 3600) {
            unset($_SESSION[self::$csrf_token_name]);
            return false;
        }
        return hash_equals($_SESSION[self::$csrf_token_name], $tokenFromRequest);
    }

    // Convenience: check POST on pages that mutate
    public static function require_csrf() {
        $token = $_POST[self::$csrf_token_name] ?? $_REQUEST[self::$csrf_token_name] ?? '';
        if (!self::validate_csrf($token)) {
            http_response_code(403);
            error_log("CSRF validation failed for IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
            die("Invalid CSRF token.");
        }
    }

    // ------------------------
    // Input sanitization & output escaping
    // Use sanitize_input for basic normalization; always escape on output with escape()
    public static function sanitize_input($data) {
        if (is_array($data)) {
            array_walk_recursive($data, function (&$v) {
                $v = trim($v);
            });
            return $data;
        }
        return trim($data);
    }

    // Use this to escape output in HTML contexts (prevents XSS)
    public static function escape($str) {
        return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    // Use when inserting into attribute values safely
    public static function escape_attr($str) {
        return self::escape($str);
    }

    // ------------------------
    // Database safe query wrapper: uses MySQLi prepared statements
    // $sql with ? placeholders; $types like 'si' and $params as array
    public static function safe_query($conn, $sql, $types = '', $params = []) {
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            error_log("DB prepare failed: ".$conn->error);
            return false;
        }
        if (!empty($types) && !empty($params)) {
            // bind params
            $stmt->bind_param($types, ...$params);
        }
        if (!$stmt->execute()) {
            error_log("DB execute failed: ".$stmt->error);
            $stmt->close();
            return false;
        }
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    // ------------------------
    // Safe redirect (prevents open redirect)
    public static function safe_redirect($url, $permanent = false) {
        // Only allow relative paths or same-host absolute URLs
        $parsed = parse_url($url);
        if (isset($parsed['host']) && $parsed['host'] !== ($_SERVER['HTTP_HOST'] ?? '')) {
            // Reject or map to home
            $url = '/';
        }
        header('Location: ' . $url, true, $permanent ? 301 : 302);
        exit();
    }

    // ------------------------
    // File upload validation helper
    // $file is from $_FILES['name']
    public static function validate_upload(array $file) {
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['ok' => false, 'msg' => 'Invalid upload'];
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['ok' => false, 'msg' => 'Upload error code: ' . $file['error']];
        }
        if ($file['size'] > self::$max_upload_size) {
            return ['ok' => false, 'msg' => 'File too large'];
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!in_array($mime, self::$allowed_file_mime, true)) {
            return ['ok' => false, 'msg' => 'Invalid file type: ' . $mime];
        }
        // generate safe filename
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $safeName = bin2hex(random_bytes(12)) . '.' . preg_replace('/[^a-zA-Z0-9]/', '', $ext);
        return ['ok' => true, 'tmp' => $file['tmp_name'], 'name' => $safeName, 'mime' => $mime];
    }

    // ------------------------
    // Basic rate limiting: per IP, file-backed. Replace with Redis in production.
    private static function rate_limit() {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $now = time();
        $file = self::$rate_limit_storage;
        $data = [];
        if (file_exists($file)) {
            $json = @file_get_contents($file);
            $data = $json ? json_decode($json, true) : [];
            if (!is_array($data)) $data = [];
        }
        // purge old
        foreach ($data as $k => $val) {
            if ($val['ts'] < $now - self::$rate_limit_window) {
                unset($data[$k]);
            }
        }
        if (!isset($data[$ip])) {
            $data[$ip] = ['count' => 1, 'ts' => $now];
        } else {
            $data[$ip]['count']++;
        }

        // persist atomically
        @file_put_contents($file, json_encode($data), LOCK_EX);

        if ($data[$ip]['count'] > self::$rate_limit_requests) {
            http_response_code(429);
            header('Retry-After: ' . self::$rate_limit_window);
            die('Too many requests. Try again later.');
        }
    }

    // ------------------------
    // Prevent register_globals-like issues and remove dangerous globals
    private static function harden_globals() {
        // Unset any potentially dangerous global variables from request
        $danger = ['GLOBALS','_GET','_POST','_COOKIE','_FILES','_REQUEST','_SESSION','_SERVER','_ENV'];
        foreach ($danger as $d) {
            if (isset($GLOBALS[$d]) && !in_array($d, ['GLOBALS'])) {
                unset($GLOBALS[$d]);
            }
        }
    }

    // ------------------------
    // Helper: safe output for JSON (prevents JSON XSS)
    public static function json_response($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        // JSON_UNESCAPED_SLASHES removed to avoid embedding </script> pitfalls
        echo json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        exit();
    }

    // ------------------------
    // Password hashing helper: use password_hash / password_verify
    public static function hash_password($plain) {
        return password_hash($plain, PASSWORD_DEFAULT);
    }
    public static function verify_password($plain, $hash) {
        return password_verify($plain, $hash);
    }

    // ------------------------
    // Logging helper stub
    public static function log_event($level, $msg) {
        error_log("[$level] $msg");
    }

    // ------------------------
    // Utility to disallow direct file inclusion of user input
    public static function safe_include($baseDir, $requestedFile) {
        // Prevent directory traversal
        $safe = realpath($baseDir . DIRECTORY_SEPARATOR . $requestedFile);
        $baseReal = realpath($baseDir);
        if ($safe === false || strpos($safe, $baseReal) !== 0) {
            throw new Exception("Invalid include path");
        }
        include $safe;
    }
}
?>
