<?php

list(, $__devCodeFilePath, $__mainCodeFilePath, $__testCodeFilePath, $__timeLimit) = $argv;

/**
 * Class AtCoderException
 */
class AtCoderException extends Exception
{
}

$__time0       = microtime(true);
$__test_result = ['message' => 'Memory Limit Exceeded'];

set_time_limit($__timeLimit);

// ob_start and ob_end_clean prevent time/memory limit exceeded outputs.
ob_start();

register_shutdown_function(function () use (&$__test_result, $__timeLimit, $__time0) {
    ob_end_clean();

    $__test_result['elapsed_time'] = microtime(true) - $__time0;
    if ($__test_result['elapsed_time'] >= $__timeLimit) {
        $__test_result['message'] = 'Time Limit Exceeded';
    }

    echo json_encode($__test_result);
});

try {
    require $__devCodeFilePath;
    require $__testCodeFilePath;
    require $__mainCodeFilePath;

    try {
        $__test_result['message'] = $stream->response();
    } catch (AtCoderException $e) {
        $__test_result['message'] = $e->getMessage();
    } catch (Throwable $e) {
        $__test_result['message'] = "Internal Error\n{$e->getMessage()}\n{$e->getTraceAsString()}";
    }
} catch (Throwable $e) {
    $__test_result['message'] = "Runtime Error\n{$e->getMessage()}\n{$e->getTraceAsString()}";
}

$__test_result['elapsed_time'] = microtime(true) - $__time0;

exit(0);
