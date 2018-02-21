<?php

namespace Purple;

class Repl
{
  function __construct($reader, $evaluator, $printer) {
    $this->reader = $reader;
    $this->evaluator = $evaluator;
    $this->printer = $printer;
  }

  function run() {
    foreach (call_user_func($this->reader) as $source) {
      $result = call_user_func($this->evaluator, $source);
      call_user_func($this->printer, $result);
    }
  }
}
