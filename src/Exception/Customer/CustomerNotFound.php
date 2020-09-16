<?php declare(strict_types=1);

namespace App\Exception\Customer;

final class CustomerNotFound extends \Exception
{
    public static function withId(string $id): self
    {
        return new self(
            sprintf('Customer not found for id: %s.', $id)
        );
    }

    public static function withEmail(string $email): self
    {
        return new self(
            sprintf('Customer not found for email: %s.', $email)
        );
    }
}
