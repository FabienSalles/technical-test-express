<?php declare(strict_types=1);

namespace App\Provider;

interface MailerProviderInterface
{
    public function sendEmail(string $templateId, string $destination, array $data);
}
