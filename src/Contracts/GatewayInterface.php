<?php
namespace Huid\Sms\Contracts;

use Huid\Sms\Support\Config;

interface GatewayInterface
{
    /**
     * Send a short message.
     *
     * @param int|string|array $to
     * @param MessageInterface $message
     * @param $config
     *
     * @return array
     */
    public function send($to, MessageInterface $message, Config $config);
}
