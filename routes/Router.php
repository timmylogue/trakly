<?php
class Router
{
    private $routes = [];

    public function get($path, $controller, $method)
    {
        $this->addRoute('GET', $path, $controller, $method);
    }

    public function post($path, $controller, $method)
    {
        $this->addRoute('POST', $path, $controller, $method);
    }

    private function addRoute($httpMethod, $path, $controller, $method)
    {
        $this->routes[] = [
            'http_method' => $httpMethod,
            'path' => $path,
            'controller' => $controller,
            'method' => $method
        ];
    }

    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Clean up the request URI
        $requestUri = '/' . trim($requestUri, '/');

        // If empty, set to root
        if ($requestUri === '/') {
            $requestUri = '/';
        }

        foreach ($this->routes as $route) {
            $pattern = $this->convertToRegex($route['path']);

            if ($route['http_method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // Remove full match

                require_once __DIR__ . '/../app/controllers/' . $route['controller'] . '.php';
                $controllerInstance = new $route['controller']();

                call_user_func_array([$controllerInstance, $route['method']], $matches);
                return;
            }
        }

        // 404 Not Found
        http_response_code(404);
        echo "404 - Page Not Found";
    }

    private function convertToRegex($path)
    {
        // Convert :param to regex capture group
        $pattern = preg_replace('/\/:([a-zA-Z0-9_]+)/', '/([a-zA-Z0-9_-]+)', $path);
        return '#^' . $pattern . '$#';
    }
}
