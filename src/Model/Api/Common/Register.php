<?php declare(strict_types=1);

namespace App\Model\Api\Common;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     itemOperations={},
 *     collectionOperations={
 *         "register"={
 *             "path"="/register",
 *             "method"="post",
 *             "swagger_context"={
 *                 "summary"="Register a new Customer.",
 *                 "tags"={"Register"},
 *                 "parameters"={
 *                     {
 *                         "in"="body",
 *                         "name"="body",
 *                         "required"=true,
 *                         "description"="The new Customer resource",
 *                         "schema"={
 *                             "$ref"="#/definitions/Register"
 *                         }
 *                     }
 *                 },
 *                 "responses"={
 *                     "201"={
 *                         "description"="Customer resource created",
 *                         "schema"={
 *                             "$ref"="#/definitions/RegisterResult"
 *                         }
 *                     },
 *                     "400"={
 *                         "description"="Invalid input"
 *                     },
 *                     "404"={
 *                         "description"="Resource not found"
 *                     }
 *                 }
 *             }
 *         }
 *     },
 *     output="App\Model\Api\Common\RegisterResult"
 * )
 */
final class Register
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @ApiProperty(swaggerContext={"example"="name@mail.com"})
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Choice({"male", "female"})
     *
     * @ApiProperty(swaggerContext={"example"="female"})
     */
    private $gender;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @Assert\Length(
     *     min=1,
     *     max=30,
     *     maxMessage="first_name.max.length"
     * )
     *
     * @ApiProperty(swaggerContext={"example"="Jeanne"})
     */
    private $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @Assert\Length(
     *     min=1,
     *     max=30,
     *     maxMessage="last_name.max.length"
     * )
     *
     * @ApiProperty(swaggerContext={"example"="Martin"})
     */
    private $lastName;

    /**
     * @var bool
     *
     * @Assert\Type("bool")
     *
     * @ApiProperty(swaggerContext={"example"=true})
     */
    private $optinNewsletter;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type("string")
     *
     * @ApiProperty(readable=false, swaggerContext={"example"="my-password"})
     */
    private $rawPassword;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Type("string")
     *
     * @ApiProperty(swaggerContext={"example"="MY-OFFER-ID"})
     */
    private $offerId;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function isOptinNewsletter(): bool
    {
        return $this->optinNewsletter;
    }

    public function setOptinNewsletter(bool $optinNewsletter): void
    {
        $this->optinNewsletter = $optinNewsletter;
    }

    public function getRawPassword(): string
    {
        return $this->rawPassword;
    }

    public function setRawPassword(string $rawPassword): void
    {
        $this->rawPassword = $rawPassword;
    }

    public function getOfferId(): string
    {
        return $this->offerId;
    }

    public function setOfferId(string $offerId): void
    {
        $this->offerId = $offerId;
    }
}
