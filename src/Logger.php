<?php

namespace Atoms\Log;

use Atoms\Log\Handlers\HandlerInterface;
use Atoms\Log\Handlers\StreamHandler;
use InvalidArgumentException;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use RuntimeException;

class Logger extends AbstractLogger
{
    /**
     * @var \Atoms\Log\Handlers\HandlerInterface[] All registered handlers.
     */
    protected $handlers;

    /**
     * Defined log levels.
     *
     * @see https://tools.ietf.org/html/rfc5424
     * @var array
     */
    public static $levels = [
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
        LogLevel::WARNING,
        LogLevel::NOTICE,
        LogLevel::INFO,
        LogLevel::DEBUG
    ];

    /**
     * Creates a new Logger instance.
     *
     * @param array $handlers
     */
    public function __construct(array $handlers = [])
    {
        foreach ($handlers as $handler) {
            if (!$handler instanceof HandlerInterface) {
                throw new InvalidArgumentException(
                    'Invalid log handler; must be an instance of \Atoms\Log\Handlers\HandlerInterface'
                );
            }
        }

        $this->handlers = $handlers;
    }

    /**
     * Returns a list of all handlers.
     *
     * @return \Atoms\Log\Handlers\HandlerInterface[]
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * Adds a handler to the list.
     *
     * @param \Atoms\Log\Handlers\HandlerInterface $handler
     */
    public function addHandler(HandlerInterface $handler): void
    {
        array_unshift($this->handlers, $handler);
    }

    /**
     * {@inheritDoc}
     */
    public function log($level, $message, array $context = []): void
    {
        /** Add a basic log handler if none has been defined */
        if (count($this->handlers) == 0) {
            $this->addHandler(new StreamHandler(fopen('php://stderr', 'a'), LogLevel::DEBUG));
        }

        foreach ($this->handlers as $handler) {
            if ($handler->isHandling($level)) {
                try {
                    $handler->handle($level, $message, $context);
                } catch (RuntimeException $e) {
                    error_log($e->getMessage());
                }
            }
        }
    }
}
