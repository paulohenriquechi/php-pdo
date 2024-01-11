<?php

use Alura\Pdo\Infra\Persistence\ConnectionCreator;
use Alura\Pdo\Infra\Repository\PdoStudentRepository;

require_once 'vendor/autoload.php';

$connection = ConnectionCreator::createConnection();

$repository = new PdoStudentRepository($connection);
$students = $repository->allStudents();

var_dump($students);