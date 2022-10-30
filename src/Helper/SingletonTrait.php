<?php

declare(strict_types=1);

namespace App\Helper;

trait SingletonTrait
{
    protected static ?self $instance = null;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
