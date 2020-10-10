<?php

declare(strict_types=1);
/*
 * This file is part of the bardoqi/sight package.
 *
 * (c) BardoQi <bardoqi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bardoqi\Sight\Registries;

/**
 * Class FunctionRegister.
 */
final class FunctionRegistry
{
    /**
     * @var null | FunctionRegistry
     */
    public static $instance = null;

    /**
     * @var array
     */
    private $callables = [];

    /**
     * FunctionRegister construct.
     */
    private function __construct()
    {
    }

    /**
     * @return \Bardoqi\Sight\Registries\FunctionRegistry|null
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @param $name
     * @param $parameters
     *
     * @return mixed
     */
    public function forwardCall($name, $parameters)
    {
        [$object, $method] = $this->callables[$name];
        if (! is_array($parameters)) {
            $parameters = [$parameters];
        }

        return call_user_func_array([$object, $method], $parameters);
    }

    /**
     * @param $function_alias
     * @param $object
     * @param $method_name
     *
     * @return void
     */
    public function setItem($function_alias, $object, $method_name)
    {
        $this->callables[$function_alias] = [$object, $method_name];
    }
}
