<?php

namespace App\Repository;

use App\Entity\Comment;
use Framework\ORM\Attributes\Repository;
use Framework\ORM\BaseEntityRepository;

#[Repository(entity: Comment::class)]
class CommentRepository extends BaseEntityRepository
{
}
