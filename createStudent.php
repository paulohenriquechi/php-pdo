<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infra\Persistence\ConnectionCreator;
use Alura\Pdo\Infra\Repository\PdoStudentRepository;

require_once 'vendor/autoload.php';

$connection = ConnectionCreator::createConnection();
$repository = new PdoStudentRepository($connection);

$student = new Student(null, 'Gabriel', new DateTimeImmutable('05-11-2003'));

$repository->save($student);
