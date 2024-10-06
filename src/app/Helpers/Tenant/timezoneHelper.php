<?php


use Illuminate\Support\Carbon;

if (!function_exists('nowFromApp')) {

    function nowFromApp(): Carbon
    {
        return now('Africa/Lagos');
    }

}

if (!function_exists('todayFromApp')) {

    function todayFromApp(): Carbon
    {
        return today('Africa/Lagos');
    }

}