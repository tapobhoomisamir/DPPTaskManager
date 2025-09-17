<!DOCTYPE html>
<html>
<head>
    <title>Edit Task</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- Header Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">Task Manager</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('') ?>">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="<?= base_url('tasks') ?>">Tasks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('reports') ?>">Reports</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2>Edit Task</h2>

    <form id="editTaskForm">
        <input type="hidden" name="id" value="<?= $task['id'] ?>">

        <div class="mb-3">
            <label for="taskTitle" class="form-label">Title</label>
            <input type="text" class="form-control" id="taskTitle" name="title"
                   value="<?= esc($task['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="taskDescription" class="form-label">Description</label>
            <textarea class="form-control" id="taskDescription" name="description" rows="3" required><?= esc($task['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="taskDepartment" class="form-label">Department</label>
            <select class="form-select" id="taskDepartment" name="department_id" required>
                <option value="">Select Department</option>
                <?php foreach($departments as $dept): ?>
                    <option value="<?= $dept['id'] ?>" <?= $task['department_id'] == $dept['id'] ? 'selected' : '' ?>>
                        <?= esc($dept['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="taskType" class="form-label">Type</label>
            <select class="form-select" id="taskType" name="tasktype_id" required>
                <option value="">Select Type</option>
                <?php foreach($tasktypes as $type): ?>
                    <option value="<?= $type['id'] ?>" <?= $task['tasktype_id'] == $type['id'] ? 'selected' : '' ?>>
                        <?= esc($type['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="taskUser" class="form-label">Assign To</label>
            <select class="form-select" id="taskUser" name="user_id" required>
                <option value="">Select Assign To</option>
                <?php foreach($users as $user): ?>
                    <option value="<?= $user['id'] ?>" <?= $task['user_id'] == $user['id'] ? 'selected' : '' ?>>
                        <?= esc($user['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="work_week" class="form-label">Work Week</label>
            <select name="work_week" class="form-select" id="work_week">
                <option value="">All Work Weeks</option>
                <?php foreach($workweeks as $ww): ?>
                    <option value="<?= $ww['id'] ?>" <?= $task['workweek_id'] == $ww['id'] ? 'selected' : '' ?>>
                        <?= esc($ww['workweek']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" class="form-control" id="due_date" name="due_date"
                   value="<?= esc($task['due_date']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="expense" class="form-label">Expense</label>
            <input type="number" class="form-control" id="expense" name="expense"
                   value="<?= esc($task['expense']) ?>">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="Pending" <?= $task['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="In Progress" <?= $task['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="Done" <?= $task['status'] == 'Done' ? 'selected' : '' ?>>Done</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="<?= base_url('tasks/view/'.$task['id']) ?>" class="btn btn-secondary">Cancel</a>
    </form>
    <h4>Comments</h4>
    <?php if (!empty($comments)): ?>
        <ul class="list-group mb-3">
            <?php foreach ($comments as $c): ?>
                <li class="list-group-item">
                    <strong><?= esc($c['user_id']) ?>:</strong>
                    <?= esc($c['comment']) ?>
                    <br>
                    <small class="text-muted"><?= $c['created_at'] ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No comments yet.</p>
    <?php endif; ?>

    <!-- Add Comment Form -->
    <form method="post" id="commentForm">
        <input type="hidden" id="commentTaskId" name="task_id" value="<?= $task['id'] ?>">
        <div class="mb-3">
            <label for="comment" class="form-label">Add Comment</label>
            <textarea class="form-control" name="comment" id="taskComment" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Post Comment</button>
    </form>
</div>

<script>
    const BASE_URL = "<?= base_url() ?>";

    const apiUrl = `${BASE_URL}api/tasks`;
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
                
            // alert('Comment added successfully');
            } else {
                alert(result.message || 'Failed to add comment.');
            }
        })
        .catch(() => alert('Failed to add comment.'));
    });

    document.getElementById('editTaskForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const taskId = formData.get('id');

        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });

        fetch(`${BASE_URL}api/tasks/${taskId}`, {
            method: 'PUT',
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                alert("Task updated successfully");
                window.location.href = `${BASE_URL}tasks`;
            } else {
                alert(result.message || "Failed to update task");
            }
        })
        .catch(() => alert("Error while updating task"));
    });
</script>
</body>
</html>
