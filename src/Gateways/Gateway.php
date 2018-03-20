<?php

namespace Huid\Sms\Gateways;


use Huid\Sms\Contracts\GatewayInterface;
use Huid\Sms\Support\Config;

abstract class Gateway implements GatewayInterface
{

    const DEFAULT_TIMEOUT = 5.0;

    /**
     * @var Config
     */
    protected $config;

    protected $timeout;

    /**
     * init config detail
     *
     * Gateway constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    /**
     * @return mixed
     */
    public function getTimeout()
    {
        return $this->timeout ?: $this->config->get('timeout', self::DEFAULT_TIMEOUT);
    }

    /**
     * @param $timeout
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = floatval($timeout);
        return $this;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param Config $config
     * @return $this
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }

}