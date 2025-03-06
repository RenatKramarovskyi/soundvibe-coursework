<?php

namespace App\Repository;

use App\Entity\Post;
use Framework\ORM\Attributes\Repository;
use Framework\ORM\BaseEntityRepository;

#[Repository(entity: Post::class)]
class PostRepository extends BaseEntityRepository
{
}
