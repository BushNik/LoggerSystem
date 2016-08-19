<?php

use ru\f_technology\logger\Logger;

require_once '../Logger.php';
require_once 'classes.php';

$params = array('persistent' => true);
$dbProperties = array(
    'host' => 'localhost',
    'dbName' => 'test_db',
    'username' => 'test',
    'password' => '123456',
    'table' => 'log_table',
    'charset' => 'utf8'
);
$logger = Logger::singleton('SQL', $dbProperties, 'ident', $params);
for ($i = 0; $i < 3; $i++) {
    $logger->log("Log entry $i");
}
$cars = array("Volvo", "BMW", "Toyota");
$logger->log($cars);
$logger->log(new Exception('Деление на ноль.'));

// создание экземпляров объектов
$veggie = new Vegetable(true, "blue");
$leafy = new Spinach();

$logger->log($veggie);
$logger->log($leafy);
