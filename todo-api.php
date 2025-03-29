<?php
header('Content-Type: application/json');

/**
 * Schreibt einen Log-Eintrag in die Datei log.txt.
 *
 * @param string $action Art der Aktion (GET, POST, DELETE)
 * @param mixed  $data   Die zugehörigen Daten
 */
function write_log($action, $data) {
    $log = fopen('log.txt', 'a');
    $timestamp = date('Y-m-d H:i:s');
    fwrite($log, "$timestamp - $action: " . json_encode($data) . "\n");
    fclose($log);
}

// Name der JSON-Datei, in der die Todos gespeichert werden
$todo_file = 'todo.json';

// Inhalte der Datei einlesen und in ein Array umwandeln
if (file_exists($todo_file)) {
    $todo_items = json_decode(file_get_contents($todo_file), true);
} else {
    // Falls die Datei nicht existiert, wird ein leeres Array erstellt
    $todo_items = [];
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Rückgabe der Todo-Liste als JSON
        echo json_encode($todo_items);
        write_log("GET", $todo_items);
        break;
    case 'POST':
        // Daten aus dem Request-Body auslesen
        $data = json_decode(file_get_contents('php://input'), true);
        // Neues Todo mit einer eindeutigen ID erstellen
        $new_todo = ["id" => uniqid(), "title" => $data['title']];
        // Neues Todo der Liste hinzufügen
        $todo_items[] = $new_todo;
        // Die aktualisierte Liste in die JSON-Datei schreiben
        file_put_contents($todo_file, json_encode($todo_items));
        // Das neue Todo an den Client zurückgeben
        echo json_encode($new_todo);
        break;
    case 'PUT':
        // Hier kann der Code zur Aktualisierung eines Todos eingefügt werden
        break;
    case 'DELETE':
        // Daten aus dem Request-Body auslesen
        $data = json_decode(file_get_contents('php://input'), true);
        // Todo-Liste filtern und das Todo mit der angegebenen ID entfernen
        $todo_items = array_values(array_filter($todo_items, function($todo) use ($data) {
            return $todo['id'] !== $data['id'];
        }));
        // Die aktualisierte Liste in die JSON-Datei schreiben
        file_put_contents($todo_file, json_encode($todo_items));
        // Rückgabe eines Erfolgsstatus an den Client
        echo json_encode(['status' => 'success']);
        write_log("DELETE", $data);
        break;
}
?>
