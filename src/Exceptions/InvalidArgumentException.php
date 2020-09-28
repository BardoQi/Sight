<?php

/*
 * This file is part of the bardoqi/sight package.
 *
 * (c) BardoQi <bardoqi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Bardoqi\Sight\Exceptions;

use Throwable;

/**
 * Class PresenterException.
 */
class InvalidArgumentException extends InvalidArgumentExceptionAbstract implements Throwable
{
    /**
     * @return InvalidArgumentException
     */
    public static function KeyedByCannotBeEmpty()
    {
        return new self('KeyedBy can not be empty!');
    }

    /**
     * @param $name
     *
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function KeyedByIsNotCorrect($name)
    {
        return new self('The KeyedBy '.$name.' is not correct!');
    }

    /**
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function AliasCanNotBeEmpty()
    {
        return new self('Alias Can Not Be Empty!');
    }

    /**
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function MappingKeyCanNotBeEmpty()
    {
        return new self('Mapping Key Can Not Be Empty!');
    }

    /**
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function MappingSourceCanNotBeEmpty()
    {
        return new self('Mapping Source Can Not Be Empty!');
    }

    /**
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function MappingTypeIsNotValid()
    {
        return new self('Mapping Type Is Not Valid!');
    }

    /**
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function FieldMappingListNotFound()
    {
        return new self('Field Mapping List Not Found!');
    }

    /**
     * @param $message
     *
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function MappingArrayIsNotValid($message)
    {
        return new self('Mapping Array Is Not Valid! Message: '.$message);
    }

    /**
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function ParamsOfRelationIsMissing()
    {
        return new self('Params Of Relation Is Missing!');
    }

    /**
     * @param $method
     *
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function MethodNotFound($method)
    {
        return new self('The method '.$method.' not found!');
    }

    /**
     * @param $name
     *
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function FunctionMustBeCallable($name)
    {
        return new self('Function '.$name.' Must Be Callable!');
    }

    /**
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function PaginateDataNotFound()
    {
        return new self('The Paginate Data Not Found!');
    }

    /**
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function LocalAliasIsNotCorrect()
    {
        return new self('Local Alias Is Not Correct!');
    }

    /**
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function ForeignAliasNotExists()
    {
        return new self('Foreign Alias Not Exists! Please call innerJoinForeign() or outerJoinForeign()function first');
    }

    /**
     * @param $offset
     *
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function UndefinedOffset($offset)
    {
        return new self('Undefined Offset '.$offset.' !');
    }

    /**
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function ParamaterIsNotArray()
    {
        return new self('Paramater Is Not Array !');
    }

    /**
     * @param $name
     *
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function FieldOrMappingNotFound($name)
    {
        return new self('Field Or Mapping '.$name.' Not Found, Or value is null! Please give default value!');
    }

    /**
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function LocalArrayCantBeEmpty()
    {
        return new self("Local Array Can't Be Empty!");
    }

    /**
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function RelationParamaterMustBeKeyValue()
    {
        return new self('Relation Paramater Must Be Structure Of Key-Value!');
    }

    /**
     * @param $name
     *
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function FieldMappingIsInvalid($name)
    {
        return new self('Field Mapping '.$name.' Is Invalid!');
    }

    /**
     * @param $name
     *
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function FunctionExistsAlready($name)
    {
        return new self('Function '.$name.' Exists Already!');
    }

    /**
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function ItemIsNotJsonString()
    {
        return new self('Item Is Not Json String!');
    }

    /**
     * @return \Bardoqi\Sight\Exceptions\InvalidArgumentException
     */
    public static function ThereMustBeAtLeast1EmptyItemInTheJoinArray()
    {
        return new self('There Must Be At Least 1 Empty Item In The Join Array!');
    }
}
