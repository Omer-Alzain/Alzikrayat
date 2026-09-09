<?php
class Router
{
    protected $routes = [];

    public function addRoute($method, $path, $callback)
    {
        $this->routes[$method][$path] = $callback;
    }

    public function dispatch($method, $path)
    {
        if (isset($this->routes[$method][$path])) {
            callback($this->routes[$method][$path]);
        } else {
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}
