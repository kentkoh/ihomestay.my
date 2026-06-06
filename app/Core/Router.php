<?php

class Router {
    private array $routes = [];

    public function get(string $path, $handler): void {
        $this->routes[] = ['GET', $path, $handler];
    }

    public function post(string $path, $handler): void {
        $this->routes[] = ['POST', $path, $handler];
    }

    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri    = rtrim($uri, '/') ?: '/';

        foreach ($this->routes as [$routeMethod, $routePath, $handler]) {
            $pattern = $this->toRegex($routePath);
            if ($routeMethod === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                if (is_callable($handler)) {
                    call_user_func_array($handler, $matches);
                } elseif (is_array($handler)) {
                    [$controller, $action] = $handler;
                    (new $controller())->$action(...$matches);
                }
                return;
            }
        }

        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
    }

    private function toRegex(string $path): string {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
}
