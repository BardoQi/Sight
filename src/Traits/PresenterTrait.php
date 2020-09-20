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

namespace Bardoqi\Sight\Traits;

/**
 * Trait PresenterTrait.
 */
trait PresenterTrait
{
    /**
     * @param $value
     *
     * @return string
     */
    public function created_at($value)
    {
        return date('Y-m-d H:i:s', intval($value));
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function updated_at($value)
    {
        return date('Y-m-d H:i:s', intval($value));
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function deleted_at($value)
    {
        return date('Y-m-d H:i:s', intval($value));
    }
}
