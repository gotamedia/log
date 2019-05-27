<?php

declare(strict_types=1);

namespace Atoms\Log\Handler;

use Atoms\Log\Logger;
use InvalidArgumentException;
use Psr\Log\LogLevel;
use ReflectionClass;

abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var string
     */
    protected $level = LogLevel::DEBUG;

    /**
     * @var bool
     */
    protected $bubble = true;

    /**
     * @var \Atoms\Log\Formatter\FormatterInterface|null
     */
    protected $formatter;

    /**
     * {@inheritDoc}
     */
    public function isHandling(string $level): bool
    {
        return $this->bubble ?
            $this->logLevelSeverity($level) <= $this->logLevelSeverity($this->level) :
            $this->logLevelSeverity($level) === $this->logLevelSeverity($this->level);
    }

    /**
     * Set the log level.
     *
     * @param string $level
     * @throws \InvalidArgumentException
     */
    public function setLevel(string $level): void
    {
        /** Check if the provided log level is a valid level */
        if (!defined(LogLevel::class . '::' . strtoupper($level))) {
            throw new InvalidArgumentException(
                'Invalid log level; must be one of ' .
                implode(', ', $this->getAllLevels())
            );
        }

        $this->level = $level;
    }

    /**
     * Sets the behavior of bubbling.
     *
     * @param bool $bubble
     */
    public function setBubble(bool $bubble): void
    {
        $this->bubble = $bubble;
    }

    /**
     * Gets a numerical value of the log level. Lower value = more severe.
     *
     * @param string $level
     * @return int
     */
    private function logLevelSeverity(string $level): int
    {
        return array_search($level, Logger::$levels);
    }

    /**
     * Returns all valid log levels.
     *
     * @return array
     */
    private function getAllLevels(): array
    {
        return (new ReflectionClass(LogLevel::class))->getConstants();
    }
}
