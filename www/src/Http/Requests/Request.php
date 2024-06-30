<?php

namespace Src\Http\Requests;

class Request
{
    public function __construct(
        private readonly array $queryParams, 
        private readonly array $postData, 
        private readonly array $jsonData, 
        private readonly array $cookies, 
        private readonly array $files, 
        private readonly array $server, 
        private array $urlParam = [])
    {
    }

    /**
     * @return static
     */
    public static function init(): static
    {
        $jsonData = json_decode(file_get_contents('php://input'), true);

        return new static($_GET, $_POST, isset($jsonData) ? $jsonData : [], $_COOKIE, $_FILES, $_SERVER);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getJsonData(string $name): mixed
    {
        return @$this->jsonData[$name];
    }

    /**
     * @return array
     */
    public function getJsonDataList(): array
    {
        return $this->jsonData;
    }

    /**
     * @param string $name
     * @return ?string
     */
    public function getQueryParam(string $name): ?string
    {
        return @$this->queryParams[$name];
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        $uri = strtok($this->server['REQUEST_URI'], '?');

        return $uri[-1] == '/' ? substr($uri, 0, -1) : $uri;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    /**
     * @param string $name
     * @return ?string
     */
    public function getHeader(string $name): ?string
    {
        return @$this->server['HTTP_' . strtoupper(preg_replace('/[-,\s]/', '_', $name))];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasHeader(string $name): bool
    {
        return isset($this->server['HTTP_' . strtoupper(preg_replace('/[-,\s]/', '_', $name))]);
    }

    /**
     * @param string $name
     * @param string $value
     * @return void 
     */
    public function setUrlParam(string $name, string $value): void
    {
        $this->urlParam[$name] = $value;
    }

    /**
     * @param string $name
     * @return mixed 
     */
    public function getUrlParam(string $name): mixed
    {
        return @$this->urlParam[$name];
    }
}
