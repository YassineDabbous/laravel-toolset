<?php

namespace Yaseen\Toolset\Casts\Spatial;

class Point
{
    protected float $lat;
    protected float $lng;
    protected int $srid;

    public function __construct($lat, $lng, int $srid = 4326)
    {
        $this->lat = (float) $lat;
        $this->lng = (float) $lng;
        $this->srid = 4326;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLng(): float
    {
        return $this->lng;
    }

    public function getSrid(): int
    {
        return $this->srid;
    }

    // public function toWKT()
    // {
    //     return "ST_PointFromText('POINT({$this->lng} {$this->lat})')";
    //     //return sprintf('POINT(%s)', (string) $this);
    // }
    // public function __toString()
    // {
    //     return DB::raw("ST_PointFromText('POINT({$this->lng} {$this->lat})')");
    //     //return "ST_PointFromText('POINT(60.7484404 -73.9878441)')";
    //     //return DB::raw("ST_PointFromText('POINT(60.7484404 -73.9878441)')");
    //     // $point = "POINT({$this->lng} {$this->lat})";
    //     // return DB::raw($point);
    //     //return DB::raw("ST_PointFromText('$point', 4326)");
    //     //return sprintf("ST_PointFromText(%s, 4326)", $point);
    //     // ST_PointFromText('POINT(-73.9878441 40.7484404)', 4326)
    //     //return "ST_GeomFromText($point, 4326, 'axis-order=long-lat')";
    //     //return DB::raw("ST_PointFromText('$point', 4326)");
    //     // $p = $this->getLng().' '.$this->getLat();
    //     // return sprintf('POINT(%s)', (string) $p);
    // }

}
