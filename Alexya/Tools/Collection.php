<?php
namespace Alexya\Tools;

use \ArrayAccess;

/**
 * Collection class.
 *
 * This class provides a powerfull way of managing arrays.
 *
 * The constructor accepts as parameter the array to manage.
 *
 * Example:
 *
 *     $arr = new \Alexya\Tools\Collection(["test", "foo", "bar", "more", "tests"]);
 *
 *     echo $arr[0]; // test
 *
 *     // Loop through all array.
 *     $arr->foreach(function($key, $value) {
 *         echo "{$key} => {$value}\n";
 *     });
 *     // 0 => test
 *     // 1 => foo
 *     // 2 => bar
 *     // 3 => more
 *     // 4 => tests
 *
 *     // Enhanced foreach loop.
 *     $arr->foreachElse(function($key, $value) {
 *         echo "{$key} => {$value}\n";
 *     }, function() {
 *         echo "The array is empty!";
 *     });
 *
 *     // Make a new array by filtering the current one.
 *     $newArr = $arr->filter(function($key, $value) {
 *         return $key % 2 == 0;
 *     });
 *     // $newArr = new \Alexya\Tools\Collection([
 *     //     "test",
 *     //     "bar",
 *     //     "tests",
 *     // ]);
 *
 *     // You can also modify all items of the array by walking it
 *     $newArr->wal(function($key, $value) {
 *         return [$key, strtoupper($value)];
 *     });
 *     // $newArr = new \Alexya\Tools\Collection([
 *     //     "TEST",
 *     //     "BAR",
 *     //     "TESTS",
 *     // ]);
 *
 * @see https://stackoverflow.com/questions/3432257/difference-between-array-map-array-walk-and-array-filter
 * For more information about `map`, `filter`, `walk` and `accumulate` methods.
 *
 * @author Manulaiko <manulaiko@gmail.com>
 */
class Collection implements ArrayAccess
{
    /**
     * The all mighty array.
     *
     * @var array
     */
    private $_data = [];

    /**
     * Constructor.
     *
     * @param array $data The all mighty array.
     */
    public function __construct(array $data = [])
    {
        $this->_data = $data;
    }

    /**
     * Loops through all items of the array and executes `$foreach` on each item.
     *
     * The callback must accept as parameter they key and the value of the item:
     *
     *     $arr->foreach(function($key, $value) {
     *         echo "{$key} => {$value}";
     *     });
     *
     * @param callable $foreach Callback to execute on each item.
     */
    public function foreach(callable $foreach)
    {
        array_map($foreach, $this->_data);
    }

    /**
     * Enhanced foreach loop.
     *
     * Loops through all items of the array and executes `$foreach` on each item.
     * If the array is empty the callback `$else` will be executed.
     *
     * The callback must accept as parameter they key and the value of the item:
     *
     *     $arr->foreachElse(function($key, $value) {
     *         echo "{$key} => {$value}";
     *     }, function() {
     *         echo "The array is empty!";
     *     });
     * @param callable $foreach Callback to execute on each item.
     * @param callable $else    Callback to execute if the array is empty.
     */
    public function foreachElse(callable $foreach, callable $else)
    {
        if(empty($this->_data)) {
            $else();
        } else {
            $this->foreach($foreach);
        }
    }

    /**
     * Filters the array and returns a new object of this class.
     *
     * Example:
     *
     *     $arr = new \Alexya\Tools\Collection([
     *         "test",
     *         "foo",
     *         "bar",
     *         "tests"
     *     ]);
     *
     *     $newArr = $arr->filter(function($key, $value) {
     *         return $key % 2 == 0;
     *     });
     *     // $newArr = new \Alexya\Tools\Collection([
     *     //     "test",
     *     //     "bar",
     *     //     "tests",
     *     // ]);
     *
     * @param callable $filter Filter to execute.
     *
     * @return \Alexya\Tools\Collection New array with filtered items.
     */
    public function filter(callable $filter) : Collection
    {
        return new static(array_filter($this->_data, $filter));
    }

