<?php
/**
 * Newslensbd (নিউজলেন্সবিডি) Configuration Loader
 * Loads environment settings and establishes base application constants
 */

if (!defined('ROOT_PATH')) define('ROOT_PATH', dirname(__DIR__));
if (!defined('APP_PATH')) define('APP_PATH', ROOT_PATH . '/app');
if (!defined('CONFIG_PATH')) define('CONFIG_PATH', ROOT_PATH . '/config');
if (!defined('STORAGE_PATH')) define('STORAGE_PATH', ROOT_PATH . '/storage');
if (!defined('PUBLIC_PATH')) define('PUBLIC_PATH', ROOT_PATH . '/public');

// Simple .env parser
if (!function_exists('loadEnv')) {
    function loadEnv($path) {
        if (!file_exists($path)) return;
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) continue;
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                $value = trim($value, '"\'');
                if (!array_key_exists($key, $_ENV)) {
                    $_ENV[$key] = $value;
                    putenv("$key=$value");
                }
            }
        }
    }
}

// Load .env or fallback to .env.example
if (file_exists(ROOT_PATH . '/.env')) {
    loadEnv(ROOT_PATH . '/.env');
} else {
    loadEnv(ROOT_PATH . '/.env.example');
}

if (!function_exists('env')) {
    function env($key, $default = null) {
        $val = getenv($key);
        return $val !== false ? $val : ($_ENV[$key] ?? $default);
    }
}

return [
    'app' => [
        'name'       => env('APP_NAME', 'Newslensbd'),
        'name_bn'    => 'নিউজলেন্সবিডি',
        'tagline_bn' => 'সাধারণের বাইরে, সত্যের খোঁজে',
        'tagline_en' => 'News Beyond the Ordinary',
        'url'        => env('APP_URL', 'http://localhost/NewsLens/public'),
        'env'        => env('APP_ENV', 'development'),
        'debug'      => filter_var(env('APP_DEBUG', true), FILTER_VALIDATE_BOOLEAN),
        'admin_path' => env('ADMIN_PATH', 'lens-desk'), // Non-default admin URL
        'timezone'   => 'Asia/Dhaka',
    ],
    'db' => [
        'host'     => env('DB_HOST', '127.0.0.1'),
        'port'     => env('DB_PORT', '3306'),
        'dbname'   => env('DB_NAME', 'newslensbd'),
        'username' => env('DB_USER', 'root'),
        'password' => env('DB_PASS', ''),
        'charset'  => 'utf8mb4',
        'options'  => [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ],
    ],
    'colors' => [
        'primary'   => '#0b1f44', // Brand Navy
        'secondary' => '#0E7A3A', // Green category badges
        'accent'    => '#E31E24', // Red breaking news / Live badge
    ],
    'partner' => [
        'name' => 'Stratifyx Global',
        'url'  => 'https://stratifyxglobal.com',
    ],
    'upload' => [
        'max_size' => (int) env('UPLOAD_MAX_SIZE', 5242880), // 5MB
        'allowed_types' => ['image/jpeg', 'image/png', 'image/webp'],
        'path' => PUBLIC_PATH . '/uploads',
    ]
];
