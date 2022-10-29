<?php

class UserRepository implements Repository
{
    use SingletonTrait;

    /**
     * @param int $id
     */
    public function getById($id): User
    {
        $generator = Faker\Factory::create();
        $generator->seed($id);

        return new User($id, $generator->firstName, $generator->lastName, $generator->email);
    }
}
