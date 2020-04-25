<?php

list(, $mainCodeFile, $testCodeFile) = $argv;
require $testCodeFile;
$stream = new AtCoderStream([
    '1',
    '2 3',
    'test'
]);
require $mainCodeFile;
$stream->assert('6 test');
