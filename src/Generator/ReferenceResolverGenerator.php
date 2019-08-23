<?php

declare(strict_types=1);

namespace Synatos\Porta\Generator;

use Civis\Common\StringUtil;
use JsonSerializable;
use Nitria\ClassGenerator;
use Synatos\Porta\Contract\ReferenceResolver;
use Synatos\Porta\Exception\InvalidReferenceException;
use Synatos\Porta\Model\Example;
use Synatos\Porta\Model\Header;
use Synatos\Porta\Model\Link;
use Synatos\Porta\Model\OpenAPI;
use Synatos\Porta\Model\Parameter;
use Synatos\Porta\Model\RequestBody;
use Synatos\Porta\Model\Response;
use Synatos\Porta\Model\Schema;
use Synatos\Porta\Model\SecurityScheme;

class ReferenceResolverGenerator
{

    /**
     * @var ClassGenerator
     */
    protected $classGenerator;

    /**
     * @var OpenAPI
     */
    protected $openAPI;


    /**
     * @param OpenAPI $openAPI
     * @param string $className
     * @param string $psrPrefix
     * @param string $basePath
     */
    public function generateReferenceResolver(OpenAPI $openAPI, string $className, string $psrPrefix, string $basePath)
    {
        $this->openAPI = $openAPI;
        $this->classGenerator = new ClassGenerator($className);
        $this->classGenerator->addImplements(ReferenceResolver::class);
        $this->classGenerator->addUsedClassName(InvalidReferenceException::class);

        $this->generateSetOpenAPI();
        $this->generateResolveExample();
        $this->generateResolveHeader();
        $this->generateResolveLink();
        $this->generateResolveParameter();
        $this->generateResolveRequestBody();
        $this->generateResolveResponse();
        $this->generateResolveSchema();
        $this->generateResolveSecurityScheme();

        $this->classGenerator->writeToPSR4($basePath, $psrPrefix);
    }


    /**
     *
     */
    private function generateSetOpenAPI()
    {
        $method = $this->classGenerator->addMethod('setOpenAPI');
        $method->addParameter(OpenAPI::class, 'openAPI');
        $method->setReturnType('void', false);
    }


    /**
     *
     */
    private function generateResolveExample()
    {
        $list = $this->getExampleList();
        $this->generateResolver("resolveExample", Example::class, $list, "examples");
    }


    /**
     * @return Example[]
     */
    private function getExampleList(): array
    {
        $components = $this->openAPI->getComponents();
        if ($components === null) {
            return [];
        }
        $list = $components->getExamples();

        return $list !== null ? $list : [];
    }


    /**
     *
     */
    private function generateResolveHeader()
    {
        $headerList = $this->getHeaderList();
        $this->generateResolver("resolveHeader", Header::class, $headerList, "headers");
    }


    /**
     * @return array
     */
    private function getHeaderList(): array
    {
        $components = $this->openAPI->getComponents();
        if ($components === null) {
            return [];
        }
        $list = $components->getHeaders();

        return $list !== null ? $list : [];
    }


    /**
     *
     */
    private function generateResolveLink()
    {
        $list = $this->getLinkList();
        $this->generateResolver("resolveLink", Link::class, $list, "links");
    }


    /**
     * @return array
     */
    private function getLinkList(): array
    {
        $components = $this->openAPI->getComponents();
        if ($components === null) {
            return [];
        }
        $list = $components->getLinks();

        return $list !== null ? $list : [];
    }


    /**
     *
     */
    private function generateResolveParameter()
    {
        $list = $this->getParameterList();
        $this->generateResolver("resolveParameter", Parameter::class, $list, "parameters");
    }


    /**
     * @return array
     */
    private function getParameterList(): array
    {
        $components = $this->openAPI->getComponents();
        if ($components === null) {
            return [];
        }
        $list = $components->getParameters();

        return $list !== null ? $list : [];
    }


    /**
     *
     */
    private function generateResolveRequestBody()
    {
        $list = $this->getRequestBodyList();
        $this->generateResolver("resolveRequestBody", RequestBody::class, $list, "requestBodies");
    }


    /**
     * @return array
     */
    private function getRequestBodyList(): array
    {
        $components = $this->openAPI->getComponents();
        if ($components === null) {
            return [];
        }
        $list = $components->getRequestBodies();

        return $list !== null ? $list : [];
    }


    /**
     *
     */
    private function generateResolveResponse()
    {
        $list = $this->getResponseList();
        $this->generateResolver("resolveResponse", Response::class, $list, "responses");
    }


    /**
     * @return array
     */
    private function getResponseList(): array
    {
        $components = $this->openAPI->getComponents();
        if ($components === null) {
            return [];
        }
        $list = $components->getResponses();

        return $list !== null ? $list : [];
    }


    /**
     *
     */
    private function generateResolveSchema()
    {
        $schemaList = $this->getSchemaList();
        $this->generateResolver("resolveSchema", Schema::class, $schemaList, "schemas");
    }


    /**
     * @return Schema[]
     */
    private function getSchemaList(): array
    {
        $components = $this->openAPI->getComponents();
        if ($components === null) {
            return [];
        }
        $schemaList = $components->getSchemas();

        return $schemaList !== null ? $schemaList : [];
    }


    /**
     *
     */
    private function generateResolveSecurityScheme()
    {
        $list = $this->getSecuritySchemeList();
        $this->generateResolver("resolveSecurityScheme", SecurityScheme::class, $list, "securitySchemes");
    }


    /**
     * @return Schema[]
     */
    private function getSecuritySchemeList(): array
    {
        $components = $this->openAPI->getComponents();
        if ($components === null) {
            return [];
        }
        $schemaList = $components->getSecuritySchemes();

        return $schemaList !== null ? $schemaList : [];
    }


    /**
     * @param string $methodName
     * @param string $objectClassName
     * @param JsonSerializable[] $objectList
     * @param string $referencePath
     */
    private function generateResolver(string $methodName, string $objectClassName, array $objectList, string $referencePath)
    {
        $classNameShort = StringUtil::getEndAfterLast($objectClassName, "\\");

        $method = $this->classGenerator->addMethod($methodName);
        $method->addException(InvalidReferenceException::class);
        $method->setReturnType($objectClassName, false);
        $method->addParameter($objectClassName, "object");

        $method->addIfStart('!$object->isReference()');
        $method->addCodeLine('return $object;');
        $method->addIfEnd();

        $method->addCodeLine('$object = new ' . $classNameShort . '();');
        $method->addCodeLine('$ref = $object->getRef();');

        $method->addSwitch('$ref');
        foreach ($objectList as $name => $object) {
            $method->addSwitchCase('"#/components/' . $referencePath . '/' . $name . '"');
            $array = ArrayToString::compileArray($object->jsonSerialize());
            $method->addCodeLine('$object->fromArray(' . $array . ');');
            $method->addSwitchBreak();
        }
        $method->addSwitchDefault();
        $method->addCodeLine('throw new InvalidReferenceException("invalid reference" . $ref);');
        $method->addSwitchReturnBreak();

        $method->addSwitchEnd();

        $method->addCodeLine('return $object;');
    }


}