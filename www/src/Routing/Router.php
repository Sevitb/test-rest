<?php

namespace Src\Routing;

use FastRoute;
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use Src\Http\Requests\Request;
use Src\Http\Controllers\AbstractController;

use Src\Http\Exceptions\MethodNotAllowedException;
use Src\Http\Exceptions\RouteNotFoundException;

class Router
{
    /** @var array */
    private array $routes;

    public function __construct()
    {
        $this->loadRoutes();
    }

    /**
     * @param \Src\Http\Requests\Request
     * @return array
     * @throws MethodNotAllowedException
     * @throws RouteNotFoundException
     */
    public function dispatch(Request $request)
    {
        $dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $collector) {
            foreach ($this->routes as $route) {
                $route[1] = $route[1][-1] == '/' ? substr($route[1], 0, -1) : $route[1];
                $collector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(), 
            $request->getPath()
        );

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:

                [$status, [[$controller, $method], $middlewares], $args] = $routeInfo;

                foreach ($args as $argKey => $arg) {
                    $request->setUrlParam($argKey, $arg);
                }

                foreach ($middlewares as $middleware) {
                    call_user_func_array([new $middleware, 'handle'], ['request' => $request]);
                }

                $handlerController = new $controller;
                
                if (array_key_exists(AbstractController::class, class_parents($handlerController)))
                {
                    $handlerController->request = $request;
                }
                
                return [[$handlerController, $method], $args];
                
            case Dispatcher::METHOD_NOT_ALLOWED:
                $exc = new MethodNotAllowedException('Method Not Allowed');
                throw $exc->setStatusCode(405);
            
            default:
                $exc = new RouteNotFoundException('Route not found');
                throw $exc->setStatusCode(404);
        }
    }

    /**
     * @return void
     */
    public function loadRoutes(): void
    {
        $this->routes = include BASE_DIR . '/app/Routes/api.php';
    }
}
