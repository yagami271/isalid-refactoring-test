<?php

declare(strict_types=1);

namespace App;

use App\Context\ApplicationContext;
use App\Entity\Quote;
use App\Entity\Template;
use App\Entity\User;
use App\Repository\DestinationRepository;
use App\Repository\SiteRepository;
use App\ValueObject\DestinationLink;

class TemplateManager
{
    /**
     * @param array<string, Quote|User> $data
     */
    public function getTemplateComputed(Template $template, array $data): Template
    {
        $cloneTemplate = clone $template;
        $cloneTemplate->subject = $this->replacePlaceholders($cloneTemplate->subject, $data);
        $cloneTemplate->content = $this->replacePlaceholders($cloneTemplate->content, $data);

        return $cloneTemplate;
    }

    /**
     * @param array<string, Quote|User> $data Can contain Quote Or/And User.
     */
    private function replacePlaceholders(string $text, array $data): string
    {
        $placeholdersData = [];

        $placeholdersData += $this->getQuotePlaceholdersData($data);
        $placeholdersData += $this->getUserPlaceholdersData($data);

        return \strtr($text, $placeholdersData);
    }

    /**
     * @param array<string, Quote|User> $data
     * @return array<string, string>
     */
    private function getQuotePlaceholdersData(array $data): array
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

    /**
     * @param array<string, Quote|User> $data
     * @return array<string, string>
     */
    private function getUserPlaceholdersData(array $data): array
    {
        $applicationContext = ApplicationContext::getInstance();
        $user = $data['user'] ?? $applicationContext->getCurrentUser();

        if (($user instanceof User) === false) {
            return [];
        }

        return [
            '[user:first_name]' => $user->firstname
        ];
    }
}
