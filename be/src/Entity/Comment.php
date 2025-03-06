<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Framework\ORM\Attributes\Column;
use Framework\ORM\Attributes\Entity;
use Framework\ORM\Attributes\Id;
use Framework\ORM\BaseEntity;
use Framework\ORM\ColumnType;
use Framework\ORM\JsonSerializable;

#[Entity(repository: CommentRepository::class, table: "comments")]
class Comment extends BaseEntity implements JsonSerializable
{
    #[Id]
    #[Column(type: ColumnType::INT, column: "id")]
    public ?int $id = null;

    #[Column(type: ColumnType::VARCHAR, column: "content", length: 255)]
    public ?string $content = null;

    #[Column(type: ColumnType::INT, column: "postId")]
    public int $postId;

    #[Column(type: ColumnType::INT, column: "authorId")]
    public int $authorId;

    #[Column(type: ColumnType::DATETIME, column: "createdAt")]
    public \DateTime $createdAt;

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPostId(): int
    {
        return $this->postId;
    }

    public function setPostId(int $postId): self
    {
        $this->postId = $postId;
        return $this;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function setAuthorId(int $authorId): self
    {
        $this->authorId = $authorId;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->getId(),
            "content" => $this->getContent(),
            "postId" => $this->getPostId(),
            "authorId" => $this->getAuthorId(),
        ];
    }
}
