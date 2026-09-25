<?php
//import nedded files.
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../controllers/PhotoController.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/CommentController.php';
//create objects of the needed classes
$router = new Router();
$homeController = new HomeController();
$photoController = new PhotoController();
$authController = new AuthController();
$commentController = new CommentController();
//add router for the home request
$router->addRoute('GET', '/', [$homeController, 'index']);
//add routes for the photo requests
$router->addRoute('GET', '/gallery', [$photoController, 'index']);
$router->addRoute('GET', '/photo/{id}', [$photoController, 'show']);
$router->addRoute('GET', '/upload', [$photoController, 'create']);
$router->addRoute('POST', '/upload', [$photoController, 'store']);
$router->addRoute('POST', '/photo/{id}/delete', [$photoController, 'delete']);
//add routes for comment requests
$router->addRoute('POST', '/comment', [$commentController, 'createComment']);
$router->addRoute('POST', '/comment/{commentId}/delete/{photoId}', [$commentController, 'delete']);
//add routes for auth requests
$router->addRoute('GET', '/auth/register', [$authController, 'register']);
$router->addRoute('POST', '/auth/register', [$authController, 'register']);
$router->addRoute('GET', '/auth/login', [$authController, 'login']);
$router->addRoute('POST', '/auth/login', [$authController, 'login']);
$router->addRoute('GET', '/auth/logout', [$authController, 'logout']);
// dispatch all routes
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);