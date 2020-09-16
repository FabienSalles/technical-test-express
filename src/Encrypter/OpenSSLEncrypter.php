<?php declare(strict_types=1);

namespace App\Encrypter;

/**
 * Test/ReadOnly
 */
final class OpenSSLEncrypter implements EncrypterInterface
{
    private const ENCRYPTION_METHOD = 'AES-256-CBC';
    private const ENCRYPTION_IV_LENGTH = 16;
    /** @var string */
    private $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    public function encrypt(string $value, string $key) : string
    {
        $iv = openssl_random_pseudo_bytes(self::ENCRYPTION_IV_LENGTH);
        if (!$iv) {
            throw new \RuntimeException(
                sprintf(
                    'Error while generating IV randam pseudo bytes : %s.',
                    openssl_error_string()
                )
            );
        }

        $token = openssl_encrypt(
            $value,
            self::ENCRYPTION_METHOD,
            $this->secret . $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return base64_encode($iv.$token);
    }

    public function decrypt(string $value, string $key) : string
    {
        $value = base64_decode($value);
        if (false === $value) {
            throw new \RuntimeException('Could not base64_decode encrypted payload.');
        }

        $iv = substr($value, 0, self::ENCRYPTION_IV_LENGTH);
        $encodedData = substr($value, self::ENCRYPTION_IV_LENGTH);

        $result = openssl_decrypt(
            $encodedData,
            self::ENCRYPTION_METHOD,
            $this->secret . $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        if (!$result) {
            throw new \RuntimeException(sprintf(
                'Could not decrypt value : %s.',
                openssl_error_string()
            ));
        }

        return $result;
    }
}
