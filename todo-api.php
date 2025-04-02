<?php
header('Content-Type: application/json');

$host    = '127.0.0.1';
$db      = 'todo_list';
$user    = 'ferasebraheem';
$pass    = '123456789feras';
$charset = 'utf8mb4';

// DSN (Data Source Name) definieren
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO Optionen definieren
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Fehler als Exception werfen
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC        // Ergebnisse als assoziatives Array abrufen
];

try {
    // PDO-Verbindung aufbauen
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    error_log("PDOException: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Funktion zur Protokollierung in log.txt
function write_log($action, $data) {
    $log = fopen('log.txt', 'a');
    $timestamp = date('Y-m-d H:i:s');
    // Mit JSON_PRETTY_PRINT für eine bessere Formatierung
    fwrite($log, "$timestamp - $action: " . json_encode($data, JSON_PRETTY_PRINT) . "\n");
    fclose($log);
}

// REST-API: Verarbeitung der HTTP-Anfragen
switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        // Alle Todos aus der Datenbank abrufen
        $statement = $pdo->query("SELECT * FROM todo");
        $todo_items = $statement->fetchAll();
        echo json_encode($todo_items, JSON_PRETTY_PRINT);
        write_log("GET", $todo_items);
        break;

    case 'POST':
        // Daten aus dem Request-Body abrufen
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Neues Todo einfügen (nur title und completed)
        $statement = $pdo->prepare(
            "INSERT INTO todo (title, completed) VALUES (:title, :completed)"
        );
        $statement->execute([
            'title'     => $data['title'],
            'completed' => 0
        ]);
        echo json_encode(['status' => 'created']);
        write_log("POST", $data);
        break;

    case 'PUT':
        // Aktualisierungsdaten aus dem Request-Body abrufen
        $data = json_decode(file_get_contents('php://input'), true);

        $fields = [];
        $args = ['id' => $data['id']];

        // Aktualisiere den Titel, falls vorhanden
        if (isset($data['title'])) {
            $fields[] = 'title = :title';
            $args['title'] = $data['title'];
        }

        // Aktualisiere den Erledigt-Status, falls vorhanden
        if (isset($data['completed'])) {
            $fields[] = 'completed = :completed';
            $args['completed'] = $data['completed'];
        }

        // Nur wenn Felder zum Aktualisieren vorhanden sind, wird der SQL-Befehl ausgeführt
        if (!empty($fields)) {
            $sql = "UPDATE todo SET " . implode(', ', $fields) . " WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->execute($args);
        }

        echo json_encode(['status' => 'updated']);
        write_log("PUT", $data);
        break;

    case 'DELETE':
        // Daten für das Löschen aus dem Request-Body abrufen
        $data = json_decode(file_get_contents('php://input'), true);

        // Todo anhand der ID löschen
        $statement = $pdo->prepare("DELETE FROM todo WHERE id = :id");
        $statement->execute(['id' => $data['id']]);
        echo json_encode(['status' => 'deleted']);
        write_log("DELETE", $data);
        break;
}
?>
