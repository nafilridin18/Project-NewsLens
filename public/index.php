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

// Load Composer autoloader if present
if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
}

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

// Public Author Profile (/author/{id})
$router->get('/author/{id}', 'AuthorController@show');

// Single Article Routes (with category and slug support)
$router->get('/news/{category}/{id}/{slug}', 'NewsController@show');
$router->get('/news/{category}/{id}', 'NewsController@show');
$router->get('/news/{id}', 'NewsController@show');

// Public Reader Authentication & Bookmarks
$router->get('/register', 'UserAuthController@showRegister');
$router->post('/register', 'UserAuthController@register');
$router->get('/user/register', 'UserAuthController@showRegister');
$router->post('/user/register', 'UserAuthController@register');
$router->get('/login', 'UserAuthController@showLogin');
$router->post('/login', 'UserAuthController@login');
$router->get('/user/login', 'UserAuthController@showLogin');
$router->post('/user/login', 'UserAuthController@login');
$router->get('/logout', 'UserAuthController@logout');
$router->get('/user/logout', 'UserAuthController@logout');
$router->get('/writer/logout', 'UserAuthController@logout');
$router->get('/user/bookmarks', 'UserAuthController@bookmarks');
$router->post('/api/bookmark/toggle', 'UserAuthController@toggleBookmark');

// OTP Verification & Resend Flow
$router->get('/otp/verify', 'OtpController@showVerify');
$router->post('/otp/verify', 'OtpController@verify');
$router->post('/otp/resend', 'OtpController@resend');

// Password Reset Flow
$router->get('/forgot-password', 'PasswordResetController@showForgot');
$router->post('/forgot-password', 'PasswordResetController@sendResetOtp');
$router->get('/reset-password', 'PasswordResetController@showReset');
$router->post('/reset-password', 'PasswordResetController@resetPassword');

// User Profile & Analytics (Self-service for readers, writers, editors, admins)
$router->get('/profile', 'ProfileController@show');
$router->get('/profile/edit', 'ProfileController@edit');
$router->post('/profile/update', 'ProfileController@update');
$router->post('/profile/change-password', 'ProfileController@changePassword');

// Writer Onboarding & Registration
$router->get('/writer/register', 'WriterAuthController@showRegister');
$router->post('/writer/register', 'WriterAuthController@register');
$router->get('/writer/pending', 'WriterAuthController@showPending');

// Writer Panel (Restricted to Writers, Strictly Scoped Ownership)
$router->get('/writer/dashboard', 'Writer\WriterPanelController@index');
$router->get('/writer/posts', 'Writer\WriterPanelController@posts');
$router->get('/writer/posts/create', 'Writer\WriterPanelController@create');
$router->post('/writer/posts/store', 'Writer\WriterPanelController@store');
$router->get('/writer/posts/edit', 'Writer\WriterPanelController@edit');
$router->post('/writer/posts/update', 'Writer\WriterPanelController@update');
$router->post('/writer/posts/delete', 'Writer\WriterPanelController@delete');

// In-App Notifications & Web Push APIs
$router->get('/api/notifications', 'NotificationController@getNotifications');
$router->post('/api/notifications/mark-read', 'NotificationController@markRead');
$router->post('/api/push/subscribe', 'NotificationController@subscribe');
$router->get('/api/push/public-key', 'NotificationController@getPublicKey');

// Public Poll & Newsletter APIs
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

// Admin Writer Control & Approvals
$router->get('/' . $adminPath . '/writers', 'Admin\WriterControlController@index');
$router->get('/' . $adminPath . '/writers/show', 'Admin\WriterControlController@show');
$router->post('/' . $adminPath . '/writers/approve', 'Admin\WriterControlController@approve');
$router->post('/' . $adminPath . '/writers/reject', 'Admin\WriterControlController@reject');
$router->post('/' . $adminPath . '/writers/permission', 'Admin\WriterControlController@updatePermission');
$router->post('/' . $adminPath . '/writers/update-permission', 'Admin\WriterControlController@updatePermission');
$router->post('/' . $adminPath . '/writers/status', 'Admin\WriterControlController@toggleStatus');
$router->post('/' . $adminPath . '/writers/toggle-status', 'Admin\WriterControlController@toggleStatus');
$router->post('/' . $adminPath . '/writers/remove', 'Admin\WriterControlController@removeWriter');

// Post Management (List, Create, Store, Edit, Update, Delete, Status)
$router->get('/' . $adminPath . '/posts', 'Admin\PostController@index');
$router->get('/' . $adminPath . '/posts/create', 'Admin\PostController@create');
$router->post('/' . $adminPath . '/posts/store', 'Admin\PostController@store');
$router->get('/' . $adminPath . '/posts/edit', 'Admin\PostController@edit');
$router->post('/' . $adminPath . '/posts/update', 'Admin\PostController@update');
$router->post('/' . $adminPath . '/posts/delete', 'Admin\PostController@delete');
$router->post('/' . $adminPath . '/posts/status', 'Admin\PostController@updateStatus');

// Poll Records & Management
$router->get('/' . $adminPath . '/polls', 'Admin\PollController@index');
$router->post('/' . $adminPath . '/polls', 'Admin\PollController@store');
$router->post('/' . $adminPath . '/polls/active', 'Admin\PollController@setActive');
$router->post('/' . $adminPath . '/polls/reset', 'Admin\PollController@resetVotes');
$router->post('/' . $adminPath . '/polls/delete', 'Admin\PollController@delete');

// Newsletter Subscribers
$router->get('/' . $adminPath . '/subscribers', 'Admin\SubscriberController@index');
$router->post('/' . $adminPath . '/subscribers/delete', 'Admin\SubscriberController@delete');

// Site Branding & Logo Management
$router->get('/' . $adminPath . '/settings', 'Admin\SettingsController@index');
$router->post('/' . $adminPath . '/settings', 'Admin\SettingsController@update');

// Advertisement Click Tracker & Redirection
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

// Advertisement Management
$router->get('/' . $adminPath . '/ads', 'Admin\AdController@index');
$router->post('/' . $adminPath . '/ads/store', 'Admin\AdController@store');
$router->post('/' . $adminPath . '/ads/update', 'Admin\AdController@update');
$router->post('/' . $adminPath . '/ads/toggle', 'Admin\AdController@toggle');
$router->post('/' . $adminPath . '/ads/delete', 'Admin\AdController@delete');

// Dispatch incoming request
$router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD'] ?? 'GET');
