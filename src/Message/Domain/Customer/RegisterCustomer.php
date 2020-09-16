<?php declare(strict_types=1);

namespace App\Message\Domain\Customer;

final class RegisterCustomer
{
    /** @var string */
    private $customerId;
    /** @var \DateTimeImmutable */
    private $registeredAt;
    /** @var string */
    private $gender;
    /** @var string */
    private $email;
    /** @var string|null */
    private $password;
    /** @var string */
    private $firstName;
    /** @var string */
    private $lastName;
    /** @var bool */
    private $optinNewsletter;
    /** @var string */
    private $offerId;

    public function __construct(
        string $customerId,
        \DateTimeImmutable $registeredAt,
        string $gender,
        string $email,
        ?string $password,
        string $firstName,
        string $lastName,
        bool $optinNewsletter,
        string $offerId
    ) {
        $this->customerId = $customerId;
        $this->registeredAt = $registeredAt;
        $this->gender = $gender;
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->optinNewsletter = $optinNewsletter;
        $this->offerId = $offerId;
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function getRegisteredAt(): \DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function isOptinNewsletter(): bool
    {
        return $this->optinNewsletter;
    }

    public function getOfferId(): string
    {
        return $this->offerId;
    }
}
