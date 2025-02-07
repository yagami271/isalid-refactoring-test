<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Destination;
use App\Helper\SingletonTrait;

class DestinationRepository implements Repository
{
    use SingletonTrait;

    /**
     * @param int $id
     *
     * @return Destination
     */
    public function getById($id)
    {
        // DO NOT MODIFY THIS METHOD
        $generator = \Faker\Factory::create();
        $generator->seed($id);

        return new Destination(
            $id,
            $generator->country,
            'en',
            $generator->slug()
        );
    }
}
