<?php

use ru\f_technology\logger\Logger;

require_once '../Logger.php';
require_once 'classes.php';

$paramsConsole = array('timeFormat' => '%Y-%m-%d %H:%M:%S');
$loggerConsole = &Logger::singleton('Console', '', 'ident', $paramsConsole);
$paramsFile = array('mode' => 0600, 'timeFormat' => '%Y-%m-%d %H:%M:%S', 'append' => true);
$loggerFile = &Logger::singleton('File', 'logs/out.log', 'ident', $paramsFile);
$paramsSQL = array('persistent' => true);
$dbProperties = array(
    'host' => 'localhost',
    'dbName' => 'test_db',
    'username' => 'test',
    'password' => '123456',
    'table' => 'log_table',
    'charset' => 'utf8'
);
$loggerSQL = &Logger::singleton('SQL', $dbProperties, 'ident', $paramsSQL);
for ($i = 0; $i < 3; $i++) {
    $loggerFile->log("Log entry $i");
    $loggerSQL->log("Log entry $i");
    $loggerConsole->log("Log entry $i");
}
$cars = array("Volvo", "BMW", "Toyota");
$loggerFile->log($cars);
$loggerFile->log(new Exception('Деление на ноль.'));
$loggerSQL->log($cars);
$loggerSQL->log(new Exception('Деление на ноль.'));
$loggerConsole->log($cars);
$loggerConsole->log(new Exception('Деление на ноль.'));

// создание экземпляров объектов
$veggie = new Vegetable(true, "blue");
$leafy = new Spinach();

$loggerFile->log($veggie);
$loggerFile->log($leafy);
$loggerSQL->log($veggie);
$loggerSQL->log($leafy);
$loggerConsole->log($veggie);
$loggerConsole->log($leafy);