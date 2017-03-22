<?php
$name = $argv[1];
$cls = 'Vue_' . $name;
$filePath = __DIR__ . '/components/' . $name . '/' . $name . '.php';
if ($argv[2]) {
    $filePath = $argv[2];
}
include_once($filePath);
$instance = new $cls();
$info = $instance->_render();
echo $info['html'];