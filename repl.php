#!/usr/bin/env php
<?php

// $x = 44;
// 3 + 3

/*
class Color
{
    protected static $ANSI_CODES = array(
        "off"        => 0,
        "bold"       => 1,
        "italic"     => 3,
        "underline"  => 4,
        "blink"      => 5,
        "inverse"    => 7,
        "hidden"     => 8,
        "black"      => 30,
        "red"        => 31,
        "green"      => 32,
        "yellow"     => 33,
        "blue"       => 34,
        "magenta"    => 35,
        "cyan"       => 36,
        "white"      => 37,
        "black_bg"   => 40,
        "red_bg"     => 41,
        "green_bg"   => 42,
        "yellow_bg"  => 43,
        "blue_bg"    => 44,
        "magenta_bg" => 45,
        "cyan_bg"    => 46,
        "white_bg"   => 47
    );
    public static function set($str, $color)
    {
        $color_attrs = explode("+", $color);
        $ansi_str = "";
        foreach ($color_attrs as $attr) {
            $ansi_str .= "\033[" . self::$ANSI_CODES[$attr] . "m";
        }
        $ansi_str .= $str . "\033[" . self::$ANSI_CODES["off"] . "m";
        return $ansi_str;
    }
    public static function log($message, $color)
    {
        error_log(self::set($message, $color));
    }
    public static function replace($full_text, $search_regexp, $color)
    {
        $new_text = preg_replace_callback(
            "/($search_regexp)/",
            function ($matches) use ($color) {
                return Color::set($matches[1], $color);
            },
            $full_text
        );
        return is_null($new_text) ? $full_text : $new_text;
    }
}

while (true) {
  $code = readline(Color::set('php', 'magenta') . '> ');
  if ($code[0] === null) {
    break;
  }

}
 */

class Evaluator
{
  function run($source) {
    static $statements = [
      T_RETURN, T_THROW, T_CLASS, T_FUNCTION, T_INTERFACE, T_ABSTRACT,
      T_STATIC, T_ECHO, T_INCLUDE, T_INCLUDE_ONCE, T_REQUIRE, T_REQUIRE_ONCE,
      T_TRY
    ];

    $tokens = token_get_all("<?php $source");

    if (in_array($tokens[1], $statements)) {
      // statement
    } else {
      // Expression.
      $source = "return $source;";
    }

    return eval($source);

    //$output = eval('return ' . $code . ';');
    //$output = eval($code . ';');
    //echo "$output\n";
  }
}

class Shell
{
  function __construct() {
    $this->evaluator = new Evaluator();
  }

  function run() {
    while (true) {
      $source = readline('php> ');
      if ($source[0] === null) {
        break;
      }

      $result = $this->evaluator->run($source);
      echo($result . "\n");
    }
  }
}

/*
class Repl
{
  function __construct($reader, $evaluator, $printer) {
    $this->reader = $reader;
    $this->evaluator = $evaluator;
    $this->printer = $printer;
  }

  function run() {
    while ($line = $reader->read()) {
      $result = $this->evaluator->run($line);
      $this->printer->print($result);
    }
  }
}
*/

$shell = new Shell();
$shell->run();

function run($test) {
  $eval = new Evaluator();
  foreach ($test as $line => $expected) {
    $actual = $eval->run($line);
    if ($actual !== $expected) {
      throw new Exception('Test failure.');
    }
  }
}

$tests = [
  ['1' => 1],
  ['true' => true],
  ['TRUE' => TRUE],
  ['false' => false],
  ['FALSE' => FALSE],
  ['"a"' => 'a'],
  ['1 + 1' => 2],
  //['$x = 10; $x * 2' => 20],
];

foreach ($tests as $test) {
  run($test);
}
