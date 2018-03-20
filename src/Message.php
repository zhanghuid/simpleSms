<?php
namespace Huid\Sms;

use Huid\Sms\Contracts\GatewayInterface;
use Huid\Sms\Contracts\MessageInterface;

/**
 * Class Message.
 */
class Message implements MessageInterface
{
    /**
     * @var string
     */
    protected $content;
    /**
     * @var string
     */
    protected $template;
    /**
     * @var array
     */
    protected $data = [];
    /**
     * Message constructor.
     *
     * @param array  $attributes
     * @param string $type
     */
    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }
    /**
     * Return message content.
     *
     * @param GatewayInterface|null $gateway
     *
     * @return string
     */
    public function getContent()
    {
         if (! empty($this->content)) {
             return $this->content;
         }
        return $this->parseTemplate();
    }
    /**
     * Return the template id of message.
     *
     * @param GatewayInterface|null $gateway
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }
    /**
     * @param GatewayInterface|null $gateway
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }
    /**
     * @param $property
     *
     * @return string
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function parseTemplate()
    {
        $tpl = $this->getTemplate();
        $keys = array_keys($this->getData());
        $values = array_values($this->getData());
        return str_replace($keys, $values, $tpl);
    }
}