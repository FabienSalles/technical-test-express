<?php declare(strict_types=1);

namespace App\MessageHandler\Domain\Customer;

use App\Encrypter\EncrypterInterface;
use App\Entity\Customer;
use App\Event\CustomerRegistered;
use App\Exception\Customer\CustomerNotFound;
use App\Message\Domain\Customer\RegisterCustomer;
use App\Repository\CustomerRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class RegisterCustomerHandler implements MessageHandlerInterface
{
    /** @var CustomerRepositoryInterface */
    private $customerRepository;
    /** @var EventDispatcherInterface */
    private $eventDispatcher;
    /** @var EncrypterInterface */
    private $encrypter;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        EventDispatcherInterface $eventDispatcher,
        EncrypterInterface $encrypter
    ) {
        $this->customerRepository = $customerRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->encrypter = $encrypter;
    }

    public function __invoke(RegisterCustomer $command): void
    {
        try {
            $customer = $this->customerRepository->getByEmail($command->getEmail());

            $customer->setRegisteredAt($command->getRegisteredAt());
            $customer->setGender($command->getGender());
            $customer->setEmail($command->getEmail());
            $customer->setFirstName($command->getFirstName());
            $customer->setLastName($command->getLastName());
            $customer->setOptinNewsletter($command->isOptinNewsletter());

            if (null !== $command->getPassword()) {
                $rawPassword = $this->encrypter->decrypt($command->getPassword(), $command->getCustomerId());
                $password = $this->encrypter->encrypt($rawPassword, $customer->getId());
                $customer->setPassword($password);
            }
        } catch (CustomerNotFound $e) {
            $customer = new Customer(
                $command->getCustomerId(),
                $command->getRegisteredAt(),
                $command->getGender(),
                $command->getEmail(),
                $command->getPassword(),
                $command->getFirstName(),
                $command->getLastName(),
                $command->isOptinNewsletter()
            );
        }

        $this->customerRepository->save($customer);

        $this->eventDispatcher->dispatch(new CustomerRegistered(
            $customer->getId(),
            $customer->getRegisteredAt(),
            $customer->getGender(),
            $customer->getEmail(),
            $customer->getFirstName(),
            $customer->getLastName(),
            $customer->isOptinNewsletter(),
            $command->getOfferId()
        ));
    }
}
