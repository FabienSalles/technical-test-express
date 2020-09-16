<?php

declare(strict_types=1);

namespace App\Tests\Unit\MessageHandler\Domain\Customer;

use App\Encrypter\EncrypterInterface;
use App\Entity\Customer;
use App\Event\CustomerRegistered;
use App\Exception\Customer\CustomerNotFound;
use App\Message\Domain\Customer\RegisterCustomer;
use App\MessageHandler\Domain\Customer\RegisterCustomerHandler;
use App\Repository\CustomerRepositoryInterface;
use App\Utils\Clock\ClockInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class RegisterCustomerHandlerTest extends TestCase
{
    /** @var EncrypterInterface */
    private $encrypter;
    /** @var \DateTimeImmutable */
    private $clock;

    public function setUp(): void
    {
        $this->encrypter = $this->createMock(EncrypterInterface::class);
        $now = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-04-06 23:59:59');
        $this->clock = $this->createMock(ClockInterface::class);
        $this->clock->method('now')->willReturn($now);
    }

    public function testRegisterNewCustomer(): void
    {
        $customerId = Uuid::uuid4()->toString();

        $registerProviderMessage = new RegisterCustomer(
            $customerId,
            $this->clock->now(),
            'male',
            'corantin.scri@unit.test',
            'my-password-encrypted',
            'Corantin',
            'Scri',
            true,
            'my-offer-id'
        );

        $expectedCustomerSaved = new Customer(
            $customerId,
            $this->clock->now(),
            'male',
            'corantin.scri@unit.test',
            'my-password-encrypted',
            'Corantin',
            'Scri',
            true
        );

        $customerRepository = $this->createMock(CustomerRepositoryInterface::class);
        $customerRepository
            ->expects($this->once())
            ->method('getByEmail')
            ->with('corantin.scri@unit.test')
            ->willThrowException(CustomerNotFound::withEmail('corantin.scri@unit.test'));
        $customerRepository
            ->expects($this->once())
            ->method('save')
            ->with($expectedCustomerSaved);

        $expectedEvent = new CustomerRegistered(
            $customerId,
            $this->clock->now(),
            'male',
            'corantin.scri@unit.test',
            'Corantin',
            'Scri',
            true,
            'my-offer-id'
        );

        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $dispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($expectedEvent);

        $handler = new RegisterCustomerHandler(
            $customerRepository,
            $dispatcher,
            $this->encrypter
        );

        $handler->__invoke($registerProviderMessage);
    }
}
