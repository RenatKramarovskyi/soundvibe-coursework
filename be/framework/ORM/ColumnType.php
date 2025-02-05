<?php

namespace Framework\ORM;

use Closure;
use DateTime;
use DateTimeInterface;

enum ColumnType: string
{
    case INT = "INT";
    case BIGINT = "BIGINT";
    case DECIMAL = "DECIMAL";
    case VARCHAR = "VARCHAR";
    case TEXT = "TEXT";
    case BOOL = "BOOL";
    case JSON = "JSON";
    case DATETIME = "DATETIME";

    public const DATETIME_FORMAT = "Y-m-d H:i:s";

    /**
     * @return bool
     */
    public function hasLength() : bool
    {
        return match($this) {
            self::INT, self::BIGINT, self::VARCHAR => true,
            default => false
        };
    }

    /**
     * @return bool
     */
    public function hasPrecisionAndScale() : bool
    {
        return match($this) {
            self::DECIMAL => true,
            default => false
        };
    }

    /**
     * @return string
     */
    public function phpTypeName() : string
    {
        return match($this) {
            self::INT, self::BIGINT => "int",
            self::DECIMAL => "float",
            self::JSON => "array",
            self::BOOL => "bool",
            self::DATETIME => DateTimeInterface::class,
            default => "string"
        };
    }

    /**
     * @return Closure
     */
    public function sqlToPhp() : Closure
    {
       return match($this){
            self::INT => fn($value) => (int)$value,
            self::BIGINT => fn($value) => (int)$value,
            self::DECIMAL => fn($value) => (float)$value,
            self::VARCHAR => fn($value) => (string)$value,
            self::TEXT => fn($value) => (string)$value,
            self::BOOL => fn($value) => (bool)$value,
            self::JSON => fn($value) => json_decode($value, true),
            self::DATETIME => fn($value) => DateTime::createFromFormat(self::DATETIME_FORMAT ,$value),
       };
    }

    /**
     * @return Closure
     */
    public function phpToSql() : Closure
    {
        return match($this) {
            self::INT => fn(?int $value) => $value,
            self::BIGINT => fn(?int $value) => $value,
            self::DECIMAL => fn(?float $value) => $value,
            self::VARCHAR => fn(?string $value) => $value,
            self::TEXT => fn(?string $value) => $value,
            self::BOOL => fn(?bool $value) => $value === null ? null : ($value ? 1 : 0),
            self::JSON => fn(?array $value) =>  $value === null ? null : json_encode($value),
            self::DATETIME => fn(?DateTimeInterface $value) => $value->format(self::DATETIME_FORMAT)
        };
    }
}
