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
        $routeDetails = $this->routes[$method][$uri] ?? null;

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

            if(is_callable($handler)){
                call_user_func($handler);
            } else {
                $class_name = new $handler;
                call_user_func([$class_name, $class_method]);
            }
        } else {
            echo "Resource not found.";
        }
    }
}