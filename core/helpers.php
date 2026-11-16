<?php
function base_url(string $path = ''): string
{
    static $detectedBase = null;
    if ($detectedBase === null) {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $fullBase = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        if ($fullBase === '/' || $fullBase === '.') {
            $fullBase = '';
        }
        $requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        if ($fullBase !== '' && !str_starts_with($requestUri, $fullBase)) {
            $parentBase = rtrim(str_replace('\\', '/', dirname($fullBase)), '/');
            if ($parentBase === '/' || $parentBase === '.') {
                $parentBase = '';
            }
            if ($parentBase !== '' && str_starts_with($requestUri, $parentBase)) {
                $detectedBase = $parentBase;
            } else {
                $detectedBase = $fullBase;
            }
        } else {
            $detectedBase = $fullBase;
        }
    }

    $cleanPath = ltrim($path, '/');
    return $detectedBase . ($cleanPath !== '' ? '/' . $cleanPath : '');
}

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function rupiah(float|int|string $amount): string
{
    return 'Rp ' . number_format((float)$amount, 0, ',', '.');
}

function tgl_indo(?string $date): string
{
    if (!$date) return '-';
    $bulan = [
        1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
    ];
    $ts = strtotime($date);
    if (!$ts) return $date;
    return date('d', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

function csrf_token(): string
{
    return Csrf::token();
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf_token" value="' . e(Csrf::token()) . '">';
}

function auth_user(): ?array
{
    return Auth::user();
}

function redirect(string $path): void
{
    header('Location: ' . base_url($path));
    exit;
}
