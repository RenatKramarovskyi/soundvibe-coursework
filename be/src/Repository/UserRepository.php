<?php

namespace App\Repository;

use App\Entity\User;
use Framework\ORM\Attributes\Repository;
use Framework\ORM\BaseEntityRepository;

#[Repository(entity: User::class)]
class UserRepository extends BaseEntityRepository
{

}