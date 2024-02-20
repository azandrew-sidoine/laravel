<?php

namespace App\Support\Config\Value;

class _Type
{
    /**
     * @var string
     */
    const STR = 'str';

    /**
     * @var string
     */
    const NUMERIC = 'num';

    /**
     * @var string
     */
    const INT = 'int';

    /**
     * @var string
     */
    const BOOL = 'bool';

    /**
     * @var string
     */
    const OBJ = 'obj';

    /**
     * @var string
     */
    const YEARS_DIFF = 'y_diff';

    /**
     * @var string
     */
    const DAYS_DIFF = 'd_diff';

    /**
     * @var string
     */
    const DATE = 'date';

    /**
     * @var string[]
     */
    const VALUES = [self::STR, self::NUMERIC, self::INT, self::BOOL, self::OBJ, self::YEARS_DIFF, self::DAYS_DIFF, self::DAYS_DIFF];

    /**
     * Returns an imploded string of the current enumeration
     * 
     * @param string $separator 
     * @return string 
     */
    public static function implode(string $separator = ',')
    {
        return implode($separator, self::VALUES);
    }
}
