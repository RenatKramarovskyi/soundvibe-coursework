<?php

namespace Framework\HTTP;


class Request
{
    public const METHOD_GET = "GET";
    public const METHOD_POST = "POST";
    public const METHOD_PUT = "PUT";
    public const METHOD_PATCH = "PATCH";
    public const METHOD_DELETE = "DELETE";
    public const METHOD_OPTION = "OPTION";
    public const METHODS = [
        self::METHOD_GET,
        self::METHOD_POST,
        self::METHOD_PUT,
        self::METHOD_PATCH,
        self::METHOD_DELETE,
        self::METHOD_OPTION,
    ];

    /**
     * @var string
     */
    protected string $method;

    /**
     * @var array
     */
    protected array $headers;

    /**
     * @var array
     */
    protected array $query;

    public function getQuery(): array
    {
        return $this->query;
    }

    public function setQuery(array $query): self
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @var mixed
     */
    protected mixed $content;

    /**
     * @var array
     */
    protected array $files;

    /**
     * @var string
     */
    protected string $url;

    /**
     * @var string
     */
    protected string $path;

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    public function getContent(): mixed
    {
        return $this->content;
    }

    public function setContent(mixed $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function setFiles(array $files): self
    {
        $this->files = $files;
        return $this;
    }
}