<?php

namespace Squinones\Woof;

/**
 * Class Socket
 *
 * @package Squinones\Woof
 */
class Socket
{
    /**
     * @var resource
     */
    private $socket;

    /**
     *
     */
    public function __construct()
    {
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_set_nonblock($socket);
        $this->socket = $socket;
    }

    /**
     * @param $dgram
     * @param $hostname
     * @param $port
     *
     * @return int
     */
    public function send($dgram, $hostname, $port)
    {
        return socket_sendto($this->socket, $dgram, strlen($dgram), 0, $hostname, $port);
    }

    /**
     *
     */
    public function close()
    {
        socket_close($this->socket);
    }

}