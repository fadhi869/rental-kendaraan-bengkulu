<?php
class View
{
    public static function render(string $view, array $data = [], string $layout = 'layouts/app'): void
    {
        extract($data, EXTR_SKIP);
        $baseDir = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__);
        $viewFile = $baseDir . '/view/' . $view . '.php';

        if (!file_exists($viewFile)) {
            // Check if nested index view exists (e.g., kendaraan -> view/kendaraan/index.php)
            if (file_exists($baseDir . '/view/' . $view . '/index.php')) {
                $viewFile = $baseDir . '/view/' . $view . '/index.php';
            }
        }

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "View tidak ditemukan: " . htmlspecialchars($view, ENT_QUOTES, 'UTF-8');
            exit;
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        $layoutFile = $baseDir . '/view/' . $layout . '.php';
        if ($layout && file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }
}
