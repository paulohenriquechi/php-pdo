<?php

use Alura\Pdo\Domain\Model\Student;

require_once 'vendor/autoload.php';

$databasePath = __DIR__.'/database.sqlite';
$pdo = new PDO("sqlite:$databasePath");

$sqlGet = "SELECT * FROM students";

$statement = $pdo->query($sqlGet);
$studentsDataList = $statement->fetchAll(PDO::FETCH_ASSOC);
$students = [];

foreach ($studentsDataList as $studentData) {
    $students[] = new Student(
        $studentData['id'], 
        $studentData['name'], 
        new DateTimeImmutable($studentData['birth_date'])
    );
}

var_dump($students);