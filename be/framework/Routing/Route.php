<?php

namespace Framework\Routing;

use Framework\Context;
use Framework\HTTP\Request;
use http\Params;
use ReflectionMethod;

/**
 *
 */
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
    private string $pathRegex;
    /**
     * @var array
     */
    private array $methods;
    /**
     * @var string
     */
    private string $controller;
    /**
     * @var string
     */
    private string $method;

    /**
     * @var array
     */
    private array $params;



    /**
     * @param string $name
     * @param string $path
     * @param array $methods
     * @param string $controller
     * @param string $method
     */
    public function __construct(string $name, string $path, array $methods, string $controller, string $method)
    {
        $this->setName($name);
        $this->setPath($path);
        $this->setMethods($methods);
        $this->setController($controller);
        $this->setMethod($method);


    }


    /**
     * @return void
     */

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath(string $path): self
    {
        $this->path = rtrim($path, "/");
        $this->generateParams();
        $this->generateRegex();
        return $this;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param array $methods
     * @return $this
     */
    public function setMethods(array $methods): self
    {
        $this->methods = $methods;
        return $this;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     * @return $this
     */
    public function setController(string $controller): self
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }


    public function getPathRegex(): string
    {
        return $this->pathRegex;
    }

    public function setPathRegex(string $pathRegex): self
    {
        $this->pathRegex = $pathRegex;
        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): self
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function matches(Request $request): bool
    {
        return preg_match($this->pathRegex, $request->getPath()) &&
            in_array($request->getMethod(), $this->getMethods());
    }
    public function generateParams() : void
    {
        preg_match_all("/{([^}]+)}/", $this->path, $matches);
        $this->params = $matches[1];
    }

    /**
     * @return void
     */
    public function generateRegex() : void
    {
        $quotedPath = preg_quote($this->path, "/");
        $regex = $quotedPath;
        foreach ($this->params as $param){
            $regex = str_replace("\{". $param ."\}", "([^\/]+)", $regex);
        }
        $this->pathRegex = "/^" . $regex . "$/";
    }


    public function prepareParams(string $url) : array
    {
        $result = [];
        preg_match_all($this->pathRegex, $url, $matches);

        foreach ($this->getParams() as $index => $param) {
            $result[$param] = $matches[$index + 1][0];
        }
        return $result;
    }

    /**
     * @return mixed
     */
    public function execute(Context $context) : mixed
    {
        $controllerName = $this->getController();
        $controllerInstance = new $controllerName();

        $reflectionMethod = new ReflectionMethod($controllerName, $this->getMethod());
        return $reflectionMethod->invokeArgs($controllerInstance, [...$this->prepareParams($context->request->getPath()), "context" => $context]);
    }

}