<?php

declare(strict_types=1);

namespace App\ValueObject;

use App\Entity\Destination;
use App\Entity\Quote;
use App\Entity\Site;

final class DestinationLink
{
    private string $url;

    private function __construct(string $url)
    {
        $this->url = $url;
    }

    public static function createUrlFromEntities(Site $site, Destination $destination, Quote $quote): self
    {
        return new self(urlencode($site->url . '/' . $destination->countryName . '/quote/' . $quote->id));
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}