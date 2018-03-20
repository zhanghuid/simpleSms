<?php
namespace Huid\Sms\Contracts;



interface MessageInterface
{
    /**
     * Return message content.
     *
     * @param GatewayInterface|null $gateway
     *
     * @return string
     */
    public function getContent();
    /**
     * Return the template id of message.
     *
     * @param GatewayInterface|null $gateway
     *
     * @return string
     */
    public function getTemplate();
    /**
     * Return the template data of message.
     *
     * @param GatewayInterface|null $gateway
     *
     * @return array
     */
    public function getData();

}