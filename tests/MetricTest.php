<?php
class MetricTest extends PHPUnit_Framework_TestCase
{
    public function testStringConversion()
    {
        $metric = new \Squinones\Woof\Metric("test", 42, "c", ["foo", "bar", ["foo" => "bar"]]);
        $this->assertEquals("test:42|c|@1.000|#foo,bar,foo:bar", (string) $metric);
    }

    /**
     * @param $rate
     * @dataProvider sampleRateProvider
     */
    public function testInvalidSampleRate($rate)
    {
        $this->setExpectedException('\InvalidArgumentException', "Sample rate must be a floating point value between 0 and 1");
        new \Squinones\Woof\Metric("test", 1, "c", ["foo"], $rate);
    }

    /**
     * @param $type
     * @dataProvider typeProvider
     */
    public function testInvalidType($type)
    {
        $this->setExpectedException('\InvalidArgumentException', "Type must be one of 'c', 'g', 'h', 'ms', or 's'");
        new \Squinones\Woof\Metric("test", 1, $type, ["foo"]);
    }

    public function sampleRateProvider()
    {
        return [
            [-1],
            [2],
            ["foo"],
            [str_repeat("a", 1000)],
            [100*100*100]
        ];
    }

    public function typeProvider()
    {
        return [
            ["foo"],
            [str_repeat("a", 1000)],
            ["count"]
        ];
    }
}
 