<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Framework\ORM\Attributes\Column;
use Framework\ORM\Attributes\Entity;
use Framework\ORM\BaseEntity;
use Framework\ORM\ColumnType;

#[Entity(repository: UserRepository::class, table: "user")]
class User extends BaseEntity
{
    #[Column(type : ColumnType::INT, column: "id")]
    public ?int $id = null;

    #[Column(type : ColumnType::VARCHAR, column: "username", length: 255)]
    public ?string $username = null;

    #[Column(type : ColumnType::BOOL, column: "sex")]
    public ?bool $sex = null;
}