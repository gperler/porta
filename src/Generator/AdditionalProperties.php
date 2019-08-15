<?php

declare(strict_types=1);

namespace Synatos\Porta\Generator;

use Civis\Common\StringUtil;
use Synatos\Porta\Model\Schema;

class AdditionalProperties
{

    /**
     * @var Schema|null
     */
    private $schema;

    /**
     * @var string
     */
    private $type;

    /**
     * AdditionalProperties constructor.
     * @param Schema|null $schema
     * @param string|null $type
     */
    public function __construct(Schema $schema = null, string $type = null)
    {
        $this->schema = $schema;
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isArray(): bool
    {
        if ($this->type === null) {
            return false;
        }
        return StringUtil::endsWith($this->type, "[]");
    }

}