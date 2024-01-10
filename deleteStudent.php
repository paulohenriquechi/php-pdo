<?php

require_once 'vendor/autoload.php';

$databasePath = __DIR__.'/database.sqlite';
$pdo = new PDO("sqlite:$databasePath");

$sqlDelete = "DELETE FROM students WHERE id = ?;";
$preparedStatement = $pdo->prepare($sqlDelete);
$preparedStatement->bindValue(1, 4, PDO::PARAM_INT);

$preparedStatement->execute();