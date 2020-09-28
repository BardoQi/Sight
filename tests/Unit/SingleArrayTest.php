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

namespace Bardoqi\Sight\Tests\Unit;

use Bardoqi\Sight\Tests\Fixture\JphUserPresenter;
use Bardoqi\Sight\Tests\Fixture\Mock;
use Bardoqi\Sight\Tests\Fixture\UserPresenter;
use Bardoqi\Sight\Tests\TestCase;

/**
 * Class SingleArrayTest.
 */
final class SingleArrayTest extends TestCase
{
    public $userPresenter;

    /** @atest */
    public function testPresenterCreate()
    {
        $user_array_string = include dirname(dirname(__DIR__)).'/tests/Fixture/Users.php';
        $user_array = json_decode($user_array_string, true);
        $user = new UserPresenter();
        $users = $user->selectFields(['id', 'username', 'mobile', 'name', 'avatar_id', 'created_at', 'updated_at'])
         ->fromLocal($user_array, 'user')
         ->toArray();
        $this->assertTrue(is_array($users));
    }

    /** @test */
    public function testFindByPathAndMergeFields()
    {
        $user_array = Mock::getLocalData(Mock::USER_DATA);
        $user = new JphUserPresenter();

        $users = $user->selectFields($user->list_fields)
            ->fromLocal($user_array)
            ->addFieldMappingList($user->list_mappings)
            ->toArray();
        $this->assertTrue(isset($users[0]['address_detail']));
        $this->assertTrue(isset($users[0]['commpany_name']));
    }
}
