<?php

ini_set('assert.exception', 1);

require 'index.php';

$games = [
  [
    'firstTeam' => 0,
    'secondTeam' => 1,
    'score' => [1, 1]
  ],[
    'firstTeam' => 1,
    'secondTeam' => 0,
    'score' => [1, 1]
  ],[
    'firstTeam' => 0,
    'secondTeam' => 2,
    'score' => [1, 0]
  ],[
    'firstTeam' => 2,
    'secondTeam' => 0,
    'score' => [0, 1]
  ]
];

foreach ($games as $game) {
  $score = match($game['firstTeam'], $game['secondTeam']);
  assert($score === $game['score'], msg($score, $game));
  print('.');
}
print("\n");
print("All tests were completed successfully.\n");

function msg(array $actual, array $game) {
  return sprintf(
    "The actual score [%s] does not equal the expected score [%s] for game %d vs %d.\n",
    implode(',', $actual),
    implode(',', $game['score']),
    $game['firstTeam'],
    $game['secondTeam']
  );
}