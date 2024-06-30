<?php

namespace Src\Http\Responses;

class JsonResponse extends Response
{

    public function __construct(array $content, int $statusCode = 200)
    {
        parent::__construct(
            json_encode($content),
            $statusCode,
            [
                'Content-Type' => 'application/json; charset=utf-8'
            ]
        );
    }
}
