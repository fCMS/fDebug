<?php
/**
 * Created by SYNAXON AG.
 * User: Karl Spies
 * Date: 28.11.11
 */

class Zend_Log_Writer_FDebug extends Zend_Log_Writer_Abstract
{

    /**
     * Maps logging priorities to logging display styles
     *
     * @var array
     */
    protected $_priorityStyles = array(
        Zend_Log::EMERG => fDebug::FATAL,
        Zend_Log::ALERT => fDebug::FATAL,
        Zend_Log::CRIT => fDebug::FATAL,
        Zend_Log::ERR => fDebug::ERROR,
        Zend_Log::WARN => fDebug::WARNING,
        Zend_Log::NOTICE => fDebug::MESSAGE,
        Zend_Log::INFO => fDebug::MESSAGE,
        Zend_Log::DEBUG => fDebug::MESSAGE
    );

    /**
     * The default logging style for un-mapped priorities
     *
     * @var string
     */
    protected $_defaultPriorityStyle = fDebug::MESSAGE;
    /**
     * Flag indicating whether the log writer is enabled
     *
     * @var boolean
     */
    protected $_enabled = true;
    /**
     * @var fDebug
     */
    protected $_fDebug = null;
    /**
     * @var string
     */
    protected $_port = '5005';
    /**
     * @var string
     */
    protected $_host = 'localhost';
    /**
     * @var string
     */
    protected $_url = '/';
    /**
     * @var string
     */
    protected $_remoteIP = '';

    /**
     * Class constructor
     *
     * @return void
     */
    public function __construct($ip)
    {
        $this->_remoteIP = $ip;
        $this->_formatter = new Zend_Log_Formatter_Simple();
    }

    /**
     * Create a new instance of Zend_Log_Writer_FDebug
     *
     * @param  array|Zend_Config $config
     * @return Zend_Log_Writer_Firebug
     */
    static public function factory($config)
    {
        $config = self::_parseConfig($config);
        if (!isset($config['remoteIP'])) {
            throw new InvalidArgumentException();
        }

        $instance = new self($config['remoteIP']);

        foreach ($config as $key => $value) {
            if (method_exists($instance, 'set' . ucfirst($key))) {
                $instance->{'set' . ucfirst($key)}($value);
            }
        }

        $instance->connect();

		return $instance;
    }

    /**
     * Enable or disable the log writer.
     *
     * @param boolean $enabled Set to TRUE to enable the log writer
     * @return boolean The previous value.
     */
    public function setEnabled($enabled)
    {
        $previous = $this->_enabled;
        $this->_enabled = $enabled;
        return $previous;
    }

    /**
     * Determine if the log writer is enabled.
     *
     * @return boolean Returns TRUE if the log writer is enabled.
     */
    public function getEnabled()
    {
        return $this->_enabled;
    }

    /**
     * Log a message to the fDebug Console.
     *
     * @param array $event The event data
     * @return void
     */
    protected function _write($event)
    {
        if (!$this->getEnabled()) {
            return;
        }

        if (array_key_exists($event['priority'], $this->_priorityStyles)) {
            $type = $this->_priorityStyles[$event['priority']];
        } else {
            $type = $this->_defaultPriorityStyle;
        }

        if ($this->getFDebug() instanceof fDebug) {
            $line = $this->_formatter->format($event);
            $method = 'send' . ucfirst(strtolower($type));
            $this
                    ->getFDebug()
                    ->{$method}($line);
        }
    }

    /**
     * Close socket from fDebug
     *
     * @return void
     */
    public function shutdown()
    {
        $this
                ->getFDebug()
                ->closeSocket();
    }

    /**
     * @param fDebug $fDebug
     * @return void
     */
    public function setFDebug(fDebug $fDebug)
    {
        $this->_fDebug = $fDebug;
    }

    /**
     * @return fDebug
     */
    public function getFDebug()
    {
        if ($this->_fDebug === null) {
            $this->_fDebug = fDebug::getInstance();
        }
        return $this->_fDebug;
    }

    public function connect()
    {
        $this->getFDebug()->setSession($this->getHost(), $this->getUrl());
        $this->getFDebug()->openSocket($this->getRemoteIP(), $this->getPort());
    }

    public function setHost($host)
    {
        $this->_host = $host;
    }

    public function getHost()
    {
        return $this->_host;
    }

    public function setPort($port)
    {
        $this->_port = $port;
    }

    public function getPort()
    {
        return $this->_port;
    }

    public function setRemoteIP($remoteIP)
    {
        $this->_remoteIP = $remoteIP;
    }

    public function getRemoteIP()
    {
        return $this->_remoteIP;
    }

    public function setUrl($url)
    {
        $this->_url = $url;
    }

    public function getUrl()
    {
        return $this->_url;
    }

}
