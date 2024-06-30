<?php

namespace Src\Http\Middleware;

use Src\Http\Requests\Request;

interface MiddlewareContract
{
    /**
     * @param \Src\Http\Requests\Request
     */
    public function handle(Request $request);
}