<?php 

namespace App\Helpers;

class BadgeHelper
{
    public static function getBadge(int $points): string
    {
        return match (true) {
            $points >= 1000 => 'VIP',
            $points >= 500  => 'Gold',
            $points >= 100  => 'Silver',
            default => 'Newbie',
        };
    }
}
