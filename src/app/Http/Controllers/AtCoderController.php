<?php
namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class AtCoderController
 * @package App\Http\Controllers
 */
class AtCoderController extends BaseController
{
    /**
     * @return Response
     */
    public function get(): Response
    {
        return response()->view('at_coder');
    }

    /**
     * @return JsonResponse
     */
    public function submit(Request $request): JsonResponse
    {
        $data = $request->toArray();

        $data['kokokita'] = true;
        sleep(3);

        return response()->json($data);
    }
}
