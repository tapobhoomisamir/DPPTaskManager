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
                                <option value="Done">Done</option>
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
                    <nav>
                        <ul class="pagination" id="pagination">
                            <!-- Pagination will be rendered here -->
                        </ul>
                    </nav>
                </div>
                
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
                    <div class="mb-3">
                        <label for="expense" class="form-label">Expense</label>
                        <input type="number" class="form-control" id="expense" name="expense" min="0" step="1" required>
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

 <script src="/js/tasks.js"></script>
<script>
  // Initialize tasks when page loads
  document.addEventListener('DOMContentLoaded', function() {
      fetchTasks(1); 
  });

  
</script>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap 5 Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>