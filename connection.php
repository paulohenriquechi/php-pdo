<?php

use Alura\Pdo\Infra\Persistence\ConnectionCreator;

$pdo = ConnectionCreator::createConnection();
echo "Connected";

$pdo->exec("INSERT INTO phones (area_code, number, student_id) VALUES ('24', '999999999', 2),('21', '222222222', 2);");
exit();

$createTableSql = '
    CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY,
        name TEXT,
        birth_date TEXT
    );

    CREATE TABLE IF NOT EXISTS phones (
        id INTEGER PRIMARY KEY,
        area_code TEXT,
        number TEXT,
        student_id INTEGER,
        FOREIGN KEY(student_id) REFERENCES students(id)
    );
';

$pdo->exec($createTableSql);