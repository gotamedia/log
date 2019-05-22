<?php

declare(strict_types=1);

namespace Atoms\Log\Formatter;

use Atoms\Log\Record;

interface FormatterInterface
{
    /**
     * Checks if the handler will handle a log record of given log level.
     *
     * @param \Atoms\Log\Record $record
     * @return string
     */
    public function format(Record $record): string;

    /**
     * Formats a log record.
     *
     * @param \Atoms\Log\Record[] $records
     * @return array
     */
    public function formatBatch(array $records): array;
}
