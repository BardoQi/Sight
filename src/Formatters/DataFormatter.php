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
 * Class DataFormater
 *
 * @package Bardoqi\Sight\DataFormaters
 */
class DataFormatter
{

    /**
     * Keep the method added on fly
     * @var array
     */
    protected $macros = [];
    /**
     * @var null|Bardoqi\Sight\DataFormaters\DataFormatter
     */
    public static $instance = null;

    /**
     *
     */
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
     * @return \Bardoqi\Sight\DataFormaters\DataFormatter
     */
    public static function getInstance(){
        if(null === self::$instance){
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * Add the method on fly.
     * @param $function_name
     * @param $function
     *
     * @return void
     */
    public function addFunction($function_name,$function){
        if (!is_callable($function)) {
            throw InvalidArgumentException::FunctionMustBeCallable($function_name);
        }
        $this->marcro[$function_name] = Closure::bind($methodCallable, $this, get_class());
    }

    /**
     * @param $method_name
     * @param $callable
     *
     * @return void
     */
    public function addMethod($method_name,$callable){
        return $this->addFunction($method_name,$callable);
    }

    /**
     * @param $formatter
     * @param $value
     *
     * @return void
     */
    public function format($formatter,$value){
        if(isset($this->macros[$formatter])){
            return call_user_func($this->macros[$formatter], $value);
        }
        $class = get_called_class();
        if(method_exists($class,$formatter)){
            return call_user_func([$class,$formatter],$value);
        }
        throw InvalidArgumentException::MethodNotFound($method);
    }

    /**
     * @param $value
     *
     * @return false|string
     */
    public function toDate($value){

        return date("Y-m-d",intval($value));
    }

    /**
     * @param $value
     *
     * @return false|string
     */
    public function toTime($value){
        return date("H:i:s",intval($value));
    }

    /**
     * @param $value
     *
     * @return false|string
     */
    public function toDatetime($value){
        return date("Y-m-d H:i:s",intval($value));
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function toCurrency($value){
        return number_format(intval($value),4);
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function toCNY($value){
        return "￥" . number_format(intval($value),2);
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function toUSD($value){
        return "$" . number_format(intval($value),2);
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function toBool($value){
        return (empty($value))?"false":"true";
    }

}
