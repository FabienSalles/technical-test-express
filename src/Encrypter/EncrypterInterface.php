<?php declare(strict_types=1);

namespace App\Encrypter;

/**
 * Test/ReadOnly
 */
interface EncrypterInterface
{
    public function encrypt(string $value, string $key) : string;
    public function decrypt(string $value, string $key) : string;
}
