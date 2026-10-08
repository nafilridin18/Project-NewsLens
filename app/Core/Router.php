<?php
namespace App\Core;

class Router {
    private array $routes = [];

    public function get(string $path, $handler): void {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, $handler): void {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, $handler): void {
        // Convert route pattern like /news/{category}/{id}/{slug} to regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#u';
        $this->routes[] = [
            'method'  => $method,
            'path'    => $path,
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }

    public function dispatch(string $uri, string $method): void {
        $parsedUri = parse_url($uri, PHP_URL_PATH);
        
        // Strip project base path if running in subfolder like /NewsLens/public
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $scriptDir = rtrim($scriptDir, '/');
        if ($scriptDir !== '' && stripos($parsedUri, $scriptDir) === 0) {
            $parsedUri = substr($parsedUri, strlen($scriptDir));
        }
        $parsedUri = '/' . trim($parsedUri, '/');
        if ($parsedUri === '//') $parsedUri = '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;

            if (preg_match($route['pattern'], $parsedUri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->invokeHandler($route['handler'], $params);
                return;
            }
        }

        // 404 Not Found
        http_response_code(404);
        View::render('errors.404', ['pageTitle' => '৪০৪ - পাতাটি পাওয়া যায়নি | Newslensbd']);
    }

    private function invokeHandler($handler, array $params = []): void {
        if (is_callable($handler)) {
            call_user_func_array($handler, [$params]);
            return;
        }

        if (is_string($handler) && strpos($handler, '@') !== false) {
            list($controller, $action) = explode('@', $handler);
            $controllerClass = "App\\Controllers\\{$controller}";
            if (class_exists($controllerClass)) {
                $instance = new $controllerClass();
                if (method_exists($instance, $action)) {
                    call_user_func_array([$instance, $action], [$params]);
                    return;
                }
            }
        }

        http_response_code(500);
        echo "Error: Handler could not be executed.";
    }
}
