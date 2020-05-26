<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ChangeRequestStatus extends Enum
{
    const PENDING = "pending";
    const APPROVE = "approve";
    const REJECT = "reject";

    public static function getDescription($value): string
    {
        switch ($value){
            case self::PENDING:
                return 'Menunggu Verifikasi';
                break;
            case self::APPROVE:
                return 'Diterima';
                break;
            case self::REJECT:
                return 'Ditolak';
                break;
        }
        return parent::getDescription($value);
    }
}
