<?php

namespace App\Tests\Unit\Utils;

use App\Entity\Customer;
use App\Utils\Clock\ClockInterface;
use App\Utils\Template;
use App\Utils\TemplateManager;
use PHPUnit\Framework\TestCase;

final class TemplateManagerTest extends TestCase
{
    private $clock;

    public function setUp(): void
    {
        $now = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-04-06 23:59:59');
        $this->clock = $this->createMock(ClockInterface::class);
        $this->clock->method('now')->willReturn($now);
    }

    public function testTemplateComputed(): void
    {
        $customer = new Customer(
            'my-customer-id',
            $this->clock->now(),
            'female',
            'test@phpunit.com',
            'my-password-encrypt',
            'Corantin',
            'Scri',
            true
        );

        $expectedResult = $this->createResultTemplate($customer);

        $templateManager = new TemplateManager(getenv('URL_LINK_MY_ACCOUNT'));
        $result = $templateManager->getTemplateComputed($this->createTemplate(), ['customer' => $customer]);

        $this->assertEquals($expectedResult, $result);
    }

    private function createTemplate(): Template
    {
        return new Template(
        1,
        '[customer:first_name] [customer:last_name] Votre compte a bien été crée',
        "
Bonjour [customer:first_name],

Merci d'avoir créé votre compte sur notre site vous pouvez maintenant accéder à votre espace en cliquant ici:
[link:my-account]

Bien cordialement,
");
    }

    private function createResultTemplate(Customer $customer): Template
    {
        $expectedSubject = <<<MSG
        Corantin Scri Votre compte a bien été crée
        MSG;

        $expectedMessage = <<<MSG

        Bonjour Corantin,

        Merci d'avoir créé votre compte sur notre site vous pouvez maintenant accéder à votre espace en cliquant ici:
        http://localhost/my-account/{$customer->getId()}

        Bien cordialement,

        MSG;

        return new Template(1, $expectedSubject, $expectedMessage);
    }
}
