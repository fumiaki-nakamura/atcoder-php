<?php
namespace Domain\Repositories;

use Domain\DTOs\TestCase;
use Domain\Entities\TestCaseEntity;

/**
 * Interface TestCaseRepositoryInterface
 * @package Domain\Repositories
 */
interface TestCaseRepositoryInterface
{
    /**
     * @param array $testCase
     * @return TestCaseEntity
     */
    public function create(TestCase $testCase): TestCaseEntity;

    /**
     * @param TestCaseEntity $testCaseEntity
     */
    public function run(TestCaseEntity $testCaseEntity): void;
}
