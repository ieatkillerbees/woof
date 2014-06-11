<?php

/**
 * Class WoofTest
 */
class WoofTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function getSocket()
    {
        $socket = $this->getMock('\Squinones\Woof\Socket', ['send', 'close']);
        $socket->expects($this->any())->method("close")->willReturn(true);
        return $socket;
    }

    /**
     *
     */
    public function testIncrement()
    {
        $socket = $this->getSocket();
        $socket->expects($this->any())
            ->method("send")
            ->with(
                $this->equalTo("foo:1|c|@1.000"),
                $this->equalTo("localhost"),
                $this->equalTo(8125)
            )
            ->willReturn(true);

        $client = new \Squinones\Woof\Woof();
        $client->setSocket($socket);
        $client->increment("foo");
    }

    /**
     *
     */
    public function testDecrement()
    {
        $socket = $this->getSocket();
        $socket->expects($this->any())
            ->method("send")
            ->with(
                $this->equalTo("foo:-1|c|@1.000"),
                $this->equalTo("localhost"),
                $this->equalTo(8125)
            )
            ->willReturn(true);

        $client = new \Squinones\Woof\Woof();
        $client->setSocket($socket);
        $client->decrement("foo");
    }

    /**
     *
     */
    public function testGauge()
    {
        $socket = $this->getSocket();
        $socket->expects($this->any())
            ->method("send")
            ->with(
                $this->equalTo("foo:42|g|@1.000"),
                $this->equalTo("localhost"),
                $this->equalTo(8125)
            )
            ->willReturn(true);

        $client = new \Squinones\Woof\Woof();
        $client->setSocket($socket);
        $client->gauge("foo", 42);
    }

    /**
     *
     */
    public function testHistogram()
    {
        $socket = $this->getSocket();
        $socket->expects($this->any())
            ->method("send")
            ->with(
                $this->equalTo("foo:42|h|@1.000"),
                $this->equalTo("localhost"),
                $this->equalTo(8125)
            )
            ->willReturn(true);

        $client = new \Squinones\Woof\Woof();
        $client->setSocket($socket);
        $client->histogram("foo", 42);
    }

    /**
     *
     */
    public function testTiming()
    {
        $socket = $this->getSocket();
        $socket->expects($this->any())
            ->method("send")
            ->with(
                $this->equalTo("foo:42|ms|@1.000"),
                $this->equalTo("localhost"),
                $this->equalTo(8125)
            )
            ->willReturn(true);

        $client = new \Squinones\Woof\Woof();
        $client->setSocket($socket);
        $client->timing("foo", 42);
    }

    /**
     *
     */
    public function testSet()
    {
        $socket = $this->getSocket();
        $socket->expects($this->any())
            ->method("send")
            ->with(
                $this->equalTo("foo:42|s|@1.000"),
                $this->equalTo("localhost"),
                $this->equalTo(8125)
            )
            ->willReturn(true);

        $client = new \Squinones\Woof\Woof();
        $client->setSocket($socket);
        $client->set("foo", 42);
    }

    /**
     *
     */
    public function testSampleRate()
    {
        $socket = $this->getSocket();
        $socket->expects($this->atLeast(40), $this->atMost(60))
            ->method("send")
            ->with(
                $this->equalTo("foo:1|c|@0.500"),
                $this->equalTo("localhost"),
                $this->equalTo(8125)
            )
            ->willReturn(true);

        $client = new \Squinones\Woof\Woof();
        $client->setSocket($socket);

        for ($i=0; $i<100; $i++) {
            $client->increment("foo", 1, [], 0.5);
        }
    }
}
 