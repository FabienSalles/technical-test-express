<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 *
 * @ApiResource(
 *     itemOperations={
 *          "get"={
 *              "path"="/v1/customer/{id}",
 *              "swagger_context"={
 *                 "tags"={"Customer"},
 *                 "summary"="Get a Customer resource by Id."
 *             }
 *          }
 *     },
 *     collectionOperations={}
 * )
 */
class Customer
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="guid")
     *
     * @ApiProperty(swaggerContext={"example"="b79a3c73-203a-4d0c-b064-edb884a1abce"})
     */
    private $id;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable")
     *
     * @ApiProperty(swaggerContext={"example"="2019-03-27T15:47:29.047Z"})
     */
    private $registeredAt;

    /**
     * @var string
     *
     * @ORM\Column(type="text", unique=true)
     *
     * @ApiProperty(swaggerContext={"example"="jeanne.martin@mail.com"})
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @ApiProperty(swaggerContext={"example"="ffc47266c221d2cafb9def4dbe2221ccada49af6ff669c438da29bc0b4f4ffce"})
     */
    private $emailHash;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     *
     * @ApiProperty(readable=false, swaggerContext={"example"="HIDDEN"})
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=12)
     *
     * @ApiProperty(swaggerContext={"example"="female"})
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @ApiProperty(swaggerContext={"example"="Jeanne"})
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @ApiProperty(swaggerContext={"example"="Martin"})
     */
    private $lastName;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     *
     * @ApiProperty(swaggerContext={"example"="true"})
     */
    private $optinNewsletter;

    public function __construct(
        string $id,
        \DateTimeImmutable $registeredAt,
        string $gender,
        string $email,
        ?string $password,
        string $firstName,
        string $lastName,
        bool $optinNewsletter
    ) {
        $this->id = $id;
        $this->registeredAt = $registeredAt;
        $this->gender = $gender;
        $this->email = $email;
        $this->emailHash = $this->encryptEmail($email);
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->optinNewsletter = $optinNewsletter;
    }

    private function encryptEmail(string $email) : string
    {
        return hash('sha256', $email);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Customer
    {
        $this->id = $id;
        return $this;
    }

    public function getRegisteredAt(): \DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeImmutable $registeredAt): Customer
    {
        $this->registeredAt = $registeredAt;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Customer
    {
        $this->email = $email;
        return $this;
    }

    public function getEmailHash(): string
    {
        return $this->emailHash;
    }

    public function setEmailHash(string $emailHash): Customer
    {
        $this->emailHash = $emailHash;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): Customer
    {
        $this->password = $password;
        return $this;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): Customer
    {
        $this->gender = $gender;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): Customer
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): Customer
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function isOptinNewsletter(): bool
    {
        return $this->optinNewsletter;
    }

    public function setOptinNewsletter(bool $optinNewsletter): Customer
    {
        $this->optinNewsletter = $optinNewsletter;
        return $this;
    }
}
