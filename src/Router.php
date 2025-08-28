<?php
namespace App;

use Exception;

class Router {
    public $routes = [];
    public $currentMiddlewares = [];

    public function addRoute($method, $uri, $handler){
        if(is_callable($handler)){
            $handler = $handler;
        } else {
            list($handler, $function) = $handler;
        }

        $this->routes[$method][$uri] = [
            "handler" => $handler
        ];

        if(isset($function) && !empty($function)){
            $this->routes[$method][$uri]['class_method'] = $function;
        }

        if(isset($this->currentMiddlewares) && !empty($this->currentMiddlewares)){
            $this->routes[$method][$uri]['middlewares'] = $this->currentMiddlewares;
        }

        return $this;
    }

    public function get(string $uri, $handler){
        $this->addRoute("GET", $uri, $handler);
        return $this;
    }

    public function post(string $uri, $handler){
        $this->addRoute("POST", $uri, $handler);
        return $this;
    }

    public function put(string $uri, $handler){
        $this->addRoute("PUT", $uri, $handler);
        return $this;
    }

    public function patch(string $uri, $handler){
        $this->addRoute("PATCH", $uri, $handler);
        return $this;
    }

    public function delete(string $uri, $handler){
        $this->addRoute("DELETE", $uri, $handler);
        return $this;
    }

    public function middleware(array $middlewares = []){
        foreach($middlewares as $middleware){
            $this->currentMiddlewares[] = $middleware;
        }

        return $this;
    }

    public function group(callable $callback){
        $callback($this);
        $this->currentMiddlewares = [];
        return $this;
    }

    public function dispatch(){
        $method = $_SERVER['REQUEST_METHOD'] ?? null;
        $uri = $_SERVER['REQUEST_URI'] ?? null;

        $routeDetails = null;

        // Check if route matches exactly
        if (isset($this->routes[$method][$uri])) {
            $routeDetails = $this->routes[$method][$uri];
        } else {
            // Try placeholder match: /task/{id}
            foreach ($this->routes[$method] ?? [] as $route => $details) {
                // Find all placeholder names in the route
                preg_match_all('#\{([^/]+)\}#', $route, $paramNames); // ['id']
                $paramNames = $paramNames[1]; // only the names

                // Convert route to regex
                $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $route);

                if (preg_match("#^$pattern$#", $uri, $matches)) {
                    array_shift($matches); // remove full match

                    // Combine placeholder names with matched values
                    $_GET['__params'] = array_combine($paramNames, $matches);

                    $routeDetails = $details;
                    break;
                }
            }
        }

        if($routeDetails){
            $handler = $routeDetails['handler'] ?? null;
            $class_method = $routeDetails['class_method'] ?? null;
            $middlewares = $routeDetails['middlewares'] ?? [];

            foreach($middlewares as $middleware){
                require __DIR__ . "/../src/middlewares.php";
                $middleware_fn = $middleware_mapping[$middleware] ?? null;
                if (is_callable($middleware_fn)) {
                    $middleware_fn();
                }
            }

            if(!$handler){
                throw new Exception("Resource not found.");
            }

            $Request = new Request();

            if(is_callable($handler)){
                call_user_func($handler, $Request);
            } else {
                $class_name = new $handler;
                call_user_func([$class_name, $class_method], $Request);
            }
        } else {
            echo "Resource not found.";
        }
    }
}