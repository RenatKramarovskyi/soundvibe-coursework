<?php

namespace Framework\ORM\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Id
{
    public function __construct()
    {
    }
}