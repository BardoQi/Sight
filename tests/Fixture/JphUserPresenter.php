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

use Bardoqi\Sight\Enums\MappingTypeEnum as MTE;
use Bardoqi\Sight\Presenter;

/**
 * Class JphUserPresenter.
 */
final class JphUserPresenter extends Presenter
{
    /**
     * @var array
     */
    public $list_fields = ['id', 'name', 'username', 'email',
        'address_detail', 'phone', 'website', 'commpany_name', ];

    /**
     * @var array
     */
    public $list_mappings = [
        'address_detail' => ['src' => 'address', 'type' => MTE::METHOD_NAME],
        'commpany_name' => ['src' => 'company.name', 'type' => MTE::ARRAY_PATH, 'local'],
        'street' => ['src' => 'address.street', 'type' => MTE::ARRAY_PATH, 'local'],
        'suite' => ['src' => 'address.suite', 'type' => MTE::ARRAY_PATH, 'local'],
        'city' => ['src' => 'address.city', 'type' => MTE::ARRAY_PATH, 'local'],
        'zipcode' => ['src' => 'address.zipcode', 'type' => MTE::ARRAY_PATH, 'local'],
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $value
     *
     * @return void
     */
    public function address_detail($value)
    {
        // Just For test the function getValue,
        // Otherwise you could using the $value!
        $value = $this->getValue('street')."\r\n"
            .$this->getValue('suite')."\r\n"
            .$this->getValue('city').'  '
            .$this->getValue('cizipcodety');

        return $value;
    }
}
