const employeesTable = document.getElementById('employeesTable');
const addEmployeeForm = document.getElementById('addEmployeeForm');
const editEmployeeForm = document.getElementById('editEmployeeForm');
const saveEmployeeBtn = document.getElementById('saveEmployeeBtn');
const updateEmployeeBtn = document.getElementById('updateEmployeeBtn');
const alertMessage = document.getElementById('alertMessage');
const totalEmployeesCount = document.getElementById('totalEmployeesCount');
const averageSalary = document.getElementById('averageSalary');
const newEmployeesCount = document.getElementById('newEmployeesCount');
const departmentStats = document.getElementById('departmentStats');
const salaryReport = document.getElementById('salaryReport');

let addModal, editModal;

document.addEventListener('DOMContentLoaded', function() {
    addModal = new bootstrap.Modal(document.getElementById('addEmployeeModal'));
    editModal = new bootstrap.Modal(document.getElementById('editEmployeeModal'));
    
    loadEmployees();
    
    saveEmployeeBtn.addEventListener('click', saveEmployee);
    updateEmployeeBtn.addEventListener('click', updateEmployee);
    
    if (departmentStats) {
        departmentStats.addEventListener('click', function(e) {
            e.preventDefault();
            showAlert('Department statistics report is coming soon!', 'info');
        });
    }
    
    if (salaryReport) {
        salaryReport.addEventListener('click', function(e) {
            e.preventDefault();
            showAlert('Salary report is coming soon!', 'info');
        });
    }
});

function loadEmployees() {
    employeesTable.innerHTML = '<tr><td colspan="7" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';
    
    fetch('api/read.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (Array.isArray(data)) {
                displayEmployees(data);
                updateDashboardStats(data);
            } else {
                showAlert('No employees found or error loading data.', 'warning');
                employeesTable.innerHTML = '<tr><td colspan="7" class="text-center">No employees found</td></tr>';
                resetDashboardStats();
            }
        })
        .catch(error => {
            console.error('Error loading employees:', error);
            showAlert('Failed to load employees: ' + error.message, 'danger');
            employeesTable.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error loading data</td></tr>';
            resetDashboardStats();
        });
}

function updateDashboardStats(employees) {
    if (!employees || employees.length === 0) {
        resetDashboardStats();
        return;
    }
    
    totalEmployeesCount.textContent = employees.length;
    
    const totalSalary = employees.reduce((sum, emp) => sum + parseFloat(emp.salary), 0);
    const avgSalary = totalSalary / employees.length;
    averageSalary.textContent = '$' + avgSalary.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth();
    const currentYear = currentDate.getFullYear();
    
    const newEmps = employees.filter(emp => {
        const joinDate = new Date(emp.date_joined);
        return joinDate.getMonth() === currentMonth && joinDate.getFullYear() === currentYear;
    });
    
    newEmployeesCount.textContent = newEmps.length;
}

function resetDashboardStats() {
    totalEmployeesCount.textContent = '0';
    averageSalary.textContent = '$0.00';
    newEmployeesCount.textContent = '0';
}

