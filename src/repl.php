#!/usr/bin/env php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

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

$reader = function() {
  while (true) {
    $source = readline(Color::set('php', 'magenta') . '> ');
    if ($source[0] === null) {
      break;
    }

    yield $source;
  }
};

$evaluator = [new \Purple\Evaluator(), 'run'];

$printer = function($result) {
  var_dump($result);
};

$shell = new \Purple\Shell($reader, $evaluator, $printer);
$shell->run();

// TODO: Create a bundled Purple shell that wraps this all together.
