<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $page, array $params = []): string
{
    $query = array_merge(['page' => $page], $params);

    return BASE_URL . '?' . http_build_query($query);
}

function asset(string $path): string
{
    return ltrim($path, '/');
}

function redirect(string $page, array $params = []): void
{
    header('Location: ' . url($page, $params));
    exit;
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function request_int(string $key): ?int
{
    $value = filter_input(INPUT_GET, $key, FILTER_VALIDATE_INT);

    return $value === false ? null : $value;
}

function old(string $key, array $source = [], string $default = ''): string
{
    return (string) ($source[$key] ?? $default);
}

function format_date(?string $date): string
{
    if (!$date) {
        return '';
    }

    return date('d.m.Y H:i', strtotime($date));
}

function short_text(string $text, int $length = 140): string
{
    $text = trim(strip_tags($text));

    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return mb_substr($text, 0, $length - 3) . '...';
    }

    if (strlen($text) <= $length) {
        return $text;
    }

    return substr($text, 0, $length - 3) . '...';
}

function csrf_token(): string
{
    $token = Session::get('csrf_token');

    if (!$token) {
        $token = bin2hex(random_bytes(32));
        Session::set('csrf_token', $token);
    }

    return $token;
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function validate_csrf(): bool
{
    $postedToken = $_POST['csrf_token'] ?? '';
    $sessionToken = Session::get('csrf_token', '');

    return is_string($postedToken)
        && is_string($sessionToken)
        && hash_equals($sessionToken, $postedToken);
}

function event_image(?string $image): string
{
    $image = trim((string) $image);

    if ($image === '') {
        return DEFAULT_EVENT_IMAGE;
    }

    if (preg_match('/^https?:\/\//', $image) === 1) {
        return $image;
    }

    if (str_starts_with($image, 'public/')) {
        return asset($image);
    }

    return asset(UPLOAD_URL . $image);
}
