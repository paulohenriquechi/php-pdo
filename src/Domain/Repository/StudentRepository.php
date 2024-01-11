<?php

namespace Alura\Pdo\Domain\Repository;

use Alura\Pdo\Domain\Model\Student;
use DateTimeInterface;
use PDOStatement;

interface StudentRepository 
{
    public function allStudents(): array;
    public function studentsWithPhones(): array;
    public function studentsByBirth(DateTimeInterface $birthDate): array;
    public function save(Student $student): bool;
    public function remove(int $id): bool;
}