<?php
namespace App\Http\Controllers;

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
    public function __invoke(): Response
    {
        return response()->view('at_coder');
    }
}
