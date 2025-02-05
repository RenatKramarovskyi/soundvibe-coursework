<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Framework\ORM\Attributes\Column;
use Framework\ORM\Attributes\Entity;
use Framework\ORM\Attributes\Id;
use Framework\ORM\BaseEntity;
use Framework\ORM\ColumnType;
use Framework\ORM\JsonSerializable;

#[Entity(repository: UserRepository::class, table: "user")]
class User extends BaseEntity implements JsonSerializable
{
    #[Id]
    #[Column(type : ColumnType::INT, column: "id")]
    public ?int $id = null;

    #[Column(type : ColumnType::VARCHAR, column: "username", length: 255)]
    public ?string $username = null;

    #[Column(type : ColumnType::BOOL, column: "sex")]
    public ?bool $sex = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getSex(): ?bool
    {
        return $this->sex;
    }

    public function setSex(?bool $sex): self
    {
        $this->sex = $sex;
        return $this;
    }


    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "username" => $this->getUsername(),
            "sex" => $this->getSex()
        ];
    }
}