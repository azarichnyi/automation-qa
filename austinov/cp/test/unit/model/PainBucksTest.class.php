<?php

class PainBucksTest extends PainBucks
{
    public $dynamicDriving = 0;
    public $dynamicCommunity = 0;
    public $communityCommunity = 0;
    public $contrib = 0;
    public $average_speed = 0;
    public $average_speed_deviation = 0;
    public $average_speed_point_count = 0;
    public $average_speed_tile = 0;
    public $average_speed_tile_deviation = 0;
    public $average_speed_road = 0;
    public $average_speed_road_deviation = 0;
    public $average_speed_city = 0;
    public $average_speed_city_deviation = 0;    
    
    public function getAverages($road_id, $road_type, $road_direction, $road_direction_acc, $tile, $city_id, $received)
    {
        return parent::getAverages($road_id, $road_type, $road_direction, $road_direction_acc, $tile, $city_id, $received);
    }
    
    public function calculeteDeviationDistance($speed, $average, $deviation)
    {
        return parent::calculeteDeviationDistance($speed, $average, $deviation);
    }
    
}