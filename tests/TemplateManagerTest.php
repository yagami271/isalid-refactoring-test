<?php

declare(strict_types=1);

namespace Test;

use App\Context\ApplicationContext;
use App\Entity\Quote;
use App\Entity\Template;
use App\Repository\DestinationRepository;
use App\Repository\SiteRepository;
use App\Repository\UserRepository;
use App\TemplateManager;
use App\ValueObject\DestinationLink;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

class TemplateManagerTest extends TestCase
{
    public function testNominalCase(): void
    {
        $faker = Factory::create();

        $destinationId = $faker->randomNumber();
        $expectedDestination = DestinationRepository::getInstance()->getById($destinationId);
        $expectedUser = ApplicationContext::getInstance()->getCurrentUser();

        $quote = new Quote($faker->randomNumber(), $faker->randomNumber(), $destinationId, $faker->dateTime());

        $template = new Template(
            1,
            'Votre livraison à [quote:destination_name]',
            "
            Bonjour [user:first_name],
            
            Merci de nous avoir contacté pour votre livraison à [quote:destination_name].
            
            Bien cordialement,
            
            L'équipe de Shipper");

        $templateManager = new TemplateManager();
        $message = $templateManager->getTemplateComputed(
            $template,
            [
                'quote' => $quote
            ]
        );

        $this->assertEquals('Votre livraison à ' . $expectedDestination->countryName, $message->subject);
        $this->assertEquals("
            Bonjour " . $expectedUser->firstname . ",
            
            Merci de nous avoir contacté pour votre livraison à " . $expectedDestination->countryName . ".
            
            Bien cordialement,
            
            L'équipe de Shipper", $message->content
        );
    }

    public function testAllPlaceHolders(): void
    {
        $faker = Factory::create();

        $destinationId = $faker->randomNumber();
        $expectedDestination = DestinationRepository::getInstance()->getById($destinationId);
        $expectedUser = ApplicationContext::getInstance()->getCurrentUser();

        $siteId = $faker->randomNumber();
        $expectedSite = SiteRepository::getInstance()->getById($siteId);

        $quote = new Quote($faker->randomNumber(), $siteId, $destinationId, $faker->dateTime());

        $template = new Template(
            1,
            'test all placeholders case [user:first_name]',
            '
                quote:destination_name => [quote:destination_name],
                quote:destination_link => [quote:destination_link],
                quote:summary_html => [quote:summary_html],
                quote:summary => [quote:summary]');

        $templateManager = new TemplateManager();
        $message = $templateManager->getTemplateComputed(
            $template,
            [
                'quote' => $quote
            ]
        );

        $this->assertEquals('test all placeholders case ' . $expectedUser->firstname, $message->subject);
        $this->assertEquals('
                quote:destination_name => ' . $expectedDestination->countryName . ',
                quote:destination_link => ' . DestinationLink::createUrlFromEntities($expectedSite, $expectedDestination, $quote)->getUrl() . ',
                quote:summary_html => <p>' . $quote->id . '</p>,
                quote:summary => '. $quote->id, $message->content);
    }

    public function testAllPlaceHoldersWithOutCurrentUser(): void
    {
        $faker = Factory::create();

        $destinationId = $faker->randomNumber();
        $expectedDestination = DestinationRepository::getInstance()->getById($destinationId);

        $expectedUserId = $faker->randomNumber();
        $expectedUser = UserRepository::getInstance()->getById($expectedUserId);

        $siteId = $faker->randomNumber();
        $expectedSite = SiteRepository::getInstance()->getById($siteId);

        $quote = new Quote($faker->randomNumber(), $siteId, $destinationId, $faker->dateTime());

        $template = new Template(
            1,
            'test all placeholders case [user:first_name]',
            '
                quote:destination_name => [quote:destination_name],
                quote:destination_link => [quote:destination_link],
                quote:summary_html => [quote:summary_html],
                quote:summary => [quote:summary]');

        $templateManager = new TemplateManager();
        $message = $templateManager->getTemplateComputed(
            $template,
            [
                'quote' => $quote,
                'user' => $expectedUser
            ]
        );

        $this->assertEquals('test all placeholders case ' . $expectedUser->firstname, $message->subject);
        $this->assertEquals('
                quote:destination_name => ' . $expectedDestination->countryName . ',
                quote:destination_link => ' . DestinationLink::createUrlFromEntities($expectedSite, $expectedDestination, $quote)->getUrl() . ',
                quote:summary_html => <p>' . $quote->id . '</p>,
                quote:summary => ' . $quote->id, $message->content);
    }

    public function testAllPlaceHoldersWithUserOnlyShouldGetDestinationLinkEmpty(): void
    {
        $faker = Factory::create();

        $expectedUserId = $faker->randomNumber();
        $expectedUser = UserRepository::getInstance()->getById($expectedUserId);

        $template = new Template(
            1,
            'test all placeholders case [user:first_name]',
            '
                quote:destination_name => [quote:destination_name],
                quote:destination_link => [quote:destination_link],
                quote:summary_html => [quote:summary_html],
                quote:summary => [quote:summary]');

        $templateManager = new TemplateManager();
        $message = $templateManager->getTemplateComputed(
            $template,
            [
                'user' => $expectedUser
            ]
        );

        $this->assertEquals('test all placeholders case ' . $expectedUser->firstname, $message->subject);
        $this->assertEquals('
                quote:destination_name => [quote:destination_name],
                quote:destination_link => ,
                quote:summary_html => [quote:summary_html],
                quote:summary => [quote:summary]', $message->content);
    }

    public function testDestinationUrlLinkShouldNotContainSpaces(): void
    {
        $faker = Factory::create();

        $quote = new Quote($faker->randomNumber(), $faker->randomNumber(), $faker->randomNumber(), $faker->dateTime());

        $template = new Template(
            1,
            'test destination_link without spaces',
            '[quote:destination_link]');

        $templateManager = new TemplateManager();
        $message = $templateManager->getTemplateComputed(
            $template,
            [
                'quote' => $quote
            ]
        );

        $this->assertEquals('test destination_link without spaces', $message->subject);

        $this->assertNotRegExp('/\s/', $message->content);
    }
}
