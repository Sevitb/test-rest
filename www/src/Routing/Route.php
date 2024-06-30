<?php

namespace Src\Routing;

class Route
{
    /**
     * @param string $uri
     * @param array $handler
     * @param array $middlewares
     * @return array [method, uri, [handler, middlewares]]
     */
    public static function get(string $uri, array $handler, array $middlewares = []): array
    {
        return ['GET', $uri, [$handler, $middlewares]];
    }

    /**
     * @param string $uri
     * @param array $handler класс обработчик и его метод [controller, method]
     * @param array $middlewares
     * @return array [method, uri, [handler, middlewares]]
     */
    public static function post(string $uri, array $handler, array $middlewares = []): array
    {
        return ['POST', $uri, [$handler, $middlewares]];
    }
}