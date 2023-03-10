<?php

namespace MyCustom\Utils;

abstract class BaseUtil implements \JsonSerializable
{
    protected array $errors = [];
    protected bool $isLogging;
    protected string $className;

    function __construct(bool $isLogging = false, ?BaseUtil $class = null)
    {
        $this->isLogging = $isLogging;
        $this->className = is_null($class) ? "" : className($class);
    }
    function __destruct()
    {
        if ($this->isLogging && !empty($this->errors)) {
            errorLog("");
            errorLog("----- " . $this->className . " ERROR LOG START -----");
            errorLog("");

            $loopCount = 0;
            foreach ($this->errors as $error) {
                errorLog("ERROR" . $loopCount . ": " . $error);
            }


            errorLog("");
            errorLog("----- " . $this->className . " ERROR LOG END -----");
            errorLog("");
        }
    }

    abstract function __clone();
    public function copy(): self
    {
        return clone $this;
    }

    abstract public function params(): array;
    public function jsonSerialize(): array
    {
        return $this->params();
    }

    final public function isLogging(): bool
    {
        return $this->isLogging;
    }
    final public function setLogging(bool $isLogging): void
    {
        $this->isLogging = $isLogging;
    }

    final public function errors(): array
    {
        return $this->errors();
    }
    final public function hasError(): bool
    {
        return empty($this->errors);
    }
    final protected function addError(string $lang): self
    {
        $this->errors[] = ___($lang);
        return $this;
    }
}
