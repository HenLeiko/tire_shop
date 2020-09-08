<?php


namespace app\core;

use app\core\View;
class Router
{

    protected $routes = [];
    protected $params = [];

    public function __construct()
    {
        $routs = require 'app/config/routes.php';
        foreach ($routs as $route => $params)
        {
            $this->addRoutes($route, $params);
        }
    }

    public function addRoutes($route, $params)
    {
        $route = '#^'.$route.'$#';

        $this->routes[$route] = $params;
    }

    public function match()
    {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        foreach ($this->routes as $route => $params)
        {
            if (preg_match($route, $url, $matches))
            {
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    public function run()
    {
        if ($this->match())
        {
            $path = 'app\controllers\\'.ucfirst($this->params['controller']).'Controller';
            var_dump($path);
            if (class_exists($path))
            {
                $action = $this->params['action'].'Action';
                if (method_exists($path, $action)) {
                    $controller = new $path($this->params);
                    $controller->$action();
                }
                else
                    {
                        echo 'Ошибка 404';

                }
            }
            else
                {
                    echo 'Ошибка 404';
            }
        } else
            {
            echo 'Ошибка 404';
        }
    }
}