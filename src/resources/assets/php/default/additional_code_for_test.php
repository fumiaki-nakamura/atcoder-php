<?php

/**
 * Class AtCoderStream
 */
class AtCoderStream
{
    private $inputs;
    private $outputs;
    private $expected;

    /**
     * AtCoderStream constructor.
     * @param array $inputs
     * @param string $expected
     */
    public function __construct(array $inputs, string $expected)
    {
        $this->inputs   = $inputs;
        $this->outputs  = [];
        $this->expected = $expected;
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
     * @return string
     * @throws Exception
     */
    public function response(): string
    {
        $actual  = end($this->outputs);
        $outputs = implode("\n", $this->outputs);

        if (strlen($actual) > 1024) {
            throw new AtCoderException("Output Limit Exceeded\noutputs:\n$outputs");
        }

        if ($actual != $this->expected) {
            throw new AtCoderException("Wrong Answer\nactual: $actual\nexpected: {$this->expected}\noutputs:\n$outputs");
        }

        return "Accepted\noutputs:\n$outputs";
    }
}
