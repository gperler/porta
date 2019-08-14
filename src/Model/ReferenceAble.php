<?php


namespace Synatos\Porta\Model;


trait ReferenceAble
{

    /**
     * @var string|null
     */
    protected $ref;


    public function isReference(): bool
    {
        return $this->ref !== null;
    }


    /**
     * @return string|null
     */
    public function getRef(): ?string
    {
        return $this->ref;
    }


    /**
     * @return Reference
     */
    public function getReference(): Reference
    {
        return new Reference($this->ref);
    }


    /**
     * @param string|null $ref
     */
    public function setRef(?string $ref): void
    {
        $this->ref = $ref;
    }


}