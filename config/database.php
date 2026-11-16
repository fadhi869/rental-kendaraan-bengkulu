<?php
// Support cloud PaaS (Railway, Render, Fly.io, Heroku) via DATABASE_URL or individual env vars
$dbUrl = getenv('DATABASE_URL') ?: getenv('MYSQL_URL') ?: '';

if ($dbUrl) {
    $parsed = parse_url($dbUrl);
    return [
        'host'     => $parsed['host'] ?? '127.0.0.1',
        'port'     => (string)($parsed['port'] ?? '3306'),
        'database' => ltrim($parsed['path'] ?? 'rental_kendaraan', '/'),
        'username' => $parsed['user'] ?? 'root',
        'password' => $parsed['pass'] ?? '',
        'charset'  => 'utf8mb4',
    ];
}

return [
    'host'     => getenv('DB_HOST') ?: '127.0.0.1',
    'port'     => (string)(getenv('DB_PORT') ?: '3306'),
    'database' => getenv('DB_DATABASE') ?: getenv('DB_NAME') ?: 'rental_kendaraan',
    'username' => getenv('DB_USERNAME') ?: getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '',
    'charset'  => 'utf8mb4',
];
