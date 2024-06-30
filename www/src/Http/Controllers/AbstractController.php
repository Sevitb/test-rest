<?php

namespace Src\Http\Controllers;

use Src\Http\Requests\Request;

abstract class AbstractController
{
    /**
     * @var \Src\Http\Requests\Request
     */
    public Request $request;
}