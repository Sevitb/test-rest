<?php

namespace Src\Http\Exceptions;

class HttpException extends \Exception
{
    /**
     * @var int
     */
    private int $statusCode = 400;

    /**
     * @param int $statusCode
     * @return HttpException
     */
    public function setStatusCode(int $statusCode): HttpException
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
