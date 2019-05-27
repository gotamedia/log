<?php

declare(strict_types=1);

namespace Atoms\Log\Formatter;

use Atoms\Log\RecordInterface;

class Line implements FormatterInterface
{
    /**
     * {@inheritDoc}
     */
    public function format(RecordInterface $record): string
    {
        $string = sprintf(
            '%s [%s] %s',
            $record->getDateTimeInterface()->format('c'),
            strtoupper($record->getLevel()),
            $record->getMessage()
        );

        if (count($record->getContext()) > 0) {
            $string .= ' ' . print_r($record->getContext(), true);
        }

        return $string;
    }

    /**
     * {@inheritDoc}
     */
    public function formatBatch(array $records): array
    {
        $formattedRecords = [];

        foreach ($records as $record) {
            $formattedRecords[] = $this->format($record);
        }

        return $formattedRecords;
    }
}
