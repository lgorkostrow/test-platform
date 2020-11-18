<?php

declare(strict_types=1);

namespace App\App\Http\Request\User;

use Symfony\Component\Validator\Constraints as Assert;

class SignUpRequest
{
    /**
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\Email
     * @Assert\NotBlank
     * @Assert\Length(max="255")
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\NotBlank
     * @Assert\Length(min="6", max="16")
     */
    private $password;

    /**
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\NotBlank
     * @Assert\Length(max="255")
     */
    private $firstName;

    /**
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\NotBlank
     * @Assert\Length(max="255")
     */
    private $lastName;

    /**
     * @var string|null
     *
     * @Assert\Type("string")
     * @Assert\Length(max="255")
     */
    private $biography;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return SignUpRequest
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return SignUpRequest
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     * @return SignUpRequest
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     * @return SignUpRequest
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * @param mixed $biography
     * @return SignUpRequest
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;

        return $this;
    }
}
