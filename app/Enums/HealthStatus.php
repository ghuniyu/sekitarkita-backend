<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class HealthStatus extends Enum
{
    const HEALTHY = "healthy";
    const ODP = "odp";
    const PDP = "pdp";
    const POSITIVE = "positive";
    const OTG = "otg";
    const TRAVELER = "traveler";

    public static function getDescription($value): string
    {
        switch ($value){
            case self::HEALTHY:
                return 'Sehat';
                break;
            case self::ODP:
                return 'Orang Dalam Pemantauan';
                break;
            case self::PDP:
                return 'Pasien Dalam Pemantauan';
                break;
            case self::POSITIVE:
                return 'Pasien Positif';
                break;
            case self::OTG:
                return 'Orang Tanpa Gejala';
                break;
            case self::TRAVELER:
                return 'Pelaku Perjalanan';
                break;
        }
        return parent::getDescription($value);
    }
}
