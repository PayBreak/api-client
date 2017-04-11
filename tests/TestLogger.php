<?php

namespace PayBreak\ApiClient\Test;

use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;

/**
 * Class TestLogger - Add log messages to an array for testing purposes
 *
 * @author JH
 * @package PayBreak\Api\Tests
 */
class TestLogger implements LoggerInterface
{
    protected $log = [];

    /**
     * @author JH
     * @param string $message
     * @param array $context
     */
    public function emergency($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message);
    }

    /**
     * @author JH
     * @param string $message
     * @param array $context
     */
    public function alert($message, array $context = array())
    {
        $this->log(LogLevel::ALERT, $message);
    }

    /**
     * @author JH
     * @param string $message
     * @param array $context
     */
    public function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message);
    }

    /**
     * @author JH
     * @param string $message
     * @param array $context
     */
    public function error($message, array $context = array())
    {
        $this->log(LogLevel::ERROR, $message);
    }

    /**
     * @author JH
     * @param string $message
     * @param array $context
     */
    public function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message);
    }

    /**
     * @author JH
     * @param string $message
     * @param array $context
     */
    public function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message);
    }

    /**
     * @author JH
     * @param string $message
     * @param array $context
     */
    public function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message);
    }

    /**
     * @author JH
     * @param string $message
     * @param array $context
     */
    public function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message);
    }

    /**
     * @author JH
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = array())
    {
        $this->log[] = ['level' => $level, 'message' => $message];
    }

    /**
     * @author JH
     * @return array
     */
    public function getLog()
    {
        return $this->log;
    }
}
