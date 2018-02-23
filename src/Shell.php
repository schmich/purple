<?php

namespace Purple;

use function Colorize\magenta;

class Shell
{
  function __construct() {
    $reader = function() {
      while (true) {
        $source = readline(magenta('php') . '> ');
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
