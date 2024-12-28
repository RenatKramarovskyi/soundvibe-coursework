<?php

namespace Framework\HTTP;

class Response
{
    /**
     * @var mixed|null
     */
    protected mixed $data;

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return $this
     */
    public function setData(mixed $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param int $code
     * @return $this
     */
    public function status(int $code)
     {
         http_response_code($code);
         return $this;
     }

    /**
     * @param $data
     * @param int $code
     */
    public function __construct($data = null, int $code = 200)
     {
         $this->data = $data;
         $this->status($code);
     }

    /**
     * @return string
     */
    public function __toString() : string
     {
         return (string) $this->data;
     }
}