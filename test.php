<?php
$timeStart = microtime(true);

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
    'score' => [1, 1]
  ],[
    'firstTeam' => 2,
    'secondTeam' => 0,
    'score' => [1, 1]
  ],[
    'firstTeam' => 0,
    'secondTeam' => 31,
    'score' => [3, 0]
  ],[
    'firstTeam' => 31,
    'secondTeam' => 0,
    'score' => [0, 3]
  ]
];

foreach ($games as $game) {
  $score = match($game['firstTeam'], $game['secondTeam']);
  assert($score === $game['score'], msg($score, $game));
  print('.');
}
print("\n");

$timeEnd = microtime(true);
$executionTime = $timeEnd - $timeStart;
$interval = new DateInterval('PT'.intval($executionTime).'S');
printf("All tests were completed successfully in %s.\n", $interval->format("%i min %S sec"));
printf("Peak memory usage (real): %d bytes.\n", memory_get_peak_usage(true));

function msg(array $actual, array $game) {
  return sprintf(
    "The actual score [%s] does not equal the expected score [%s] for game %d vs %d.\n",
    implode(',', $actual),
    implode(',', $game['score']),
    $game['firstTeam'],
    $game['secondTeam']
  );
}