<?php

namespace Purple;

class Shell
{
  function __construct() {
    $reader = function() {
      while (true) {
        $source = readline('php> ');
        if ($source[0] === null) {
          break;
        }

        yield $source;
      }
    };

    $evaluator = [new Evaluator(), 'run'];

    $printer = function($result) {
      var_dump($result);
    };

    $this->repl = new Repl($reader, $evaluator, $printer);
  }

  function run() {
    $this->repl->run();
  }
}
