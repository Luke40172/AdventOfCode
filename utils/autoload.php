<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'functions.php';

spl_autoload_register(function ($class_name) {
    if (strpos($class_name, 'Utils') === 0) {
        $dir       = __DIR__;
        $file_name = str_replace('Utils\\', '', $class_name);
    } else {
        $dir       = getcwd();
        $file_name = $class_name;
    }
    $file_name = str_replace('\\', '/', $file_name);

    require $dir . DIRECTORY_SEPARATOR . $file_name . '.php';
});
