<?php declare(strict_types=1);

namespace App\Persister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Encrypter\EncrypterInterface;
use App\Message\Domain\Customer\RegisterCustomer;
use App\Model\Api\Common\Register;
use App\Model\Api\Common\RegisterResult;
use App\Utils\Clock\ClockInterface;
use App\Repository\CustomerRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class RegisterPersister implements DataPersisterInterface
{
    use HandleTrait;

    private EncrypterInterface $encrypter;
    private ClockInterface $clock;
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(
        MessageBusInterface $messageBus,
        EncrypterInterface $encrypter,
        CustomerRepositoryInterface $customerRepository,
        ClockInterface $clock
    ) {
        $this->messageBus = $messageBus;
        $this->encrypter = $encrypter;
        $this->customerRepository = $customerRepository;
        $this->clock = $clock;
    }

    public function supports($data): bool
    {
        return $data instanceof Register;
    }

    /** @param Register $data */
    public function persist($data): RegisterResult
    {
        $id = Uuid::uuid4()->toString();
        $this->messageBus->dispatch(
            new RegisterCustomer(
                $id,
                $this->clock->now(),
                $data->getGender(),
                $data->getEmail(),
                $this->encrypter->encrypt($data->getRawPassword(), $id),
                $data->getFirstName(),
                $data->getLastName(),
                $data->isOptinNewsletter(),
                $data->getOfferId()
            )
        );

        $customer = $this->customerRepository->getByEmail($data->getEmail());

        return new RegisterResult(
            $customer->getId(),
            $customer->getEmail()
        );
    }

    public function remove($data): void
    {
        throw new \RuntimeException('Not implemented.');
    }
}
