<?php
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../controllers/HomeController.php';

$router = new Router();
$homeController = new HomeController();

$router->addRoute('GET', '/', [$homeController, 'index']);
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);