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

namespace Bardoqi\Sight\Tests\Fixture;

use Bardoqi\Sight\Enums\FormatterEnum;
use Bardoqi\Sight\Enums\MappingTypeEnum;
use Bardoqi\Sight\Mapping\FieldMapping;
use Bardoqi\Sight\Presenter;
use Bardoqi\Sight\Traits\PresenterTrait;

/**
 * Class NormalPresenter.
 */
final class NormalPresenter extends Presenter
{
    use PresenterTrait;

    /**
     * @return array
     */
    public function list_mapping()
    {
        $mappig = [
            'price' => FieldMapping::of()->key('price')
                ->src(FormatterEnum::TO_CURRENCY)
                ->type(MappingTypeEnum::DATA_FORMATER),
            'amount' => FieldMapping::of()->key('amount')
                ->src(FormatterEnum::TO_CNY)
                ->type(MappingTypeEnum::DATA_FORMATER),
            'sum' => FieldMapping::of()->key('sum')
                ->src(FormatterEnum::TO_USD)
                ->type(MappingTypeEnum::DATA_FORMATER),
            'divered_at' => FieldMapping::of()->key('divered_at')
                ->src(FormatterEnum::TO_DATE)
                ->type(MappingTypeEnum::DATA_FORMATER),
            'created_at' => FieldMapping::of()->key('created_at')
                ->src('created_at')
                ->type(MappingTypeEnum::METHOD_NAME),
            'updated_at' => FieldMapping::of()->key('updated_at')
                ->src('updated_at')
                ->type(MappingTypeEnum::METHOD_NAME),
            'deleted_at' => FieldMapping::of()->key('deleted_at')
                ->src('deleted_at')
                ->type(MappingTypeEnum::METHOD_NAME),
        ];

        return $mappig;
    }
}
