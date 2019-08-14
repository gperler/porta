<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class Info extends ArraySerializableModel
{


    /**
     * @var string|null
     */
    protected $title;

    /**
     * @var string|null
     */
    protected $description;

    /**
     * @var string|null
     */
    protected $termsOfService;

    /**
     * @var Contact|null
     */
    protected $contact;

    /**
     * @var License|null
     */
    protected $license;

    /**
     * @var string|null
     */
    protected $version;

    /**
     * Info constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty("title"),
            new ModelProperty("description"),
            new ModelProperty("termsOfService"),
            new ModelProperty("contact", ModelProperty::TYPE_OBJECT, static function () {
                return new Contact();
            }),
            new ModelProperty("license", ModelProperty::TYPE_OBJECT, static function () {
                return new License();
            }),
            new ModelProperty("version"),
        ]);
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getTermsOfService(): ?string
    {
        return $this->termsOfService;
    }

    /**
     * @param string|null $termsOfService
     */
    public function setTermsOfService(?string $termsOfService): void
    {
        $this->termsOfService = $termsOfService;
    }

    /**
     * @return Contact|null
     */
    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    /**
     * @param Contact|null $contact
     */
    public function setContact(?Contact $contact): void
    {
        $this->contact = $contact;
    }

    /**
     * @return License|null
     */
    public function getLicense(): ?License
    {
        return $this->license;
    }

    /**
     * @param License|null $license
     */
    public function setLicense(?License $license): void
    {
        $this->license = $license;
    }

    /**
     * @return string|null
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * @param string|null $version
     */
    public function setVersion(?string $version): void
    {
        $this->version = $version;
    }


}