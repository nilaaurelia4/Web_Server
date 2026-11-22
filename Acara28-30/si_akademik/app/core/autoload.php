<?php

spl_autoload_register(function (string $class): void {
    foreach (['config', 'core', 'models', 'controllers'] as $folder) {
        $file = __DIR__ . '/../' . $folder . '/' . $class . '.php';

        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});