<?php
namespace Infra\Repositories;

use Domain\DTOs\Result;
use Domain\DTOs\TestCase;
use Domain\Entities\TestCaseEntity;
use Domain\Factories\TestCaseFactory;
use Domain\Repositories\TestCaseRepositoryInterface;

/**
 * Class TestCaseRepository
 * @package Infra\Repositories
 */
class TestCaseRepository implements TestCaseRepositoryInterface
{
    /** @var TestCaseFactory */
    private $testCaseFactory;

    /**
     * TestCaseRepository constructor.
     * @param TestCaseFactory $testCaseFactory
     */
    public function __construct(TestCaseFactory $testCaseFactory)
    {
        $this->testCaseFactory = $testCaseFactory;
    }

    /**
     * @param array $testCase
     * @return TestCaseEntity
     */
    public function create(TestCase $testCase): TestCaseEntity
    {
        return $this->testCaseFactory->create($testCase);
    }

    /**
     * @param TestCaseEntity $testCaseEntity
     */
    public function run(TestCaseEntity $testCaseEntity): void
    {
        $command = $testCaseEntity->createCommand();

        exec($command, $outputs);
        $primitives = json_decode(implode("\n", $outputs), true);

        if (is_array($primitives)) {
            $result = Result::create($primitives);
        } else {
            $result = Result::create([
                'elapsed_time' => 0,
                'message'      => "Unknown Error\n" . implode("\n", $outputs),
            ]);
        }

        $testCaseEntity->setResult($result);
    }
}
