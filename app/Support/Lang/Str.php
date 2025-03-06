<?php

namespace App\Support\Lang;

class Str
{

    /**
     * takes a date string as input and return a formatted output of the provided
     * date string if it's a valid data or the input value if not valid date
     * 
     * @param string $date 
     * @param string $format
     * 
     * @return string 
     */
    public static function toDateFormat(string $date, string $format = 'Y-m-d')
    {
        return false !== ($result = @strtotime($date)) ? (new \DateTimeImmutable)->setTimestamp($result)->format($format) :  $date;
    }
}
