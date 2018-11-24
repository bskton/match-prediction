<?php

class Prediction
{
  private $data;

  private $avgScoredGoals;

  private $avgSkippedGoals;

  function __construct(array $data) {
    $this->data = $data;
  }

  function makeFor(int $firstTeam, int $secondTeam) : array {
    $this->avgScoredAndSkippedForLeague();

    return [
      $this->mostExpectedScoredGoals($firstTeam, $secondTeam),
      $this->mostExpectedScoredGoals($secondTeam, $firstTeam)
    ];
  }

  private function avgScoredAndSkippedForLeague() {
    $totalGames = 0;
    $totalScoredGoals = 0;
    $totalSkippedGoals = 0;
    foreach ($this->data as $team) {
      $totalGames += $team['games'];
      $totalScoredGoals += $team['goals']['scored'];
      $totalSkippedGoals += $team['goals']['skiped'];
    }
    $this->avgScoredGoals = $totalScoredGoals / $totalGames;
    $this->avgSkippedGoals = $totalSkippedGoals / $totalGames;
  }

  private function avgScoredGoalsBy(int $team) : float {
    return $this->data[$team]['goals']['scored'] / $this->data[$team]['games'];
  }

  private function avgSkippedGoalsBy(int $team) : float {
     return $this->data[$team]['goals']['skiped'] / $this->data[$team]['games'];
  }

  private function relAttackStrengthFor(int $team) : float {
    return $this->avgScoredGoalsBy($team) / $this->avgScoredGoals;
  }

  private function relDefenseStrengthFor(int $team) : float {
    return $this->avgSkippedGoalsBy($team) / $this->avgSkippedGoals;
  }

  private function avgExpectedScoredGoalsFor(int $team, int $opponent) : float {
    return $this->relAttackStrengthFor($team) 
      * $this->relDefenseStrengthFor($opponent) 
      * $this->avgScoredGoals;
  }

  private function scoredGoalsProbability(int $team, int $opponent) : array {
    $res = [];
    for ($i = 0; $i < 6; $i++) {
      $res[] = $this->poisson($i, $this->avgExpectedScoredGoalsFor($team, $opponent));
    }
    return $res;
  }

  private function poisson(int $x, float $lb) {
    return pow($lb, $x) / $this->factorial($x) * exp(-$lb);
  }

  private function factorial(int $n) {
    $f = 1; 
    for ($i = 1; $i <= $n; $i++){ 
      $f = $f * $i; 
    } 
    return $f;
  }

  function mostExpectedScoredGoals(int $team, int $opponent) : int {
    $scoredGoalsProbability = $this->scoredGoalsProbability($team, $opponent);
    return array_keys($scoredGoalsProbability, max($scoredGoalsProbability))[0];
  }
}
