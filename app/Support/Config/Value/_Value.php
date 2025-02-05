<?php

namespace App\Support\Config\Value;

use Drewlabs\Core\Helpers\ImmutableDateTime;

class _Value
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string
     */
    private $type;

    /**
     * Creates a config value instance
     * 
     * @param mixed $value 
     * @param string $type
     */
    public function __construct($value, string $type = null)
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
    public static function new($value, string $type = null)
    {
        return new static($value, $type);
    }

    /**
     * Encode the configuration value. Case the provided type information equals o,
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
            $this->type === _Type::OBJ ? json_encode($this->value) : $this->value;
        }
        return is_array($this->value) || is_object($this->value) ? json_encode($this->value)  : $this->value;
    }


    /**
     * Decode the configuration value to it supported type
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
            _Type::OBJ => function ($value) {
                return json_decode($value);
            },
            _Type::NUMERIC => function ($value) {
                return floatval($value);
            },
            _Type::INT => function ($value) {
                return intval($value);
            },
            _Type::BOOL => function ($value) {
                return boolval($value);
            },
            _Type::STR => function ($value) {
                return strval($value);
            },
            _Type::YEARS_DIFF => function ($value) {
                return intval(ImmutableDateTime::yrsDiff(ImmutableDateTime::nowTz(), $value));
            },
            _Type::DAYS_DIFF => function ($value) {
                return intval(ImmutableDateTime::daysDiff(ImmutableDateTime::nowTz(), $value));
            },
            _Type::DATE => function ($value) {
                return ImmutableDateTime::create($value);
            },
        ];

        $fn = $decoders[$type] ?? function ($value) {
            return $value;
        };
        return $fn($this->value);
    }
}
