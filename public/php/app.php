<?php
include __DIR__ . '/person.php';

$person = new Person;
$person2 = new Person;

$person->name = 'Mohamed';
$person2->name ='Ayman';

$person::$country='Mansoura';
$person2::$country='Egypt';

var_dump($person,$person2);

echo $person::$country;
// echo $person2::$country;