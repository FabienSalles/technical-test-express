<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Event\CustomerRegistered;

final class RegisterListener
{
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
    }
}
