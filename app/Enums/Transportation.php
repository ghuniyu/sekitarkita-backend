<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class Transportation extends Enum
{
    const AIR = "air";
    const LAND = "land";
    const SEA = "sea";

    public static function getDescription($value): string
    {
        switch ($value){
            case self::AIR:
                return 'Udara';
                break;
            case self::SEA:
                return 'Laut';
                break;
            case self::LAND:
                return 'Darat';
                break;
        }
        return parent::getDescription($value);
    }
}
