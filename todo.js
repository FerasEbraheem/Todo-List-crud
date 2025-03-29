document.addEventListener('DOMContentLoaded', function() {
    // URL zur API
    const apiUrl = "todo-api.php";

    /**
     * Erzeugt einen "Löschen"-Button für ein Todo.
     * @param {Object} item - Das Todo-Objekt
     * @returns {HTMLElement} - Der erstellte Button
     */
    const getDeleteButton = (item) => {
        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Löschen';

        // Klick-Event zum Löschen des Todo
        deleteButton.addEventListener('click', function() {
            fetch(apiUrl, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: item.id })
            })
            .then(response => response.json())
            .then(() => fetchTodos()) // Todo-Liste neu laden
            .catch(error => console.error('Löschfehler:', error));
        });

        return deleteButton;
    };

    // Formular-Submit zum Hinzufügen eines neuen Todos
    document.getElementById('todo-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const inputElement = document.getElementById('todo-input');
        const todoInput = inputElement.value.trim();
        // Verhindert das Hinzufügen leerer Einträge
        if (!todoInput) return;
        inputElement.value = "";

        fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ title: todoInput })
        })
        .then(response => response.json())
        .then(data => {
            // Hinzufügen des neuen Todos zur Liste ohne die gesamte Liste neu zu laden
            const todoList = document.getElementById('todo-list');
            const li = document.createElement('li');
            li.textContent = data.title;
            li.appendChild(getDeleteButton(data));
            todoList.appendChild(li);
        })
        .catch(error => console.error('Fehler beim Hinzufügen:', error));
    });

    /**
     * Lädt alle Todos von der API und zeigt sie an.
     */
    const fetchTodos = () => {
        fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            const todoList = document.getElementById('todo-list');
            // Vor dem Neuladen wird die Liste geleert, um Dopplungen zu vermeiden
            todoList.innerHTML = "";
            data.forEach(item => {
                const li = document.createElement('li');
                li.textContent = item.title;
                li.appendChild(getDeleteButton(item));
                todoList.appendChild(li);
            });
        })
        .catch(error => console.error('Fehler beim Laden der Todos:', error));
    };

    // Initiales Laden der Todos beim Seitenstart
    fetchTodos();
});
