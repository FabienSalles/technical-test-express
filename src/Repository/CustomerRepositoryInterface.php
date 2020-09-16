<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Customer;
use App\Exception\Customer\CustomerNotFound;

interface CustomerRepositoryInterface
{
    public function save(Customer $customer): void;
    /** @throws CustomerNotFound */
    public function get(string $id): Customer;
    /** @throws CustomerNotFound */
    public function getByEmail(string $email): Customer;
    public function exists(string $id): bool;
}
