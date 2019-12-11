<?php
require_once __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(function ($class)
{
    if (strpos($class, 'KnotModule\\WhoopsExceptionHandler\\Test\\') === 0) {
        $name = substr($class, strlen('KnotModule\\WhoopsExceptionHandler\\Test\\'));
        $name = array_filter(explode('\\',$name));
        $file = __DIR__ . '/include/' . implode('/',$name) . '.php';
        /** @noinspection PhpIncludeInspection */
        require_once $file;
    }
});
