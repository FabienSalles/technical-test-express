<?php declare(strict_types=1);

namespace App\Utils\Clock;

/**
 * Test/ReadOnly
 */
interface ClockInterface
{
    public function now(): \DateTimeImmutable;
    public function create(string $datetime): \DateTimeImmutable;
}
