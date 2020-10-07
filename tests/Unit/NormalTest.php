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
use Bardoqi\Sight\Enums\PaginateTypeEnum;
use Bardoqi\Sight\Exceptions\InvalidArgumentException;
use Bardoqi\Sight\Tests\Fixture\NormalPresenter;
use Bardoqi\Sight\Tests\TestCase;

/**
 * Class NormalTest.
 */
final class NormalTest extends TestCase
{
    protected $data = '[{"id":1,"parent_id":0,"price":12.345,"amount":35,"sum":432.075,"divered_at":1328119322,"created_at":1328464922,"updated_at":1328983322,"deleted_at":1334167322},{"id":2,"parent_id":0,"price":13.456,"amount":37,"sum":497.872,"divered_at":1328205722,"created_at":1328724122,"updated_at":1330020122,"deleted_at":1336240922},{"id":3,"parent_id":0,"price":14.567,"amount":39,"sum":568.113,"divered_at":1328292122,"created_at":1328983322,"updated_at":1331056922,"deleted_at":1338314522},{"id":4,"parent_id":1,"price":15.678,"amount":41,"sum":642.798,"divered_at":1328378522,"created_at":1329242522,"updated_at":1332093722,"deleted_at":1340388122},{"id":5,"parent_id":1,"price":16.789,"amount":43,"sum":721.927,"divered_at":1328464922,"created_at":1328983322,"updated_at":1333130522,"deleted_at":1342461722},{"id":6,"parent_id":1,"price":17.9,"amount":45,"sum":805.5,"divered_at":1328551322,"created_at":1329760922,"updated_at":1334167322,"deleted_at":1344535322},{"id":7,"parent_id":2,"price":19.011,"amount":47,"sum":893.517,"divered_at":1328637722,"created_at":1330020122,"updated_at":1335204122,"deleted_at":1346608922},{"id":8,"parent_id":2,"price":20.122,"amount":49,"sum":985.978,"divered_at":1328724122,"created_at":1330279322,"updated_at":1336240922,"deleted_at":1348682522},{"id":9,"parent_id":2,"price":21.233,"amount":51,"sum":1082.883,"divered_at":1328810522,"created_at":1330538522,"updated_at":1337277722,"deleted_at":1350756122},{"id":10,"parent_id":3,"price":22.344,"amount":53,"sum":1184.232,"divered_at":1328896922,"created_at":1330797722,"updated_at":1338314522,"deleted_at":1352829722},{"id":11,"parent_id":3,"price":23.455,"amount":55,"sum":1290.025,"divered_at":1328983322,"created_at":1331056922,"updated_at":1339351322,"deleted_at":1354903322},{"id":12,"parent_id":3,"price":24.566,"amount":57,"sum":1400.262,"divered_at":1329069722,"created_at":1331316122,"updated_at":1340388122,"deleted_at":1356976922},{"id":13,"parent_id":4,"price":25.677,"amount":59,"sum":1514.943,"divered_at":1329156122,"created_at":1331575322,"updated_at":1341424922,"deleted_at":1359050522},{"id":14,"parent_id":4,"price":26.788,"amount":61,"sum":1634.068,"divered_at":1329242522,"created_at":1331834522,"updated_at":1342461722,"deleted_at":1361124122},{"id":15,"parent_id":4,"price":27.899,"amount":63,"sum":1757.637,"divered_at":1329328922,"created_at":1332093722,"updated_at":1343498522,"deleted_at":1363197722},{"id":16,"parent_id":5,"price":29.01,"amount":65,"sum":1885.65,"divered_at":1329415322,"created_at":1332352922,"updated_at":1344535322,"deleted_at":1365271322},{"id":17,"parent_id":5,"price":30.121,"amount":67,"sum":2018.107,"divered_at":1329501722,"created_at":1332612122,"updated_at":1345572122,"deleted_at":1367344922},{"id":18,"parent_id":5,"price":31.232,"amount":69,"sum":2155.008,"divered_at":1329588122,"created_at":1332871322,"updated_at":1346608922,"deleted_at":1369418522},{"id":19,"parent_id":6,"price":32.343,"amount":71,"sum":2296.353,"divered_at":1329674522,"created_at":1333130522,"updated_at":1347645722,"deleted_at":1371492122},{"id":20,"parent_id":6,"price":33.454,"amount":73,"sum":2442.142,"divered_at":1329760922,"created_at":1333389722,"updated_at":1348682522,"deleted_at":1373565722}]';

