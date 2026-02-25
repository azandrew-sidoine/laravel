<?php

namespace App\Support\Lang;

class Types
{
    /** @var string */
    const STR = 'str';

    /**  @var string */
    const NUMERIC = 'num';

    /** @var string */
    const INT = 'int';

    /** @var string */
    const BOOL = 'bool';

    /** @var string */
    const OBJ = 'obj';

    /** @var string */
    const YEARS_DIFF = 'y_diff';

    /** @var string */
    const MONTHS_DIFF = 'm_diff';

    /** @var string */
    const DAYS_DIFF = 'd_diff';

    /** @var string */
    const HOURS_DIFF = 'h_diff';

    /** @var string */
    const MINUTES_DIFF = 'i_diff';

    /** @var string */
    const SECONDS_DIFF = 'S_diff';

    /** @var string */
    const DATE = 'date';

    /** @var string[] */
    const VALUES = [
        self::STR,
        self::NUMERIC,
        self::INT,
        self::BOOL,
        self::OBJ,
        self::YEARS_DIFF,
        self::MONTHS_DIFF,
        self::DAYS_DIFF,
        self::HOURS_DIFF,
        self::MINUTES_DIFF,
        self::SECONDS_DIFF,
        self::DAYS_DIFF
    ];

    /**
     * returns an imploded string of the current enumeration
     * 
     * @param string $separator 
     * @return string 
     */
    public static function implode(string $separator = ',')
    {
        return implode($separator, self::VALUES);
    }
}
