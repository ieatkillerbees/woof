<?php

namespace Squinones\Woof;

/**
 * Class Woof
 *
 * A dogstatsd client
 *
 * @package Squinones\Woof
 */
class Woof
{
    /**
     * @var string
     */
    protected $hostname;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var resource
     */
    private   $socket;

    /**
     * @param string $hostname
     * @param int    $port
     */
    public function __construct($hostname = "localhost", $port = 8125)
    {
        $this->hostname = $hostname;
        $this->port     = (int) $port;
    }

    /**
     * @param Socket $socket
     */
    public function setSocket(Socket $socket)
    {
        $this->socket = $socket;
    }

    /**
     * @return Socket
     */
    public function getSocket()
    {
        if (!isset ($this->socket)) {
            $this->socket = new Socket();
        }
        return $this->socket;
    }

    /**
     * @param $sampleRate
     *
     * @return bool
     */
    private function randomizeSample($sampleRate)
    {
        return (mt_rand() / mt_getrandmax() >= $sampleRate);
    }

    /**
     * @param string $name
     * @param int    $value
     * @param array  $tags
     * @param float  $sampleRate
     *
     * @return bool
     */
    public function increment($name, $value = 1, array $tags = [], $sampleRate = 1.0)
    {
        return $this->send(new Metric($name, (int) $value, "c", $tags, (float) $sampleRate));
    }

    /**
     * @param string $name
     * @param int    $value
     * @param array  $tags
     * @param float  $sampleRate
     *
     * @return bool
     */
    public function decrement($name, $value = 1, array $tags = [], $sampleRate = 1.0)
    {
        return $this->send(new Metric($name, (int) -$value, "c", $tags, (float) $sampleRate));
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @param array  $tags
     * @param float  $sampleRate
     *
     * @return bool
     */
    public function gauge($name, $value, array $tags = [], $sampleRate = 1.0)
    {
        return $this->send(new Metric($name, $value, "g", $tags, (float) $sampleRate));
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @param array  $tags
     * @param float  $sampleRate
     *
     * @return bool
     */
    public function histogram($name, $value, array $tags = [], $sampleRate = 1.0)
    {
        return $this->send(new Metric($name, $value, "h", $tags, (float) $sampleRate));
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @param array  $tags
     * @param float  $sampleRate
     *
     * @return bool
     */
    public function timing($name, $value, array $tags = [], $sampleRate = 1.0)
    {
        return $this->send(new Metric($name, $value, "ms", $tags, (float) $sampleRate));
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @param array  $tags
     * @param float  $sampleRate
     *
     * @return bool
     */
    public function set($name, $value, array $tags = [], $sampleRate = 1.0)
    {
        return $this->send(new Metric($name, $value, "s", $tags, (float) $sampleRate));
    }

    /**
     * @param Metric $metric
     *
     * @return bool
     */
    protected function send(Metric $metric)
    {
        $rate = $metric->getSampleRate();
        if ($rate < 1 && !$this->randomizeSample($rate)) {
            return true;
        }

        $dgram  = (string) $metric;
        $socket = $this->getSocket();
        $socket->send($dgram, $this->hostname, $this->port);
        $socket->close();
        return true;
    }
} 