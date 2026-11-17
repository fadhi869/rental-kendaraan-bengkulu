<?php
declare(strict_types=1);

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

/**
 * Konfigurasi Database Terpusat (Singleton PDO)
 */
class Database
{
    private static ?PDO $pdo = null;

    public static function getConfig(): array
    {
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
    }

    public static function connection(): PDO
    {
        if (self::$pdo === null) {
            $cfg = self::getConfig();
            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $cfg['host'], $cfg['port'], $cfg['database'], $cfg['charset']);
            self::$pdo = new PDO($dsn, $cfg['username'], $cfg['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return self::$pdo;
    }
}

/**
 * Manajemen Session
 */
class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }
}

/**
 * Autentikasi Pengguna
 */
class Auth
{
    public static function user(): ?array
    {
        return Session::get('auth_user');
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function id(): ?int
    {
        $u = self::user();
        return $u ? (int)$u['id'] : null;
    }

    public static function isAdmin(): bool
    {
        $u = self::user();
        return $u && ($u['role'] ?? '') === 'admin';
    }

    public static function login(array $user): void
    {
        if (!headers_sent() && session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
        unset($user['password']);
        Session::set('auth_user', $user);
    }

    public static function logout(): void
    {
        Session::destroy();
    }
}

/**
 * Keamanan CSRF
 */
class Csrf
{
    public static function token(): string
    {
        $token = Session::get('_csrf_token');
        if (!$token) {
            $token = bin2hex(random_bytes(32));
            Session::set('_csrf_token', $token);
        }
        return $token;
    }

    public static function verify(?string $token): bool
    {
        $sessionToken = Session::get('_csrf_token');
        return $sessionToken && $token && hash_equals($sessionToken, $token);
    }
}

/**
 * Pesan Notifikasi Flash
 */
class Flash
{
    public static function set(string $type, string $message): void
    {
        Session::set('_flash', ['type' => $type, 'message' => $message]);
    }

    public static function get(): ?array
    {
        $f = Session::get('_flash');
        Session::forget('_flash');
        return $f;
    }
}

/**
 * Validasi Input
 */
class Validator
{
    public static function validate(array $data, array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $ruleStr) {
            $val = trim((string)($data[$field] ?? ''));
            foreach (explode('|', $ruleStr) as $rule) {
                if ($rule === 'required' && $val === '') {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' wajib diisi.';
                    break;
                }
                if (str_starts_with($rule, 'numeric') && $val !== '' && !is_numeric($val)) {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' harus berupa angka.';
                    break;
                }
            }
        }
        return $errors;
    }
}

/**
 * HTTP Request Helper
 */
class Request
{
    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($_GET, $_POST);
    }
}

/**
 * Helper Global
 */
function base_url(string $path = ''): string
{
    static $base = null;
    if ($base === null) {
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        $dir = rtrim(str_replace('\\', '/', dirname($script)), '/');
        $base = ($dir === '/' || $dir === '.') ? '' : $dir;
    }
    $clean = ltrim($path, '/');
    return $base . ($clean !== '' ? '/' . $clean : '');
}

function e(?string $v): string
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function rupiah(float|int|string $amt): string
{
    return 'Rp ' . number_format((float)$amt, 0, ',', '.');
}

