<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Framework\ORM\Attributes\Column;
use Framework\ORM\Attributes\Entity;
use Framework\ORM\Attributes\Id;
use Framework\ORM\BaseEntity;
use Framework\ORM\ColumnType;
use Framework\ORM\JsonSerializable;

#[Entity(repository: PostRepository::class, table: "post")]
class Post extends BaseEntity implements JsonSerializable
{
    #[Id]
    #[Column(type: ColumnType::INT, column: "id")]
    public ?int $id = null;

    #[Column(type: ColumnType::VARCHAR, column: "title", length: 255)]
    public ?string $title = null;

    #[Column(type: ColumnType::VARCHAR, column: "content", length: 255)]
    public ?string $content = null;

    #[Column(type: ColumnType::VARCHAR, column: "category", length: 255)]
    public ?string $category = null;

    #[Column(type: ColumnType::INT, column: "views")]
    public int $views = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function setViews(int $views): self
    {
        $this->views = $views;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "title" => $this->getTitle(),
            "content" => $this->getContent(),
            "category" => $this->getCategory(),
            "views" => $this->getViews(),
        ];
    }
}
