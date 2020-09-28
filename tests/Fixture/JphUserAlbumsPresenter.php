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

    public function __construct()
    {
        parent::__construct();
    }
}
