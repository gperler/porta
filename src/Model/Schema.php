<?php

declare(strict_types=1);

namespace Synatos\Porta\Model;

use Synatos\Porta\Helper\ArrayHelper;

class Schema extends ArraySerializableModel
{

    use ReferenceAble;

    const TYPE_STRING = "string";

    const TYPE_NUMBER = "number";

    const TYPE_INTEGER = "integer";

    const TYPE_BOOLEAN = "boolean";

    const TYPE_ARRAY = "array";

    const TYPE_OBJECT = "object";

    const FORMAT_NUMBER_FLOAT = "float";

    const FORMAT_NUMBER_DOUBLE = "double";

    const FORMAT_INTEGER_INT_32 = "int32";

    const FORMAT_INTEGER_INT_64 = "int64";

    /**
     * https://tools.ietf.org/html/rfc3339#section-5.6
     */
    const FORMAT_STRING_DATE = "date";

    /**
     * https://tools.ietf.org/html/rfc3339#section-5.6
     */
    const FORMAT_STRING_DATE_TIME = "date-time";

    const FORMAT_STRING_PASSWORD = "password";

    const FORMAT_STRING_BYTE = "byte";

    const FORMAT_STRING_BINARY = "binary";

    /**
     *
     */
    const FORMAT_STRING_EMAIL = "email";

    /**
     * @var bool Specifies that a schema is deprecated and SHOULD be transitioned out of usage. Default value is false.
     */
    protected $deprecated;

    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $format;

    /**
     * @var bool|null Allows sending a null value for the defined schema. Default value is false
     */
    protected $nullable;

    /**
     * @var array|null An instance validates successfully against this keyword if its value is equal to one of the elements in this keyword's array value.
     */
    protected $enum;

    /**
     * @var mixed|null The default value represents what would be assumed by the consumer of the input as the value of the schema if one is not provided.
     */
    protected $default;

    /**
     * @var string|null A title will preferably be short
     */
    protected $title;

    /**
     * @var string|null description will provide explanation about the purpose of the instance described by this schema.
     */
    protected $description;

    /**
     * @var mixed|null
     */
    protected $example;


    // Number

    /**
     * @var int|null Use the minimum and maximum keywords to specify the range of possible values
     */
    protected $minimum;

    /**
     * @var int|null Use the minimum and maximum keywords to specify the range of possible values
     */
    protected $maximum;

    /**
     * @var bool|null The word “exclusive” in exclusiveMinimum and exclusiveMaximum means the corresponding boundary is excluded
     */
    protected $exclusiveMaximum;

    /**
     * @var bool|null The word “exclusive” in exclusiveMinimum and exclusiveMaximum means the corresponding boundary is excluded
     */
    protected $exclusiveMinimum;

    /**
     * @var float|null Use the multipleOf keyword to specify that a number must be the multiple of another number
     */
    protected $multipleOf;

    // String


    /**
     * @var int|null String length can be restricted using minLength and maxLength
     */
    protected $minLength;

    /**
     * @var int|null String length can be restricted using minLength and maxLength
     */
    protected $maxLength;

    /**
     * @var string|null The pattern keyword lets you define a regular expression template for the string value
     */
    protected $pattern;


    // array

    /**
     * @var Schema|null
     */
    protected $items;

    /**
     * @var int|null You can define the minimum and maximum length of an array.
     */
    protected $minItems;

    /**
     * @var int|null You can define the minimum and maximum length of an array.
     */
    protected $maxItems;

    /**
     * @var bool|null You can use uniqueItems: true to specify that all items in the array must be unique:
     */
    protected $uniqueItems;


    // object

    /**
     * @var Schema[]|null The properties keyword is used to define the object properties – you need to list the property names and specify a schema for each property.
     */
    protected $properties;

    /**
     * @var string[]|null By default, all object properties are optional. You can specify the required properties in the required list
     */
    protected $required;

    /**
     * @var int|null An object instance is valid against "minProperties" if its number of properties is greater than, or equal to, the value of this keyword.
     */
    protected $minProperties;

