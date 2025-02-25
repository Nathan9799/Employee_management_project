<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --danger-color: #e74c3c;
            --success-color: #2ecc71;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            background-color: var(--secondary-color);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 20px;
            box-shadow: 3px 0 10px rgba(0,0,0,0.1);
            z-index: 100;
        }
        
        .sidebar .nav-link {
            color: var(--light-color);
            border-radius: 0;
            margin: 5px 0;
            padding: 10px 20px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover, 
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            padding-left: 25px;
        }
        
        .sidebar .brand {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar .brand h3 {
            color: white;
            margin: 0;
            font-weight: 600;
        }
        
        .sidebar .brand span {
            color: var(--primary-color);
            font-weight: 700;
        }
        
        .sidebar .nav-category {
            color: rgba(255,255,255,0.5);
            font-size: 0.8rem;
            text-transform: uppercase;
            padding: 20px 20px 10px;
            font-weight: 500;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px 30px;
        }
        
        .card {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .stats-card {
            background: linear-gradient(to right, var(--primary-color), #4facfe);
            color: white;
            transition: transform 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-card .card-title {
            font-size: 1rem;
            font-weight: 500;
        }
        
        .stats-card .card-value {
            font-size: 2rem;
            font-weight: 700;
        }
        
        .stats-card .icon {
            opacity: 0.5;
            font-size: 3rem;
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table th {
            background-color: var(--secondary-color);
            color: white;
            font-weight: 500;
            border: none;
        }
        
        .table td {
            vertical-align: middle;
            border-color: #f1f1f1;
        }
        
        .btn-action {
            border-radius: 50px;
            padding: 5px 15px;
            margin: 0 3px;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .btn-primary:hover, .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .page-header h1 {
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .modal-content {
            border-radius: 10px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            border: none;
        }
        
        .modal-title {
            font-weight: 600;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
        
        .btn-close {
            filter: brightness(0) invert(1);
        }
        
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }
            
            .sidebar .brand h3, .sidebar .nav-text, .sidebar .nav-category {
                display: none;
            }
            
            .sidebar .nav-link {
                padding: 15px 0;
                text-align: center;
            }
            
            .sidebar .nav-link i {
                font-size: 1.25rem;
            }
            
            .main-content {
                margin-left: 70px;
            }
        }
        
        @media (max-width: 576px) {
            .sidebar {
                width: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<nav class="sidebar">
    <div class="brand">
        <h3><span>Bakh</span>Track</h3>
    </div>
    
    <p class="nav-category">Navigation</p>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="index.php">
                <i class="fas fa-tachometer-alt me-2"></i>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" id="addEmployeeLink" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                <i class="fas fa-user-plus me-2"></i>
                <span class="nav-text">Add Employee</span>
            </a>
        </li>
    </ul>
    
    <p class="nav-category">Reports</p>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="#" id="departmentStats">
                <i class="fas fa-chart-pie me-2"></i>
                <span class="nav-text">Department Stats</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" id="salaryReport">
                <i class="fas fa-dollar-sign me-2"></i>
                <span class="nav-text">Salary Report</span>
            </a>
        </li>
    </ul>
</nav>

<div class="main-content">
    <div class="page-header">
        <h1>Employee Dashboard</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
            <i class="fas fa-plus me-2"></i>Add New Employee
        </button>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stats-card">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <div class="card-title">Total Employees</div>
                        <div class="card-value" id="totalEmployeesCount">--</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card" style="background: linear-gradient(to right, #2ecc71, #1abc9c);">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <div class="card-title">Average Salary</div>
                        <div class="card-value" id="averageSalary">UGX --</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stats-card" style="background: linear-gradient(to right, #9b59b6, #8e44ad);">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <div class="card-title">New This Month</div>
                        <div class="card-value" id="newEmployeesCount">--</div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="alertMessage" class="alert alert-success d-none" role="alert"></div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-4">Employee Directory</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Salary</th>
                            <th>Date Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="employeesTable">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEmployeeForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" class="form-control" id="position" required>
                    </div>
                    <div class="mb-3">
                        <label for="salary" class="form-label">Salary (UGX)</label>
                        <input type="number" step="0.01" class="form-control" id="salary" required>
                    </div>
                    <div class="mb-3">
                        <label for="date_joined" class="form-label">Date Joined</label>
                        <input type="date" class="form-control" id="date_joined" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveEmployeeBtn">
                    <i class="fas fa-save me-1"></i> Save Employee
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editEmployeeForm">
                    <input type="hidden" id="edit_id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="edit_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="edit_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_position" class="form-label">Position</label>
                        <input type="text" class="form-control" id="edit_position" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_salary" class="form-label">Salary (UGX)</label>
                        <input type="number" step="0.01" class="form-control" id="edit_salary" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_date_joined" class="form-label">Date Joined</label>
                        <input type="date" class="form-control" id="edit_date_joined" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="updateEmployeeBtn">
                    <i class="fas fa-save me-1"></i> Update Employee
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>