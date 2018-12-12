<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

function _autoload($class)
{
    require_once $class . '.php';
}

spl_autoload_register('_autoload');