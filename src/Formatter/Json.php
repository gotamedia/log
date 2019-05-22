<?php

declare(strict_types=1);

namespace Atoms\Log\Formatter;

use Atoms\Log\Record;

class Json implements FormatterInterface
{
    /**
     * @var int
     */
    private $encodingOptions = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION;

    /**
     * {@inheritDoc}
     */
    public function format(Record $record): string
    {
        $defaultValues = [
            'level' => $record->getLevel(),
            'message' => $record->getMessage(),
            'dateTime' => $record->getDateTime()->format('c')
        ];

        return json_encode(
            array_merge($defaultValues, $record->getContext()),
            $this->encodingOptions
        );
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