function displayEmployees(employees) {
    employeesTable.innerHTML = '';
    
    if (employees.length === 0) {
        employeesTable.innerHTML = '<tr><td colspan="7" class="text-center">No employees found</td></tr>';
        return;
    }
    
    employees.forEach(employee => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${employee.id}</td>
            <td>
                <div class="fw-bold">${employee.name}</div>
            </td>
            <td>${employee.email}</td>
            <td><span class="badge bg-info text-dark">${employee.position}</span></td>
            <td>$${parseFloat(employee.salary).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
            <td>${formatDate(employee.date_joined)}</td>
            <td>
                <button class="btn btn-sm btn-primary btn-action edit-btn" data-id="${employee.id}">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button class="btn btn-sm btn-danger btn-action delete-btn" data-id="${employee.id}">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </td>
        `;
        employeesTable.appendChild(row);
    });
    
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            populateEditForm(employees.find(emp => emp.id == id));
        });
    });
    
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = employees.find(emp => emp.id == id).name;
            
            if (confirm(`Are you sure you want to delete ${name}?`)) {
                deleteEmployee(id);
            }
        });
    });
}

function saveEmployee() {
    if (!validateForm('addEmployeeForm')) {
        return;
    }
    
    saveEmployeeBtn.disabled = true;
    saveEmployeeBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
    
    const employeeData = {
        name: document.getElementById('name').value.trim(),
        email: document.getElementById('email').value.trim(),
        position: document.getElementById('position').value.trim(),
        salary: document.getElementById('salary').value,
        date_joined: document.getElementById('date_joined').value
    };
    
    if (!validateEmail(employeeData.email)) {
        showAlert('Please enter a valid email address.', 'danger');
        saveEmployeeBtn.disabled = false;
        saveEmployeeBtn.innerHTML = '<i class="fas fa-save me-1"></i> Save Employee';
        return;
    }
    
    fetch('api/create.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(employeeData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            showAlert(data.message, 'success');
            addModal.hide();
            resetForm('addEmployeeForm');
            loadEmployees();
        } else {
            showAlert('Error creating employee.', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Failed to create employee: ' + error.message, 'danger');
    })
    .finally(() => {
        saveEmployeeBtn.disabled = false;
        saveEmployeeBtn.innerHTML = '<i class="fas fa-save me-1"></i> Save Employee';
    });
}

function populateEditForm(employee) {
    document.getElementById('edit_id').value = employee.id;
    document.getElementById('edit_name').value = employee.name;
    document.getElementById('edit_email').value = employee.email;
    document.getElementById('edit_position').value = employee.position;
    document.getElementById('edit_salary').value = employee.salary;
    document.getElementById('edit_date_joined').value = employee.date_joined;
    
    editModal.show();
}

function updateEmployee() {
    if (!validateForm('editEmployeeForm')) {
        return;
    }
    
    updateEmployeeBtn.disabled = true;
    updateEmployeeBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
    
    const employeeData = {
        id: document.getElementById('edit_id').value,
        name: document.getElementById('edit_name').value.trim(),
        email: document.getElementById('edit_email').value.trim(),
        position: document.getElementById('edit_position').value.trim(),
        salary: document.getElementById('edit_salary').value,
        date_joined: document.getElementById('edit_date_joined').value
    };
    
    if (!validateEmail(employeeData.email)) {
        showAlert('Please enter a valid email address.', 'danger');
        updateEmployeeBtn.disabled = false;
        updateEmployeeBtn.innerHTML = '<i class="fas fa-save me-1"></i> Update Employee';
        return;
    }
    
    fetch('api/update.php', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(employeeData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            showAlert(data.message, 'success');
            editModal.hide();
            loadEmployees();
        } else {
            showAlert('Error updating employee.', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Failed to update employee: ' + error.message, 'danger');
    })
    .finally(() => {
        updateEmployeeBtn.disabled = false;
        updateEmployeeBtn.innerHTML = '<i class="fas fa-save me-1"></i> Update Employee';
    });
}

function deleteEmployee(id) {
    fetch('api/delete.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            showAlert(data.message, 'success');
            loadEmployees();
        } else {
            showAlert('Error deleting employee.', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Failed to delete employee: ' + error.message, 'danger');
    });
}

function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form.checkValidity()) {
        const tmpButton = document.createElement('button');
        form.appendChild(tmpButton);
        tmpButton.click();
        form.removeChild(tmpButton);
        return false;
    }
    return true;
}

function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function resetForm(formId) {
    document.getElementById(formId).reset();
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

function showAlert(message, type) {
    alertMessage.textContent = message;
    alertMessage.className = `alert alert-${type}`;
    
    if (alertMessage.classList.contains('d-none')) {
        alertMessage.classList.remove('d-none');
    }
    
    alertMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
    
    setTimeout(() => {
        alertMessage.classList.add('d-none');
    }, 5000);
}