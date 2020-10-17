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

use Bardoqi\Sight\Enums\MappingTypeEnum;
use Bardoqi\Sight\Enums\RelationEnum;
use Bardoqi\Sight\Tests\Fixture\JphUserAlbumsPresenter;
use Bardoqi\Sight\Tests\TestCase;

/**
 * Class FindByPathTest.
 */
final class FindByPathTest extends TestCase
{
    /* @test */
    public function testHasManyInnerJoinFindByPath()
    {
        $data = '[{"id":1,"name":"LeanneGraham","username":"Bret","email":"Sincere@april.biz","address":{"street":"KulasLight","suite":"Apt.556","city":"Gwenborough","zipcode":"92998-3874","geo":{"lat":"-37.3159","lng":"81.1496"}},"phone":"1-770-736-8031x56442","website":"hildegard.org","company":{"name":"Romaguera-Crona","catchPhrase":"Multi-layeredclient-serverneural-net","bs":"harnessreal-timee-markets"}}]';

        $user_array = json_decode($data, true);
        $user = new JphUserAlbumsPresenter();

        $house_array = json_decode('[{"userId":1,"id":1,"location":""}]', true);
        $house_array[0]['location'] = '{"lon":123.123456,"lat":32.456789}';

        $list_fields = [
            'id', 'name', 'username', 'email', 'address', 'phone', 'website', 'company',
            'house_id', 'house_lng', 'house_lat',
        ];

        $list_mapping = [
            'house_id' => ['src' => 'id', 'type' => MappingTypeEnum::JOIN_FIELD, 'alias' => 'house'],
            'house_lng' => ['src' => 'location.lon', 'type' => MappingTypeEnum::ARRAY_PATH, 'alias' => 'house'],
            'house_lat' => ['src' => 'location.lat', 'type' => MappingTypeEnum::ARRAY_PATH, 'alias' => 'house'],
        ];

        $users = $user->selectFields($list_fields)
            ->fromLocal($user_array)
            ->innerJoinForeign($house_array, 'house', 'userId')
            ->onRelation('id', 'house', 'userId', RelationEnum::HAS_MANY)
            ->addFieldMappingList($list_mapping)
            ->toArray();
        $this->assertTrue(isset($users[0]['house_id']));
        $this->assertTrue(isset($users[0]['house_lng']));
    }

    /* @test */
    public function testHasOneInnerJoinFindByPath()
    {
        $data = '[{"id":1,"name":"LeanneGraham","username":"Bret","email":"Sincere@april.biz","address":{"street":"KulasLight","suite":"Apt.556","city":"Gwenborough","zipcode":"92998-3874","geo":{"lat":"-37.3159","lng":"81.1496"}},"phone":"1-770-736-8031x56442","website":"hildegard.org","company":{"name":"Romaguera-Crona","catchPhrase":"Multi-layeredclient-serverneural-net","bs":"harnessreal-timee-markets"}}]';

        $user_array = json_decode($data, true);
        $user = new JphUserAlbumsPresenter();

        $house_array = json_decode('[{"userId":1,"id":1,"location":""}]', true);
        $house_array[0]['location'] = '{"lon":123.123456,"lat":32.456789}';

        $list_fields = [
            'id', 'name', 'username', 'email', 'address', 'phone', 'website', 'company',
            'house_id', 'house_lng', 'house_lat',
        ];

        $list_mapping = [
            'house_id' => ['src' => 'id', 'type' => MappingTypeEnum::JOIN_FIELD, 'alias' => 'house'],
            'house_lng' => ['src' => 'location.lon', 'type' => MappingTypeEnum::ARRAY_PATH, 'alias' => 'house'],
            'house_lat' => ['src' => 'location.lat', 'type' => MappingTypeEnum::ARRAY_PATH, 'alias' => 'house'],
        ];

        $users = $user->selectFields($list_fields)
            ->fromLocal($user_array)
            ->innerJoinForeign($house_array, 'house', 'userId')
            ->onRelation('id', 'house', 'userId', RelationEnum::HAS_ONE)
            ->addFieldMappingList($list_mapping)
            ->toArray();
        $this->assertTrue(isset($users[0]['house_id']));
        $this->assertTrue(isset($users[0]['house_lng']));
    }

