<?php

namespace App\Middleware;

use Src\Http\Requests\Request;
use Src\Http\Middleware\MiddlewareContract;
use Src\Http\Exceptions\HttpException;
use Src\Database\Database;
use App\Validation\ProductValidation;

class ValidateGetOrdresRequest implements MiddlewareContract
{
    /**
     * @throws \Src\Http\Exceptions\HttpException;
     */
    public function handle(Request $request)
    {
        $done = $request->getQueryParam('done');

        if (isset($done) && ($done < 0 || $done > 1)) {
            throw new HttpException('invalid value for parameter "done"');
        }
    }
}
