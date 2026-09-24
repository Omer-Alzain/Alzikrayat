# Alzikrayat

## Project Name

**Alzikrayat – Photo Memo**

## Description

Alzikrayat is a PHP-based web application for storing and sharing memorable photos. Users can create an account, log in, browse photos, view photo details, upload their own photos, and interact with photos through comments.

The project was developed as a college web development project to demonstrate practical use of PHP, relational databases, authentication, sessions, validation, file uploads, and an MVC-style application structure.

## Technologies

* **PHP** – Server-side application logic
* **HTML5** – Page structure
* **CSS3** – User interface styling
* **JavaScript** – Client-side functionality
* **MariaDB / MySQL** – Relational database
* **PDO** – Database communication
* **Apache** – Web server
* **Git & GitHub** – Version control

## Project Structure

```text
Alzikrayat/
├── config/          # Database configuration
├── controllers/     # Application controllers
├── core/            # Router, Model, Controller, Session, Validator, etc.
├── database/        # Database SQL file
├── models/          # Database-related models
├── public/          # Public assets and uploaded images
├── views/           # Application views
├── .gitignore
└── README.md
```

## Main Features

* User registration and login
* Session-based authentication
* Password validation
* Photo gallery
* Photo details page
* Photo upload
* Image validation
* Photo deletion with ownership verification
* Comments on photos
* Comment ownership verification
* MariaDB/MySQL database integration
* MVC-style project structure
* Prepared SQL statements using PDO

## How to Run

### Requirements

You need:

* PHP
* Apache or another PHP-compatible web server
* MySQL or MariaDB
* A web browser

XAMPP can also be used because it provides Apache, PHP, and MySQL/MariaDB in one environment.

### 1. Clone the repository

```bash
git clone https://github.com/Omer-Alzain/Alzikrayat.git
cd Alzikrayat
```

### 2. Create the database

Create a database named:

```text
Alzikrayat
```

Then import:

```text
database/alzikrayat.sql
```

### 3. Configure the database connection

Open:

```text
config/database.php
```

Update the database settings to match your local environment.

Example:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'Alzikrayat');
define('DB_USER', 'your_database_username');
define('DB_PASS', 'your_database_password');
```

The username and password may be different depending on whether you are using XAMPP, Linux, MariaDB, or another local setup.

### 4. Configure the web server

Place the project in the appropriate web-server directory.

For example, with XAMPP:

```text
xampp/htdocs/Alzikrayat
```

Start:

* Apache
* MySQL/MariaDB

### 5. Open the application

Open the project through your local web server in a browser.

Example:

```text
http://localhost/Alzikrayat/
```

The exact URL may differ depending on the local Apache configuration.

## Database

The database SQL file is included in:

```text
database/alzikrayat.sql
```

The database contains the main tables:

* Users
* Photos
* Comments

Foreign keys are used to connect users, photos, and comments.

## Student:

**Omer Abd-Almpnem Alzain mohamed.**
