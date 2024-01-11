<?php

use Alura\Pdo\Infra\Persistence\ConnectionCreator;
use Alura\Pdo\Infra\Repository\PdoStudentRepository;

require_once 'vendor/autoload.php';

$connection = ConnectionCreator::createConnection();

$repository = new PdoStudentRepository($connection);
$deleted = $repository->remove(4);