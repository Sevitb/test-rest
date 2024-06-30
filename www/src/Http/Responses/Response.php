<?php

namespace Src\Http\Responses;

class Response
{
    public function __construct(
        private mixed $content,
        private int $statusCode = 200,
        private array $headers = []
    )
    {
        http_response_code($this->statusCode);

        foreach ($headers as $headerTitle => $headerValue) {
            header("$headerTitle: $headerValue");
        }
    }

    /**
     * @return void
     */
    public function send(): void
    {
        echo $this->content;
    }
}
