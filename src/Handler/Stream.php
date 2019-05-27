<?php

declare(strict_types=1);

namespace Atoms\Log\Handler;

use Atoms\Log\Formatter;
use Atoms\Log\Formatter\FormatterInterface;
use Atoms\Log\RecordInterface;
use InvalidArgumentException;

class Stream extends AbstractHandler
{
    /**
     * @var resource
     */
    protected $resource;

    /**
     * Creates a new StreamHandler instance.
     *
     * @param resource $resource
     * @param \Atoms\Log\Formatter\FormatterInterface|null $formatter
     */
    public function __construct($resource, FormatterInterface $formatter = null)
    {
        if (!is_resource($resource) || get_resource_type($resource) !== 'stream') {
            throw new InvalidArgumentException('Invalid resource; must be a valid resource');
        }

        $this->resource = $resource;
        $this->formatter = $formatter;
    }

    /**
     * {@inheritDoc}
     */
    public function write(RecordInterface $record): void
    {
        $formattedMessage = is_null($this->formatter)
            ? (new Formatter\Line())->format($record)
            : $this->formatter->format($record);

        fwrite($this->resource, $formattedMessage . PHP_EOL);
    }
}
