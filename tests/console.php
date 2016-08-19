<?php

use ru\f_technology\logger\Logger;

require_once '../Logger.php';
require_once 'classes.php';

$params = array('timeFormat' => '%Y-%m-%d %H:%M:%S');
$logger = Logger::singleton('Console', '', 'ident', $params);
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
