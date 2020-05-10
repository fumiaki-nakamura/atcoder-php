<?php
namespace Domain\Factories;

use Domain\DTOs\TestCase;
use Domain\Entities\TestCaseEntity;

/**
 * Class TestCaseFactory
 * @package Domain\Factories
 */
class TestCaseFactory
{
    /**
     * @param TestCase $testCase
     */
    public function create(TestCase $testCase)
    {
        return new TestCaseEntity($testCase);
    }
}
