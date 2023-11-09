<?php

namespace App\Helpers;

class CaseHelper
{
    const CASES = [
        ['value' => 1, 'color' => 'red'],
        ['value' => 2, 'color' => 'black'],
        ['value' => 3, 'color' => 'red'],
        ['value' => 4, 'color' => 'black'],
        ['value' => 5, 'color' => 'red'],
        ['value' => 6, 'color' => 'black'],
        ['value' => 7, 'color' => 'red'],
        ['value' => 8, 'color' => 'black'],
        ['value' => 9, 'color' => 'red'],
        ['value' => 10, 'color' => 'black'],
        ['value' => 11, 'color' => 'green']
    ];

    public static function random()
    {
        return self::CASES[random_int(0, count(self::CASES) - 1)];
    }
}