    /* @test */
    public function testHasManyOuterJoinFindByPath()
    {
        $data = '[{"id":1,"name":"LeanneGraham","username":"Bret","email":"Sincere@april.biz","address":{"street":"KulasLight","suite":"Apt.556","city":"Gwenborough","zipcode":"92998-3874","geo":{"lat":"-37.3159","lng":"81.1496"}},"phone":"1-770-736-8031x56442","website":"hildegard.org","company":{"name":"Romaguera-Crona","catchPhrase":"Multi-layeredclient-serverneural-net","bs":"harnessreal-timee-markets"}}]';

        $user_array = json_decode($data, true);
        $user = new JphUserAlbumsPresenter();

        $house_array = json_decode('[{"userId":1,"id":1,"location":""}]', true);
        $house_array[0]['location'] = '{"lon":123.123456,"lat":32.456789}';

        $list_fields = [
            'id', 'name', 'username', 'email', 'address', 'phone', 'website', 'company',
            'house_id', 'house_lng', 'house_lat',
        ];

        $list_mapping = [
            'house_id' => ['src' => 'id', 'type' => MappingTypeEnum::JOIN_FIELD, 'alias' => 'house'],
            'house_lng' => ['src' => 'location.lon', 'type' => MappingTypeEnum::ARRAY_PATH, 'alias' => 'house'],
            'house_lat' => ['src' => 'location.lat', 'type' => MappingTypeEnum::ARRAY_PATH, 'alias' => 'house'],
        ];

        $users = $user->selectFields($list_fields)
            ->fromLocal($user_array)
            ->outerJoinForeign($house_array, 'house', 'userId')
            ->onRelation('id', 'house', 'userId', RelationEnum::HAS_MANY)
            ->addFieldMappingList($list_mapping)
            ->toArray();
        $this->assertTrue(isset($users[0]['house_id']));
        $this->assertTrue(isset($users[0]['house_lng']));
    }

    /* @test */
    public function testHasOneOuterJoinFindByPath()
    {
        $data = '[{"id":1,"name":"LeanneGraham","username":"Bret","email":"Sincere@april.biz","address":{"street":"KulasLight","suite":"Apt.556","city":"Gwenborough","zipcode":"92998-3874","geo":{"lat":"-37.3159","lng":"81.1496"}},"phone":"1-770-736-8031x56442","website":"hildegard.org","company":{"name":"Romaguera-Crona","catchPhrase":"Multi-layeredclient-serverneural-net","bs":"harnessreal-timee-markets"}}]';

        $user_array = json_decode($data, true);
        $user = new JphUserAlbumsPresenter();

        $house_array = json_decode('[{"userId":1,"id":1,"location":""}]', true);
        $house_array[0]['location'] = '{"lon":123.123456,"lat":32.456789}';

        $list_fields = [
            'id', 'name', 'username', 'email', 'address', 'phone', 'website', 'company',
            'house_id', 'house_lng', 'house_lat',
        ];

        $list_mapping = [
            'house_id' => ['src' => 'id', 'type' => MappingTypeEnum::JOIN_FIELD, 'alias' => 'house'],
            'house_lng' => ['src' => 'location.lon', 'type' => MappingTypeEnum::ARRAY_PATH, 'alias' => 'house'],
            'house_lat' => ['src' => 'location.lat', 'type' => MappingTypeEnum::ARRAY_PATH, 'alias' => 'house'],
        ];

        $users = $user->selectFields($list_fields)
            ->fromLocal($user_array)
            ->outerJoinForeign($house_array, 'house', 'userId')
            ->onRelation('id', 'house', 'userId', RelationEnum::HAS_ONE)
            ->addFieldMappingList($list_mapping)
            ->toArray();
        $this->assertTrue(isset($users[0]['house_id']));
        $this->assertTrue(isset($users[0]['house_lng']));
    }

