<?php

class Router
{
    protected array $routes = [];

    public function get(string $path, callable|array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function addRoute(string $method, string $path, callable|array $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(): void
    {
        $path = $this->getPath();
        $method = $_SERVER['REQUEST_METHOD'];

        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            // Simple not found handler
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        if (is_callable($handler)) {
            call_user_func($handler);
        } elseif (is_array($handler)) {
            [$class, $methodName] = $handler;
            if (class_exists($class)) {
                $controller = new $class();
                if (method_exists($controller, $methodName)) {
                    $controller->$methodName();
                } else {
                    http_response_code(500);
                    echo "Controller method not found: {$methodName}";
                }
            } else {
                http_response_code(500);
                echo "Controller class not found: {$class}";
            }
        }
    }

    protected function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }
        return substr($path, 0, $position);
    }
}
