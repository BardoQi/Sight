<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-09-06
 * Time: 23:28
 */

namespace Bardoqi\Sight\Enums;

/**
 * Class FormatterEnum
 *
 * @package Bardoqi\Sight\Enums
 */
final class FormatterEnum
{
    const TO_DATE = 'toDate';
    const TO_DATETIME = 'toDatetime';
    const TO_TIME = 'toTime';
    const TO_BOOL = 'toBool';
    const TO_CURRENCY = 'toCurrency';
    const TO_CNY = 'toCny';
    const TO_USD = 'toUsd';
}
