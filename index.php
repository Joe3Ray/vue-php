<?php
$name = $argv[1];

function getClassName($name) {
    $str = str_replace('-', '_', $name);
    $arr = explode('_', $str);
    $res = array();
    foreach ($arr as $v) {
        $v[0] = strtoupper($v[0]);
        $res[] = $v;
    }
    $str = join('_', $res);
    return $str;
}
$cls = getClassName($name);
$filePath = __DIR__ . '/components/' . $name . '/index.php';
include_once($filePath);
$instance = new $cls();
$info = $instance->_render();
echo $info['html'];