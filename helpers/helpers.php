<?php

function view(string $template, array $data = []): void
{
    extract($data);
    $path = ROOT_PATH . '/views/' . $template . '.php';

    if (!file_exists($path)) {
        throw new RuntimeException("View not found: {$template}");
    }

    include $path;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $submitted = (string)($_POST['csrf_token'] ?? '');
    $stored = (string)($_SESSION['csrf_token'] ?? '');

    if ($submitted === '' || $stored === '' || !hash_equals($stored, $submitted)) {
        http_response_code(419);
        exit('Permintaan tidak valid. Silakan muat ulang halaman dan coba lagi.');
    }
}

function send_security_headers(): void
{
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . BASE_URL . $path);
    exit;
}

function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

function requireAuth(string|array|null $role = null): void
{
    if (!isset($_SESSION['user'])) {
        redirect('/login');
    }

    $currentRole = (string)($_SESSION['user']['role'] ?? '');
    $allowedRoles = is_array($role) ? $role : ($role === null ? [] : [$role]);

    if ($role !== null && !in_array($currentRole, $allowedRoles, true)) {
        http_response_code(403);
        exit('Akses ditolak.');
    }
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user']);
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return $message;
    }

    if (isset($_SESSION['flash'][$key])) {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }

    return null;
}

function formatDate(?string $date): string
{
    if (empty($date)) {
        return '-';
    }

    return date('d M Y', strtotime($date));
}
