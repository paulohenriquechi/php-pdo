<?php

use Alura\Pdo\Domain\Model\Student;

require_once 'vendor/autoload.php';

$databasePath = __DIR__.'/database.sqlite';
$pdo = new PDO("sqlite:$databasePath");

$student = new Student(null, 'Gabriel', new DateTimeImmutable('05-11-2003'));

$sqlInsert = "INSERT INTO students (name, birth_date) VALUES (:name , :birth_date);";
$statement = $pdo->prepare($sqlInsert);

$statement->bindValue(':name', $student->name());
$statement->bindValue(':birth_date', $student->birthDate()->format('Y-m-d'));

if ($statement->execute()) {
   var_dump("New student created successfully"); 
}
