<?php

/**
 * ----------------------------------------------------------------
 * Constants used for logging only
 *
 * this constant are used for logging functions only
 * by using this constant you can define severity levels
 * ----------------------------------------------------------------
 */
define('ERROR', 1);
define('WARNING', 2);
define('CRITICAL', 3);


if (! function_exists('strreplace')) {
    function strreplace($string, $from = '_', $to = ' ') {
        return str_replace($from, $to, $string);
    }
}
