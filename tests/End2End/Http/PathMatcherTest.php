<?php

declare(strict_types=1);

namespace Synatos\PortaTest\Http;

use Codeception\Test\Unit;
use Synatos\Porta\Http\RouteMatcher;

class PathMatcherTest extends Unit
{


    /**
     *
     */
    public function testMatching()
    {
        $pathMatcher = new RouteMatcher("/api/customer/do-something", "/api/customer/do-something/");
        $this->assertTrue($pathMatcher->matches());

        $pathMatcher = new RouteMatcher("/api/customer/do-something", "/api/supplier/do-something/");
        $this->assertFalse($pathMatcher->matches());


        $pathMatcher = new RouteMatcher("/api/customer/save/{id}", "/api/customer/save/19");
        $this->assertTrue($pathMatcher->matches());

        $this->assertEquals([
            "id" => "19"
        ], $pathMatcher->getRouteParameterList());

        $pathMatcher = new RouteMatcher("/api/customer/save/{id}/with/{method}", "/api/customer/save/19");
        $this->assertFalse($pathMatcher->matches());

        $pathMatcher = new RouteMatcher("/api/customer/save/{id}/with/{method}", "/api/customer/save/19/with/full");
        $this->assertTrue($pathMatcher->matches());

        $this->assertEquals([
            "id" => "19",
            "method" => "full"
        ], $pathMatcher->getRouteParameterList());
    }


    /**
     *
     */
    public function testRouteFinding()
    {
        $routeMatcher = RouteMatcher::findRoute([
            "/api/supplier/save/",
            "/api/customer/{method}",
            "/api/customer/{method}/{id}/full"
        ], "/api/customer/save/19/full");

        $this->assertNotNull($routeMatcher);
        $this->assertEquals([
            "method" => "save",
            "id" => "19"
        ], $routeMatcher->getRouteParameterList());
    }

}