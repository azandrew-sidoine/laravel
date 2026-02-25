<?php

namespace App\Support\Lang;

use Drewlabs\Core\Helpers\DateTime;

class Value
{
    /** @var mixed */
    private $value;

    /** @var string */
    private $type;

    /**
     * creates a config value instance
     * 
     * @param mixed $value 
     * @param string $type
     */
    public function __construct($value, ?string $type = null)
    {
        $this->value = $value;
        $this->type = $type;
    }

    /**
     * Creates new config value instance
     * 
     * @param mixed $value 
     * @param string $type 
     * 
     * @return static
     */
    public static function new($value, ?string $type = null)
    {
        return new static($value, $type);
    }

    /**
     * encodes the configuration value. Case the provided type information equals o,
     * the configuration is json encoded using PHP native `json_encode` function.
     * 
     * @param string $type 
     * @return mixed 
     */
    public function encode()
    {
        if (null === $this->value) {
            return $this->value;
        }
        if (!is_null($this->type)) {
            $this->type === Types::OBJ ? json_encode($this->value) : $this->value;
        }
        return is_array($this->value) || is_object($this->value) ? json_encode($this->value)  : $this->value;
    }


    /**
     * decodes the configuration value to it supported type
     * 
     * @return mixed 
     */
    public function decode()
    {
        if (null === $this->value) {
            return $this->value;
        }
        $type = strtolower($this->type);
        $decoders = [
            Types::OBJ => function ($value) {
                return json_decode($value);
            },
            Types::NUMERIC => function ($value) {
                return floatval($value);
            },
            Types::INT => function ($value) {
                return intval($value);
            },
            Types::BOOL => function ($value) {
                return boolval($value);
            },
            Types::STR => function ($value) {
                return strval($value);
            },
            Types::YEARS_DIFF => function ($value) {
                return is_string($value) && (false === @strtotime($value)) ? -1 : intval(DateTime::yrsDiff(DateTime::nowTz(), $value));
            },
            Types::DAYS_DIFF => function ($value) {
                return is_string($value) && (false === @strtotime($value)) ? -1 : intval(DateTime::daysDiff(DateTime::nowTz(), $value));
            },
            Types::DATE => function ($value) {
                return DateTime::create($value);
            },
        ];

        $fn = $decoders[$type] ?? function ($value) {
            return $value;
        };
        return $fn($this->value);
    }
}
