<?php
/**
 * Created by SYNAXON AG.
 * User: Karl Spies
 * Date: 28.11.11
 */

/** Zend_Log_Writer_Abstract */
require_once 'Zend/Log/Writer/Abstract.php';

/** Zend_Log_Formatter_Simple */
require_once 'Zend/Log/Formatter/Simple.php';

class Zend_Log_Writer_FDebug extends Zend_Log_Writer_Abstract {

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
     * Class constructor
     *
     * @return void
     */
    public function __construct(array $params = array())
    {
		$this->_formatter = new Zend_Log_Formatter_Simple();
    }

    /**
     * Create a new instance of Zend_Log_Writer_Firebug
     *
     * @param  array|Zend_Config $config
     * @return Zend_Log_Writer_Firebug
     */
    static public function factory($config)
    {
        return new self(self::_parseConfig($config));
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
     * Log a message to the Firebug Console.
     *
     * @param array $event The event data
     * @return void
     */
    protected function _write($event)
    {
		if(!$this->_enabled) return;

		if($this->getFDebug() instanceof fDebug) {
			$line = $this->_formatter->format($event);
			$this->getFDebug()->sendMessage($line);
		}
	}

	 /**
     * Remove reference to database adapter
     *
     * @return void
     */
    public function shutdown()
    {
        $this->getFDebug()->closeSocket();
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
	 * @return fDebug|null
	 */
	public function getFDebug()
	{
		return $this->_fDebug;
	}

}
