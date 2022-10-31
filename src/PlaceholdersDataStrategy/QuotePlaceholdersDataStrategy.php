<?php

declare(strict_types=1);

namespace App\PlaceholdersDataStrategy;

use App\Entity\Quote;
use App\Repository\DestinationRepository;
use App\Repository\SiteRepository;
use App\ValueObject\DestinationLink;

final class QuotePlaceholdersDataStrategy implements PlaceholdersDataStrategyInterface
{
    public function getPlaceholdersData(array $data): array
    {
        $quote = $data['quote'] ?? null;
        if (($quote instanceof Quote) === false) {
            // I think we could remove this rule after a thorough analysis of the data.
            return ['[quote:destination_link]' => ''];
        }

        $site = SiteRepository::getInstance()->getById($quote->siteId);
        $destination = DestinationRepository::getInstance()->getById($quote->destinationId);

        return [
            '[quote:destination_link]' => DestinationLink::createUrlFromEntities($site, $destination, $quote)->getUrl(),
            '[quote:summary_html]' => $quote->getIdAsHtml(),
            '[quote:summary]' => $quote->getIdAsString(),
            '[quote:destination_name]' => $destination->countryName,
        ];
    }
}