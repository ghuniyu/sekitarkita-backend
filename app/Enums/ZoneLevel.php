<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ZoneLevel extends Enum
{
    const RED = "merah";
    const YELLOW = "kuning";
    const GREEN = "hijau";

    public static function getDescription($value): string
    {
        switch ($value){
            case self::RED:
                return 'Zona Merah';
                break;
            case self::GREEN:
                return 'Zona Hijau';
                break;
            case self::YELLOW:
                return 'Zona Kuning';
                break;
        }
        return parent::getDescription($value);
    }
}
