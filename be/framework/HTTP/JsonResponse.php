<?php

namespace Framework\HTTP;

use Framework\HTTP\Response;
use Framework\ORM\JsonSerializable;

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
        return json_encode($this->serializeRecursive($this->data));
    }

    public function serializeRecursive(mixed $data)
    {
        if(is_a($data, JsonSerializable::class)){
            return $data->jsonSerialize();
        }
        if(!is_array($data)){
            return $data;
        }
        return array_map(fn($item) => $this->serializeRecursive($item), $data);
    }
}