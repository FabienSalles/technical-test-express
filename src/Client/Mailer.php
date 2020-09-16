<?php

declare(strict_types=1);

namespace App\Client;

/**
 * Test/ReadOnly.
 */
final class Mailer implements MailerInterface
{
    private string $cacheFilename;
    private string $rootUrl;
    private array $cache = [];

    public function __construct(string $cacheFilename, string $rootUrl)
    {
        $this->cacheFilename = $cacheFilename;
        $this->rootUrl = $rootUrl;
        $this->load();
    }

    public function sendEmail(string $templateId, string $destination, array $data)
    {
        $this->cache[] = [
            'root_url' => $this->rootUrl,
            'templateId' => $templateId,
            'destination' => $destination,
            'data' => $data,
        ];
        $this->serialize();
    }

    private function serialize(): void
    {
        file_put_contents($this->cacheFilename, serialize($this->cache));
    }

    private function load(): void
    {
        if (file_exists($this->cacheFilename)) {
            $content = file_get_contents($this->cacheFilename);
            $this->cache = unserialize($content, ['allowed_classes' => true]);
        }
    }
}
