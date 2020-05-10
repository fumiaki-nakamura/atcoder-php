<?php
// https://atcoder.jp/contests/practice/tasks/practice_1
list($a)     = $stream->readLine('%d');
list($b, $c) = $stream->readLine('%d %d');
list($s)     = $stream->readLine('%s');
$stream->writeLine(($a + $b + $c) . ' ' . $s);
