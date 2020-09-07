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

namespace Bardoqi\Sight\Exceptions;

use InvalidArgumentException;
use Throwable;
/**
 * Class AbstractPresenterException
 *
 * @package Bardoqi\Sight\Exceptions
 */
abstract class InvalidArgumentExceptionAbstract extends InvalidArgumentException implements Throwable
{

}
