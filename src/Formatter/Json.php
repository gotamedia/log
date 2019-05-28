<?php

declare(strict_types=1);

namespace Atoms\Log\Formatter;

use Atoms\Log\RecordInterface;
use RuntimeException;

class Json implements FormatterInterface
{
    /**
     * @todo Add option to enable JSON_PRETTY_PRINT
     * @var int
     */
    private $encodingOptions =
        JSON_UNESCAPED_SLASHES |
        JSON_UNESCAPED_UNICODE |
        JSON_PRESERVE_ZERO_FRACTION;

    /**
     * {@inheritDoc}
     */
    public function format(RecordInterface $record): string
    {
        $string = json_encode([
            'message' => $record->getMessage(),
            'context' => $record->getContext(),
            'level' => $record->getLevel(),
            'datetime' => $record->getDateTime()->format('c')
        ], $this->encodingOptions);

        if ($string === false) {
            throw new RuntimeException('Could not encode JSON: ' . json_last_error_msg());
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