    /**
     * Maps the array and returns a new object of this class.
     *
     * Example:
     *
     *     $arr = new \Alexya\Tools\Collection([
     *         "test",
     *         "foo",
     *         "bar",
     *         "tests"
     *     ]);
     *
     *     $newArr = $arr->map(function($key, $value) {
     *         $value = strtoupper($value);
     *
     *         return $value;
     *     });
     *     // $newArr = new \Alexya\Tools\Collection([
     *     //     "TEST",
     *     //     "FOO",
     *     //     "BAR",
     *     //     "TESTS"
     *     // ]);
     *
     * @param callable $callback Callback to apply to the array.
     *
     * @return \Alexya\Tools\Collection Mapped array.
     */
    public function map(callable $callback) : Collection
    {
        $result = array_map($callback, array_keys($this->_data), $this->_data);

        return new static($result);
    }

    /**
     * Walks through the array.
     *
     * The difference between this method and `map` is that
     * the callback must return an array with two indexes:
     *
     *  * The new key of the item.
     *  * The new value of the item.
     *
     * This will override the current array while `map` will only loop
     * through the array and call the callback with the item key and value.
     *
     * Example:
     *
     *     $arr = new \Alexya\Tools\Collection([
     *         "test",
     *         "foo",
     *         "bar",
     *         "tests"
     *     ]);
     *
     *     $arr->walk(function($key, $value) {
     *         $value = strtoupper($value);
     *
     *         return [$key, $value];
     *     });
     *     // $arr = new \Alexya\Tools\Collection([
     *     //     "TEST",
     *     //     "FOO",
     *     //     "BAR",
     *     //     "TESTS"
     *     // ]);
     *
     * @param callable $callback Callback to apply to the array.
     */
    public function walk(callable $callback)
    {
        $newData = [];
        foreach($this->_data as $key => $value) {
            $ret = $callback($key, $value);

            $newData[$ret[0]] = $ret[1];
        }

        $this->_data = $newData;
    }

    /**
     * Performs a `array_reduce` action with the array.
     *
     * This method loops through the array and executes the callback
     * on each element.
     *
     * The difference between `accumulate` and `map` and `filter` is that
     * this method sends the result of each call to the callback as
     * a second parameter to the callback.
     *
     * Example:
     *
     *     $arr = new \Alexya\Tools\Collection([
     *         "test",
     *         "foo",
     *         "bar",
     *         "tests"
     *     ]);
     *
     *     $arr->accumulate(function($key, $value, $accumulator) {
     *         echo "Previous value {$accumulator}, current value {$value}";
     *
     *         return $value;
     *     }, "");
     *     // Previous value , current value test
     *     // Previous value test, current value foo
     *     // Previous value foo, current value bar
     *     // Previous value bar, current value tests
     *
     * @param callable $callback    Callback to apply to each element.
     * @param mixed    $accumulator Initial accumulator.
     *
     * @return mixed The resulting $accumulator.
     */
    public function accumulate(callable $callback, $accumulator = "")
    {
        foreach($this->_data as $key => $value) {
            $accumulator = $callback($key, $value, $accumulator);
        }

        return $accumulator;
    }

    /**
     * Combines this and another array and returns a new object.
     *
     * The resulting array will contain the values of this array as key and the values
     * of the parameter as values:
     *
     *     $keys = new Collection([
     *         "foo" => "bar"
     *     ]);
     *     $values = new Collection([
     *         "test" => "test"
     *     ]);
     *
     *     $newCollection = $keys->combine($values);
     *     // $newCollection = new Collection([
     *     //     "bar" => "test"
     *     // ]);
     *
     * @param array|\Alexya\Tools\Collection $arr Array to combine.
     *
     * @return \Alexya\Tools\Collection The combination of this array and `$arr`.
     */
    public function combine($arr) : Collection
    {
        if($arr instanceof \Alexya\Tools\Collection) {
            $arr = $arr->getAll();
        }

        if(!is_array($arr)) {
            return new static([]);
        }

        return new static(array_combine($this->_data, $arr));
    }

    /**
     * Appends the contents of an array to this array.
     *
     * The indexes with same key will be overwritten.
     *
     * @param array|\Alexya\Tools\Collection $arr Array to append.
     */
    public function append($arr)
    {
        if($arr instanceof Collection) {
            $arr = $arr->getAll();
        }

        if(!is_array($arr)) {
            return;
        }

        foreach($arr as $key => $value) {
            $this->_data[$key] = $value;
        }
    }

