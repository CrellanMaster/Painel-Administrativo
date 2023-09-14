<?php

namespace Crellan\App\Core;

use Crellan\App\Helpers\Response;
use Exception;

class RouterCore
{
    private $host;
    private $routes = array();

    private $verb;

    private $controllerPath = ConfigCore::PATH_CONTROLLER;

    public function __construct($url)
    {
        $this->host = $url;
    }

    public function get()
    {
        $this->verb = 'GET';
        return $this;
    }

    public function post()
    {
        $this->verb = 'POST';
        return $this;
    }

    public function patch()
    {
        $this->verb = 'PATCH';
        return $this;
    }

    public function put()
    {
        $this->verb = 'PUT';
        return $this;
    }

    public function delete()
    {
        $this->verb = 'DELETE';
        return $this;
    }

    public function route($path, $action, $name)
    {
        $action = array_filter(explode('@', $action));

        $this->routes[$path] =
            [
                'name' => $name,
                'controller' => $action[0],
                'method' => $action[1],
                'verb' => $this->verb,
                'path' => explode('/', $path)
            ];
    }

    /**
     * @throws Exception
     */
    public function dispatch()
    {
        $_SERVER['ROUTES'] = $this->routes;
        $url = trim(filter_input(INPUT_GET, 'url', FILTER_DEFAULT), '/');
        $url = '/' . $url;

        $routeData = [];
        foreach ($_SERVER['ROUTES'] as $route => $routeBody) {
            if ($url == $route) {
                $routeData = $routeBody;
                break;
            }
        }

        if (empty($routeData)) {
            header('HTTP/1.1 404 NOT FOUND');
            Response::responseNotFound(throw new Exception('Rota não encontrada'));
        }

        var_dump($routeData);

        if ($_SERVER['REQUEST_METHOD'] != $routeData['verb']) {
            Response::responseException(throw new Exception('Verbo não suportado nessa rota'));
        }

        $controller = "{$this->controllerPath}\\{$routeData['controller']}";
        if (class_exists($controller)) {
            $controller = new $controller();
            if (method_exists($controller, $routeData['method'])) {
                $method = $routeData['method'];
                $controller->$method();
            } else {
                Response::responseException(throw new Exception('Método não encontrado'));
            }
        } else {
            Response::responseException(throw new Exception('Controlador não encontrado'));
        }
    }
}