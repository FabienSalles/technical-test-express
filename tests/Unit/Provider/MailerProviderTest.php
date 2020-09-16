<?php

namespace App\Tests\Unit\Provider;

use App\Client\MailerInterface;
use App\Entity\Customer;
use App\Provider\MailerProvider;
use App\Utils\Clock\ClockInterface;
use PHPUnit\Framework\TestCase;

final class MailerProviderTest extends TestCase
{
    private $clock;

    public function setUp(): void
    {
        $now = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-04-06 23:59:59');
        $this->clock = $this->createMock(ClockInterface::class);
        $this->clock->method('now')->willReturn($now);
    }

    public function testEmptyMail(): void
    {
        $client = $this->createMock(MailerInterface::class);
        $mailerProvider = new MailerProvider($client);
        $client->expects($this->once())
            ->method('sendEmail')
            ->with('', '', ['subject' => '', 'message' => '']);

        $mailerProvider->sendEmail('', '', []);
    }

    public function testConfirmMail(): void
    {
        $client = $this->createMock(MailerInterface::class);
        $mailerProvider = new MailerProvider($client);
        $templateId = 'confirmation_001';
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

        $expectedSubject = <<<MSG
        Corantin Scri Votre compte a bien été crée
        MSG;

        $expectedMessage = <<<MSG

        Bonjour Corantin,

        Merci d'avoir créé votre compte sur notre site vous pouvez maintenant accéder à votre espace en cliquant ici:
        http://localhost/my-account/{$customer->getId()}

        Bien cordialement,

        MSG;

        $client->expects($this->once())
            ->method('sendEmail')
            ->with($templateId, $customer->getEmail(), ['subject' => $expectedSubject, 'message' => $expectedMessage]);

        $mailerProvider->sendEmail($templateId, $customer->getEmail(), ['customer' => $customer]);
    }
}