    /**
     * Returns the amount of items in the array.
     *
     * @return int Size of the array.
     */
    public function count() : int
    {
        return count($this->_data);
    }

    /**
     * Returns all items.
     *
     * @return array The all mighty array.
     */
    public function getAll() : array
    {
        return $this->_data;
    }

    /**
     * Returns all values.
     *
     * @return array All mighty array's values.
     */
    public function values() : array
    {
        return array_values($this->_data);
    }

    /**
     * Returns all keys.
     *
     * @return array All mighty array's keys.
     */
    public function keys() : array
    {
        return array_keys($this->_data);
    }

    /**
     * Returns an index from the array.
     *
     * @param mixed $key Index key.
     *
     * @return mixed `$key`'s value.
     */
    public function get($key)
    {
        if(!isset($this->_data[$key])) {
            return null;
        }

        return $this->_data[$key];
    }

    /**
     * Sets an index in the array.
     *
     * @param mixed $key   Index key.
     * @param mixed $value Index value.
     */
    public function set($key, $value)
    {
        $this->_data[$key] = $value;
    }

    /**
     * Checks that a key exists in the array.
     *
     * @param mixed $key Index key.
     *
     * @return bool `true` if `$key` exists as a key in the array, `false` if not.
     */
    public function keyExists($key) : bool
    {
        return isset($this->_data[$key]);
    }

    /**
     * Checks that a value exists in the array.
     *
     * @param mixed $value  Index value.
     * @param bool  $strict Flag indicating that the value must be extactly the same or not (default = `true`).
     *
     * @return bool `true` if `$value` exists as a value in the array, `false` if not.
     */
    public function valueExists($value, bool $strict = true) : bool
    {
        foreach($this->_data as $data) {
            $ret = ($strict) ? ($data === $value)
                             : ($data == $value);

            if($ret) {
                return true;
            }
        }

        return false;
    }

    /**
     * Deletes an index in the array.
     *
     * @param mixed $key Index key.
     */
    public function deleteByKey($key)
    {
        unset($this->_data[$key]);
    }

    /**
     * Deletes an index in the array by its value.
     *
     * @param mixed $value  Index value.
     * @param bool  $strict Flag indicating that the value must be extactly the same or not (default = `true`).
     */
    public function deleteByValue($value, bool $strict)
    {
        foreach($this->_data as $data) {
            $exists = ($strict) ? ($data === $value)
                                : ($data == $value);

            if($exists) {
                unset($this->_data[$value]);
            }
        }
    }

    /////////////////////////
    // Start Magic Methods //
    /////////////////////////
    /**
     * Returns an index from the array.
     *
     * @param mixed $key Index key.
     *
     * @return mixed `$key`'s value.
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Sets an index in the array.
     *
     * @param mixed $key   Index key.
     * @param mixed $value Index value.
     */
    public function __set($key, $value)
    {
        return $this->set($key, $value);
    }

    /**
     * Checks that a key exists in the array.
     *
     * @param mixed $key Index key.
     *
     * @return bool `true` if `$key` exists as a key in the array, `false` if not.
     */
    public function __isset($key) : bool
    {
        return $this->keyExists($key);
    }

    /**
     * Deletes an index in the array.
     *
     * @param mixed $key Index key.
     */
    public function __unset($key)
    {
        return $this->delete($key);
    }
    ///////////////////////
    // End Magic Methods //
    ///////////////////////

    ////////////////////////
    // Start ArrayAccess  //
    ////////////////////////
    /**
     * Returns an index from the array.
     *
     * @param mixed $key Index key.
     *
     * @return mixed `$key`'s value.
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Sets an index in the array.
     *
     * @param mixed $key   Index key.
     * @param mixed $value Index value.
     */
    public function offsetSet($key, $value)
    {
        return $this->set($key, $value);
    }

    /**
     * Checks that a key exists in the array.
     *
     * @param mixed $key Index key.
     *
     * @return bool `true` if `$key` exists as a key in the array, `false` if not.
     */
    public function offsetExists($key) : bool
    {
        return $this->keyExists($key);
    }

    /**
     * Deletes an index in the array.
     *
     * @param mixed $key Index key.
     */
    public function offsetUnset($key)
    {
        return $this->delete($key);
    }
    //////////////////////
    // End Array Access //
    //////////////////////

}
