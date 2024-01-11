<?php

namespace Alura\Pdo\Infra\Repository;

use Alura\Pdo\Domain\Model\Phone;
use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;
use DateTimeImmutable;
use DateTimeInterface;
use PDO;
use PDOStatement;

class PdoStudentRepository implements StudentRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;

    }

    public function allStudents(): array
    {
        $query = 'SELECT * FROM students';

        $statement = $this->connection->query($query);

        return $this->hydrateStudentList($statement);

    }

    public function studentsByBirth(DateTimeInterface $birthDate): array
    {
        $query = 'SELECT * FROM students WHERE birth_date = ?;';
        $statement = $this->connection->prepare($query);
        $statement->bindValue(1, $birthDate->format('Y-m-d'));
        $statement->execute();

        return $this->hydrateStudentList($statement);
    }

    public function hydrateStudentList(PDOStatement $statement): array
    {
        $studentsDataList = $statement->fetchAll();
        $students = [];

        foreach ($studentsDataList as $studentData) {
            $students[] = new Student(
                $studentData['id'], 
                $studentData['name'], 
                new DateTimeImmutable($studentData['birth_date'])
            );            
        }

        return $students;
    }

    // private function fillPhonesOf(Student $student): void
    // {
    //     $query = 'SELECT id, area_code, number FROM phones WHERE student_id = ?;';
    //     $statement = $this->connection->prepare($query);
    //     $statement->bindValue(1, $student->id(), PDO::PARAM_INT);
    //     $statement->execute();
        
    //     $phoneDataList = $statement->fetchAll();
    //     foreach ($phoneDataList as $phoneData) {
    //         $phone = new Phone(
    //             $phoneData['id'],
    //             $phoneData['area_code'],
    //             $phoneData['number']
    //         );

    //         $student->addPhone($phone);
    //     }
    // }

    public function save(Student $student): bool
    {
        if ($student->id() === null) 
            return $this->insert($student);

        return $this->update($student);
    }

    public function insert(Student $student): bool
    {
        $query = 'INSERT INTO students (name, birth_date) VALUES (:name , :birth_date);';
        $statement = $this->connection->prepare($query);

        $success = $statement->execute([
            ':name' => $student->name(),
            ':birth_date' => $student->birthDate()->format('Y-m-d')
        ]);

        if ($success) 
            $student->defineId($this->connection->lastInsertId());
        
        return $success;

    }

    public function update(Student $student): bool
    {
        $query = 'UPDATE students SET name = :name, birth_date = :birth_date WHERE id = :id';
        $statement = $this->connection->prepare($query);

        $statement->bindValue(':name', $student->name());
        $statement->bindValue(':birth_date', $student->birthDate()->format('Y-m-d'));
        $statement->bindValue(':id', $student->id(), PDO::PARAM_INT);

        return $statement->execute();
    }

    public function remove(int $id): bool
    {
        $query = 'DELETE FROM students WHERE id = ?;';
        $statement = $this->connection->prepare($query);
        $statement->bindValue(1, $id, PDO::PARAM_INT);

        return $statement->execute();
    }
    
    public function studentsWithPhones(): array
    {
        $query = 'SELECT 
            students.id, students.name, students.birth_date,
            phones.id AS phone_id, phones.area_code, phones.number
            FROM students JOIN phones ON students.id = phones.student_id;';

        $statement = $this->connection->query($query);
        $result = $statement->fetchAll();
        $students = [];
        
        foreach ($result as $row) {
            if (!array_key_exists($row['id'], $students)) {
                $students[$row['id']] = new Student(
                    $row['id'],
                    $row['name'],
                    new DateTimeImmutable($row['birth_date'])
                );
            }
            $phone = new Phone(
                $row['phone_id'],
                $row['area_code'],
                $row['number']
            );
            $students[$row['id']]->addPhone($phone);
        }
        
        return $students;
    }
}