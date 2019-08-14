<?php

declare(strict_types=1);

namespace Synatos\Porta\Http;

class RouteMatcher
{

    /**
     * @param array $routeList
     * @param string $path
     *
     * @return RouteMatcher|null
     */
    public static function findRoute(array $routeList, string $path): ?RouteMatcher
    {
        foreach ($routeList as $route) {
            $routeMatcher = new RouteMatcher($route, $path);
            if ($routeMatcher->matches()) {
                return $routeMatcher;
            }
        }
        return null;
    }


    /**
     * @var string
     */
    private $route;

    /**
     * @var string[]
     */
    private $routeItemList;

    /**
     * @var string[]
     */
    private $pathItemList;

    /**
     * @var string[]
     */
    private $routeParameterList;


    /**
     * PathMatcher constructor.
     *
     * @param string $path
     * @param string $route
     */
    public function __construct(string $route, string $path)
    {
        $this->route = $route;
        $this->routeItemList = $this->getItemList($route);
        $this->pathItemList = $this->getItemList($path);
        $this->routeParameterList = [];
    }


    /**
     * @return bool
     */
    public function matches(): bool
    {
        if (sizeof($this->routeItemList) !== sizeof($this->pathItemList)) {
            return false;
        }

        for ($index = 0; $index < sizeof($this->routeItemList); $index++) {
            $routeItem = $this->routeItemList[$index];
            $pathItem = $this->pathItemList[$index];

            $parameterName = $this->getParameterName($routeItem);

            if ($routeItem !== $pathItem && $parameterName === null) {
                return false;
            }

            if ($parameterName !== null) {
                $this->routeParameterList[$parameterName] = $pathItem;
            }
        }
        return true;
    }


    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }


    /**
     * @return array
     */
    public function getRouteParameterList(): array
    {
        return $this->routeParameterList;
    }


    /**
     * @param string $routeItem
     *
     * @return bool
     */
    private function getParameterName(string $routeItem)
    {
        if (substr($routeItem, 0, 1) !== '{' || substr($routeItem, strlen($routeItem) - 1, 1) !== "}") {
            return null;
        };
        return substr($routeItem, 1, strlen($routeItem) - 2);
    }


    /**
     * @param string $path
     *
     * @return array
     */
    private function getItemList(string $path): array
    {
        if (strpos($path, '?') !== false) {
            $path = substr($path, 0, strpos($path, '?'));
        }

        $path = trim($path, '/');
        return explode("/", $path);
    }


}