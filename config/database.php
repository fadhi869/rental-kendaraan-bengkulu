<?php
declare(strict_types=1);

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

// Database Connection Manager
class Database
{
    private static ?PDO $pdo = null;
    private static array $config = [];

    public static function getConfig(): array
    {
        if (empty(self::$config)) {
            $dbUrl = getenv('DATABASE_URL') ?: getenv('MYSQL_URL') ?: '';
            if ($dbUrl) {
                $parsed = parse_url($dbUrl);
                self::$config = [
                    'host'     => $parsed['host'] ?? '127.0.0.1',
                    'port'     => (string)($parsed['port'] ?? '3306'),
                    'database' => ltrim($parsed['path'] ?? 'rental_kendaraan', '/'),
                    'username' => $parsed['user'] ?? 'root',
                    'password' => $parsed['pass'] ?? '',
                    'charset'  => 'utf8mb4',
                ];
            } else {
                self::$config = [
                    'host'     => getenv('DB_HOST') ?: '127.0.0.1',
                    'port'     => (string)(getenv('DB_PORT') ?: '3306'),
                    'database' => getenv('DB_DATABASE') ?: getenv('DB_NAME') ?: 'rental_kendaraan',
                    'username' => getenv('DB_USERNAME') ?: getenv('DB_USER') ?: 'root',
                    'password' => getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '',
                    'charset'  => 'utf8mb4',
                ];
            }
        }
        return self::$config;
    }

    public static function connection(): PDO
    {
        if (self::$pdo === null) {
            $cfg = self::getConfig();
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $cfg['host'],
                $cfg['port'],
                $cfg['database'],
                $cfg['charset']
            );
            self::$pdo = new PDO($dsn, $cfg['username'], $cfg['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return self::$pdo;
    }
}

// Load core utilities and base classes
require_once BASE_PATH . '/config/helpers.php';
require_once BASE_PATH . '/config/Session.php';
require_once BASE_PATH . '/config/Csrf.php';
require_once BASE_PATH . '/config/Flash.php';
require_once BASE_PATH . '/config/Auth.php';
require_once BASE_PATH . '/config/Request.php';
require_once BASE_PATH . '/config/Response.php';
require_once BASE_PATH . '/config/Validator.php';
require_once BASE_PATH . '/config/Router.php';
require_once BASE_PATH . '/model/Model.php';
require_once BASE_PATH . '/controller/Controller.php';
require_once BASE_PATH . '/view/View.php';

// Universal Class Autoloader
spl_autoload_register(function (string $class): void {
    $cleanClass = ltrim($class, '\\');
    $parts = explode('\\', $cleanClass);
    $className = end($parts);

    $lookupDirs = [
        BASE_PATH . '/model/',
        BASE_PATH . '/controller/',
        BASE_PATH . '/config/',
        BASE_PATH . '/view/',
    ];

    foreach ($lookupDirs as $dir) {
        $file = $dir . $className . '.php';
        if (file_exists($file)) {
            require_once $file;

            $nsModels = 'App\\Models\\' . $className;
            $nsControllers = 'App\\Controllers\\' . $className;
            $nsServices = 'App\\Services\\' . $className;

            if (class_exists($nsModels, false) && !class_exists($className, false)) {
                class_alias($nsModels, $className);
            } elseif (class_exists($nsControllers, false) && !class_exists($className, false)) {
                class_alias($nsControllers, $className);
            } elseif (class_exists($nsServices, false) && !class_exists($className, false)) {
                class_alias($nsServices, $className);
            }

            if (class_exists($className, false)) {
                if (!class_exists($nsModels, false)) {
                    class_alias($className, $nsModels);
                }
                if (!class_exists($nsControllers, false)) {
                    class_alias($className, $nsControllers);
                }
                if (!class_exists($nsServices, false)) {
                    class_alias($className, $nsServices);
                }
            }
            return;
        }
    }
});

Session::start();

// Expose standard $conn PDO connection object
$conn = Database::connection();

return Database::getConfig();
