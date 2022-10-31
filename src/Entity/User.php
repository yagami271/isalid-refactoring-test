<?php

declare(strict_types=1);

namespace App\Entity;

class User
{
    public int $id;
    public string $firstname;
    public string $lastname;
    public string $email;

    public function __construct(int $id, string $firstname, string $lastname, string $email)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
    }

    public function getFirstname(): string
    {
        return \ucfirst(\mb_strtolower($this->firstname));
    }
}
