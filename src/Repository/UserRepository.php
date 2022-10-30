<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Helper\SingletonTrait;

class UserRepository implements Repository
{
    use SingletonTrait;

    /**
     * @param int $id
     */
    public function getById($id): User
    {
        $generator = \Faker\Factory::create();
        $generator->seed($id);

        return new User($id, $generator->firstName, $generator->lastName, $generator->email);
    }
}
