<?php

namespace App\Support\Lang;

class LogicalOperation
{
    /** @var string */
    private $operator;

    /**
     * Create new class instance
     * 
     * @param string $operator 
     * @return void 
     */
    public function __construct(string $operator)
    {
        $this->operator = $operator;
    }

    /**
     * Creates new class ins
     * @param string $operator 
     * @return static 
     */
    public static function new(string $operator)
    {
        return new static($operator);
    }


    /**
     * Executes the logical operation on the arguments
     * 
     * @param mixed ...$args
     * 
     * @return bool 
     */
    public function exec(...$args): bool
    {
        $operations = [
            LogicalOperator::EQUALS => fn ($f, $s) => $f === $s,
            LogicalOperator::NOT_EQUALS => fn ($f, $s) => $f !== $s,
            LogicalOperator::LESS_THAN => fn ($f, $s) => $f < $s,
            LogicalOperator::LESS_THAN_OR_EQUALS => fn ($f, $s) => $f <= $s,
            LogicalOperator::GREATER_THAN => fn ($f, $s) => $f > $s,
            LogicalOperator::GREATER_THAN_OR_EQUALS => fn ($f, $s) => $f >= $s,
        ];
        return call_user_func_array($operations[$this->operator] ?? fn () => true, $args);
    }
}
