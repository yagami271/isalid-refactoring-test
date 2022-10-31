<?php

declare(strict_types=1);

namespace App\PlaceholdersDataStrategy;

use App\Entity\Quote;
use App\Entity\User;

interface PlaceholdersDataStrategyInterface
{
    /**
     * @param array<string, User|Quote> $data
     * @return array<string, string>
     */
    public function getPlaceholdersData(array $data): array;
}