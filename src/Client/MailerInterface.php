<?php declare(strict_types=1);

namespace App\Client;

/**
 * Test/ReadOnly
 */
interface MailerInterface
{
    public function sendEmail(string $templateId, string $destination, array $data);
}
