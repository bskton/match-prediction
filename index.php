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

  print("Scored Goals Probability For First Team\n");
  $scoredGoalsProbabilityForFirstTeam = scoredGoalsProbability($avgExpectedScoredGoalsForFirstTeam);
  foreach($scoredGoalsProbabilityForFirstTeam as $scoredGoals => $probability) {
    printf("%d %f \n", $scoredGoals, $probability);
  }

  print("Scored Goals Probability For Second Team\n");
  $scoredGoalsProbabilityForSecondTeam = scoredGoalsProbability($avgExpectedScoredGoalsForSecondTeam);
  foreach($scoredGoalsProbabilityForSecondTeam as $scoredGoals => $probability) {
    printf("%d %f \n", $scoredGoals, $probability);
  }

  return [$firstTeam, $secondTeam];
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

function scoredGoalsProbability(float $avgExpectedScoredGoals) {
  for ($i = 0; $i < 8; $i++) {
    yield stats_dens_pmf_poisson($i, $avgExpectedScoredGoals);
  }
}