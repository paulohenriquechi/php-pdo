<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infra\Persistence\ConnectionCreator;
use Alura\Pdo\Infra\Repository\PdoStudentRepository;

require_once 'vendor/autoload.php';

$connection = ConnectionCreator::createConnection();
$studentRepository = new PdoStudentRepository($connection);

$connection->beginTransaction();

try {
    $aStudent = new Student(null, 'Nico Steppat', New DateTimeImmutable('10/01/2000'));
    $studentRepository->save($aStudent);
    
    $anotherStudent = new Student(null, 'Feitan', new DateTimeImmutable('08/10/1999'));
    $studentRepository->save($anotherStudent);

    $connection->commit();
} catch (\PDOException $e) {
    echo $e->getMessage();
    $connection->rollBack();
}

