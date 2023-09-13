<?php

namespace Crellan\App\Core;

use mysql_xdevapi\Exception;

class RouterCore
{
    private $host;
    private $routes = array();

    private $controllerPath = ConfigCore::PATH_CONTROLLER;

    public function __construct($url)
    {
        $this->host = $url;
    }

    public function route($path, $action, $name): void
    {
        $action = array_filter(explode('@', $action));

        $this->routes[$name] =
            [
                'path' => $path,
                'controller' => $action[0],
                'method' => $action[1]
            ];
    }

    public function dispatch(): void
    {
        $_SERVER['ROUTES'] = $this->routes;

        $url = trim(filter_input(INPUT_GET, 'url', FILTER_DEFAULT), '/');
        $url = '/' . $url;
        var_dump($_SERVER);
        foreach ($this->routes as $route => $routeData) {
            if ($url == $routeData['path']) {
                $controller = "{$this->controllerPath}\\{$routeData['controller']}";
                if (class_exists($controller)) {
                    $controller = new $controller();
                    if (method_exists($controller, $routeData['method'])) {
                        $method = $routeData['method'];
                        $controller->$method();
                    } else {
                        \Crellan\App\Helpers\Response::redirectIfError('Método não encontrado');
                    }
                    return;
                }
            } else {
                \Crellan\App\Helpers\Response::redirectIfError('Rota não encontrada');
            }
        };

    }

}