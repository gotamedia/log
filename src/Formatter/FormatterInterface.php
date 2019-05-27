<?php

declare(strict_types=1);

namespace Atoms\Log\Formatter;

use Atoms\Log\RecordInterface;

interface FormatterInterface
{
    /**
     * Checks if the handler will handle a log record of given log level.
     *
     * @param \Atoms\Log\RecordInterface $record
     * @return string
     */
    public function format(RecordInterface $record): string;

    /**
     * Formats a log record.
     *
     * @param \Atoms\Log\RecordInterface[] $records
     * @return array
     */
    public function formatBatch(array $records): array;
}
