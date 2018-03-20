<?php

namespace Huid\Sms\Gateways;


use Huid\Sms\Contracts\MessageInterface;
use Huid\Sms\Exceptions\GatewayErrorException;
use Huid\Sms\Support\Config;
use Huid\Sms\Traits\HasHttpRequest;

class QcloudGateway extends Gateway
{

    use HasHttpRequest;

    const ENDPOINT_URL = 'https://yun.tim.qq.com/v5/';
    const ENDPOINT_METHOD = 'tlssmssvr/sendsms';
    const ENDPOINT_VERSION = 'v5';
    const ENDPOINT_FORMAT = 'json';


    /**
     * Send a short message.
     *
     * @param int|string|array $to
     * @param MessageInterface $message
     * @param $config
     *
     * @return array
     */
    public function send($to, MessageInterface $message, Config $config)
    {
        $params = [
            'tel' => [
                'nationcode' => $config->get('nationcode') ?? '86',
                'mobile' => $to,
            ],
            'type' => $config->get('type') ?? 0,
            'msg' => $message->getContent(),
            'time' => time(),
            'extend' => '',
            'ext' => '',
        ];
        $random =  rand(100000, 999999);
        $params['sig'] = $this->generateSign($params, $random);
        $url = self::ENDPOINT_URL.self::ENDPOINT_METHOD.'?sdkappid='.$config->get('sdk_app_id').'&random='.$random;
        $result = $this->request('post', $url, [
            'headers' => ['Accept' => 'application/json'],
            'json' => $params,
        ]);
        if (0 != $result['result']) {
            throw new GatewayErrorException($result['errmsg'], $result['result'], $result);
        }
        return $result;
    }

    /**
     * Generate Sign.
     *
     * @param array  $params
     * @param string $random
     *
     * @return string
     */
    protected function generateSign($params, $random)
    {
        ksort($params);
        return hash('sha256', sprintf(
            'appkey=%s&random=%s&time=%s&mobile=%s',
            $this->config->get('app_key'),
            $random,
            $params['time'],
            $params['tel']['mobile']
        ), false);
    }

}