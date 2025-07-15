<?php

if (!function_exists('gridview')) {
    function gridview(array $config = [])
    {
        return (new \Picobaz\GridView\GridView($config))->render();
    }
}