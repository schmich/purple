<?php

namespace Purple;

class Evaluator
{
  function __construct() {
    $this->context = $this->runInContext();
  }

  function run($source) {
    $this->context->send($source);
    $result = $this->context->current();
    return $result;
  }

  private function prepare($source) {
    // http://php.net/manual/en/tokens.php
    static $statements = [
      T_RETURN, T_THROW, T_CLASS, T_FUNCTION, T_INTERFACE, T_ABSTRACT,
      T_STATIC, T_ECHO, T_INCLUDE, T_INCLUDE_ONCE, T_REQUIRE, T_REQUIRE_ONCE,
      T_TRY, T_UNSET
    ];

    $prepared = trim($source);
    $tokens = token_get_all("<?php $prepared");

    if (in_array($tokens[1][0], $statements)) {
      // Statement.
    } else {
      // Expression.
      $prepared = "return $prepared";
    }

    if ($prepared[strlen($prepared) - 1] !== ';') {
      $prepared .= ';';
    }

    return $prepared;
  }

  // TODO: Move to external function?
  // TODO: Use rare naming scheme to avoid evaluation collision (e.g. $source).
  private function runInContext() {
    $source = yield;
    while (true) {
      $source = (yield eval($this->prepare($source)));
    }
  }
}
