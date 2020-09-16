<?php declare(strict_types=1);

namespace App\Model\Api\Common;

class RegisterResult
{
    private string $customerId;
    private string $email;

    public function __construct(string $customerId, string $email)
    {
        $this->customerId = $customerId;
        $this->email = $email;
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
