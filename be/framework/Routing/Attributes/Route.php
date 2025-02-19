<?php

namespace Framework\Routing\Attributes;
use Attribute;
use Framework\HTTP\Request;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route
{
    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private string $path;

    /**
     * @var string
     */
    private array $methods;

    /**
     * @param string $name
     * @param string $path
     * @param array|string $methods
     */
    public function __construct(string $name, string $path, array $methods = Request::METHODS)
    {
        $this->name = $name;
        $this->path = $path;
        $this->methods = $methods;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }





}
