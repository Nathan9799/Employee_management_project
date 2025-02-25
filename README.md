# Employee Management System

## Overview
This is website allows users to manage employee records. Users can add, update, view, and delete employee details such as name, position, salary, and hire date. The website uses a **MariaDB** database which is compatible with MySQL to store employee data securely.

## Features
- Add, Edit, View, and Delete Employee records
- Display employee details in a responsive table
- Database integration with MariaDB
- Basic form validation using JavaScript

## Tech Stack
- **PHP**: Used for backend functionality and database operations.
- **MariaDB**: Used for managing and storing employee data (compatible with MySQL).
- **HTML/CSS**: Used for building the structure and styling of the app.
- **JavaScript**: Used for form validation and dynamic interactions.

## Database Structure
The database has a single table `employees` with the following columns:
- `id`: Primary key, auto-increment
- `name`: Employee's name
- `position`: Employee's job position
- `salary`: Employee's salary
- `hire_date`: Date when the employee was hired

## Setup Instructions

### Prerequisites
- **PHP 7 or higher**
- **MariaDB** (included with XAMPP)
- A local server (e.g., **XAMPP**)

### Steps to Run Locally:
 Clone the repository:
   
Set up the database:
Open phpMyAdmin 
Create a new database -employee_management
copy the query to create the table and then update your database.php accordingly

ensure to activate the necessary extensions in php.ini

place the files in htdocs in xamp, startup xamp and run the project.
