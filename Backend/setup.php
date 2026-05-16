<?php

use App\Database\Database;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$db = new Database;
$db->createUserTable();
$db->createTasksTable();
$db->createTasksDataTable();
$db->createHabitsTable();
$db->createHabitsDataTable();
$db->createNotesTable();
$db->createNotesHistoryTable();
