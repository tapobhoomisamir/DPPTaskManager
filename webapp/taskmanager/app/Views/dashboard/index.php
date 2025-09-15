<!DOCTYPE html>
<html>
<head>
    <title>Task List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">Task Manager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('') ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('tasks') ?>">Tasks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('reports') ?>">Reports</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- ...existing code... -->
    <?php
    // Example: Get user ID from session (adjust as needed)
    //$userId = session()->get('user_id');

    // Fetch pending tasks count for this user (from controller)
    $pendingTasks = isset($pendingTasks) ? $pendingTasks : 0;
    ?>
    <div class="container">
        <div class="row mb-4">
            <div class="col-6 col-md-3">
                <div class="card text-bg-primary mb-2" style="min-height:120px;">
                    <div class="card-body py-2 px-3">
                        <h5 class="card-title mb-1" style="font-size:1rem;">All Tasks</h5>
                        <p class="card-text mb-0" style="font-size:2rem;"><?= isset($allTasks) ? $allTasks : 0 ?></p>
                        <small>Total assigned</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-bg-warning mb-2" style="min-height:120px;">
                    <div class="ccard-body py-2 px-3">
                        <h5 class="card-title mb-1" style="font-size:1rem;">Pending Tasks</h5>
                        <p class="card-text mb-0" style="font-size:2rem;"><?= isset($pendingTasks) ? $pendingTasks : 0 ?></p>
                        <small>For you</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-bg-info mb-2" style="min-height:120px;">
                    <div class="card-body py-2 px-3">
                        <h5 class="card-title mb-1" style="font-size:1rem;">In Progress</h5>
                        <p class="card-text mb-0" style="font-size:2rem;"><?= isset($inProgressTasks) ? $inProgressTasks : 0 ?></p>
                        <small>Currently working</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-bg-secondary mb-2" style="min-height:120px;">
                    <div class="card-body py-2 px-3">
                        <h5 class="card-title mb-1" style="font-size:1rem;">Await Approval</h5>
                        <p class="card-text mb-0" style="font-size:2rem;"><?= isset($awaitApprovalTasks) ? $awaitApprovalTasks : 0 ?></p>
                        <small>Waiting for approval</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-bg-dark mb-2" style="min-height:120px;">
                    <div class="card-body py-2 px-3">
                        <h5 class="card-title mb-1" style="font-size:1rem;">Hold</h5>
                        <p class="card-text mb-0" style="font-size:2rem;"><?= isset($holdTasks) ? $holdTasks : 0 ?></p>
                        <small>On hold</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">All Tasks</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newTaskModal">+ New Task</button>
        </div>
        <!-- Filter (above task list) -->
        <div class="mb-3">
            <button class="btn btn-outline-secondary mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilter" aria-expanded="true" aria-controls="collapseFilter">
                Filters
            </button>
            <div class="collapse" id="collapseFilter">
                <div class="card card-body">
                    <form id="filterForm" class="row g-3">
                        <div class="col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-select" id="status">
                                <option value="">All Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Completed">Completed</option>
                                <option value="Awail Approval">Awail Approval</option>
                                <option value="Approved">Approved</option>
                                <option value="Closed">Closed</option>
                                <option value="Hold">Hold</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="department_id" class="form-label">Department</label>
                            <select name="department_id" class="form-select" id="department_id">
                                <option value="">All Departments</option>
                                <?php foreach($departments as $dept): ?>
                                    <option value="<?= $dept['id'] ?>"><?= esc($dept['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tasktype_id" class="form-label">Type</label>
                            <select name="tasktype_id" class="form-select" id="tasktype_id">
                                <option value="">All Types</option>
                                <?php foreach($tasktypes as $type): ?>
                                    <option value="<?= $type['id'] ?>"><?= esc($type['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-secondary w-25">Apply Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Task List -->
        <div class="row">
            <div class="col-12" id="mainContentCol">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-bordered table-hover align-middle" style="min-width: 800px;">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Department</th>
                                <th>Type</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tasksBody">
                            <!-- Tasks will be rendered here -->
                        </tbody>
                    </table>
                </div>
                <nav>
                    <ul class="pagination" id="pagination">
                        <!-- Pagination will be rendered here -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <!-- New Task Modal -->
    <div class="modal fade" id="newTaskModal" tabindex="-1" aria-labelledby="newTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="newTaskForm">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="newTaskModalLabel">Create New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="taskTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="taskTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="taskDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="taskDescription" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="taskDepartment" class="form-label">Department</label>
                        <select class="form-select" id="taskDepartment" name="department_id" required>
                            <option value="">Select Department</option>
                            <?php foreach($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>"><?= esc($dept['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="taskType" class="form-label">Type</label>
                        <select class="form-select" id="taskType" name="tasktype_id" required>
                            <option value="">Select Type</option>
                            <?php foreach($tasktypes as $type): ?>
                                <option value="<?= $type['id'] ?>"><?= esc($type['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="taskUser" class="form-label">Assign To</label>
                        <select class="form-select" id="taskUser" name="user_id" required>
                            <option value="">Select Assign To</option>
                            <?php foreach($users as $user): ?>
                                <option value="<?= $user['id'] ?>"><?= esc($user['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="work_week" class="form-label">Work Week</label>
                        <select name="work_week" class="form-select" id="work_week">
                            <option value="">All Work Weeks</option>
                            <?php foreach($workweeks as $ww): ?>
                                <option value="<?= esc($ww['id']) ?>">
                                    <?= esc($ww['workweek']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Task</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    <!-- Change Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="statusForm">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Change Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                <input type="hidden" id="statusTaskId" name="task_id">
                <label for="newStatus">Change Status</label>
                <select id="newStatus" class="form-control"></select>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
            </form>
        </div>
    </div>

    <!-- Add Comment Modal -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="commentForm">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Add Comment</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
            <input type="hidden" id="commentTaskId" name="task_id">
            <div class="mb-3">
                <label for="taskComment" class="form-label">Comment</label>
                <textarea class="form-control" id="taskComment" name="comment" rows="3" required></textarea>
            </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Comment</button>
            </div>
        </div>
        </form>
    </div>
    </div>
<script>
const BASE_URL = "<?= base_url() ?>";
const apiUrl = `${BASE_URL}api/tasks`;
let currentPage = 1;
let totalPages = 1;

// Adjust main content width when filter is collapsed/expanded
document.addEventListener('DOMContentLoaded', function() {
    const collapseFilter = document.getElementById('collapseFilter');
    const mainContentCol = document.getElementById('mainContentCol');
    collapseFilter.addEventListener('show.bs.collapse', function () {
        mainContentCol.classList.remove('col-md-12');
        mainContentCol.classList.add('col-md-12');
    });
    collapseFilter.addEventListener('hide.bs.collapse', function () {
        mainContentCol.classList.remove('col-md-12');
        mainContentCol.classList.add('col-md-12');
    });
});

function fetchTasks(page = 1) {
    const status = document.getElementById('status').value;
    const department_id = document.getElementById('department_id').value;
    const tasktype_id = document.getElementById('tasktype_id').value;

    let url = `${apiUrl}?page=${page}`;
    if (status) url += `&status=${encodeURIComponent(status)}`;
    if (department_id) url += `&department_id=${encodeURIComponent(department_id)}`;
    if (tasktype_id) url += `&tasktype_id=${encodeURIComponent(tasktype_id)}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            renderTasks(data.tasks);
            renderPagination(data.page, data.total_pages);
            currentPage = data.page;
            totalPages = data.total_pages;
        });
}

function renderTasks(tasks) {
    const tbody = document.getElementById('tasksBody');
    tbody.innerHTML = '';
    tasks.forEach(task => {
        tbody.innerHTML += `
            <tr>
                <td>${escapeHtml(task.title)}</td>
                <td>${escapeHtml(task.department_name)}</td>
                <td>${escapeHtml(task.tasktype_name)}</td>
                <td>${escapeHtml(task.user_name)}</td>
                <td>
                    <span class="badge ${task.status === 'Completed' ? 'bg-success' : 'bg-warning text-dark'}">
                        ${escapeHtml(task.status)}
                    </span>
                </td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="overflow: visible;">
                            <span class="bi bi-three-dots"></span>
                            <span style="font-size:1.5em;">&#8942;</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width: 180px; max-width: 100vw; white-space: normal;">
                            <li><a class="dropdown-item" href="/tasks/edit/${task.id}">Edit</a></li>
                            <li><a class="dropdown-item" href="/tasks/view/${task.id}">View</a></li>
                            <li><a class="dropdown-item" href="#" onclick="openStatusModal(${task.id}, '${task.status}')">Change Status</a></li>
                            <li><a class="dropdown-item" href="#" onclick="openCommentModal(${task.id})">Add Comment</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        `;
    });
}

//delete task
function deleteTask(taskId) {
    if (!confirm('Are you sure you want to delete this task?')) return;
    fetch(`${BASE_URL}api/tasks/${taskId}`, {
        method: 'DELETE'
    })
    .then(res => res.json())
    .then(result => {
        if(result.success){
            fetchTasks();
        } else {
            alert(result.message || 'Failed to delete task.');
        }
    })
    .catch(() => alert('Failed to delete task.'));
}


function renderPagination(page, total) {
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';
    for (let i = 1; i <= total; i++) {
        pagination.innerHTML += `
            <li class="page-item ${i === page ? 'active' : ''}">
                <a class="page-link" href="#" onclick="fetchTasks(${i}); return false;">${i}</a>
            </li>
        `;
    }
}

function escapeHtml(text) {
    if (typeof text !== 'string') {
        return text === null || text === undefined ? '' : String(text);
    }
    return text.replace(/[&<>"'`=\/]/g, function (s) {
        return ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;',
            "'": '&#39;', '`': '&#96;', '=': '&#61;', '/': '&#47;'
        })[s];
    });
}

document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    fetchTasks(1);
});

// Initial fetch
fetchTasks();

document.getElementById('newTaskForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const data = {
        title: form.title.value,
        description: form.description.value, 
        department_id: form.department_id.value,
        tasktype_id: form.tasktype_id.value,
        user_id: form.user_id.value,
        work_week: form.work_week.value,
        due_date: form.due_date.value,
        status: "Pending"
    };
    fetch(`${BASE_URL}api/tasks`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(result => {
        if(result.success){
            form.reset();
            fetchTasks(1);
            const modalEl = document.getElementById('newTaskModal');
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalInstance.hide();
        } else {
            alert(result.message || 'Failed to create task.');
        }
    })
    .catch(() => alert('Failed to create task.'));
});


// Open status modal
function openStatusModal(taskId, currentStatus) {
    const statusSelect = document.getElementById('newStatus');
    const hiddenTaskId = document.getElementById('statusTaskId');
    hiddenTaskId.value = taskId;

    statusSelect.innerHTML = "";

    let allowedStatuses = [];

    if (currentStatus === "Await Approval") {
        allowedStatuses = ["Approved", "Closed"];
    } 
    else if (currentStatus === "Approved" || currentStatus === "Completed") {
        allowedStatuses = [ "Closed"];
    }
    else {
        allowedStatuses = ["Pending", "In Progress", "Await Approval","Completed", "Closed"];
    }

    allowedStatuses.forEach(status => {
        const option = document.createElement("option");
        option.value = status;
        option.textContent = status;
        statusSelect.appendChild(option);
    });

    const modal = new bootstrap.Modal(document.getElementById('statusModal'));
    modal.show();
}

// Handle status form submit
document.getElementById('statusModal').addEventListener('submit', function(e) {
    e.preventDefault();
    debugger;
    const taskId = document.getElementById('statusTaskId').value;
    const status = document.getElementById('newStatus').value;

    fetch(`${BASE_URL}api/tasks/${taskId}/status`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ status })
    })
    .then(res => res.json())
    .then(result => {
        if(result.success){
            fetchTasks(currentPage);
            bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
        } else {
            alert(result.message || 'Failed to update status.');
        }
    })
    .catch(() => alert('Failed to update status.'));
});


// Open comment modal
function openCommentModal(taskId) {
    document.getElementById('commentTaskId').value = taskId;
    document.getElementById('taskComment').value = '';
    const modal = new bootstrap.Modal(document.getElementById('commentModal'));
    modal.show();
}

// Handle comment form submit
document.getElementById('commentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const taskId = document.getElementById('commentTaskId').value;
    const comment = document.getElementById('taskComment').value;

    fetch(`${BASE_URL}api/tasks/${taskId}/comments`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ comment })
    })
    .then(res => res.json())
    .then(result => {
        if(result.success){
            bootstrap.Modal.getInstance(document.getElementById('commentModal')).hide();
            alert('Comment added successfully');
        } else {
            alert(result.message || 'Failed to add comment.');
        }
    })
    .catch(() => alert('Failed to add comment.'));
});

</script>
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap 5 Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>