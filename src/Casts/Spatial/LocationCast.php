<?php

declare(strict_types=1);

namespace Yaseen\Toolset\Casts\Spatial;

use Exception;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\SerializesCastableAttributes;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

class LocationCast implements CastsAttributes, SerializesCastableAttributes
{
    public function get($model, string $key, $value, array $attributes): ?Point
    {
        if($value == null){
            return new Point(lat: 0, lng: 0);
        }
        if(bin2hex($value) == 'e6100000010100000000000000000000000000000000000000'){
            return new Point(lat: 0, lng: 0);
        }

        // 4 bytes for srid
        $srid = substr($value, 0, 4);
        $srid = unpack('L', $srid)[1];  //4326

        // the POINT
        $wkb = substr($value, 4);

        return MyGeoFactory::parser()->parse($wkb);
    }

    public function set($model, string $key, $value, array $attributes): Expression
    {
        if (!$value instanceof Point) {
            throw new Exception(message: 'The '.$key.' field must be instance of '.Point::class);
        }

        if ($value->getSrid() > 0) {
            return DB::raw(
                value: "ST_GeomFromText('POINT({$value->getLng()} {$value->getLat()})', {$value->getSrid()}, 'axis-order=long-lat')"
            );
        }

        return DB::raw(value: "ST_GeomFromText('POINT({$value->getLng()} {$value->getLat()})')");
    }

    public function serialize($model, string $key, $value, array $attributes): array
    {
        return [
            'latitude'  => $value->getLat(),
            'longitude'  => $value->getLng(),
            'srid' => $value->getSrid(),
        ];
    }
}
