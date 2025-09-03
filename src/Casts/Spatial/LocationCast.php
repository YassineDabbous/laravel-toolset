<?php

declare(strict_types=1);

namespace Ysn\SuperCore\Casts\Spatial;

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
            //dump('the default value');
            return new Point(lat: 0, lng: 0);
        }
        // dump(unpack( "a*", $value ));
        // dump('-------------------');
        // dump(bin2hex($value));
        // dump('-------------------');

        // 4 bytes for srid
        $srid = substr($value, 0, 4);
        $srid = unpack('L', $srid)[1];  //4326

        // the POINT
        $wkb = substr($value, 4);

        return MyGeoFactory::parser()->parse($wkb);
        // return app('wkb')->parse($wkb); // POINT(2.0 4.0)




        // $coordinates = explode(',', $value);

        // if (count($coordinates) > 1) {
        //     $clean = str_replace(['POINT(', ')', ' '], ['', '', ','], $coordinates[0]); // POINT(0 0) => 0,0
        //     $location = explode(',', $clean);
        //     return new Point(lat: (float) $location[1], lng: (float) $location[0], srid: (int) $coordinates[1]);
        // }

        // $clean = str_replace(['POINT(', ')', ' '], ['', '', ','], $value); // POINT(0 0) => 0,0
        // $location = explode(',', $clean);
        // //dump($location);
        // if(count($location) >= 2){
        //     return new Point(lat: (float) $location[1], lng: (float) $location[0]);
        // }
        // // it's raw binary data!
        // // return (new WKB)->read($location[0]);
        // return null;
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
