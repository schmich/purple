<?php

namespace Purple;
 
use function Colorize\magenta;

class Shell
{
  function __construct($historyFile) {
    $this->historyFile = $historyFile;
    if (file_exists($historyFile)) {
      readline_read_history($historyFile);
    }

    readline_completion_function(function($input) {
      // get_declared_interfaces()
      // get_declared_classes();
      // get_declared_traits();
      return array_merge(...array_values(get_defined_functions()));
    });

    $reader = [$this, 'read'];
    $evaluator = [new Evaluator(), 'run'];
    $printer = [$this, 'show'];
    
    $this->repl = new Repl($reader, $evaluator, $printer);
  }

  function run() {
    echo('Purple REPL · PHP ' . phpversion() . "\n");
    $this->repl->run();
  }

  function read() {
    while (true) {
      $source = readline(magenta('php') . '> ');
      if ($source[0] === null) {
        readline_write_history($this->historyFile);
        break;
      }

      readline_add_history($source);

      yield $source;
    }
  }

  function show($result) {
    var_dump($result);
  }
}
