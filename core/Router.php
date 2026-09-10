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
        foreach ($this->routes[$method] as $routePath => $callback) {
            $routePattern = preg_replace('/\{([a-zA-Z]+)\}/','([^/]+)',$routePath);
            $routePattern = '#^' . $routePattern . '$#';
            if (preg_match($routePattern, $path, $matches)) {
                return call_user_func_array($callback, array_slice($matches, 1));
            }
        }
        http_response_code(404);
        echo "404 Not Found";
    }
}
