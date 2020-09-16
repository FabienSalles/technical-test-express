<?php declare(strict_types=1);

namespace App\Utils\Clock;

/**
 * Test/ReadOnly
 */
final class Clock implements ClockInterface
{
    private const NOW = 'now';

    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now');
    }

    public function create(string $datetime): \DateTimeImmutable
    {
        if (self::NOW === $datetime) {
            return $this->now();
        }
        return $this->now()->modify($datetime);
    }
}
