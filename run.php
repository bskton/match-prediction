<?php

require 'src/data.php';
require 'index.php';

$n = 1;
for($i = 0; $i < count($data); $i++) {
  for ($j = 0; $j < count($data); $j++) {
    if ($i != $j) {
      $r = match($i, $j);
      printf("%d) %d vs %d score %d:%d \n", $n++, $i, $j, $r[0], $r[1]);
    }
  }
}