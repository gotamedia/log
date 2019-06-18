<?php

declare(strict_types=1);

namespace Atoms\Log\Formatter;

use Atoms\Log\RecordInterface;
use RuntimeException;

class Json implements FormatterInterface
{
    use NormalizationTrait;

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
            'date' => $record->getDateTime()->format('c'),
            'level' => $record->getLevel(),
            'message' => $record->getMessage(),
            'context' => $this->normalizeContext($record->getContext())
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
