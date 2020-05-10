<?php
namespace App\Http\Requests;

use Domain\Entities\TestCaseEntity;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

/**
 * Class TestCaseRequest
 * @package App\Http\Requests
 */
class TestCaseRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'dev_code'     => 'required|string',
            'main_code'    => 'required|string',
            'test_code'    => 'required|string',
            'time_limit'   => 'required|numeric|min:1|max:60',
            'memory_limit' => 'required|numeric|min:1|max:512',
        ];
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator): void
    {
        $response = [
            'errors' => $validator->errors()->toArray(),
        ];

        throw new HttpResponseException(response()->json($response, JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }

    /**
     * @param string $error
     * @return JsonResponse
     */
    public static function responseSyntaxError(string $error): JsonResponse
    {
        return response()->json([
            'passed'  => false,
            'message' => "Compilation Error\n$error",
        ]);
    }

    /**
     * @param TestCaseEntity $testCaseEntity
     * @return JsonResponse
     */
    public static function response(TestCaseEntity $testCaseEntity): JsonResponse
    {
        return response()->json([
            'passed'  => $testCaseEntity->isAccepted(),
            'message' => sprintf("%.3Fs elapsed.\n%s", $testCaseEntity->elapsedTime, $testCaseEntity->message),
        ]);
    }
}
