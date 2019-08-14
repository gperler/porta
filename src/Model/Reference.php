<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class Reference
{
    /**
     * @var string
     */
    private $reference;

    /**
     * @var string|null
     */
    private $remotePart;

    /**
     * @var string|null
     */
    private $localPart;


    /**
     * Reference constructor.
     *
     * @param string $reference
     */
    public function __construct(string $reference)
    {
        $this->reference = $reference;
        if ($reference === null) {
            return;
        }
        $positionOfHash = strpos($reference, '#');

        if ($positionOfHash === false) {
            $this->remotePart = $reference;
            return;
        }

        if ($positionOfHash === 0) {
            $this->localPart = substr($reference, 1);
            return;
        }
        $partList = explode("#", $reference);
        $this->remotePart = $partList[0];
        $this->localPart = $partList[1];
    }


    /**
     * @return bool
     */
    public function isLocal(): bool
    {
        return $this->remotePart === null;
    }


    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }


    /**
     * @return string
     */
    public function getRemotePart(): ?string
    {
        return $this->remotePart;
    }


    /**
     * @return string
     */
    public function getLocalPart(): ?string
    {
        return $this->localPart;
    }


    /**
     * @return array
     */
    public function getLocalPartPartList(): array
    {
        if ($this->localPart === null) {
            return [];
        }
        return explode("/", trim($this->localPart, '/'));
    }

    /**
     * @return string|null
     */
    public function getLastPartOfLocalPath(): ?string
    {
        $localPartList = $this->getLocalPartPartList();
        if (empty($localPartList)) {
            return null;
        }
        return $localPartList[count($localPartList) - 1];
    }
}