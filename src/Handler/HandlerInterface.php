<?php

declare(strict_types=1);

namespace Atoms\Log\Handler;

use Atoms\Log\RecordInterface;

interface HandlerInterface
{
    /**
     * Checks if the handler will handle a log record of given log level.
     *
     * @param string $level
     * @return bool
     */
    public function isHandling(string $level): bool;

    /**
     * Handles a log record.
     *
     * All log records may be passed to this method, and the handler should discard
     * those that it does not want to handle.
     *
     * The return value of this function controls the bubbling process of the handler
     * stack. Unless the bubbling is interrupted (by returning true), the Logger class
     * will keep on calling further handlers in the stack with a given log record.
     *
     * @param \Atoms\Log\RecordInterface $record
     */
    public function handle(RecordInterface $record): void;
}
