<?php

use PHPUnit\Framework\TestCase;

class EvaluatorTest extends TestCase
{
  protected $evaluator;

  protected function setUp() {
    $this->evaluator = new \Purple\Evaluator();
  }

  function testExpressions() {
    $tests = [
      ['1', 1],
      ['true', true],
      ['TRUE', TRUE],
      ['false', false],
      ['FALSE', false],
      ['null', null],
      ['NULL', NULL],
      ['"a"', 'a'],
      ['1+1', 2]
    ];

    $this->sequence($tests);
  }

  function testContext() {
    $this->sequence([
      ['$x', null],
      ['$x = 42', 42],
      ['$x', 42]
    ]);
  }

  function testFunctions() {
    $this->sequence([
      ['$f = function($x, $y) { return $x + $y; }'],
      ['$f(10, 20)', 30]
    ]);
  }

  private function sequence($tests) {
    foreach ($tests as $test) {
      $source = $test[0];
      $actual = $this->evaluator->run($source);
      if (count($test) === 2) {
        $expected = $test[1];
        $this->assertEquals($expected, $actual, "Evaluating `$source`");
      }
    }
  }
}