    /**
     * @var int|null An object instance is valid against "maxProperties" if its number of properties is less than, or equal to, the value of this keyword.
     */
    protected $maxProperties;


    // schema composition/inheritance

    /**
     * @var Schema[]|null An instance validates successfully against this keyword if it validates successfully against all schemas defined by this keyword's value.
     */
    protected $allOf;

    /**
     * @var Schema[]|null An instance validates successfully against this keyword if it validates successfully against exactly one schema defined by this keyword's value.
     */
    protected $oneOf;

    /**
     * @var Schema[]|null An instance validates successfully against this keyword if it validates successfully against at least one schema defined by this keyword's value.
     */
    protected $anyOf;

    /**
     * @var Schema|null An instance is valid against this keyword if it fails to validate successfully against the schema defined by this keyword.
     */
    protected $not;

    /**
     * @var Discriminator|null
     */
    protected $discriminator;

    /**
     * @var Schema|bool
     */
    protected $additionalProperties;


    /**
     * Schema constructor.
     */
    public function __construct()
    {
        $schemaFactory = function () {
            return new Schema();
        };

        parent::__construct([
            new ModelProperty('$ref', ModelProperty::TYPE_BUILD_IN, null, null, 'ref'),
            new ModelProperty("deprecated", ModelProperty::TYPE_BUILD_IN, null, false),
            new ModelProperty("type"),
            new ModelProperty("format"),
            new ModelProperty("nullable", ModelProperty::TYPE_BUILD_IN, null, false),
            new ModelProperty("enum"),
            new ModelProperty("default"),
            new ModelProperty("title"),
            new ModelProperty("description"),
            new ModelProperty("example"),
            new ModelProperty("minimum"),
            new ModelProperty("maximum"),
            new ModelProperty("exclusiveMaximum"),
            new ModelProperty("exclusiveMinimum"),
            new ModelProperty("multipleOf"),
            new ModelProperty("minLength"),
            new ModelProperty("maxLength"),
            new ModelProperty("pattern"),
            new ModelProperty("items", ModelProperty::TYPE_OBJECT, $schemaFactory),
            new ModelProperty("minItems"),
            new ModelProperty("maxItems"),
            new ModelProperty("uniqueItems"),
            new ModelProperty("properties", ModelProperty::TYPE_ASSOCIATIVE_ARRAY, $schemaFactory),
            new ModelProperty("required"),
            new ModelProperty("minProperties"),
            new ModelProperty("maxProperties"),
            new ModelProperty("allOf", ModelProperty::TYPE_ARRAY, $schemaFactory),
            new ModelProperty("oneOf", ModelProperty::TYPE_ARRAY, $schemaFactory),
            new ModelProperty("anyOf", ModelProperty::TYPE_ARRAY, $schemaFactory),
            new ModelProperty("not", ModelProperty::TYPE_OBJECT, $schemaFactory),
            new ModelProperty("discriminator", ModelProperty::TYPE_OBJECT, function () {
                return new Discriminator();
            }),
        ]);
    }


    /**
     * @param array|null $data
     */
    public function fromArray(array $data = null)
    {
        parent::fromArray($data);

        $additionalProperties = isset($data["additionalProperties"]) ? $data["additionalProperties"] : null;

        if (is_array($additionalProperties)) {
            $this->additionalProperties = new Schema();
            $this->additionalProperties->fromArray($additionalProperties);
        } else {
            $this->additionalProperties = $additionalProperties;
        }
    }


    /**
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        $array = parent::jsonSerialize();
        $array["additionalProperties"] = ($this->additionalProperties instanceof Schema) ? $this->additionalProperties->jsonSerialize() : $this->additionalProperties;
        return ArrayHelper::filterEmpty($array);
    }


    /**
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return $this->deprecated;
    }


    /**
     * @return bool
     */
    public function isAnyType(): bool
    {
        return $this->ref === null && $this->type === null;
    }


