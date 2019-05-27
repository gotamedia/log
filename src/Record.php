<?php

declare(strict_types=1);

namespace Atoms\Log;

use DateTimeImmutable;
use DateTimeInterface;

class Record implements RecordInterface
{
    /**
     * @var string
     */
    private $level;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $context;

    /**
     * @var \DateTimeInterface
     */
    private $dateTime;

    /**
     * Creates a new Record instance.
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @param \DateTimeInterface|null $dateTime
     */
    public function __construct(string $level, string $message, array $context = [], DateTimeInterface $dateTime = null)
    {
        $this->level = $level;
        $this->message = $message;
        $this->context = $context;
        $this->dateTime = $dateTime ?? new DateTimeImmutable();
    }

    /**
     * {@inheritDoc}
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * {@inheritDoc}
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * {@inheritDoc}
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * {@inheritDoc}
     */
    public function getDateTime(): DateTimeInterface
    {
        return $this->dateTime;
    }
}
