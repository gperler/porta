<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

class MediaType extends ArraySerializableModel
{

    /**
     * @var Schema|null
     */
    protected $schema;

    /**
     * @var mixed|null
     */
    protected $example;

    /**
     * @var Example[]|null
     */
    protected $examples;

    /**
     * @var Encoding[]|null
     */
    protected $encoding;


    /**
     * MediaType constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty("schema", ModelProperty::TYPE_OBJECT, function () {
                return new Schema();
            }),
            new ModelProperty("example"),
            new ModelProperty("examples", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, function () {
                return new Example();
            }),
            new ModelProperty("encoding", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, function () {
                return new Encoding();
            })
        ]);
    }


    /**
     * @return Schema|null
     */
    public function getSchema(): ?Schema
    {
        return $this->schema;
    }


    /**
     * @param Schema|null $schema
     */
    public function setSchema(?Schema $schema): void
    {
        $this->schema = $schema;
    }


    /**
     * @return mixed|null
     */
    public function getExample()
    {
        return $this->example;
    }


    /**
     * @param mixed|null $example
     */
    public function setExample($example = null): void
    {
        $this->example = $example;
    }


    /**
     * @return Example[]|null
     */
    public function getExamples(): ?array
    {
        return $this->examples;
    }


    /**
     * @param Example[]|null $examples
     */
    public function setExamples(?array $examples): void
    {
        $this->examples = $examples;
    }


    /**
     * @return Encoding[]|null
     */
    public function getEncoding(): ?array
    {
        return $this->encoding;
    }


    /**
     * @param Encoding[]|null $encoding
     */
    public function setEncoding(?array $encoding): void
    {
        $this->encoding = $encoding;
    }


}