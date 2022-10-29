<?php

final class DestinationLink
{
    private string $url;

    private function __construct(string $url)
    {
        $this->url = $url;
    }

    public static function createUrlFromEntities(Site $site, Destination $destination, Quote $quote): self
    {
        return new self($site->url . '/' . $destination->countryName . '/quote/' . $quote->id);
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}