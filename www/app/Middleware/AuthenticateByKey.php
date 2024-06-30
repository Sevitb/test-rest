<?php

namespace App\Middleware;

use Src\Http\Exceptions\ForbiddenException;
use Src\Http\Requests\Request;
use Src\Http\Middleware\MiddlewareContract;

class AuthenticateByKey implements MiddlewareContract
{
    /**
     * @throws \Src\Http\Exceptions\ForbiddenException;
     */
    public function handle(Request $request)
    {
        $config = include BASE_DIR . '/app/Config/app.php';

        if (!($request->getHeader('X-Auth-Key') == $config['auth_key'])) {
            $exc = new ForbiddenException('Access denied');
            throw $exc->setStatusCode(405);
        }
    }
}
