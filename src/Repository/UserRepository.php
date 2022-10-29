<?php

use Faker\Factory;

class UserRepository implements Repository
{
    use SingletonTrait;

    /**
     * @param int $id
     *
     * @return User
     */
    public function getById($id)
    {
        $generator = Faker\Factory::create();
        $generator->seed($id);

        return new User($id, $generator->firstName, $generator->lastName, $generator->email);
    }
}