function tgl_indo(?string $date): string
{
    if (!$date) return '-';
    $bulan = [1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    $ts = strtotime($date);
    return $ts ? date('d', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts) : $date;
}

function csrf_token(): string { return Csrf::token(); }
function csrf_field(): string { return '<input type="hidden" name="_csrf_token" value="' . e(Csrf::token()) . '">'; }
function auth_user(): ?array { return Auth::user(); }
function redirect(string $path): void { header('Location: ' . base_url($path)); exit; }

/**
 * Base Model
 */
abstract class Model
{
    protected string $table = '';
    protected function db(): PDO { return Database::connection(); }
    public function find(int $id): ?array {
        $stmt = $this->db()->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }
    public function all(string $orderBy = 'id DESC'): array {
        return $this->db()->query("SELECT * FROM {$this->table} ORDER BY {$orderBy}")->fetchAll();
    }
    public function delete(int $id): bool {
        $stmt = $this->db()->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}

/**
 * Base Controller
 */
abstract class Controller
{
    protected function requireAuth(): void
    {
        if (!Auth::check()) {
            Flash::set('error', 'Silakan login terlebih dahulu.');
            redirect('login.php');
        }
    }

    protected function requireAdmin(): void
    {
        $this->requireAuth();
        if (!Auth::isAdmin()) {
            Flash::set('error', 'Akses ditolak. Halaman ini khusus Administrator.');
            redirect('index.php');
        }
    }

    protected function verifyCsrf(Request $request): void
    {
        if (!Csrf::verify($request->input('_csrf_token'))) {
            Flash::set('error', 'Sesi kadaluarsa. Silakan ulangi.');
            redirect($_SERVER['HTTP_REFERER'] ?? 'index.php');
        }
    }
}

/**
 * Renderer Antarmuka Master (Layout + Navigasi)
 */
function render_layout(string $title, string $activePage, string $content): void
{
    $user = auth_user();
    $isAdmin = ($user['role'] ?? '') === 'admin';
    $flash = Flash::get();
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> — Rental Kendaraan</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>
    <div id="sidebarBackdrop" class="sidebar-backdrop"></div>
    <div class="app-shell">
        <aside id="appSidebar" class="app-sidebar">
            <div class="sidebar-scroll">
                <a href="<?= base_url('index.php') ?>" class="brand-header">
                    <div class="brand-badge"><i data-lucide="car" style="width:18px;height:18px;"></i></div>
                    <div><div class="brand-title">Manajemen Rental Kendaraan</div></div>
                </a>

                <div class="nav-group">
                    <div class="nav-heading">Menu Utama</div>
                    <a href="<?= base_url('index.php') ?>" class="nav-item <?= $activePage === 'dashboard' ? 'active' : '' ?>">
                        <span>Dashboard</span>
                    </a>
                    <a href="<?= base_url('penyewaan.php') ?>" class="nav-item <?= $activePage === 'penyewaan' ? 'active' : '' ?>">
                        <span>Penyewaan</span>
                    </a>
                    <a href="<?= base_url('pengembalian.php') ?>" class="nav-item <?= $activePage === 'pengembalian' ? 'active' : '' ?>">
                        <span>Pengembalian</span>
                    </a>
                </div>

                <div class="nav-group">
                    <div class="nav-heading">Data Master</div>
                    <a href="<?= base_url('kendaraan.php') ?>" class="nav-item <?= $activePage === 'kendaraan' ? 'active' : '' ?>">
                        <span>Kendaraan</span>
                    </a>
                    <a href="<?= base_url('jenis_kendaraan.php') ?>" class="nav-item <?= $activePage === 'jenis_kendaraan' ? 'active' : '' ?>">
                        <span>Jenis Kendaraan</span>
                    </a>
                    <a href="<?= base_url('pelanggan.php') ?>" class="nav-item <?= $activePage === 'pelanggan' ? 'active' : '' ?>">
                        <span>Pelanggan</span>
                    </a>
                </div>

                <?php if ($isAdmin): ?>
                <div class="nav-group">
                    <div class="nav-heading">Administrasi</div>
                    <a href="<?= base_url('pengguna.php') ?>" class="nav-item <?= $activePage === 'pengguna' ? 'active' : '' ?>">
                        <span>Pengguna</span>
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <div class="sidebar-footer">
                <div class="user-card">
                    <div style="display:flex;align-items:center;gap:9px;min-width:0;flex:1;">
                        <div class="user-avatar-sm"><?= strtoupper(substr($user['nama'] ?? 'U', 0, 1)) ?></div>
                        <div class="user-info-text">
                            <div class="user-name"><?= e($user['nama'] ?? 'Pengguna') ?></div>
                            <div class="user-role-badge"><?= e($user['role'] ?? 'petugas') ?></div>
                        </div>
                    </div>
                    <a href="<?= base_url('logout.php') ?>" class="btn-logout-icon" title="Keluar">
                        <i data-lucide="log-out" style="width:15px;height:15px;"></i>
                    </a>
                </div>
            </div>
        </aside>

        <div class="app-main">
            <header class="app-topbar">
                <div class="topbar-left">
                    <button type="button" id="sidebarToggle" class="btn-topbar-icon" title="Menu">
                        <i data-lucide="menu" style="width:17px;height:17px;"></i>
                    </button>
                    <div class="topbar-crumb"><span>Sistem Informasi Rental Kendaraan</span></div>
                </div>
                <div class="topbar-right">
                    <a href="<?= base_url('penyewaan.php?action=create') ?>" class="btn btn-primary btn-sm">
                        <i data-lucide="plus" style="width:14px;height:14px;"></i>
                        <span>Tambah Penyewaan</span>
                    </a>
                    <div class="topbar-user-pill">
                        <span class="topbar-user-avatar"><?= strtoupper(substr($user['nama'] ?? 'U', 0, 1)) ?></span>
                        <span class="topbar-user-name"><?= e($user['nama'] ?? 'Pengguna') ?></span>
                    </div>
                </div>
            </header>

            <main class="app-content">
                <?php if ($flash): ?>
                <div class="flash-alert flash-<?= e($flash['type']) ?>" style="display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:var(--radius-md);margin-bottom:20px;background:<?= $flash['type']==='success'?'rgba(22,163,74,0.1)':'rgba(220,38,38,0.1)' ?>;border:1px solid <?= $flash['type']==='success'?'rgba(22,163,74,0.3)':'rgba(220,38,38,0.3)' ?>;color:var(--text-primary);">
                    <i data-lucide="<?= $flash['type']==='success'?'check-circle':'alert-circle' ?>" style="width:18px;height:18px;color:<?= $flash['type']==='success'?'var(--success)':'var(--danger)' ?>;"></i>
                    <span><?= e($flash['message']) ?></span>
                </div>
                <?php endif; ?>

                <?= $content ?>
            </main>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('appSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        function openSidebar() { if (sidebar) sidebar.classList.add('open'); if (backdrop) backdrop.classList.add('active'); }
        function closeSidebar() { if (sidebar) sidebar.classList.remove('open'); if (backdrop) backdrop.classList.remove('active'); }
        if (toggle) {
            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                if (sidebar && sidebar.classList.contains('open')) { closeSidebar(); } else { openSidebar(); }
            });
        }
        if (backdrop) backdrop.addEventListener('click', closeSidebar);
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeSidebar(); });
        if (window.lucide && typeof window.lucide.createIcons === 'function') { window.lucide.createIcons(); }
    });
    </script>
    <script src="<?= base_url('assets/js/motion.js') ?>"></script>
</body>
</html>
    <?php
}

// Universal Class Autoloader
spl_autoload_register(function (string $class): void {
    $clean = ltrim($class, '\\');
    $parts = explode('\\', $clean);
    $name = end($parts);

    foreach ([BASE_PATH . '/model/', BASE_PATH . '/controller/'] as $dir) {
        $file = $dir . $name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

Session::start();
$conn = Database::connection();
return Database::getConfig();
