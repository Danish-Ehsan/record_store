<?php
// Get the document root
$docRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_STRING);

// Get the application path
$uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_STRING);

$dirs = explode('/', $uri);
$appPath = '/' . $dirs[1] . '/';
$includePath = $docRoot . $appPath;

// Set the include path
set_include_path($includePath);

session_start();