    /**
     * @param bool $deprecated
     */
    public function setDeprecated(bool $deprecated): void
    {
        $this->deprecated = $deprecated;
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
    public function isBoolean(): bool
    {
        return $this->type === self::TYPE_BOOLEAN;
    }


    /**
     * @return bool
     */
    public function isInteger(): bool
    {
        return $this->type === self::TYPE_INTEGER;
    }


    /**
     * @return bool
     */
    public function isNumber(): bool
    {
        return $this->type === self::TYPE_NUMBER;
    }


    /**
     * @return bool
     */
    public function isString(): bool
    {
        return $this->type === self::TYPE_STRING;
    }


    /**
     * @return bool
     */
    public function isArray(): bool
    {
        return $this->type === self::TYPE_ARRAY;
    }


    /**
     * @return bool
     */
    public function isObject(): bool
    {
        return $this->type === self::TYPE_OBJECT;
    }


    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }


    /**
     * @return string|null
     */
    public function getFormat(): ?string
    {
        return $this->format;
    }


    /**
     * @param string|null $format
     */
    public function setFormat(?string $format): void
    {
        $this->format = $format;
    }


    /**
     * @return bool
     */
    public function isNullable(): ?bool
    {
        return $this->nullable;
    }


    /**
     * @param bool $nullable
     */
    public function setNullable(?bool $nullable): void
    {
        $this->nullable = $nullable;
    }


    /**
     * @return array|null
     */
    public function getEnum(): ?array
    {
        return $this->enum;
    }


    /**
     * @param array|null $enum
     */
    public function setEnum(?array $enum): void
    {
        $this->enum = $enum;
    }


    /**
     * @return mixed|null
     */
    public function getDefault()
    {
        return $this->default;
    }


    /**
     * @param mixed|null $default
     */
    public function setDefault($default): void
    {
        $this->default = $default;
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
     * @return mixed|null
     */
    public function getExample()
    {
        return $this->example;
    }


    /**
     * @param mixed|null $example
     */
    public function setExample($example): void
    {
        $this->example = $example;
    }


    /**
     * @return int|null
     */
    public function getMinimum(): ?int
    {
        return $this->minimum;
    }


    /**
     * @param int|null $minimum
     */
    public function setMinimum(?int $minimum): void
    {
        $this->minimum = $minimum;
    }


    /**
     * @return int|null
     */
    public function getMaximum(): ?int
    {
        return $this->maximum;
    }


    /**
     * @param int|null $maximum
     */
    public function setMaximum(?int $maximum): void
    {
        $this->maximum = $maximum;
    }


    /**
     * @return bool|null
     */
    public function getExclusiveMaximum(): ?bool
    {
        return $this->exclusiveMaximum;
    }


    /**
     * @param bool|null $exclusiveMaximum
     */
    public function setExclusiveMaximum(?bool $exclusiveMaximum): void
    {
        $this->exclusiveMaximum = $exclusiveMaximum;
    }


    /**
     * @return bool|null
     */
    public function getExclusiveMinimum(): ?bool
    {
        return $this->exclusiveMinimum;
    }


    /**
     * @param bool|null $exclusiveMinimum
     */
    public function setExclusiveMinimum(?bool $exclusiveMinimum): void
    {
        $this->exclusiveMinimum = $exclusiveMinimum;
    }


    /**
     * @return float|null
     */
    public function getMultipleOf(): ?float
    {
        return $this->multipleOf;
    }


    /**
     * @param float|null $multipleOf
     */
    public function setMultipleOf(?float $multipleOf): void
    {
        $this->multipleOf = $multipleOf;
    }


    /**
     * @return int|null
     */
    public function getMinLength(): ?int
    {
        return $this->minLength;
    }


    /**
     * @param int|null $minLength
     */
    public function setMinLength(?int $minLength): void
    {
        $this->minLength = $minLength;
    }


    /**
     * @return int|null
     */
    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }


    /**
     * @param int|null $maxLength
     */
    public function setMaxLength(?int $maxLength): void
    {
        $this->maxLength = $maxLength;
    }


    /**
     * @return string|null
     */
    public function getPattern(): ?string
    {
        return $this->pattern;
    }


