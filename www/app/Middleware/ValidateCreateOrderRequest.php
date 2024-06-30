<?php

namespace App\Middleware;

use App\Validation\ProductValidation;
use Src\Http\Requests\Request;
use Src\Http\Middleware\MiddlewareContract;
use Src\Http\Exceptions\HttpException;

class ValidateCreateOrderRequest implements MiddlewareContract
{
    /**
     * @throws \Src\Http\Exceptions\HttpException;
     */
    public function handle(Request $request)
    {
        $items = $request->getJsonData('items');

        $validation = ProductValidation::checkProductArray($items);

        if (!$validation['result']) {
            throw new HttpException($validation['message']);
        }
    }
}
