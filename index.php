<?php

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

spl_autoload_register(function (string $className): void {
    $directories = ['config', 'core', 'models', 'controllers'];

    foreach ($directories as $directory) {
        $file = __DIR__ . '/' . $directory . '/' . $className . '.php';

        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

Session::start();

$routes = [
    'home' => [HomeController::class, 'index'],
    'events' => [EventController::class, 'index'],
    'event_detail' => [EventController::class, 'detail'],
    'contact' => [ContactController::class, 'index'],
    'login' => [AuthController::class, 'login'],
    'logout' => [AuthController::class, 'logout'],

    'admin_dashboard' => [AdminController::class, 'dashboard'],
    'admin_events' => [EventController::class, 'adminIndex'],
    'admin_event_create' => [EventController::class, 'adminCreate'],
    'admin_event_edit' => [EventController::class, 'adminEdit'],
    'admin_event_delete' => [EventController::class, 'adminDelete'],
    'admin_categories' => [CategoryController::class, 'adminIndex'],
    'admin_category_create' => [CategoryController::class, 'adminCreate'],
    'admin_category_edit' => [CategoryController::class, 'adminEdit'],
    'admin_category_delete' => [CategoryController::class, 'adminDelete'],
];

$page = $_GET['page'] ?? 'home';

if (!array_key_exists($page, $routes)) {
    http_response_code(404);
    $pageTitle = 'Stránka nenájdená';
    require __DIR__ . '/views/partials/header.php';
    require __DIR__ . '/views/error.php';
    require __DIR__ . '/views/partials/footer.php';
    exit;
}

[$controllerName, $method] = $routes[$page];

try {
    $controller = new $controllerName();
    $controller->$method();
} catch (Throwable $exception) {
    http_response_code(500);
    error_log($exception->getMessage());
    $pageTitle = 'Chyba aplikácie';
    $errorMessage = 'Nastala neočakávaná chyba. Skontrolujte konfiguráciu databázy alebo to skúste neskôr.';
    require __DIR__ . '/views/partials/header.php';
    require __DIR__ . '/views/error.php';
    require __DIR__ . '/views/partials/footer.php';
}
