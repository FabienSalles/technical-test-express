<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Customer;
use App\Exception\Customer\CustomerNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class CustomerRepository extends ServiceEntityRepository implements CustomerRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function save(Customer $customer): void
    {
        $this->_em->persist($customer);
        $this->_em->flush();
    }

    public function get(string $id): Customer
    {
        /** @var Customer $customer */
        $customer = $this->_em->find(Customer::class, $id);

        if (!$customer instanceof Customer) {
            throw CustomerNotFound::withId($id);
        }

        return $customer;
    }

    public function getByEmail(string $email) : Customer
    {
        /** @var Customer $customer */
        $customer = $this->findOneBy(
            [
                'email' => $email
            ]
        );

        if (!$customer instanceof Customer) {
            throw CustomerNotFound::withEmail($email);
        }

        return $customer;
    }

    public function exists(string $id): bool
    {
        return $this->count([
                'id' => $id,
            ]) > 0;
    }
}
