<?php

declare(strict_types=1);

namespace Yaseen\Toolset\Casts\Spatial;

use GeoIO\Factory;

class MyGeoFactory implements Factory
{
    // public static $singletonParser = null;
    public static function parser() : \GeoIO\WKB\Parser\Parser
    {
        $factory = new MyGeoFactory();
        $parser = new \GeoIO\WKB\Parser\Parser($factory);
        return $parser;
    }

    public function createPoint($dimension, array $coordinates, $srid = null)
    {
        return new Point(lng: $coordinates['x'], lat: $coordinates['y']);
    }

    public function createLineString($dimension, array $points, $srid = null){}
    public function createLinearRing($dimension, array $points, $srid = null){}
    public function createPolygon($dimension, array $lineStrings, $srid = null){}
    public function createMultiPoint($dimension, array $points, $srid = null){}
    public function createMultiLineString($dimension, array $lineStrings, $srid = null){}
    public function createMultiPolygon($dimension, array $polygons, $srid = null){}
    public function createGeometryCollection($dimension, array $geometries, $srid = null){}
}
