<?php

/**
 * 自动载入类文件
 */
spl_autoload_register(function ($class) {
    include str_ireplace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
});
