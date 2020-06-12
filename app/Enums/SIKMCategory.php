<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SIKMCategory extends Enum
{
    const ONE_WAY = 'one_way';
    const RETURN = 'return';

    public static function getDescription($value): string
    {
        switch ($value){
            case self::ONE_WAY:
                return 'Sekali Jalan';
                break;
            case self::RETURN:
                return 'Bolak Balik';
                break;
        }
        return parent::getDescription($value);
    }
}
