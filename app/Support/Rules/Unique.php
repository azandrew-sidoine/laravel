<?php

namespace App\Support\Rules;

use Drewlabs\Support\Traits\MethodProxy;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique as Fluent;
use InvalidArgumentException;
use ReflectionClass;

use function Drewlabs\Laravel\Query\Proxy\SelectQueryAction;
use function Drewlabs\Laravel\Query\Proxy\useActionQueryCommand;

/**
 * 
 * @method static when($value, $callback, $default = null)
 * @method static unless($value, $callback, $default = null)
 * @method static where($column, $value = null)
 * @method static whereNot($column, $value)
 * @method static whereNull($column)
 * @method static whereNotNull($column)
 * @method static whereIn($column, array $values)
 * @method static whereNotIn($column, array $values)
 * @method static using(\Closure $project)
 * @method static ignore($id, string $idColumn = null)
 * 
 * @package App\Rules
 */
class Unique
{
    use MethodProxy;

    /**
     * 
     * @var Fluent
     */
    private $fluent;

    /**
     * 
     * @var string
     */
    private $table;

    /**
     * 
     * @var string|null
     */
    private $column;


    /**
     * Create a new rule instance.
     * 
     * @param string $table 
     * @param string|null $column 
     * @return void 
     */
    public function __construct(string $table, string $column = null)
    {
        $this->table = $table;
        $this->column = $column;
        $this->fluent = Rule::unique($table, $column);
    }

    /**
     * 
     * @param string|Fluent $table 
     * @param string|null $column 
     * 
     * @return static 
     */
    public static function new($table, $column = null)
    {
        if ($table instanceof Fluent) {
            /**
             * @var self
             */
            $object = (new ReflectionClass(__CLASS__))->newInstanceWithoutConstructor();
            $object->fluent = $table;
            return $object;
        }
        return new static($table, $column);
    }

    /**
     * Provides an implementation arround illuminate `Unique::ignore(...)` method that
     * performs a query to select the value matching the id value to ignore in order to
     * avoit SQL injection
     * 
     * @param mixed $id 
     * @param string|null $idColumn 
     * @return $this 
     * @throws InvalidArgumentException 
     */
    public function ignoreSafe($id, string $idColumn = 'id')
    {
        // If the id value equals NULL, we simply return the current instance whithout
        // any further action just to support escape ignore if false
        if (empty($id)) {
            return $this;
        }
        $model = is_string($this->table) && class_exists($this->table) ?
            useActionQueryCommand($this->table)(SelectQueryAction($id))->value() : (($result = DB::table($this->table)->where($idColumn ?? 'id', $id)->first()) ?
                $result->{$idColumn} :
                null);
        $this->fluent = $this->fluent->ignore($model, $idColumn);
        return $this;
    }

    public function __call($name, $arguments)
    {
        return $this->proxy($this->fluent, $name, $arguments);
    }

    /**
     * Convert the rule to a validation string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->fluent->__toString();
    }
}
