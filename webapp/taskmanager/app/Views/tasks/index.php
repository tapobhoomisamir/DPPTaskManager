<!DOCTYPE html>
<html>
<head>
    <title>Task List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">All Tasks</h2>
        <a href="<?= base_url('tasks/create') ?>" class="btn btn-primary">+ New Task</a>
    </div>
    <!-- Filter Form -->
    <form method="get" class="row g-2 mb-3">
        <div class="col-12 col-md-3">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="Pending" <?= isset($_GET['status']) && $_GET['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Completed" <?= isset($_GET['status']) && $_GET['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
            </select>
        </div>
        <div class="col-12 col-md-3">
            <select name="department_id" class="form-select">
                <option value="">All Departments</option>
                <?php foreach($departments as $dept): ?>
                    <option value="<?= $dept['id'] ?>" <?= isset($_GET['department_id']) && $_GET['department_id'] == $dept['id'] ? 'selected' : '' ?>>
                        <?= esc($dept['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 col-md-3">
            <select name="tasktype_id" class="form-select">
                <option value="">All Types</option>
                <?php foreach($tasktypes as $type): ?>
                    <option value="<?= $type['id'] ?>" <?= isset($_GET['tasktype_id']) && $_GET['tasktype_id'] == $type['id'] ? 'selected' : '' ?>>
                        <?= esc($type['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 col-md-3">
            <button type="submit" class="btn btn-secondary w-100">Filter</button>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Deparment</th>
                    <th>Type</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($tasks as $task): ?>
                <tr>
                    <td><?= esc($task['title']) ?></td>
                    <td><?= esc($task['department_name']) ?></td>
                    <td><?= esc($task['tasktype_name']) ?></td>
                    <td><?= esc($task['user_name']) ?></td>
                    <td>
                        <span class="badge <?= $task['status'] === 'Completed' ? 'bg-success' : 'bg-warning text-dark' ?>">
                            <?= esc($task['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?= base_url('tasks/updateStatus/'.$task['id']) ?>" class="btn btn-sm btn-outline-secondary">
                            Mark as <?= $task['status'] === 'Pending' ? 'Completed' : 'Pending' ?>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Bootstrap JS CDN (optional, for interactive components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>