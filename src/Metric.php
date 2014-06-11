<?php

namespace Squinones\Woof;

/**
 * Class Metric
 *
 * @package Squinones\Woof
 */
class Metric
{
    /**
     * @var string Metric name
     */
    private $name;

    /**
     * @var mixed Value
     */
    private $value;

    /**
     * @var string The type of metric
     */
    private $type;

    /**
     * @var array An array of tags (strings and key=>value arrays)
     */
    private $tags;

    /**
     * @var float Sample rate, a value between 0 and 1
     */
    private $sampleRate;

    /**
     * @param string $name
     * @param string $value
     * @param string $type
     * @param array  $tags
     * @param float  $sampleRate
     *
     * @throws \LogicException
     */
    public function __construct($name, $value, $type, array $tags = [], $sampleRate = 1.0)
    {
        if ($sampleRate < 0 || $sampleRate > 1 || !is_numeric($sampleRate)) {
            throw new \InvalidArgumentException("Sample rate must be a floating point value between 0 and 1");
        }

        if (!in_array($type, ["c", "g", "h", "ms", "s"])) {
            throw new \InvalidArgumentException("Type must be one of 'c', 'g', 'h', 'ms', or 's'");
        }

        $this->name       = $name;
        $this->value      = $value;
        $this->type       = $type;
        $this->tags       = $this->normalizeTags($tags);
        $this->sampleRate = (float) $sampleRate;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getSampleRate()
    {
        return $this->sampleRate;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }


    /**
     * @param array $tags
     *
     * @return array
     */
    protected function normalizeTags(array $tags)
    {
        return array_map(
            function ($tag) {
                if (is_array($tag)) {
                    return array_keys($tag)[0] . ":" . array_values($tag)[0];
                }

                return $tag;
            },
            $tags
        );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $dgram = sprintf(
            "%s:%s|%s|@%0.3f",
            $this->getName(),
            $this->getValue(),
            $this->getType(),
            $this->getSampleRate()
        );

        if (count($this->getTags())) {
            $dgram .= sprintf("|#%s", join(",", $this->getTags()));
        }

        return $dgram;
    }


} 