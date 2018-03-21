<?php

use Huid\Sms\Contracts\GatewayInterface;
use Huid\Sms\Contracts\MessageInterface;
use Huid\Sms\Exceptions\InvalidArgumentException;
use Huid\Sms\SimpleSms as Sms;
use Huid\Sms\Support\Config;

class SmsTest extends \PHPUnit\Framework\TestCase
{

    public function testGatewayWithDefaultSetting()
    {
        $sms = new Sms(['default' => DummyGatewayForTest::class]);
        $this->assertSame(DummyGatewayForTest::class, $sms->getDefaultGateway());
        $this->assertInstanceOf(DummyGatewayForTest::class, $sms->gateway());
        // invalid gateway
        $sms->setDefaultGateway(DummyInvalidGatewayForTest::class);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Gateway "%s" not inherited from %s.',
                DummyInvalidGatewayForTest::class,
                GatewayInterface::class
            )
        );
        $sms->gateway();
    }

    public function testGatewayWithoutDefaultSetting()
    {
        $sms = new Sms([]);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No default gateway configured.');
        $sms->gateway();
    }

    public function testGateway()
    {
        $sms = new Sms([]);
        $this->assertInstanceOf(GatewayInterface::class, $sms->gateway('qcloud'));
        // invalid gateway
//        $this->expectException(InvalidArgumentException::class);
//        $this->expectExceptionMessage('Gateway "Huid\Sms\Gateways\ErrorlogGateway" not exists.');
//        $sms->gateway('NotExistsGatewayName');
    }

}

class DummyGatewayForTest implements GatewayInterface
{
    public function getName()
    {
        return 'aliyun';
    }
    public function send($to, MessageInterface $message, Config $config)
    {
        return 'send-result';
    }
}
class DummyInvalidGatewayForTest
{
    // nothing
}