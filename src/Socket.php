<?php
/**
 * Socket.php
 *
 * Simple socket wrapper
 *
 * @package   Squinones\Woof
 * @author    Samantha Quiñones <samantha@tembies.com>
 * @copyright 2014 Samantha Quiñones
 * @license   http://opensource.org/licenses/MIT
 */

namespace Squinones\Woof;
use Squinones\Woof\Exceptions\SocketException;

/**
 * Class Socket
 *
 * This is a stupidly simple socket wrapper to facilitate easier test doubles
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
     * Create the socket
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
        $sent = socket_sendto($this->socket, $dgram, strlen($dgram), 0, $hostname, $port);
        if ($sent === false) {
            throw new SocketException("Socket unavailable");
        }
        return $sent;
    }

    /**
     * Close the socket
     */
    public function close()
    {
        socket_close($this->socket);
    }
}
