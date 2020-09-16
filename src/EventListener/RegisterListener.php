<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Event\CustomerRegistered;
use App\Provider\MailerProviderInterface;
use App\Repository\CustomerRepositoryInterface;

final class RegisterListener
{
    private MailerProviderInterface $mailerProvider;
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(
        MailerProviderInterface $mailerProvider,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->mailerProvider = $mailerProvider;
        $this->customerRepository = $customerRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CustomerRegistered::class => [
                'onCustomerRegistered',
            ],
        ];
    }

    public function onCustomerRegistered(CustomerRegistered $event): void
    {
        file_put_contents(
            sprintf('register_%s.log', date('Y-m-d_His')),
            serialize($event)
        );

        $customer = $this->customerRepository->get($event->getCustomerId());
        $this->mailerProvider->sendEmail('confirmation_001', $customer->getEmail(), ['customer' => $customer]);
    }
}
