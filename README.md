# TODO Liste Web App

A simple, responsive to-do list application built with vanilla HTML/CSS/JS on the frontend and PHP (PDO/MySQL) on the backend.  
All CRUD operations are exposed via a REST-style API and logged to `log.txt`.

---

## 📝 Features

- **List** all tasks  
- **Create** new tasks  
- **Update** task title  
- **Toggle** completed status  
- **Delete** tasks  
- **Action logging** (each request is appended to `log.txt`)  
- **Responsive** design  

---

## 💻 Tech Stack

- **Frontend**: HTML5, CSS3, JavaScript (Fetch API)  
- **Backend**: PHP 7+, PDO (MySQL/MariaDB)  
- **Database**: MySQL / MariaDB  

---

## 🚀 Getting Started

### Prerequisites

- PHP 7.x or newer  
- MySQL or MariaDB  
- (Optional) Composer  
- A web server (Apache, Nginx) or PHP’s built-in server  

### Installation

1. **Clone the repository**  
   ```bash
   git clone https://github.com/FerasEbraheem/mini-online-shop.git
   cd mini-online-shop
   ```

2. **Install dependencies** (if you use Composer)  
   ```bash
   composer install
   ```

3. **Database setup**  
   - Create a database called `todo_list` (or any name you prefer).  
   - Run the following SQL to create the `todo` table:
     ```sql
     CREATE TABLE todo (
       id INT AUTO_INCREMENT PRIMARY KEY,
       title VARCHAR(255) NOT NULL,
       completed TINYINT(1) NOT NULL DEFAULT 0
     );
     ```
   - Update your DB credentials in `todo-api.php`:
     ```php
     $host    = '127.0.0.1';
     $db      = 'todo_list';
     $user    = 'your_db_username';
     $pass    = 'your_db_password';
     $charset = 'utf8mb4';
     ```

4. **Serve the app**  
   - **Built-in PHP server**:
     ```bash
     php -S localhost:8000
     ```
     Then browse to `http://localhost:8000/index.html`.  
   - **Apache/Nginx**: point your virtual host to this project folder.

---

## 🔍 Usage

1. Open the main page (`index.html`).  
2. Add a task in the input and hit **Hinzufügen** (Add).  
3. Click **Erledigt/Unerledigt** to toggle completion.  
4. Click **Aktualisieren** to edit the title.  
5. Click **Löschen** to remove a task.  
6. All actions are sent via fetch to `todo-api.php` and logged in `log.txt`.

---

## 📂 Project Structure

```
├── index.html          ← Main frontend interface
├── style.css           ← App styling (flexbox, cards, responsive)
├── todo.js             ← Frontend logic & Fetch calls
├── todo-api.php        ← REST API entry point (GET/POST/PUT/DELETE)
├── TodoDB.php          ← PHP class (PDO) for DB operations
├── log.txt             ← Action log (auto-generated)
├── todo.json           ← Example data store (unused in prod)
├── .gitignore          ← Ignored files (logs, vendor, docs)
├── composer.json       ← (optional) PHP dependencies
└── composer.lock       ← Composer lock file
```

---

## 🛠️ Logging

Every API request (and its payload/response) is appended to **log.txt** with a timestamp, for easy debugging and audit trail.

---

## 🤝 Contributing

1. Fork the repo  
2. Create a feature branch (`git checkout -b feature/xyz`)  
3. Commit your changes (`git commit -m 'feat: add xyz'`)  
4. Push to your branch (`git push origin feature/xyz`)  
5. Open a Pull Request

---

## ⚖️ License

This project is licensed under the [MIT License](LICENSE). Feel free to use and adapt it!
