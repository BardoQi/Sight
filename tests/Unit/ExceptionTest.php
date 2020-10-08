<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-10-08
 * Time: 23:03.
 */

namespace Bardoqi\Sight\Tests\Unit;

use Bardoqi\Sight\Enums\MappingTypeEnum;
use Bardoqi\Sight\Exceptions\InvalidArgumentException;
use Bardoqi\Sight\Mapping\FieldMapping;
use Bardoqi\Sight\Relations\Relation;
use Bardoqi\Sight\Tests\Fixture\JphUserAlbumsPresenter;
use Bardoqi\Sight\Tests\Fixture\Mock;
use Bardoqi\Sight\Tests\TestCase;

/**
 * Class ExceptionTest.
 */
final class ExceptionTest extends TestCase
{
    public function testExceptions()
    {
        $user_array = Mock::getLocalData(Mock::USER_DATA);
        $albums_array = Mock::getLocalData(Mock::ALNUMS_DATA);
        $user = JphUserAlbumsPresenter::of();
        $users = $user->selectFields($user->list_fields);

        try {
            $users = $users->fromLocal('');
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        try {
            $users = $users->fromLocal([]);
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        $src_data = ['sommepath' => [
            'subpath' => [
                'data' => [],
                'current_page' => 1,
                'from' => 1,
                'last_page' => 1,
                'per_page' => 15,
                'to' => 1,
                'total' => 0,
            ],
            ],
        ];

        try {
            $users = $users->fromLocal($src_data, 'local', 'sommepath.subpath');
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        $users = JphUserAlbumsPresenter::of();
        $users = $users->fromLocal($user_array);

        try {
            $users = $users->outerJoinForeign($albums_array, 'albums', '');
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        try {
            $users = $users->outerJoinForeign($albums_array, 'albums', 'uid');
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        try {
            $users = $users->outerJoinForeign([], 'albums', 'userId');
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        $users = $users->outerJoinForeign($albums_array, 'albums', 'userId');
        try {
            $users->onRelationbyObject(
            Relation::of('id', 'albums') //'userId'
            );
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        try {
            $users->onRelation('id', 'aLbums', 'userId');
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        try {
            $users->onRelationbyObject(Relation::fromArray(['id', 'aLbums', 'userId']));
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        try {
            $users->addFieldMappingByObject(
            FieldMapping::of('', 'id', MappingTypeEnum::JOIN_FIELD, 'albums')
            );
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        try {
            $users->addFieldMappingByObject(
            FieldMapping::of('albums_id', '', MappingTypeEnum::JOIN_FIELD, 'albums')
            );
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        try {
            $users->addFieldMappingByObject(
            FieldMapping::of('albums_id', 'id', 0, 'albums')
            );
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        $list_mapping = [
            'albums_id' => ['type' => MappingTypeEnum::JOIN_FIELD, 'alias' => 'albums'],
            'albums_title' => ['type' => MappingTypeEnum::JOIN_FIELD, 'alias' => 'albums'],
        ];
        $users->addFieldMappingList($list_mapping);

        try {
            $users = $users->getSome();
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        try {
            $users = $users->addMethod('test', 'badFunction');
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        try {
            $users = $users->addFunction('test', 'badFunction');
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        try {
            $users = $users->toArray();
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        try {
            $users = $users->onRelation('id', 'albums', 'userId')
                ->addFieldMappingList($user->list_mapping)
                ->toPaginateArray();
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }
    }
}
