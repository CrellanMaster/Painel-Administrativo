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
        $matchess = preg_match_all('/[{][a-z-]+[}]/', $path, $matches);
        $this->routes[trim($path, '/')] =
            [
                'name' => $name,
                'controller' => $action[0],
                'method' => $action[1],
                'verb' => $this->verb,
                'path' => explode('/', trim($path, '/')),
                'isQuery' => $matchess > 0,
                'params' => $matches[0]
            ];
    }

    private function matchRoute($arrayUrl): array
    {
        $routeResult = [];

        foreach ($_SERVER['ROUTES'] as $route => $routeBody) {
            if (sizeof($arrayUrl) == sizeof($routeBody['path'])) {
                $routeMatched = true;
                for ($i = 0; $i < sizeof($arrayUrl); $i++) {
                    $param = '';
                    if (preg_match_all('/[{][a-z-]*[}]/', $routeBody['path'][$i], $param)) {
                        $routeBody['path'][$i] = $arrayUrl[$i];
                        var_dump($routeBody['params']);
                        foreach ($routeBody['params'] as $key => $item) {
                            var_dump($key . ' ' . $item);
                            if ($item == $param[0][0]) {
                                $routeBody['params'][$key] = $arrayUrl[$i];
                                break;
                            }
                        }
                    }
                    if ($arrayUrl[$i] != $routeBody['path'][$i]) {
                        $routeMatched = false;
                        break;
                    }
                }
                if ($routeMatched) {
                    $routeResult = $routeBody;
                    break;
                }
            }
        }

        return $routeResult;
    }

    /**
     * @throws Exception
     */
    public
    function dispatch()
    {
        $_SERVER['ROUTES'] = $this->routes;
        $url = trim(filter_input(INPUT_GET, 'url', FILTER_DEFAULT), '/');
        $arrayUrl = explode('/', trim($url, '/'));

        $routeData = $this->matchRoute($arrayUrl);


        if (empty($routeData)) {
            Response::responseNotFound(throw new Exception('Rota não encontrada'));
        }

        if ($_SERVER['REQUEST_METHOD'] != $routeData['verb']) {
            Response::responseException(throw new Exception('Verbo não suportado nessa rota'));
        }
        var_dump($routeData);

        $controller = "{$this->controllerPath}\\{$routeData['controller']}";
        if (class_exists($controller)) {
            $controller = new $controller();
            if (method_exists($controller, $routeData['method'])) {
                $method = $routeData['method'];
                $routeData['isQuery'] ? $controller->$method($routeData['params']) : $controller->$method();;
            } else {
                Response::responseException(throw new Exception('Método não encontrado'));
            }
        } else {
            Response::responseException(throw new Exception('Controlador não encontrado'));
        }
    }
}