document.addEventListener('DOMContentLoaded', function() {

    // URL der CRUD-API definieren
    const apiUrl = 'todo-api.php';

    // Funktion zum Erstellen des "Löschen"-Buttons
    const getDeleteButton = (item) => {
        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Löschen';

        // Event-Listener für Lösch-Anfrage
        deleteButton.addEventListener('click', function() {
            fetch(apiUrl, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: item.id })
            })
            .then(response => response.json())
            .then(() => {
                fetchTodos(); // Todo-Liste neu laden
            });
        });
        return deleteButton;
    };

    // Funktion zum Erstellen des "Erledigt/Unerledigt"-Buttons
    const getCompleteButton = (item) => {
        const completeButton = document.createElement('button');
        completeButton.textContent = item.completed ? "Unerledigt" : "Erledigt";

        completeButton.addEventListener('click', function() {
            fetch(apiUrl, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id: item.id,
                    completed: item.completed ? 0 : 1
                })
            })
            .then(response => response.json())
            .then(() => {
                fetchTodos(); // Liste neu laden
            });
        });
        return completeButton;
    };

    // Funktion zum Erstellen des "Aktualisieren"-Buttons
    const getUpdateButton = (item) => {
        const updateButton = document.createElement('button');
        updateButton.textContent = 'Aktualisieren';

        // Beim Klick werden die aktuellen Werte in das Update-Formular übertragen
        updateButton.addEventListener('click', function() {
            document.getElementById('todo-id').value = item.id;
            document.getElementById('todo-update-input').value = item.title;
            // Update-Formular anzeigen
            document.getElementById('todo-update-form').style.display = 'block';
        });
        return updateButton;
    };

    // Funktion zum Abrufen und Anzeigen aller Todos
    const fetchTodos = () => {
        fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            const todoList = document.getElementById('todo-list');
            todoList.innerHTML = "";
            data.forEach(item => {
                const li = document.createElement('li');
                li.textContent = item.title;
                li.appendChild(getDeleteButton(item));
                li.appendChild(getCompleteButton(item));
                li.appendChild(getUpdateButton(item));
                // Durchstreichen, falls Todo als erledigt markiert ist
                if (item.completed) {
                    li.style.textDecoration = 'line-through';
                }
                todoList.appendChild(li);
            });
        });
    };

    // Formular zum Hinzufügen eines neuen Todos
    document.getElementById('todo-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const inputElement = document.getElementById('todo-input');
        const todoInput = inputElement.value;
        inputElement.value = "";
        fetch(apiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ title: todoInput })
        })
        .then(response => response.json())
        .then(() => {
            fetchTodos(); // Todo-Liste aktualisieren
        });
    });

    // Formular zum Aktualisieren eines Todos (nur title wird aktualisiert)
    document.getElementById('todo-update-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('todo-id').value;
        const title = document.getElementById('todo-update-input').value;
        
        fetch(apiUrl, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id, title: title })
        })
        .then(response => response.json())
        .then(() => {
            // Update-Formular ausblenden nach erfolgreichem Update
            document.getElementById('todo-update-form').style.display = 'none';
            fetchTodos(); // Todo-Liste neu laden
        })
        .catch(error => console.error("Fehler beim Update:", error));
    });

    // Todos beim Laden der Seite abrufen
    fetchTodos();
});