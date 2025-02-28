<?php

namespace Framework\Validation;

use Closure;

class Validator
{
    public const NOT_CONSTRAINT = "NOT";
    public const OR_CONSTRAINT = "OR";

    /**
     * @var mixed
     */
    private mixed $value;
    /**
     * @var bool
     */
    private bool $valueGiven;
    /**
     * @var array
     */
    private array $constraints;

    /**
     *
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * @return $this
     */
    public function reset(): self
    {
        unset($this->value);
        $this->valueGiven = false;
        $this->constraints = [];
        return $this;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function for(mixed $value): self
    {
        $this->value = $value;
        $this->valueGiven = true;
        return $this;
    }

    /**
     * @return void
     */
    private function processNots(): void
    {
        for ($i = count($this->constraints) - 2; $i >= 0; $i -= 1) {
            $constraint = $this->constraints[$i];

            if ($constraint !== self::NOT_CONSTRAINT) {
                continue;
            }

            $targetConstraint = $this->constraints[$i + 1];
            array_splice($this->constraints, $i, 2, fn(mixed $value) => !$targetConstraint($value));
        }

        $this->constraints = array_filter($this->constraints, fn($item)=>$item!==self::NOT_CONSTRAINT);
    }

    /**
     * @return void
     */
    private function processOrs(): void
    {
        for ($i = count($this->constraints) - 2; $i > 0; $i -= 1) {
            $constraint = $this->constraints[$i];

            if ($constraint !== self::OR_CONSTRAINT) {
                continue;
            }

            // fn() OR fn()

            $leftConstraint = $this->constraints[$i - 1];
            $rightConstraint = $this->constraints[$i + 1];
            array_splice($this->constraints, $i - 1, 3, fn(mixed $value) => $leftConstraint($value) || $rightConstraint($value));
        }

        $this->constraints = array_filter($this->constraints, fn($item)=>$item!==self::OR_CONSTRAINT);
    }

    /**
     * @return bool
     */
    public function check(): bool
    {
        if(!$this->valueGiven){
            return false;
        }

        $this->processNots();

        $this->processOrs();

        foreach ($this->constraints as $constraint){
            if(!$constraint($this->value)){
                return false;
            }
        }

        return true;
    }

    /**
     * @return $this
     */
    public function not(): self
    {
        $this->constraints[] = self::NOT_CONSTRAINT;
        return $this;
    }

    /**
     * @return $this
     */
    public function or(): self
    {
        if(is_a(end($this->constraints), Closure::class)){
            $this->constraints[] = self::OR_CONSTRAINT;
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function null(): self
    {
        $this->constraints[] = fn(mixed $value) => $value === null;
        return $this;
    }


    /**
     * @return $this
     */
    public function empty(): self
    {
        $this->constraints[] = function(mixed $value){
            if(!isset($value)){
                return true;
            }

            if(is_array($value) && $value === []){
                return true;
            }

            if(is_string($value) && trim($value) === ""){
                return true;
            }

            return false;
        };
        return $this;
    }

    /**
     * @param mixed $target
     * @return $this
     */
    public function equals(mixed $target): self
    {
        $this->constraints[] = fn(mixed $value) => $value === $target;
        return $this;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function in(array $values): self
    {
        $this->constraints[] = fn(mixed $value) => in_array($value, $values);
        return $this;
    }

    /**
     * @param string $typeName
     * @return $this
     */
    public function type(string $typeName): self
    {
        $this->constraints[] = fn(mixed $value) => get_debug_type($value) === $typeName;
        return $this;
    }

    /**
     * @return $this
     */
    public function email(): self
    {
        $this->constraints[] = fn(mixed $value) => filter_var($value, FILTER_VALIDATE_EMAIL);
        return $this;
    }

    /**
     * @return $this
     */
    public function password(): self
    {
        $this->constraints[] = fn(mixed $value) => preg_match( "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/", $value) === 1;
        return $this;
    }

    /**
     * @param string $pattern
     * @return $this
     */
    public function pattern(string $pattern): self
    {
        $this->constraints[] = fn(mixed $value) => preg_match( $pattern, $value) === 1;
        return $this;
    }

    /**
     * @param mixed $than
     * @return $this
     */
    public function less(mixed $than): self
    {
        $this->constraints[] = fn(mixed $value) => $value < $than;
        return $this;
    }

    /**
     * @param mixed $than
     * @return $this
     */
    public function lessOrEqual(mixed $than): self
    {
        $this->constraints[] = fn(mixed $value) => $value <= $than;
        return $this;
    }

    /**
     * @param mixed $than
     * @return $this
     */
    public function more(mixed $than): self
    {
        $this->constraints[] = fn(mixed $value) => $value > $than;
        return $this;
    }

    /**
     * @param mixed $than
     * @return $this
     */
    public function moreOrEqual(mixed $than): self
    {
        $this->constraints[] = fn(mixed $value) => $value >= $than;
        return $this;
    }

    /**
     * @param mixed $min
     * @param mixed $max
     * @return $this
     */
    public function inRange(mixed $min, mixed $max): self
    {
        $this->constraints[] = fn(mixed $value) => $value >= $min && $value <= $max;
        return $this;
    }
}