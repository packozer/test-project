<?php

namespace App\Enum;

class StatusEnum
{
    const STATUS_PENDING  = 'pending';
    const STATUS_NEW = 'new';
    const STATUS_IN_REVIEW  = 'in review';
    const STATUS_DELETED  = 'deleted';
    const STATUS_APPROVED  = 'approved';
    const STATUS_INACTIVE  = 'inactive';

    public static function getAvailableChoices(): array
    {
        return [
            self::STATUS_PENDING => self::STATUS_PENDING ,
            self::STATUS_NEW => self::STATUS_NEW,
            self::STATUS_IN_REVIEW => self::STATUS_IN_REVIEW,
            self::STATUS_DELETED => self::STATUS_DELETED,
            self::STATUS_APPROVED => self::STATUS_APPROVED,
            self::STATUS_INACTIVE => self::STATUS_INACTIVE,
        ];
    }
}