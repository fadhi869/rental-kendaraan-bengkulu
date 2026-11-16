<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

require_once BASE_PATH . '/core/helpers.php';
require_once BASE_PATH . '/core/Session.php';
require_once BASE_PATH . '/core/Csrf.php';
require_once BASE_PATH . '/core/Flash.php';
require_once BASE_PATH . '/core/Database.php';
require_once BASE_PATH . '/core/Auth.php';
require_once BASE_PATH . '/core/Request.php';
require_once BASE_PATH . '/core/Response.php';
require_once BASE_PATH . '/core/Validator.php';
require_once BASE_PATH . '/core/Model.php';
require_once BASE_PATH . '/core/View.php';
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/Router.php';

spl_autoload_register(function (string $class): void {
    $prefixes = [
        'App\\Controllers\\' => BASE_PATH . '/app/Controllers/',
        'App\\Models\\'      => BASE_PATH . '/app/Models/',
        'App\\Services\\'    => BASE_PATH . '/app/Services/',
    ];
    foreach ($prefixes as $prefix => $dir) {
        if (str_starts_with($class, $prefix)) {
            $rel = str_replace('\\', '/', substr($class, strlen($prefix)));
            $file = $dir . $rel . '.php';
            if (file_exists($file)) {
                require_once $file;
            }
        }
    }
});

Session::start();

$router = new Router();
require_once BASE_PATH . '/config/routes.php';

$request = new Request();
$router->dispatch($request);
