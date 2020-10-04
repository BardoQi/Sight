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

use Bardoqi\Sight\Enums\MappingTypeEnum;
use Bardoqi\Sight\Presenter;
use Illuminate\Support\Arr;

/**
 * Class JphUserAlbums.
 */
final class JphUserAlbumsPresenter extends Presenter
{
    public $list_fields = [
        'id', 'name', 'username', 'email', 'address', 'phone', 'website', 'company',
        'albums_id', 'albums_title',
    ];

    public $list_mapping = [
        'albums_id' => ['src' => 'id', 'type' => MappingTypeEnum::JOIN_FIELD, 'alias' => 'albums'],
        'albums_title' => ['src' => 'title', 'type' => MappingTypeEnum::JOIN_FIELD, 'alias' => 'albums'],
    ];

    public $list_merge_fields = [
        'id', 'name', 'username', 'email', 'address', 'phone', 'website', 'company',
        'albums',
    ];

    public $list_merge_mapping = [
        'albums' => ['src' => 'albums', 'type' => MappingTypeEnum::METHOD_NAME],
    ];


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $value
     *
     * @return array
     */
    public function albums($value)
    {
        $albums = $this->getCurrentItem()->getData('albums');
        $out_array = [];
        foreach ($albums as $key => $item) {
            if (is_array($item)) {
                $out_array[] = Arr::only($item, ['id', 'title']);
            }
        }

        return $out_array;
    }
}
