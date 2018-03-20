<?php

use Huid\Sms\Exceptions\GatewayErrorException;
use Huid\Sms\Gateways\QcloudGateway;
use Huid\Sms\Message;
use Huid\Sms\Support\Config;
use Huid\Sms\Tests\TestCase;

class QcloudGatewayTest extends TestCase
{

    public function testSend()
    {
        $config = [
            'sdk_app_id' => 'mock-sdk-app-id',
            'app_key' => 'mock-api-key',
            'type' => 0
        ];
        $gateway = \Mockery::mock(QcloudGateway::class.'[request]', [$config])->shouldAllowMockingProtectedMethods();
        $expected = [
            'tel' => [
                'nationcode' => '86',
                'mobile' => strval(18888888888),
            ],
            'type' => 0,
            'msg' => 'This is a test message.',
            'timestamp' => time(),
            'extend' => '',
            'ext' => '',
        ];
        $gateway->shouldReceive('request')
            ->andReturn([
                'result' => 0,
                'errmsg' => 'OK',
                'ext' => '',
                'sid' => 3310228982,
                'fee' => 1,
            ], [
                'result' => 1001,
                'errmsg' => 'sig校验失败',
            ])->twice();

        $message = new Message([
            'content' => 'This is a test message.',
        ]);

        $config = new Config($config);

        $this->assertSame([
            'result' => 0,
            'errmsg' => 'OK',
            'ext' => '',
            'sid' => 3310228982,
            'fee' => 1,
        ], $gateway->send(18888888888, $message, $config));

        $this->expectException(GatewayErrorException::class);

        $this->expectExceptionCode(1001);

        $this->expectExceptionMessage('sig校验失败');

        $gateway->send(18888888888, $message, $config);
    }


}