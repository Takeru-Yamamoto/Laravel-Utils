<?php

namespace MyCustom\Utils\Time;

use MyCustom\Utils\BaseUtil;

final class TimeUtil extends BaseUtil
{
    private string $method;
    private string $process;

    private float $methodTime;
    private float $methodStart;
    private float $methodStop;
    private float $processTime;
    private float $processStart;
    private float $processStop;

    function __construct(string $method)
    {
        parent::__construct(config("mycustoms.utils.logging_util_time", false), $this);

        $this->method = $method;
        if ($this->isLogging) emphasisLogStart("METHOD " . $this->method);
        $this->methodStart = microtime(true);
    }

    function __destruct()
    {
        $this->methodStop = microtime(true);
        $this->methodTime = $this->methodStop - $this->methodStart;

        if ($this->isLogging) {
            emptyLog();
            infoLog("TIME : " . $this->methodTime . " SECONDS");
            emphasisLogEnd("METHOD " . $this->method);
        }
    }

    function __clone()
    {
        $this->method       = clone $this->method;
        $this->process      = clone $this->process;

        $this->methodTime   = clone $this->methodTime;
        $this->methodStart  = clone $this->methodStart;
        $this->methodStop   = clone $this->methodStop;
        $this->processTime  = clone $this->processTime;
        $this->processStart = clone $this->processStart;
        $this->processStop  = clone $this->processStop;
    }

    public function params(): array
    {
        return [
            "method" => [
                "name"  => $this->method,
                "start" => $this->methodStart,
                "stop"  => $this->methodStop,
                "time"  => $this->methodTime,
            ],
            "process" => [
                "name"  => $this->process,
                "start" => $this->processStart,
                "stop"  => $this->processStart,
                "time"  => $this->processTime,
            ],
        ];
    }

    final public function start(?string $process): void
    {
        $this->process = $process;

        if ($this->isLogging) littleEmphasisLogStart("PROCESS " . $this->process);

        $this->processStart = microtime(true);
    }

    final public function stop(): float
    {
        $this->processStop = microtime(true);
        $this->processTime = $this->processStop - $this->processStart;

        if ($this->isLogging) {
            emptyLog();
            infoLog("TIME : " . $this->processTime . " SECONDS");
            littleEmphasisLogEnd("PROCESS " . $this->process);
        }

        return $this->processTime;
    }
}
