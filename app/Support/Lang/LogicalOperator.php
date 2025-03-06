<?php

namespace App\Support\Lang;

class LogicalOperator
{
    /** @var string */
    const EQUALS = 'eq';

    /** @var string */
    const NOT_EQUALS = 'neq';

    /** @var string */
    const LESS_THAN = 'lt';

    /** @var string */
    const LESS_THAN_OR_EQUALS = 'lte';

    /** @var string */
    const GREATER_THAN = 'gt';

    /** @var string */
    const GREATER_THAN_OR_EQUALS = 'gte';

    /** @var string[] */
    const VALUES = [self::EQUALS, self::NOT_EQUALS, self::LESS_THAN, self::LESS_THAN_OR_EQUALS, self::GREATER_THAN, self::GREATER_THAN_OR_EQUALS];

    /**
     * returns an imploded string of the current enumeration
     * 
     * @param string $separator 
     * 
     * @return string 
     */
    public static function implode(string $separator = ',')
    {
        return implode($separator, self::VALUES);
    }
}
