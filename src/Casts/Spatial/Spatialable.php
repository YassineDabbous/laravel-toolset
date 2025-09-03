<?php
namespace Yaseen\Toolset\Casts\Spatial;

use Illuminate\Database\Eloquent\Builder;

trait Spatialable
{
    protected function spatialables() : array {
        return ['coordinates'];
    }

    public static function bootSpatialable()
    {
        static::creating( function( $model ) {
            foreach ($model->spatialables() as $col) {
                $model->$col = $model->$col ?? new Point(0,0); //?? doubleval(0xe6100000010100000000000000000000000000000000000000);
            }
            // $model->coordinates = $model->coordinates ?? new Point(0,0);
        });
    }


    public function getMapLinkAttribute()
    {
        return 'https://www.google.com/maps/@' . $this->coordinates->getLat() . ',' . $this->coordinates->getLng() . ',17z';
    }


    // public function scopeWithCoordinates(Builder $q): Builder
    // {
    //     $columns = $q->getQuery()->columns ?? [];

    //     $column = 'coordinates';
    //     $tc = $this->getTable().'.'.$column;
    //     $raw = "CONCAT(ST_AsText($tc, 'axis-order=long-lat'), ',', ST_SRID($tc)) as {$column}, ";
    //     $raw = substr($raw, 0, -2);
    //     $columns[] = DB::raw($raw);

    //     // dump($columns)  => *, coordinatsAsPoint
    //     return $q->select($columns); // $q->addSelect('*', DB::raw($raw));
    // }



    // public function scopeSelectDistanceTo(Builder $query, string $column, Point $point): void
    // {
    //     if (is_null($query->getQuery()->columns)) {
    //         $query->select('*');
    //     }

    //     $query->selectRaw("ST_Distance(
    //         ST_SRID({$column}, ?),
    //         ST_SRID(Point(?, ?), ?)
    //     ) as distance", [
    //         $point->getSrid(),
    //         $point->getLng(),
    //         $point->getLat(),
    //         $point->getSrid(),
    //     ]);
    // }

    public function scopeWithinDistanceTo(Builder $query, string $column, Point $point, int $distance): void
    {
        $query->whereRaw("ST_Distance(
            ST_SRID({$column}, ?),
            ST_SRID(Point(?, ?), ?)
        ) <= ?", [...[
            $point->getSrid(),
            $point->getLng(),
            $point->getLat(),
            $point->getSrid(),
        ], $distance]);
    }

    public function scopeOrderByDistanceTo(Builder $query, string $column, Point $point, string $direction = 'asc'): void
    {
        $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';

        $query->orderByRaw("ST_Distance(
            ST_SRID({$column}, ?),
            ST_SRID(Point(?, ?), ?)
        ) ".$direction, [
            $point->getSrid(),
            $point->getLng(),
            $point->getLat(),
            $point->getSrid(),
        ]);
    }





    // protected function formatMyPoint() {
    //     $this->location = DB::raw('POINT(' . $this->longitude . ' ' . $this->latitude . ')');
    //     // to prevent "unknown column" error when saving
    //     unset($this->longitude);
    //     unset($this->latitude);
    // }


    // public function scopeWithLatLng(Builder $q){
    //     $columns = $q->getQuery()->columns;
    //     if (!$columns) {
    //         $q->select('*');
    //     }
    // 	return $q->select(DB::raw('ST_X(location) AS longitude, ST_Y(location) AS latitude'));
    // }




    // public function scopeInDistance($query, float $latitude, float $longitude, int $distance)
    // {
    //     $query->whereRaw("st_distance_sphere(`location`, ST_GeomFromText(?, ?, 'axis-order=long-lat')) <= ?", [
    //         "Point($longitude $latitude)", // point
    //         4326, // srid
    //         $distance,
    //     ]);

    //     return $query;
    // }
}
