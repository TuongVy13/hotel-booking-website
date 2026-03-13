<?php
function loadClass($class)
{
    $file = ROOT . "/classes/" . $class . ".class.php";
    if (file_exists($file)) {
        include $file;
    }
}

spl_autoload_register('loadClass');

function getIndex($index, $value='')
{
	$data = isset($_GET[$index])? $_GET[$index]:$value;
	return $data;
}

function postIndex($index, $value='')
{
	$data = isset($_POST[$index])? $_POST[$index]:$value;
	return $data;
}

function requestIndex($index, $value='')
{
	$data = isset($_REQUEST[$index])? $_REQUEST[$index]:$value;
	return $data;
}