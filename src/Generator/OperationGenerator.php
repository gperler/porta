<?php

declare(strict_types=1);

namespace Synatos\Porta\Generator;

use Nitria\ClassGenerator;
use Synatos\Porta\Http\RouteMatcher;
use Synatos\Porta\Model\OpenAPI;
use Synatos\Porta\Model\Operation;
use Synatos\Porta\Model\PathItem;

class OperationGenerator
{

    const METHOD_PATH_SEPARATOR = "~";


    /**
     * @var OpenAPI
     */
    private $openAPI;


    /**
     * @var ClassGenerator
     */
    private $classGenerator;


    /**
     * @var string[]
     */
    private $operationArray;

    /**
     * @param OpenAPI $openAPI
     * @param string $className
     * @param string $psrPrefix
     * @param string $baseDir
     */
    public function generateOperationLoader(OpenAPI $openAPI, string $className, string $psrPrefix, string $baseDir)
    {
        $this->classGenerator = new ClassGenerator($className);
        $this->openAPI = $openAPI;
        $this->operationArray = [];

        $this->addRoutes();
        $this->addPathList();
        $this->addSwitch();
        $this->addGetOperation();
        $this->addRouteMatcher();
        $this->classGenerator->writeToPSR4($baseDir, $psrPrefix);
    }

    /**
     *
     */
    private function addRoutes()
    {

        $routeList = [];
        foreach ($this->openAPI->getPaths() as $route => $pathItem) {
            $routeList[] = $route;
        }

        $array = ArrayToString::compileArray($routeList);
        $this->classGenerator->addConstant("ROUTE_LIST", $array);
    }

    /**
     *
     */
    private function addSwitch()
    {
        $method = $this->classGenerator->addPrivateMethod("getOperationArray");

        $method->addParameter("string", "httpMethod");

        $method->setReturnType("array", true);
        $method->addCodeLine('$route = $this->routeMatcher->getRoute();');
        $method->addSwitch('$httpMethod . "' . self::METHOD_PATH_SEPARATOR . '" . $route');

        foreach ($this->operationArray as $localId => $arrayContent) {
            $method->addSwitchCase('"' . $localId . '"');
            $method->addCodeLine('return ' . $arrayContent . ';');
            $method->addSwitchReturnBreak();
        }

        $method->addSwitchDefault();
        $method->addCodeLine('return null;');
        $method->addSwitchReturnBreak();

        $method->addSwitchEnd();


        $method->addCodeLine("");

    }

    /**
     *
     */
    public function addGetOperation()
    {
        $method = $this->classGenerator->addMethod('getOperation');

        $method->addParameter("string", "path");
        $method->addParameter("string", "httpMethod");
        $method->setReturnType(Operation::class, true);

        $method->addCodeLine('$this->routeMatcher = RouteMatcher::findRoute(self::ROUTE_LIST, $path);');

        $method->addIfStart('$this->routeMatcher === null');
        $method->addCodeLine('return null;');
        $method->addIfEnd();


        $method->addCodeLine('$operationArray = $this->getOperationArray($httpMethod);');
        $method->addCodeLine('$operation = new Operation();');
        $method->addCodeLine('$operation->fromArray($operationArray);');
        $method->addCodeLine('return $operation;');
    }


    /**
     *
     */
    private function addRouteMatcher()
    {
        $this->classGenerator->addPrivateProperty('routeMatcher', RouteMatcher::class);
        $method = $this->classGenerator->addMethod('getRouteMatcher');

        $method->setReturnType(RouteMatcher::class, true);
        $method->addCodeLine('return $this->routeMatcher;');
    }

    /**
     *
     */
    private function addPathList()
    {
        $pathList = $this->openAPI->getPaths();
        foreach ($pathList as $path => $pathItem) {
            $this->addOperationMethods($path, $pathItem);
        }
    }


    /**
     * @param string $path
     * @param PathItem $pathItem
     */
    private function addOperationMethods(string $path, PathItem $pathItem)
    {
        $get = $pathItem->getGet();
        if ($get !== null) {
            $this->setOperationArrayByRouteAndMethod($path, "get", $get);
        }

        $post = $pathItem->getPost();
        if ($post !== null) {
            $this->setOperationArrayByRouteAndMethod($path, "post", $post);
        }
    }


    /**
     * @param string $route
     * @param string $method
     * @param Operation $operation
     */
    private function setOperationArrayByRouteAndMethod(string $route, string $method, Operation $operation): void
    {
        $identifier = $method . self::METHOD_PATH_SEPARATOR . $route;
        $this->operationArray[$identifier] = ArrayToString::compileArray($operation->jsonSerialize());
    }


}


