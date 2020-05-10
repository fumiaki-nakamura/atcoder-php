<?php
namespace Domain\Entities;

use Domain\DTOs\Result;
use Domain\DTOs\TestCase;
use Domain\VOs\Code;

/**
 * Class TestCaseEntity
 * @package Domain
 * @property-read float elapsedTime
 * @property-read string message
 */
class TestCaseEntity
{
    /** @var Code */
    private $devCode;

    /** @var Code */
    private $mainCode;

    /** @var Code */
    private $testCode;

    /** @var int */
    private $timeLimit;

    /** @var int */
    private $memoryLimit;

    /** @var float */
    private $elapsedTime;

    /** @var string */
    private $message;

    /**
     * TestCaseEntity constructor.
     * @param TestCase $testCase
     */
    public function __construct(TestCase $testCase)
    {
        $this->devCode     = $testCase->devCode;
        $this->mainCode    = $testCase->mainCode;
        $this->testCode    = $testCase->testCode;
        $this->timeLimit   = $testCase->timeLimit;
        $this->memoryLimit = $testCase->memoryLimit;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        switch ($name) {
            case 'elapsedTime':
            case 'message':
                return $this->$name;
        }

        return null;
    }

    /**
     * @return string
     */
    public function lint(): string
    {
        $error = '';

        if (!$this->devCode->isPhp()) {
            $error .= $this->devCode->lintPhp() . "\n";
        }

        if (!$this->mainCode->isPhp()) {
            $error .= $this->mainCode->lintPhp() . "\n";
        }

        if (!$this->testCode->isPhp()) {
            $error .= $this->testCode->lintPhp() . "\n";
        }

        return $error;
    }

    /**
     * @return string
     */
    public function createCommand(): string
    {
        $scriptFilePath   = resource_path('assets/php/test.php');
        $devCodeFilePath  = escapeshellarg($this->devCode->getFilePath());
        $mainCodeFilePath = escapeshellarg($this->mainCode->getFilePath());
        $testCodeFilePath = escapeshellarg($this->testCode->getFilePath());

        return "php -d memory_limit={$this->memoryLimit}MB $scriptFilePath $devCodeFilePath $mainCodeFilePath $testCodeFilePath {$this->timeLimit}";
    }

    /**
     * @param Result $result
     */
    public function setResult(Result $result): void
    {
        $this->elapsedTime = $result->elapsedTime;
        $this->message     = $result->message;
    }

    /**
     * @return bool
     */
    public function isAccepted(): bool
    {
        return strpos($this->message, 'Accepted') === 0;
    }
}
