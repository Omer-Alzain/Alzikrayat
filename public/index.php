<?php
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/PhotoController.php';
require_once __DIR__ . '/../controllers/AuthController.php';

$router = new Router();
$homeController = new HomeController();
$photoController = new PhotoController();
$authController = new AuthController();

$router->addRoute('GET', '/', [$homeController, 'index']);
$router->addRoute('GET', '/gallery', [$photoController, 'index']);
$router->addRoute('GET', '/photo/{id}', [$photoController, 'show']);
$router->addRoute('GET', '/authP', [$authController, 'index']);
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
