<?php

namespace Framework\HTTP;

use http\Params;

class Request
{
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





}