<?php
namespace App\Core;

class View {
    /**
     * Render a view file inside an optional layout
     */
    public static function render(string $view, array $data = [], ?string $layout = 'layouts/main'): void {
        $config = require CONFIG_PATH . '/config.php';

        if (!isset($data['config'])) {
            $data['config'] = $config;
        }

        if (!isset($data['appUrl'])) {
            $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
            $scriptDir = rtrim($scriptDir, '/');
            $data['appUrl'] = $scriptDir ?: '';
        }

        if (!isset($data['adminPath'])) {
            $data['adminPath'] = $config['app']['admin_path'] ?? 'lens-desk';
        }

        extract($data);

        $viewPath = APP_PATH . '/Views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(500);
            echo "View file not found: {$view}";
            return;
        }

        if ($layout) {
            $layoutPath = APP_PATH . '/Views/' . str_replace('.', '/', $layout) . '.php';
            if (file_exists($layoutPath)) {
                // Buffer the view content
                ob_start();
                require $viewPath;
                $content = ob_get_clean();

                // Render within layout
                require $layoutPath;
                return;
            }
        }

        require $viewPath;
    }
}
