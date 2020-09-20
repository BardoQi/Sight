<?php

/*
 * This file is part of the bardoqi/sight package.
 *
 * (c) BardoQi <67158925@qq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bardoqi\Sight\Formatters;

use Bardoqi\Sight\Exceptions\InvalidArgumentException;
use Closure;

/**
 * Class DataFormater.
 */
class DataFormatter
{
    /**
     * Keep the method added on fly.
     *
     * @var array
     */
    protected $macros = [];
    /**
     * @var null| \Bardoqi\Sight\Formatters\DataFormatter
     */
    public static $instance = null;

    private function __construct()
    {
    }

    /**
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * @return \Bardoqi\Sight\Formatters\DataFormatter
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * Add the method on fly.
     *
     * @param $function_name
     * @param $methodCallable
     *
     * @return bool
     */
    public function addFunction($function_name, $methodCallable)
    {
        if (!is_callable($methodCallable)) {
            throw InvalidArgumentException::FunctionMustBeCallable($function_name);
        }
        $this->macros[$function_name] = Closure::bind($methodCallable, $this, get_class());

        return true;
    }

    /**
     * @param $method_name
     * @param $callable
     *
     * @return bool
     */
    public function addMethod($method_name, $callable)
    {
        return $this->addFunction($method_name, $callable);
    }

    /**
     * @param $method
     *
     * @return bool
     */
    public function hasMothod($method)
    {
        if (method_exists($this, $method)) {
            return true;
        }
        if (isset($this->macros[$method])) {
            return true;
        }

        return false;
    }

    /**
     * @param $formatter
     * @param $value
     *
     * @return void
     */
    public function format($formatter, $value)
    {
        if (isset($this->macros[$formatter])) {
            return call_user_func($this->macros[$formatter], $value);
        }
        $class = get_called_class();
        if (method_exists($class, $formatter)) {
            return call_user_func([$class, $formatter], $value);
        }

        throw InvalidArgumentException::MethodNotFound($formatter);
    }

    /**
     * @param $value
     *
     * @return false|string
     */
    public function toDate($value)
    {
        return date('Y-m-d', intval($value));
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function toTime($value)
    {
        return date('H:i:s', intval($value));
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function toDatetime($value)
    {
        return date('Y-m-d H:i:s', intval($value));
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function toCurrency($value)
    {
        return number_format(intval($value), 4);
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function toCNY($value)
    {
        return '￥'.number_format(intval($value), 2);
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function toUSD($value)
    {
        return '$'.number_format(intval($value), 2);
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function toBool($value)
    {
        return (empty($value)) ? 'false' : 'true';
    }
}
