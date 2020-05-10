<?php
namespace Domain\DTOs;

use Domain\VOs\Code;

/**
 * Class TestCase
 * @package Domain\DTOs
 * @property-read Code devCode
 * @property-read Code mainCode
 * @property-read Code testCode
 * @property-read int timeLimit
 * @property-read int memoryLimit
 */
class TestCase extends Base
{
    /** @var Code */
    protected $devCode;

    /** @var Code */
    protected $mainCode;

    /** @var Code */
    protected $testCode;

    /** @var int */
    protected $timeLimit;

    /** @var int */
    protected $memoryLimit;

    /**
     * TestCase constructor.
     * @param array $primitives
     */
    public function __construct(array $primitives)
    {
        $this->devCode     = Code::create($primitives['dev_code']);
        $this->mainCode    = Code::create($primitives['main_code']);
        $this->testCode    = Code::create($primitives['test_code']);
        $this->timeLimit   = (int)$primitives['time_limit'];
        $this->memoryLimit = (int)$primitives['memory_limit'];
    }

    /**
     * @param array $primitives
     * @return TestCase
     */
    public static function create(array $primitives): self
    {
        return new static($primitives);
    }
}
