<?php

declare(strict_types=1);

namespace App\PlaceholdersDataStrategy;

use App\Context\ApplicationContext;
use App\Entity\User;

final class UserPlaceholdersDataStrategy implements PlaceholdersDataStrategyInterface
{
    public function getPlaceholdersData(array $data): array
    {
        $applicationContext = ApplicationContext::getInstance();
        $user = $data['user'] ?? $applicationContext->getCurrentUser();

        if (($user instanceof User) === false) {
            return [];
        }

        return [
            '[user:first_name]' => $user->getFirstname()
        ];
    }
}