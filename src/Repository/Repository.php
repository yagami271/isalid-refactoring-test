<?php

declare(strict_types=1);

namespace App\Repository;

interface Repository
{
    public function getById($id);
}