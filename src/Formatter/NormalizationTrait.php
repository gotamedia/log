<?php

declare(strict_types=1);

namespace Atoms\Log\Formatter;

use Throwable;

trait NormalizationTrait
{
    /**
     * @var bool
     */
    protected $includeTrace = false;

    /**
     * Normalizes the record context.
     *
     * @param array $context
     * @return array
     */
    protected function normalizeContext(array $context): array
    {
        if (isset($context['exception']) && $context['exception'] instanceof Throwable) {
            $exception = $context['exception'];

            $context['exception'] = [
                'class' => get_class($exception),
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile() . ':' . $exception->getLine()
            ];

            if ($this->includeTrace) {
                $rows = array_reverse($exception->getTrace());
                $trace = [];

                foreach ($rows as $row) {
                    unset($row['args']);

                    if (!isset($row['file'])) {
                        $trace[] = $row['class'] . $row['type'] . $row['function'] . '()';

                        continue;
                    }

                    $class = $row['class'] ?? '';
                    $type = $row['type'] ?? '';
                    $trace[] = $row['file'] . ':' . $row['line'] . ' called ' . $class . $type . $row['function'];
                }

                $context['exception']['trace'] = array_reverse($trace);
            }
        }

        return $context;
    }

    /**
     * Enables the inclusion of the stack trace in logs.
     */
    public function enableTrace(): void
    {
        $this->includeTrace = true;
    }

    /**
     * Disables the inclusion of stack trace in logs.
     */
    public function disableTrace(): void
    {
        $this->includeTrace = false;
    }
}
