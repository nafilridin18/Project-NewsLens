<?php
/**
 * Newslensbd (নিউজলেন্সবিডি) - Public Front Controller & Router Entry
 */

declare(strict_types=1);

// Register autoloader for App namespace
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = dirname(__DIR__) . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Load App Configuration
$config = require dirname(__DIR__) . '/config/config.php';
$adminPath = trim($config['app']['admin_path'] ?? 'lens-desk', '/');

// Initialize Core Router
$router = new App\Core\Router();

// ================= PUBLIC WEB ROUTES =================
$router->get('/', 'HomeController@index');
$router->get('/saradesh', 'SaradeshController@index');
$router->get('/search', 'NewsController@search');
$router->get('/category/{slug}', 'NewsController@category');

// Single Article Routes (with category and slug support)
$router->get('/news/{category}/{id}/{slug}', 'NewsController@show');
$router->get('/news/{category}/{id}', 'NewsController@show');
$router->get('/news/{id}', 'NewsController@show');

// Public APIs & AJAX Handlers
$router->get('/api/districts', 'SaradeshController@getDistricts');

$router->post('/api/poll/vote', function() {
    header('Content-Type: application/json; charset=utf-8');
    $pollId = (int) ($_POST['poll_id'] ?? 0);
    $optionId = (int) ($_POST['option_id'] ?? 0);
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

    if ($pollId <= 0 || $optionId <= 0) {
        echo json_encode(['success' => false, 'message' => 'অনুরোধটি সঠিক নয়।']);
        return;
    }

    $res = App\Models\Poll::vote($pollId, $optionId, $ip);
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
});

$router->post('/api/newsletter/subscribe', function() {
    header('Content-Type: application/json; charset=utf-8');
    $email = trim($_POST['email'] ?? '');
    $res = App\Models\Newsletter::subscribe($email);
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
});

$router->post('/subscribe', function() {
    header('Content-Type: application/json; charset=utf-8');
    $email = trim($_POST['email'] ?? '');
    $res = App\Models\Newsletter::subscribe($email);
    echo json_encode($res, JSON_UNESCAPED_UNICODE);
});

// Health Check
$router->get('/api/health', function() {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'ok', 'app' => 'Newslensbd', 'timestamp' => date('c')]);
});

// ================= ADMIN CMS ROUTES (/lens-desk) =================
$router->get('/' . $adminPath, 'Admin\DashboardController@index');
$router->get('/' . $adminPath . '/login', 'Admin\AuthController@login');
$router->post('/' . $adminPath . '/login', 'Admin\AuthController@login');
$router->get('/' . $adminPath . '/register', 'Admin\AuthController@register');
$router->post('/' . $adminPath . '/register', 'Admin\AuthController@register');
$router->post('/' . $adminPath . '/profile/update', 'Admin\AuthController@updateProfile');
$router->get('/' . $adminPath . '/logout', 'Admin\AuthController@logout');

// Post Management (List, Create, Store, Edit, Update, Delete, Status)
$router->get('/' . $adminPath . '/posts', 'Admin\PostController@index');
$router->get('/' . $adminPath . '/posts/create', 'Admin\PostController@create');
$router->post('/' . $adminPath . '/posts/store', 'Admin\PostController@store');
$router->get('/' . $adminPath . '/posts/edit', 'Admin\PostController@edit');
$router->post('/' . $adminPath . '/posts/update', 'Admin\PostController@update');
$router->post('/' . $adminPath . '/posts/delete', 'Admin\PostController@delete');
$router->post('/' . $adminPath . '/posts/status', 'Admin\PostController@updateStatus');

// Poll Records & Management (Picture 4)
$router->get('/' . $adminPath . '/polls', 'Admin\PollController@index');
$router->post('/' . $adminPath . '/polls', 'Admin\PollController@store');
$router->post('/' . $adminPath . '/polls/active', 'Admin\PollController@setActive');
$router->post('/' . $adminPath . '/polls/reset', 'Admin\PollController@resetVotes');
$router->post('/' . $adminPath . '/polls/delete', 'Admin\PollController@delete');

// Newsletter Subscribers (Picture 4)
$router->get('/' . $adminPath . '/subscribers', 'Admin\SubscriberController@index');
$router->post('/' . $adminPath . '/subscribers/delete', 'Admin\SubscriberController@delete');

// Site Branding & Logo Management (Picture 3)
$router->get('/' . $adminPath . '/settings', 'Admin\SettingsController@index');
$router->post('/' . $adminPath . '/settings', 'Admin\SettingsController@update');

// Advertisement Click Tracker & Redirection (Optimized Analytics)
$router->get('/ad/click/{id}', function($params) {
    $id = (int) ($params['id'] ?? 0);
    $url = \App\Models\Ad::recordClick($id);
    if (!empty($url)) {
        header("Location: " . $url, true, 302);
        exit;
    }
    header("Location: /", true, 302);
    exit;
});

// Advertisement Management (Admin Control: Test Video / Picture / Hyperlinks)
$router->get('/' . $adminPath . '/ads', 'Admin\AdController@index');
$router->post('/' . $adminPath . '/ads/store', 'Admin\AdController@store');
$router->post('/' . $adminPath . '/ads/update', 'Admin\AdController@update');
$router->post('/' . $adminPath . '/ads/toggle', 'Admin\AdController@toggle');
$router->post('/' . $adminPath . '/ads/delete', 'Admin\AdController@delete');

// Dispatch incoming request
$router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD'] ?? 'GET');
