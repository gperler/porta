<?php

declare(strict_types=1);

namespace Synatos\Porta\Validator\Schema;

use Synatos\Porta\Contract\ReferenceResolver;
use Synatos\Porta\Contract\StoppingValidator;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Exception\InvalidSchemaExceptionException;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Validator\ValidationMessage;

class SchemaValidator
{

    /**
     * @var ReferenceResolver
     */
    private $referenceResolver;

    /**
     * @var ValidationMessage[]
     */
    private $validationMessageList;


    /**
     * @param ReferenceResolver $referenceResolver
     */
    public function __construct(ReferenceResolver $referenceResolver)
    {
        $this->referenceResolver = $referenceResolver;
    }


    /**
     * @param Schema $schema
     * @param $value
     * @param bool $strictTypes
     * @param array $propertyPath
     *
     * @return array|ValidationMessage[]
     * @throws InvalidReferenceException
     * @throws InvalidSchemaExceptionException
     */
    public function validate(Schema $schema, $value, bool $strictTypes, array $propertyPath)
    {
        $this->validationMessageList = [];
        $schema = $this->referenceResolver->resolveSchema($schema);


        $validatorList = $this->getValidatorList($schema, $strictTypes);

        foreach ($validatorList as $validator) {
            $validationMessageList = $validator->validate($schema, $value, $propertyPath);

            $this->addValidationMessageList($validationMessageList);

            if (sizeof($validationMessageList) !== 0 && !$validator->canContinueOnValidationError()) {
                return $this->validationMessageList;
            }
        }
        return $this->validationMessageList;
    }


    /**
     * @param Schema $schema
     * @param bool $strictTypes
     *
     * @return StoppingValidator[]
     */
    private function getValidatorList(Schema $schema, bool $strictTypes): array
    {
        $validatorList = [
            new TypeValidator($strictTypes),
            new NullableValidator(),
            new FormatValidator(),
            new EnumValidator(),
            new AdditionalPropertiesValidator($this->referenceResolver),
            new AllOfValidator($this->referenceResolver),
            new AnyOfValidator($this->referenceResolver),
            new OneOfValidator($this->referenceResolver),
            new NotValidator($this->referenceResolver),
        ];
        switch ($schema->getType()) {
            case Schema::TYPE_BOOLEAN:
                $validatorList[] = new BooleanValidator();
                break;
            case Schema::TYPE_INTEGER:
            case Schema::TYPE_NUMBER:
                $validatorList[] = new IntegerValidator();
                break;
            case Schema::TYPE_OBJECT:
                $validatorList[] = new ObjectValidator($this->referenceResolver);
                break;
            case Schema::TYPE_ARRAY:
                $validatorList[] = new ArrayValidator($this->referenceResolver);
        }
        return $validatorList;
    }


    /**
     * @param ValidationMessage[] $validationMessageList
     */
    private function addValidationMessageList(array $validationMessageList)
    {
        $this->validationMessageList = array_merge($this->validationMessageList, $validationMessageList);
    }


    /**
     * @return ValidationMessage[]
     */
    public function getValidationMessageList(): array
    {
        return $this->validationMessageList;
    }

}