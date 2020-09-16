<?php

namespace App\Tests\Unit\EventListener;

use App\Entity\Customer;
use App\Event\CustomerRegistered;
use App\EventListener\RegisterListener;
use App\Provider\MailerProviderInterface;
use App\Repository\CustomerRepositoryInterface;
use App\Utils\Clock\ClockInterface;
use PHPUnit\Framework\TestCase;

final class RegisterListenerTest extends TestCase
{
    private $clock;

    public function setUp(): void
    {
        $now = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-04-06 23:59:59');
        $this->clock = $this->createMock(ClockInterface::class);
        $this->clock->method('now')->willReturn($now);
    }

    public function testRegisterCustomer(): void
    {
        $mailerProvider = $this->createMock(MailerProviderInterface::class);
        $customerRepository = $this->createMock(CustomerRepositoryInterface::class);

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
        $customerRegistered = new CustomerRegistered(
            $customer->getId(),
            $customer->getRegisteredAt(),
            $customer->getGender(),
            $customer->getEmail(),
            $customer->getFirstName(),
            $customer->getLastName(),
            $customer->isOptinNewsletter(),
            'my-offer-id'
        );

        $customerRepository
            ->expects($this->once())
            ->method('get')
            ->with($customer->getId())
            ->willReturn($customer);

        $mailerProvider
            ->expects($this->once())
            ->method('sendEmail')
            ->with('confirmation_001', $customer->getEmail(), ['customer' => $customer]);

        $listener = new RegisterListener($mailerProvider, $customerRepository);

        $listener->onCustomerRegistered($customerRegistered);
    }
}