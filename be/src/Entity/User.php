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
    public const ROLE_USER = "ROLE_USER";
    public const ROLE_ADMIN = "ROLE_ADMIN";

    #[Id]
    #[Column(type: ColumnType::INT, column: "id")]
    public ?int $id = null;

    #[Column(type: ColumnType::VARCHAR, column: "username", length: 255)]
    public ?string $username = null;

    #[Column(type: ColumnType::VARCHAR, column: "email", length: 255)]
    public ?string $email = null;

    #[Column(type: ColumnType::VARCHAR, column: "password", length: 255)]
    public ?string $password = null;

    #[Column(type: ColumnType::BOOL, column: "sex")]
    public ?bool $sex = null;

    #[Column(type: ColumnType::VARCHAR, column: "role", length: 255)]
    public ?string $role = self::ROLE_USER;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
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

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "username" => $this->getUsername(),
            "email" => $this->getEmail(),
            "sex" => $this->getSex(),
            "role" => $this->getRole(),
        ];
    }
}
