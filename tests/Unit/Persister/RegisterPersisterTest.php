<?php declare(strict_types=1);

namespace App\Tests\Unit\Persister;

use App\Encrypter\EncrypterInterface;
use App\Entity\Customer;
use App\Message\Domain\Customer\RegisterCustomer;
use App\Model\Api\Common\RegisterResult;
use App\Persister\RegisterPersister;
use App\Repository\CustomerRepositoryInterface;
use App\Utils\Clock\ClockInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class RegisterPersisterTest extends TestCase
{
    private $messageBus;
    private $customerRepository;
    private $encrypter;
    private $clock;

    public function setUp(): void
    {
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->customerRepository = $this->createMock(CustomerRepositoryInterface::class);
        $this->encrypter = $this->createMock(EncrypterInterface::class);
        $now = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2019-04-06 23:59:59');
        $this->clock = $this->createMock(ClockInterface::class);
        $this->clock->method('now')->willReturn($now);
    }

    public function testRegister(): void
    {
        $data = new class($this->clock)
        {
            /** @var ClockInterface */
            private $clock;

            public function __construct(ClockInterface $clock)
            {
                $this->clock = $clock;
            }
            public function getId(): string
            {
                return 'my-customer-id';
            }
            public function getRegisteredAt(): \DateTimeImmutable
            {
                return $this->clock->now();
            }
            public function getEmail(): string
            {
                return 'test@phpunit.com';
            }
            public function getFirstName(): string
            {
                return 'Jeanne';
            }
            public function getLastName(): string
            {
                return 'Martin';
            }
            public function getGender(): string
            {
                return 'female';
            }
            public function getRawPassword(): string
            {
                return 'my-password';
            }
            public function isOptinNewsletter(): bool
            {
                return true;
            }
            public function getOfferId(): string
            {
                return 'my-offer-id';
            }
        };
        $customer = new Customer(
            'my-customer-id',
            $this->clock->now(),
            'female',
            'test@phpunit.com',
            'my-password-encrypt',
            'Jeanne',
            'Martin',
            true
        );

        $message = new RegisterCustomer(
            $data->getId(),
            $data->getRegisteredAt(),
            $data->getGender(),
            $data->getEmail(),
            $data->getRawPassword(),
            $data->getFirstName(),
            $data->getLastName(),
            $data->isOptinNewsletter(),
            $data->getOfferId()
        );

        $this->encrypter->expects($this->once())
            ->method('encrypt')
            ->with('my-password')
            ->willReturn('my-password-encrypt')
        ;

        $this->messageBus->expects($this->at(0))
            ->method('dispatch')
            ->with($this->callback(
                function (RegisterCustomer $content) {
                    return
                        $content->getRegisteredAt() === $this->clock->now() &&
                        $content->getGender() === 'female' &&
                        $content->getEmail() === 'test@phpunit.com' &&
                        $content->getPassword() === 'my-password-encrypt' &&
                        $content->getFirstName() === 'Jeanne' &&
                        $content->getLastName() === 'Martin' &&
                        $content->isOptinNewsletter() === true &&
                        $content->getOfferId() === 'my-offer-id';
                }
            ))
            ->willReturn(
                new Envelope($message)
            )
        ;
        $this->customerRepository->expects($this->once())
            ->method('getByEmail')
            ->willReturn($customer)
        ;

        $persister = new RegisterPersister(
            $this->messageBus,
            $this->encrypter,
            $this->customerRepository,
            $this->clock
        );
        $result = $persister->persist($data);

        $this->assertEquals(
            new RegisterResult(
                'my-customer-id',
                'test@phpunit.com'
            ),
            $result
        );
    }

    public function testRemove(): void
    {
        $persister = new RegisterPersister(
            $this->messageBus,
            $this->encrypter,
            $this->customerRepository,
            $this->clock
        );

        $this->expectException(\RuntimeException::class);
        $persister->remove([]);
    }
}
