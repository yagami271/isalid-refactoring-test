<?php

declare(strict_types=1);

namespace App;

use App\Entity\Quote;
use App\Entity\Template;
use App\Entity\User;
use App\PlaceholdersDataStrategy\PlaceholdersDataStrategyInterface;
use App\PlaceholdersDataStrategy\QuotePlaceholdersDataStrategy;
use App\PlaceholdersDataStrategy\UserPlaceholdersDataStrategy;

class TemplateManager
{
    /**
     * @var array<PlaceholdersDataStrategyInterface>
     */
    private array $placeholdersDataStrategies = [];

    public function __construct()
    {
        /**
         * in real life :
         * with symfony dependency injection we can use tagged services to inject automatically all services that implements PlaceholdersDataStrategyInterface
         * Doc https://symfony.com/doc/4.4/service_container/tags.html
         * to discuss
         */
        $this->placeholdersDataStrategies = [
            new QuotePlaceholdersDataStrategy(),
            new UserPlaceholdersDataStrategy(),
        ];
    }


    /**
     * @param array<string, Quote|User> $data
     */
    public function getTemplateComputed(Template $template, array $data): Template
    {
        $placeholdersData = $this->getPlaceholdersData($data);

        $cloneTemplate = clone $template;
        $cloneTemplate->subject = $this->replacePlaceholders($cloneTemplate->subject, $placeholdersData);
        $cloneTemplate->content = $this->replacePlaceholders($cloneTemplate->content, $placeholdersData);

        return $cloneTemplate;
    }

    /**
     * @param array<string, string> $placeholdersData
     */
    private function replacePlaceholders(string $text, array $placeholdersData): string
    {
        return \strtr($text, $placeholdersData);
    }

    /**
     * @param array<string, Quote|User> $data
     * @return array<string,string>
     */
    private function getPlaceholdersData(array $data): array
    {
        $placeholdersData = [];

        foreach ($this->placeholdersDataStrategies as $placeholdersDataStrategy) {
            $placeholdersData += $placeholdersDataStrategy->getPlaceholdersData($data);
        }

        return $placeholdersData;
    }
}
