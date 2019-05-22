<?php

declare(strict_types=1);

namespace Atoms\Log\Handler;

use Atoms\Log\Formatter\FormatterInterface;
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
     * @var \Atoms\Log\Formatter\FormatterInterface|null
     */
    protected $formatter;

    /**
     * @param string $level
     * @param \Atoms\Log\Formatter\FormatterInterface|null $formatter
     */
    public function __construct($level = LogLevel::DEBUG, ?FormatterInterface $formatter = null)
    {
        /** If the log level is undefined; use the default one */
        if (!defined(LogLevel::class . '::' . strtoupper($level))) {
            throw new InvalidArgumentException(
                'Invalid log level; must be one of ' .
                implode(', ', (new ReflectionClass(LogLevel::class))->getConstants())
            );
        }

        $this->level = $level;
        $this->formatter = $formatter;
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
