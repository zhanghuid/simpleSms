<?php

namespace Huid\Sms;


use Huid\Sms\Contracts\GatewayInterface;
use Huid\Sms\Contracts\MessageInterface;
use Huid\Sms\Exceptions\InvalidArgumentException;
use Huid\Sms\Support\Config;

class Sms
{
    /**
     * @var Config
     */
    protected $config;

    protected $defaultGateway;
    /**
     * @var array
     */
    protected $gateways = [];

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
        if (!empty($config['default'])) {
            $this->setDefaultGateway($config['default']);
        }
    }

    /**
     * send a message
     *
     * @param string $to
     * @param MessageInterface $message
     * @param array $gateway
     * @param array $config
     * @return mixed
     */
    public function send($to, $message, $gateway = null)
    {
        return $this->gateway($gateway)->send($to, $message, $this->config);
    }

    /**
     * get the sms send handle
     *
     * @param null $name
     * @return GatewayInterface
     */
    public function gateway($name = null)
    {
        $name = $name ?: $this->getDefaultGateway();
        return $this->createGateway($name);
    }

    /**
     * get the default sms send handle
     *
     * @return mixed
     */
    public function getDefaultGateway()
    {
        if (empty($this->defaultGateway)) {
            throw new \RuntimeException('No default gateway configured.');
        }
        return $this->defaultGateway;
    }

    /**
     * @param $name
     * @return string
     */
    protected function formatGatewayClassName($name)
    {
        if (class_exists($name)) {
            return $name;
        }
        $name = ucfirst(str_replace(['-', '_', ''], '', $name));
        return __NAMESPACE__."\\Gateways\\{$name}Gateway";
    }

    /**
     * @param $name
     * @return mixed
     * @throws InvalidArgumentException | GatewayInterface
     */
    protected function createGateway($name)
    {
        $className = $this->formatGatewayClassName($name);
        $gateway = $this->makeGateway($className, $this->config->get("gateway.{$name}", []));

        if (!($gateway instanceof GatewayInterface)) {
            throw new InvalidArgumentException(sprintf('Gateway "%s" not inherited from %s.', $name, GatewayInterface::class));
        }
        return $gateway;
    }

    /**
     * create handle object
     *
     * @param $gateway
     * @param $config
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function makeGateway($gateway, $config)
    {
        if (!class_exists($gateway)) {
            throw new InvalidArgumentException(sprintf('Gateway "%s" not exists.', $gateway));
        }
        return new $gateway($config);
    }

    /**
     * Set default gateway name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setDefaultGateway($name)
    {
        $this->defaultGateway = $name;
        return $this;
    }

}