    /** @test */
    public function testTraitAndFormatters()
    {
        $src_data = json_decode($this->data, true);
        $NormalPresenter = NormalPresenter::of()->selectFields('id,parent_id,price,amount,sum,divered_at,created_at,updated_at,deleted_at')
            ->fromLocal($src_data);
        foreach ($NormalPresenter->list_mapping() as $key => $mapping) {
            $NormalPresenter->addFieldMappingByObject($mapping);
        }
        $NormalPresenter->addFieldMapping(
            'deleted_at',
            'deleted_at',
            MappingTypeEnum::METHOD_NAME,
            '');
        $tree_data = $NormalPresenter->toTreeArray();
        $this->assertTrue(count($tree_data) === 3);
        $this->assertTrue(count($tree_data[0]['children']) > 0);
        $this->assertTrue($tree_data[0]['children'][0]['id'] == 4);
    }

    /** @test */
    public function fromLocalTest()
    {
        $src_data = ['sommepath' => ['subpath' => '']];
        try {
            $NormalPresenter = NormalPresenter::of()->selectFields('id,parent_id,price,amount,sum,divered_at,created_at,updated_at,deleted_at')
                ->fromLocal($src_data, '', 'sommepath.subpath');
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }

        $src_data = ['sommepath' => ['subpath' => []]];
        try {
            $NormalPresenter = NormalPresenter::of()->selectFields('id,parent_id,price,amount,sum,divered_at,created_at,updated_at,deleted_at')
                ->fromLocal($src_data, '', 'sommepath.subpath');
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
            $NormalPresenter = NormalPresenter::of()->selectFields('id,parent_id,price,amount,sum,divered_at,created_at,updated_at,deleted_at')
            ->fromLocal($src_data, '', 'sommepath.subpath');
        } catch (\Exception $e) {
            $this->assertTrue($e instanceof InvalidArgumentException);
        }
    }

    /** @test */
    public function apiFunctionTest()
    {
        $NormalPresenter = NormalPresenter::of();
        $error = 'This is a test error!';
        $NormalPresenter->setError($error);
        $get_error = $NormalPresenter->getError();
        $this->assertTrue($error === $get_error);

        $err_code = $NormalPresenter->getStatusCode();
        $this->assertTrue($err_code === 100);

        $message = 'This is a test error message!';
        $NormalPresenter->setMessage($message);
        $get_message = $NormalPresenter->getMessage();
        $this->assertTrue($message === $get_message);

        $NormalPresenter->setStatusCode(200);
        $status_code = $NormalPresenter->getStatusCode();
        $this->assertTrue($status_code === 200);
    }

    /** @test */
    public function testPaginateData()
    {
        $src_data = json_decode($this->data, true);
        $src_data = ['sommepath' => [
            'subpath' => [
                'data' => $src_data,
                'current_page' => 1,
                'from' => 1,
                'last_page' => 1,
                'per_page' => 15,
                'to' => 1,
                'total' => 0,
            ],
            ],
        ];
        $NormalPresenter = NormalPresenter::of()->selectFields('id,parent_id,price,amount,sum,divered_at,created_at,updated_at,deleted_at')
            ->fromLocal($src_data, 'local', 'sommepath.subpath');
        foreach ($NormalPresenter->list_mapping() as $key => $mapping) {
            $NormalPresenter->addFieldMappingByObject($mapping);
        }
        $NormalPresenter->addFieldMapping(
            'deleted_at',
            'deleted_at',
            MappingTypeEnum::METHOD_NAME,
            '');
        $data = $NormalPresenter->toPaginateArray(PaginateTypeEnum::PAGINATE_API);
        $this->assertTrue($data['current_page'] === 1);
    }

    /** @test */
    public function addFunctionTest()
    {
        $NormalPresenter = NormalPresenter::of();
        $NormalPresenter->addFunction("area_of",function ($value){
            return number_format($value,2) . " ㎡";
        });
        $this->assertTrue($NormalPresenter->hasMethod('area_of'));
        // test call
        $test_area = $NormalPresenter->area_of(3.1415);
        $this->assertTrue("3.14 ㎡" === $test_area);

        $NormalPresenter->addMethod("area_value",function ($value){
            return number_format($value,2) . " ㎡";
        });
        $this->assertTrue($NormalPresenter->hasMethod('area_value'));
        // test call
        $test_area = $NormalPresenter->area_value(3.1415);
        $this->assertTrue("3.14 ㎡" === $test_area);

        $NormalPresenter->addFormatter("toArea",function ($value){
            return number_format($value,2) . " ㎡";
        });
        $formatter = $NormalPresenter->data_formatter;
        $test_area = call_user_func_array([$formatter, 'format'], ["toArea", 3.1415]);
        $this->assertTrue("3.14 ㎡" === $test_area);
    }
}
