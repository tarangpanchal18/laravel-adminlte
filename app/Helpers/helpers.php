<?php

if (! function_exists('strreplace')) {
    function strreplace($string, $from = '_', $to = ' ') {
        return str_replace($from, $to, $string);
    }
}
