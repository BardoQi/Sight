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

namespace Bardoqi\Sight\Traits;

/**
 * Trait PresenterTrait
 *
 * @package Bardoqi\Sight\Traits
 */
trait PresenterTrait
{
    /**
     * @param $value
     *
     * @return false|string
     */
    public function created_at($value){
        return date("Y-m-d H:i:s",$value);
    }

    /**
     * @param $value
     *
     * @return false|string
     */
    public function updated_at($value){
        return date("Y-m-d H:i:s",$value);
    }

    /**
     * @param $value
     *
     * @return false|string
     */
    public function deleted_at($value){
        return date("Y-m-d H:i:s",$value);
    }


}
