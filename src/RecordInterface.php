<?php

declare(strict_types=1);

namespace Atoms\Log;

use DateTimeInterface;

interface RecordInterface
{
    /**
     * Returns the record level.
     *
     * @return string
     */
    public function getLevel(): string;

    /**
     * Returns the record message.
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * Returns the record context.
     *
     * @return array
     */
    public function getContext(): array;

    /**
     * Returns the record timestamp.
     *
     * @return \DateTimeInterface
     */
    public function getDateTime(): DateTimeInterface;
}
