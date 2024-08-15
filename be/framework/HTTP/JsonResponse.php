<?php

namespace Framework\HTTP;

use Framework\HTTP\Response;

class JsonResponse extends Response
{
    /**
     * @param $data
     * @param int $code
     */
    public function __construct($data = null, int $code = 200)
    {
        parent::__construct($data, $code);
        header("Content-Type: application/json");
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->data);
    }
}