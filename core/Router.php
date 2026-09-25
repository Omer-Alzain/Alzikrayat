<?php
//class that deal with the routs of the page add them and when used call whatever file and function assoiate with it.
//Params:$routes storing the routes.
class Router
{
    protected $routes = [];

    /** 
     * adding new route and appending a function to request method and path
     * recive request method and url path and a callback function.
     * append the callback to a key of method and path in $routes.
    */  
    public function addRoute($method, $path, $callback)
    {
        $this->routes[$method][$path] = $callback;
    }

    /**
     * handel requests.
     * Params:$routePattern store the route of the url but in a clean way.
     * return the function that need to call 
     */
    public function dispatch($method, $path)
    {
        //loop through the routes in the array. 
        foreach ($this->routes[$method] as $routePath => $callback) {
            //define what pattern can be accepted and but the url in the right form.
            $routePattern = preg_replace('/\{([a-zA-Z]+)\}/','([^/]+)',$routePath);
            //turn the pattern to compleate regax.
            $routePattern = '#^' . $routePattern . '$#';
            //chack if the path match the route pattern and return it is function if it does.
            if (preg_match($routePattern, $path, $matches)) {
                return call_user_func_array($callback, array_slice($matches, 1));
            }
        }
        //if there was no match show error not found.
        http_response_code(404);
        echo "404 Not Found";
    }
}
