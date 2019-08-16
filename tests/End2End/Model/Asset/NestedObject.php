<?php

declare(strict_types=1);

namespace Synatos\PortaTest\End2End\Model\Asset;

use Synatos\Porta\Model\ArraySerializableModel;
use Synatos\Porta\Model\ModelProperty;

class NestedObject extends ArraySerializableModel
{

    /**
     * @var string
     */
    protected $name;


    /**
     * NestedObject constructor.
     */
    public function __construct()
    {
        parent::__construct([
            new ModelProperty("name")
        ]);
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


}