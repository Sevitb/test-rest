<?php

namespace Src;

use Dotenv\Dotenv;
use Src\Http\Exceptions\HttpException;
use Src\Http\Requests\Request;
use Src\Http\Responses\Response;
use Src\Http\Responses\JsonResponse;
use Src\Routing\Router;

class Application
{
    public function __construct(private Router $router)
    {
        $this->bootstrap();
    }

    /**
     * Инициализирует запуск приложения
     *
     * @return void
     */
    public function handle(Request $request): Response
    {
        try {
            [$handler, $args] = $this->router->dispatch($request);

            $response = call_user_func_array($handler, $args);

        } catch (HttpException $exc) {
            return new JsonResponse(['status' => 'fail', 'message' => $exc->getMessage()], $exc->getStatusCode());
        } catch (\Throwable $th) {
            return new JsonResponse(['status' => 'fail', 'message' => 'Server error'], 500);
        }

        return $response;
    }

    private function bootstrap()
    {
        $dotenv = Dotenv::createImmutable(BASE_DIR);
        $dotenv->load();
    }
}
