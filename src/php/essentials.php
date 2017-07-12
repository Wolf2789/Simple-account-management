<?php

function __autoload($class) {
    $classParts = explode("\\", $class);
    $classLength = count($classParts);
    $className = $classParts[$classLength - 1];
    $namespace = $classParts[0];
    for ($i = 1; $i < $classLength - 1; $i++)
        $namespace .= '/' . $classParts[$i];

    $file = "/var/www/html/src/$namespace/$className.php";
    if (file_exists($file))
        include $file;
}

function mergeAssoc($arr1, $arr2) {
    return array_combine(
        array_merge(
            array_keys($arr1),
            array_keys($arr2)
        ),
        array_merge(
            array_values($arr1),
            array_values($arr2)
        )
    );
}

function encrypt($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12, 'salt' => '$2a$07$T35T4PPL1C4T10N$']);
}

function logd($message) {
    $message = date("Y-m-d H:i:s # ").$message.PHP_EOL;
    file_put_contents('/var/www/log/php-localhost', $message, FILE_APPEND | LOCK_EX);
}

function error($message) {
    $_SESSION['message'] = array('danger', "<strong>Error!</strong> $message");
}

function success($message) {
    $_SESSION['message'] = array('success', "<strong>Success!</strong> $message");
}

function info($message) {
    $_SESSION['message'] = array('info', "<strong>&lt;?&gt;</strong> $message");
}

