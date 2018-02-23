<?php

use PHPUnit\Framework\TestCase;

class EvaluatorTest extends TestCase
{
  protected $evaluator;

  protected function setUp() {
    $this->evaluator = new \Purple\Evaluator();
  }

  function testExpressions() {
    $this->sequence([
      ['1', 1],
      [' 1 ', 1],
      ['true', true],
      ['TRUE', TRUE],
      ['false', false],
      ['FALSE', false],
      ['null', null],
      ['NULL', NULL],
      ['"a"', 'a'],
      ['!true', false],
      ['!!true', true],
      ['[1, 2]', [1, 2]],
      ['array(1, 2)', [1, 2]],
      ['1 + 1', 2],
      ['(2 * 3) + 4', 10]
    ]);
  }

  function testStatements() {
    $this->sequence([
      ['class Foo{}', null]
    ]);
  }

  function testUnset() {
    $this->sequence([
      ['$x = 42', 42],
      ['unset($x)', null],
      ['$x', null]
    ]);
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
        $this->assertSame($expected, $actual, "Evaluating `$source`");
      }
    }
  }
}
