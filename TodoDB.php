<?php
class TodoDB {
    private $pdo;

    // Erstelle die Datenbankverbindung im Konstruktor
    public function __construct($host, $db, $user, $pass, $charset = 'utf8mb4') {
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            error_log("PDOException: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    // Private Funktion zur Vorbereitung und Ausführung von Abfragen
    private function prepareExecuteStatement($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Alle Todos abrufen
    public function getAllTodos() {
        $stmt = $this->pdo->query("SELECT * FROM todo");
        return $stmt->fetchAll();
    }

    // Ein neues Todo erstellen
    public function createTodo($title) {
        $sql = "INSERT INTO todo (title, completed) VALUES (:title, :completed)";
        $this->prepareExecuteStatement($sql, ['title' => $title, 'completed' => 0]);
        return $this->pdo->lastInsertId();
    }

    // Ein Todo basierend auf den übergebenen Daten aktualisieren (Titel und Status können aktualisiert werden)
    public function updateTodo($data) {
        $fields = [];
        $params = ['id' => $data['id']];
        if (isset($data['title'])) {
            $fields[] = 'title = :title';
            $params['title'] = $data['title'];
        }
        if (isset($data['completed'])) {
            $fields[] = 'completed = :completed';
            $params['completed'] = $data['completed'];
        }
        if (!empty($fields)) {
            $sql = "UPDATE todo SET " . implode(', ', $fields) . " WHERE id = :id";
            $this->prepareExecuteStatement($sql, $params);
        }
    }

    // Ein Todo basierend auf der ID löschen
    public function deleteTodo($id) {
        $sql = "DELETE FROM todo WHERE id = :id";
        $this->prepareExecuteStatement($sql, ['id' => $id]);
    }
}
?>