    /**
     * @param string|null $pattern
     */
    public function setPattern(?string $pattern): void
    {
        $this->pattern = $pattern;
    }


    /**
     * @return Schema|null
     */
    public function getItems(): ?Schema
    {
        return $this->items;
    }


    /**
     * @param Schema|null $items
     */
    public function setItems(?Schema $items): void
    {
        $this->items = $items;
    }


    /**
     * @return int|null
     */
    public function getMinItems(): ?int
    {
        return $this->minItems;
    }


    /**
     * @param int|null $minItems
     */
    public function setMinItems(?int $minItems): void
    {
        $this->minItems = $minItems;
    }


    /**
     * @return int|null
     */
    public function getMaxItems(): ?int
    {
        return $this->maxItems;
    }


    /**
     * @param int|null $maxItems
     */
    public function setMaxItems(?int $maxItems): void
    {
        $this->maxItems = $maxItems;
    }


    /**
     * @return bool|null
     */
    public function getUniqueItems(): ?bool
    {
        return $this->uniqueItems;
    }


    /**
     * @param bool|null $uniqueItems
     */
    public function setUniqueItems(?bool $uniqueItems): void
    {
        $this->uniqueItems = $uniqueItems;
    }


    /**
     * @return Schema[]|null
     */
    public function getProperties(): ?array
    {
        return $this->properties;
    }


    /**
     * @param Schema[]|null $properties
     */
    public function setProperties(?array $properties): void
    {
        $this->properties = $properties;
    }


    /**
     * @return string[]|null
     */
    public function getRequired(): ?array
    {
        return $this->required;
    }


    /**
     * @param string[]|null $required
     */
    public function setRequired(?array $required): void
    {
        $this->required = $required;
    }


    /**
     * @return int|null
     */
    public function getMinProperties(): ?int
    {
        return $this->minProperties;
    }


    /**
     * @param int|null $minProperties
     */
    public function setMinProperties(?int $minProperties): void
    {
        $this->minProperties = $minProperties;
    }


    /**
     * @return int|null
     */
    public function getMaxProperties(): ?int
    {
        return $this->maxProperties;
    }


    /**
     * @param int|null $maxProperties
     */
    public function setMaxProperties(?int $maxProperties): void
    {
        $this->maxProperties = $maxProperties;
    }


    /**
     * @return Schema[]|null
     */
    public function getAllOf(): ?array
    {
        return $this->allOf;
    }


    /**
     * @param Schema[]|null $allOf
     */
    public function setAllOf(?array $allOf): void
    {
        $this->allOf = $allOf;
    }


    /**
     * @return Schema[]|null
     */
    public function getOneOf(): ?array
    {
        return $this->oneOf;
    }


    /**
     * @param Schema[]|null $oneOf
     */
    public function setOneOf(?array $oneOf): void
    {
        $this->oneOf = $oneOf;
    }


    /**
     * @return Schema[]|null
     */
    public function getAnyOf(): ?array
    {
        return $this->anyOf;
    }


    /**
     * @param Schema[]|null $anyOf
     */
    public function setAnyOf(?array $anyOf): void
    {
        $this->anyOf = $anyOf;
    }


    /**
     * @return Schema|null
     */
    public function getNot(): ?Schema
    {
        return $this->not;
    }


    /**
     * @param Schema|null $not
     */
    public function setNot(?Schema $not): void
    {
        $this->not = $not;
    }


    /**
     * @return Discriminator|null
     */
    public function getDiscriminator(): ?Discriminator
    {
        return $this->discriminator;
    }


    /**
     * @param Discriminator|null $discriminator
     */
    public function setDiscriminator(?Discriminator $discriminator): void
    {
        $this->discriminator = $discriminator;
    }


    /**
     * @return Schema|bool
     */
    public function getAdditionalProperties()
    {
        return $this->additionalProperties;
    }


    /**
     * @param Schema|bool $additionalProperties
     */
    public function setAdditionalProperties($additionalProperties): void
    {
        $this->additionalProperties = $additionalProperties;
    }


}