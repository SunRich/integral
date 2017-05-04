<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Integrals;

class IntegralTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * 用户添加积分
     */
    public function testAddIntegral()
    {
        $Integral = new Integrals();
        $Integral->addOneRecord(1, 1, '测试添加积分');
        $this->seeInDatabase('user_integrals', ['user_id' => 1, 'integral' => 1, 'content' => '测试添加积分']);
        $this->post('/add', ['userId' => '1', 'integral' => 1])
            ->seeJson([
                'code' => 200,
                'result' => 1
            ]);//测试请求
    }

    /**
     * 用户消耗积分
     */
    public function testExpendUserIntegral()
    {
        $Integral = new Integrals();
        $Integral->addOneRecord(1, -1, '测试消耗积分');
        $this->seeInDatabase('user_integrals', ['user_id' => 1, 'integral' => -1, 'content' => '测试消耗积分']);
        $this->post('/expend', ['userId' => '1', 'integral' => 1])
            ->seeJson([
                'code' => 404
            ]);//测试请求,积分不足请求失败
        $Integral->addOneRecord(1, 3, '测试添加积分');
        $this->post('/expend', ['userId' => '1', 'integral' => 1])
            ->seeJson([
                'code' => 200
            ]);//测试请求,积分足够，请求成功
    }

    /**
     * 获取一个用户的剩余积分数
     */
    public function testGetSurplusIntegral()
    {
        $Integral = new Integrals();
        $Integral->addOneRecord(1, 3, '测试添加积分');
        $Integral->addOneRecord(1, -1, '测试消耗积分');
        $Integral->addOneRecord(1, 1, '测试消耗积分');
        $count = $Integral->getUserIntegralCount([
            'userId' => 1,
            'type' => 'left',
            'startTime' => date('Y-m-d', time()),
            'endTime' => date('Y-m-d H:i:s', time())
        ]);
        $this->assertEquals(3, $count);
        $this->get('/counts/users/1/type/left/start/2017-04-01/end/2017-05-10')
            ->seeJson([
                'code' => 200,
                'result' => 3
            ]);
    }

    /**
     * 获取一个用户的消耗积分数
     */
    public function testGetExpendIntegral()
    {
        $Integral = new Integrals();
        $Integral->addOneRecord(1, 1, '测试添加积分');
        $Integral->addOneRecord(1, -3, '测试消耗积分');
        $Integral->addOneRecord(1, 3, '测试消耗积分');
        $count = $Integral->getUserIntegralCount([
            'userId' => 1,
            'type' => 'expend',
            'startTime' => date('Y-m-d', time()),
            'endTime' => date('Y-m-d H:i:s', time())
        ]);
        $this->assertEquals(3, $count);
        $this->get('/counts/users/1/type/expend/start/2017-04-01/end/2017-05-10')
            ->seeJson([
                'code' => 200,
                'result' => 3
            ]);
    }

    /**
     * 获取一个用户的得到的积分数
     */
    public function testGetReceiveIntegral()
    {
        $Integral = new Integrals();
        $Integral->addOneRecord(1, 2, '测试添加积分');
        $Integral->addOneRecord(1, -1, '测试消耗积分');
        $Integral->addOneRecord(1, 3, '测试消耗积分');
        $count = $Integral->getUserIntegralCount([
            'userId' => 1,
            'type' => 'add',
            'startTime' => date('Y-m-d', time()),
            'endTime' => date('Y-m-d H:i:s', time())
        ]);
        $this->assertEquals(5, $count);
        $this->get('/counts/users/1/type/add/start/2017-04-01/end/2017-05-10')
            ->seeJson([
                'code' => 200,
                'result' => 5
            ]);
    }


    /**
     * 获取一个用户的积分详情
     */
    public function testGetAllIntegral()
    {
        $Integral = new Integrals();
        $Integral->addOneRecord(1, 2, '测试添加积分');
        $Integral->addOneRecord(1, -1, '测试消耗积分');
        $data = $Integral->getUserIntegralInfo([
            'userId' => 1,
            'type' => 'add',
            'startTime' => '2017-04-01',
            'endTime' => '2017-05-10',
        ], 20)->toArray();
        $this->assertEquals(1, count($data['data']));
        $data = $Integral->getUserIntegralInfo([
            'userId' => 1,
            'type' => 'expend',
            'startTime' => '2017-04-01',
            'endTime' => '2017-05-10',
        ], 20)->toArray();
        $this->assertEquals(1, count($data['data']));
        $data = $Integral->getUserIntegralInfo([
            'userId' => 1,
            'type' => 'left',
            'startTime' => '2017-04-01',
            'endTime' => '2017-05-10',
        ], 20)->toArray();
        $this->assertEquals(2, count($data['data']));

    }

    /**
     * 获取积分排名
     */
    public function testGetIntegralRank()
    {
        $Integral = new Integrals();
        $Integral->addOneRecord(1, 2, '测试添加积分');
        $Integral->addOneRecord(1, 3, '测试添加积分');
        $Integral->addOneRecord(2, 1, '测试添加积分');
        $data = $Integral->getUserIntegralRank([
            'userId' => 1,
            'type' => 'left',
            'startTime' => '2017-04-01',
            'endTime' => '2017-05-10',
        ], 20)->toArray();
        $this->assertEquals([
            ['integral_sum' => 5, 'user_id' => 1],
            ['integral_sum' => 1, 'user_id' => 2]
        ], $data['data']);
    }

}
