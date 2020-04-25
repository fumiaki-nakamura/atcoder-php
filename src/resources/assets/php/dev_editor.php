<?php

/**
 * Class AtCoderStream
 */
class AtCoderStream
{
    private $inputs;
    private $outputs;

    /**
     * AtCoderStream constructor.
     * @param array $inputs
     */
    public function __construct(array $inputs)
    {
        $this->inputs  = $inputs;
        $this->outputs = [];
    }

    /**
     * @param string $format
     * @return array|null
     */
    public function readLine(string $format): ?array
    {
        $result = null;

        while (is_null($result) && !empty($this->inputs)) {
            $nextLine = array_shift($this->inputs);
            $result   = sscanf($nextLine, $format);
        }

        return $result;
    }

    /**
     * @param string $format
     */
    public function writeLine(string $format): void
    {
        $this->outputs[] = $format;
    }

    /**
     * @param string $expected
     */
    public function assert(string $expected): void
    {
        $actual = end($this->outputs);

        if ($actual != $expected) {
            echo 'WA - actual: ' . $actual;
        }
    }
}
