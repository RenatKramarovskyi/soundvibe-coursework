<?php

namespace Framework\ORM\Attributes;

use Attribute;
use Framework\ORM\ColumnType;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Column
{
    /**
     * @var ColumnType
     */
    private ColumnType $type;
    /**
     * @var string
     */
    private string $column;
    /**
     * @var int|null
     */
    private ?int $length;
    /**
     * @var int|null
     */
    private ?int $precision;
    /**
     * @var int|null
     */
    private ?int $scale;
    /**
     * @var bool|null
     */
    private ?bool $unique;
    /**
     * @var bool
     */
    private bool $nullable;

    /**
     * @param ColumnType $type
     * @param string $column
     * @param int|null $length
     * @param int|null $precision
     * @param int|null $scale
     * @param bool|null $unique
     * @param bool $nullable
     */
    public function __construct(
        ColumnType $type,
        string     $column,
        ?int       $length = null,
        ?int       $precision = null,
        ?int       $scale = null,
        ?bool      $unique = false,
        bool       $nullable = true
    )
    {
        $this->type = $type;
        $this->column = $column;
        $this->length = $length;
        $this->precision = $precision;
        $this->scale = $scale;
        $this->unique = $unique;
        $this->nullable = $nullable;
    }

    /**
     * @return ColumnType
     */
    public function getType(): ColumnType
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * @return int|null
     */
    public function getLength(): ?int
    {
        return $this->length;
    }

    /**
     * @return int|null
     */
    public function getPrecision(): ?int
    {
        return $this->precision;
    }

    /**
     * @return int|null
     */
    public function getScale(): ?int
    {
        return $this->scale;
    }

    /**
     * @return bool|null
     */
    public function getUnique(): ?bool
    {
        return $this->unique;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }


}