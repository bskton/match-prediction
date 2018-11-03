<?php

function match(int $firstTeam, int $secondTeam) : array {
  require 'data.php';

  $totalGames = 0;
  $totalScoredGoals = 0;
  foreach ($data as $team) {
    $totalGames += $team['games'];
    $totalScoredGoals += $team['goals']['scored'];
  }
  $avgScoredGoals = $totalScoredGoals / $totalGames; // 1.5619402985075

  $avgScoredGoalsByFirstTeam = avgScoredGoalsBy($firstTeam, $data);
  $avgSkippedGoalsByFirstTeam = avgSkippedGoalsBy($firstTeam, $data);
  $avgScoredGoalsBySecondTeam = avgScoredGoalsBy($secondTeam, $data);
  $avgSkippedGoalsBySecondTeam = avgSkippedGoalsBy($secondTeam, $data);

  $relAttackPowerForFirstTeam = relAttackPowerFor($firstTeam, $data, $avgScoredGoals);
  $relDefensePowerForFirstTeam = relDefensePowerFor($firstTeam, $data, $avgScoredGoals);
  $relAttackPowerForSecondTeam = relAttackPowerFor($secondTeam, $data, $avgScoredGoals);
  $relDefensePowerForSecondTeam = relDefensePowerFor($secondTeam, $data, $avgScoredGoals);

  $avgExpectedScoredGoalsForFirstTeam = avgExpectedScoredGoalsFor($firstTeam, $secondTeam, $data, $avgScoredGoals);
  $avgExpectedScoredGoalsForSecondTeam = avgExpectedScoredGoalsFor($secondTeam, $firstTeam, $data, $avgScoredGoals);

  $scoredGoalsProbabilityForFirstTeam = scoredGoalsProbability($avgExpectedScoredGoalsForFirstTeam);
  $mostExpectedScoredGoalsForFirstTeam = mostExpectedScoredGoals($scoredGoalsProbabilityForFirstTeam);

  $scoredGoalsProbabilityForSecondTeam = scoredGoalsProbability($avgExpectedScoredGoalsForSecondTeam);
  $mostExpectedScoredGoalsForSecondTeam = mostExpectedScoredGoals($scoredGoalsProbabilityForSecondTeam);

  return [$mostExpectedScoredGoalsForFirstTeam, $mostExpectedScoredGoalsForSecondTeam];
}

function avgScoredGoalsBy(int $team, array $data) : float {
  return $data[$team]['goals']['scored'] / $data[$team]['games'];
}

function avgSkippedGoalsBy(int $team, array $data) : float {
  return $data[$team]['goals']['skiped'] / $data[$team]['games'];
}

function relAttackPowerFor(int $team, array $data, float $avgScoredGoals) : float {
  return avgScoredGoalsBy($team, $data) / $avgScoredGoals;
}

function relDefensePowerFor(int $team, array $data, float $avgScoredGoals) : float {
  return avgSkippedGoalsBy($team, $data) / $avgScoredGoals;
}

function avgExpectedScoredGoalsFor(int $team, int $opponent, array $data, float $avgScoredGoals) : float {
  return relAttackPowerFor($team, $data, $avgScoredGoals) * relDefensePowerFor($opponent, $data, $avgScoredGoals) * $avgScoredGoals;
}

function scoredGoalsProbability(float $avgExpectedScoredGoals) : array {
  $res = [];
  for ($i = 0; $i < 8; $i++) {
    $res[] = stats_dens_pmf_poisson($i, $avgExpectedScoredGoals);
  }
  return $res;
}

function mostExpectedScoredGoals(array $scoredGoalsProbability) : int {
  return array_keys($scoredGoalsProbability, max($scoredGoalsProbability))[0];
}