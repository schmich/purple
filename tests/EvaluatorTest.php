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
      '1' => 1,
      'true' => true,
      'TRUE' => TRUE,
      'false' => false,
      'FALSE' => false,
      '"a"' => 'a',
      '1+1' => 2
    ];

    foreach ($tests as $source => $expected) {
      $actual = $this->evaluator->run($source);
      $this->assertEquals($expected, $actual);
    }
  }

  function testFunctions() {
    $this->evaluator->run('$f = function($x, $y) { return $x + $y; }');
    $actual = $this->evaluator->run('$f(10, 20)');
    $this->assertEquals(30, $actual);
  }
}
