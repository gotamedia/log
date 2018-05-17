<?php

namespace Atoms\Log\Handlers;

use Atoms\Log\Logger;
use InvalidArgumentException;
use Psr\Log\LogLevel;
use ReflectionClass;

abstract class AbstractHandler implements HandlerInterface
{
    /**
     * The minimum logging level at which the handler will be triggered.
     *
     * @var string
     */
    protected $level;

    /**
     * @param string $level
     */
    public function __construct($level = LogLevel::DEBUG)
    {
        /** If the log level is undefined; use the default one */
        if (!defined(LogLevel::class . '::' . strtoupper($level))) {
            throw new InvalidArgumentException(
                'Invalid log level; must be one of ' .
                implode(', ', (new ReflectionClass(LogLevel::class))->getConstants())
            );
        }

        $this->level = $level;
    }

    /**
     * {@inheritDoc}
     */
    public function isHandling($level): bool
    {
        return $this->logLevelImportance($level) <= $this->logLevelImportance($this->level);
    }

    /**
     * Gets a numerical value of the log level. Lower value = higher importance.
     *
     * @param  string $level
     * @return int
     */
    private function logLevelImportance($level)
    {
        return array_search($level, Logger::$levels);
    }
}
