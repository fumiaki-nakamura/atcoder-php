<?php
namespace App\Http\Controllers;

use App\Http\Requests\TestCaseRequest;
use Domain\DTOs\TestCase;
use Domain\Repositories\TestCaseRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class TestCaseController
 * @package App\Http\Controllers
 */
class TestCaseController extends BaseController
{
    /** @var TestCaseRepositoryInterface */
    private $testCaseRepository;

    /**
     * TestCaseController constructor.
     * @param TestCaseRepositoryInterface $testCaseRepository
     */
    public function __construct(TestCaseRepositoryInterface $testCaseRepository)
    {
        $this->testCaseRepository = $testCaseRepository;
    }

    /**
     * @return JsonResponse
     */
    public function __invoke(TestCaseRequest $request): JsonResponse
    {
        $testCaseEntity = $this->testCaseRepository->create(TestCase::create($request->all()));

        $error = $testCaseEntity->lint();
        if (!empty($error)) {
            return TestCaseRequest::responseSyntaxError($error);
        }

        $this->testCaseRepository->run($testCaseEntity);

        return TestCaseRequest::response($testCaseEntity);
    }
}
