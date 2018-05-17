<?php

namespace Atoms\Log\Handlers;

use InvalidArgumentException;
use Psr\Log\LogLevel;

class StreamHandler extends AbstractHandler
{
    /**
     * The log resource.
     *
     * @var resource
     */
    protected $resource;

    /**
     * Creates a new StreamHandler instance.
     *
     * @param resource $resource
     * @param string $level
     */
    public function __construct($resource, $level = LogLevel::DEBUG)
    {
        if (!is_resource($resource) || get_resource_type($resource) !== 'stream') {
            throw new InvalidArgumentException('Invalid resource; must be a valid resource');
        }

        $this->resource = $resource;

        parent::__construct($level);
    }

    /**
     * Handles the log message.
     *
     * @param string $level
     * @param mixed $message
     * @param array $context
     */
    public function handle($level, $message, array $context = [])
    {
        $date = $this->formatDate();
        $level = $this->formatLevel($level);

        fwrite($this->resource, "[{$date}] {$level}: " . $this->formatMessage($message) . PHP_EOL);
    }

    /**
     * Formats the date for the logger.
     *
     * @return string
     */
    private function formatDate()
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * Formats the level for the logger.
     *
     * @param string $level
     * @return string
     */
    private function formatLevel($level)
    {
        return strtoupper($level);
    }

    /**
     * Formats the parameters for the logger.
     *
     * @param  mixed $message
     * @return mixed
     */
    private function formatMessage($message)
    {
        if (is_array($message)) {
            return $this->createDump($message);
        } elseif (is_object($message)) {
            return $this->createDump($message);
        }
        /*
        elseif ($message instanceof Jsonable) {
            return $message->toJson();
        }
        elseif ($message instanceof Arrayable) {
            return var_export($message->toArray(), true);
        }
        */

        return $message;
    }

    /**
     * Creates a dump for logging.
     *
     * @param  mixed $message
     * @return string
     */
    private function createDump($message)
    {
        /** Start the output buffer and dump the message */
        ob_start();

        var_dump($message);

        /** Get the output buffer and empty it */
        $dump = ob_get_contents();
        ob_end_clean();

        return $dump;
    }
}
