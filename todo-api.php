<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Einbinden der Datei TodoDB.php
require_once 'TodoDB.php';

// Datenbankverbindungsdaten
$host    = '127.0.0.1';
$db      = 'todo_list';
$user    = 'ferasebraheem';
$pass    = '123456789feras';
$charset = 'utf8mb4';

try {
    // Erstelle ein Objekt der Klasse TodoDB
    $todoDB = new TodoDB($host, $db, $user, $pass, $charset);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

// Funktion zur Protokollierung der Aktionen in der Datei log.txt
function write_log($action, $data) {
    $log = fopen('log.txt', 'a');
    $timestamp = date('Y-m-d H:i:s');
    fwrite($log, "$timestamp - $action: " . json_encode($data, JSON_PRETTY_PRINT) . "\n");
    fclose($log);
}

// Verarbeitung der HTTP-Anfragen (REST-API)
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Alle Todos abrufen
        $todo_items = $todoDB->getAllTodos();
        echo json_encode($todo_items, JSON_PRETTY_PRINT);
        write_log("GET", $todo_items);
        break;

    case 'POST':
        // Daten aus dem Request-Body abrufen und ein neues Todo erstellen
        $data = json_decode(file_get_contents('php://input'), true);
        $insertId = $todoDB->createTodo($data['title']);
        echo json_encode(['status' => 'created', 'id' => $insertId]);
        write_log("POST", $data);
        break;

    case 'PUT':
        // Daten aus dem Request-Body abrufen und ein bestehendes Todo aktualisieren
        $data = json_decode(file_get_contents('php://input'), true);
        $todoDB->updateTodo($data);
        echo json_encode(['status' => 'updated']);
        write_log("PUT", $data);
        break;

    case 'DELETE':
        // Daten aus dem Request-Body abrufen und ein Todo löschen
        $data = json_decode(file_get_contents('php://input'), true);
        $todoDB->deleteTodo($data['id']);
        echo json_encode(['status' => 'deleted']);
        write_log("DELETE", $data);
        break;
}
?>