<?php

ini_set('assert.exception', 1);

require 'index.php';

$result = match(0, 1);
$expected = [0, 1];

assert($result === $expected, 'Result ['.implode(',', $result).'] does not equal expected value ['.implode(',', $expected).']');

print("All tests were completed successfully.\n");