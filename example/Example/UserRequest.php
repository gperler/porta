<?php

declare(strict_types = 1);

namespace Example;

class UserRequest implements \JsonSerializable
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $gender;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $resetCode;

    /**
     * @var bool
     */
    protected $blocked;

    /**
     * @var bool
     */
    protected $technicalUser;

    /**
     * @var string
     */
    protected $lastPasswordChange;

    /**
     * @var string
     */
    protected $jwsToken;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $timezone;

    /**
     * @param string|null $id
     * 
     * @return void
     */
    public function setId(?string $id)
    {
        $this->id = $id;
    }

    /**
     * 
     * @return string|null
     */
    public function getId() : ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $gender
     * 
     * @return void
     */
    public function setGender(?string $gender)
    {
        $this->gender = $gender;
    }

    /**
     * 
     * @return string|null
     */
    public function getGender() : ?string
    {
        return $this->gender;
    }

    /**
     * @param string|null $firstName
     * 
     * @return void
     */
    public function setFirstName(?string $firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * 
     * @return string|null
     */
    public function getFirstName() : ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $lastName
     * 
     * @return void
     */
    public function setLastName(?string $lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * 
     * @return string|null
     */
    public function getLastName() : ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $email
     * 
     * @return void
     */
    public function setEmail(?string $email)
    {
        $this->email = $email;
    }

    /**
     * 
     * @return string|null
     */
    public function getEmail() : ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $password
     * 
     * @return void
     */
    public function setPassword(?string $password)
    {
        $this->password = $password;
    }

    /**
     * 
     * @return string|null
     */
    public function getPassword() : ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $resetCode
     * 
     * @return void
     */
    public function setResetCode(?string $resetCode)
    {
        $this->resetCode = $resetCode;
    }

    /**
     * 
     * @return string|null
     */
    public function getResetCode() : ?string
    {
        return $this->resetCode;
    }

    /**
     * @param bool|null $blocked
     * 
     * @return void
     */
    public function setBlocked(?bool $blocked)
    {
        $this->blocked = $blocked;
    }

    /**
     * 
     * @return bool|null
     */
    public function getBlocked() : ?bool
    {
        return $this->blocked;
    }

    /**
     * @param bool|null $technicalUser
     * 
     * @return void
     */
    public function setTechnicalUser(?bool $technicalUser)
    {
        $this->technicalUser = $technicalUser;
    }

    /**
     * 
     * @return bool|null
     */
    public function getTechnicalUser() : ?bool
    {
        return $this->technicalUser;
    }

    /**
     * @param string|null $lastPasswordChange
     * 
     * @return void
     */
    public function setLastPasswordChange(?string $lastPasswordChange)
    {
        $this->lastPasswordChange = $lastPasswordChange;
    }

    /**
     * 
     * @return string|null
     */
    public function getLastPasswordChange() : ?string
    {
        return $this->lastPasswordChange;
    }

    /**
     * @param string|null $jwsToken
     * 
     * @return void
     */
    public function setJwsToken(?string $jwsToken)
    {
        $this->jwsToken = $jwsToken;
    }

    /**
     * 
     * @return string|null
     */
    public function getJwsToken() : ?string
    {
        return $this->jwsToken;
    }

    /**
     * @param string|null $locale
     * 
     * @return void
     */
    public function setLocale(?string $locale)
    {
        $this->locale = $locale;
    }

    /**
     * 
     * @return string|null
     */
    public function getLocale() : ?string
    {
        return $this->locale;
    }

    /**
     * @param string|null $timezone
     * 
     * @return void
     */
    public function setTimezone(?string $timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * 
     * @return string|null
     */
    public function getTimezone() : ?string
    {
        return $this->timezone;
    }

    /**
     * @param array $array
     * 
     * @return void
     */
    public function fromArray(array $array)
    {
        foreach ($array as $propertyName => $propertyValue) {
            switch ($propertyName) {
                case "id":
                    $this->id = $propertyValue;
                    break;
                case "gender":
                    $this->gender = $propertyValue;
                    break;
                case "firstName":
                    $this->firstName = $propertyValue;
                    break;
                case "lastName":
                    $this->lastName = $propertyValue;
                    break;
                case "email":
                    $this->email = $propertyValue;
                    break;
                case "password":
                    $this->password = $propertyValue;
                    break;
                case "resetCode":
                    $this->resetCode = $propertyValue;
                    break;
                case "blocked":
                    $this->blocked = $propertyValue;
                    break;
                case "technicalUser":
                    $this->technicalUser = $propertyValue;
                    break;
                case "lastPasswordChange":
                    $this->lastPasswordChange = $propertyValue;
                    break;
                case "jwsToken":
                    $this->jwsToken = $propertyValue;
                    break;
                case "locale":
                    $this->locale = $propertyValue;
                    break;
                case "timezone":
                    $this->timezone = $propertyValue;
                    break;
            }
        }
    }

    /**
     * 
     * @return array
     */
    public function jsonSerialize() : array
    {
        return [
            "id" => $this->id,
            "gender" => $this->gender,
            "firstName" => $this->firstName,
            "lastName" => $this->lastName,
            "email" => $this->email,
            "password" => $this->password,
            "resetCode" => $this->resetCode,
            "blocked" => $this->blocked,
            "technicalUser" => $this->technicalUser,
            "lastPasswordChange" => $this->lastPasswordChange,
            "jwsToken" => $this->jwsToken,
            "locale" => $this->locale,
            "timezone" => $this->timezone,
        ];
    }
}