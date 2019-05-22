<?php

declare(strict_types=1);

namespace Atoms\Log;

use DateTime;

class Record
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
     * @var \DateTime
     */
    private $dateTime;

    /**
     * Creates a new Record instance.
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @param \DateTime|null $dateTime
     */
    public function __construct(string $level, string $message, array $context = [], ?DateTime $dateTime = null)
    {
        $this->level = $level;
        $this->message = $message;
        $this->context = $context;
        $this->dateTime = $dateTime ?? new DateTime();
    }

    /**
     * Returns the record level.
     *
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * Returns the record message.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Returns the record context.
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Returns the record timestamp.
     *
     * @return \DateTime
     */
    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }
}