    /* @test */
    public function testHasManyInnerJoinFindByPathWithEmptyRecord()
    {
        $data = '[{"id":1,"name":"LeanneGraham","username":"Bret","email":"Sincere@april.biz","address":{"street":"KulasLight","suite":"Apt.556","city":"Gwenborough","zipcode":"92998-3874","geo":{"lat":"-37.3159","lng":"81.1496"}},"phone":"1-770-736-8031x56442","website":"hildegard.org","company":{"name":"Romaguera-Crona","catchPhrase":"Multi-layeredclient-serverneural-net","bs":"harnessreal-timee-markets"}}]';

        $user_array = json_decode($data, true);
        $user = new JphUserAlbumsPresenter();

        $house_array = json_decode('[{"userId":0,"id":1,"location":""}]', true);
        $house_array[0]['location'] = '{"lon":123.123456,"lat":32.456789}';

        $list_fields = [
            'id', 'name', 'username', 'email', 'address', 'phone', 'website', 'company',
            'house_id', 'house_lng', 'house_lat',
        ];

        $list_mapping = [
            'house_id' => ['src' => 'id', 'type' => MappingTypeEnum::JOIN_FIELD, 'alias' => 'house'],
            'house_lng' => ['src' => 'location.lon', 'type' => MappingTypeEnum::ARRAY_PATH, 'alias' => 'house'],
            'house_lat' => ['src' => 'location.lat', 'type' => MappingTypeEnum::ARRAY_PATH, 'alias' => 'house'],
        ];

        $users = $user->selectFields($list_fields)
            ->fromLocal($user_array)
            ->innerJoinForeign($house_array, 'house', 'userId')
            ->onRelation('id', 'house', 'userId', RelationEnum::HAS_MANY)
            ->addFieldMappingList($list_mapping)
            ->toArray();
        $this->assertTrue(empty($users));
    }

    /* @test */
    public function testHasOneInnerJoinFindByPathWithEmptyRecord()
    {
        $data = '[{"id":1,"name":"LeanneGraham","username":"Bret","email":"Sincere@april.biz","address":{"street":"KulasLight","suite":"Apt.556","city":"Gwenborough","zipcode":"92998-3874","geo":{"lat":"-37.3159","lng":"81.1496"}},"phone":"1-770-736-8031x56442","website":"hildegard.org","company":{"name":"Romaguera-Crona","catchPhrase":"Multi-layeredclient-serverneural-net","bs":"harnessreal-timee-markets"}}]';

        $user_array = json_decode($data, true);
        $user = new JphUserAlbumsPresenter();

        $house_array = json_decode('[{"userId":0,"id":1,"location":""}]', true);
        $house_array[0]['location'] = '{"lon":123.123456,"lat":32.456789}';

        $list_fields = [
            'id', 'name', 'username', 'email', 'address', 'phone', 'website', 'company',
            'house_id', 'house_lng', 'house_lat',
        ];

        $list_mapping = [
            'house_id' => ['src' => 'id', 'type' => MappingTypeEnum::JOIN_FIELD, 'alias' => 'house'],
            'house_lng' => ['src' => 'location.lon', 'type' => MappingTypeEnum::ARRAY_PATH, 'alias' => 'house'],
            'house_lat' => ['src' => 'location.lat', 'type' => MappingTypeEnum::ARRAY_PATH, 'alias' => 'house'],
        ];

        $users = $user->selectFields($list_fields)
            ->fromLocal($user_array)
            ->innerJoinForeign($house_array, 'house', 'userId')
            ->onRelation('id', 'house', 'userId', RelationEnum::HAS_ONE)
            ->addFieldMappingList($list_mapping)
            ->toArray();
        $this->assertTrue(empty($users));
    }
}
