<?php

require 'src/prediction.php';

function match(int $firstTeam, int $secondTeam) : array {
  require 'src/data.php';

  $p = new Prediction($data);
  return $p->makeFor($firstTeam, $secondTeam);
}
